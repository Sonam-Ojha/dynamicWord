<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\GeneratedDocument;
use App\Models\Template;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DocumentGenerationController extends Controller
{
    public function __construct(private FileUploadService $files)
    {
    }

    public function index(): View
    {
        $userId = auth()->id();

        $stats = [
            'total' => GeneratedDocument::where('user_id', $userId)->count(),
            'drafts' => GeneratedDocument::where('user_id', $userId)->where('status', 'draft')->count(),
            'generated' => GeneratedDocument::where('user_id', $userId)->where('status', 'generated')->count(),
        ];

        $myDocs = GeneratedDocument::where('user_id', $userId)
            ->with(['bank', 'template'])
            ->latest()
            ->limit(10)
            ->get();

        return view('frontend.dashboard', compact('stats', 'myDocs'));
    }

    public function selectBank(): View
    {
        $banks = Bank::active()->orderBy('bank_name')->get();
        return view('frontend.banks', compact('banks'));
    }

    public function selectTemplate(Bank $bank): View
    {
        abort_unless($bank->status, 404);

        $templates = $bank->templates()
            ->where('status', true)
            ->with('category')
            ->orderBy('template_name')
            ->get();

        return view('frontend.templates', compact('bank', 'templates'));
    }

    public function showForm(Template $template): View
    {
        abort_unless($template->status, 404);

        $template->load([
            'bank',
            'category',
            'fields' => fn ($q) => $q->where('status', true)->orderBy('sort_order'),
        ]);

        return view('frontend.form', compact('template'));
    }

    public function generate(Request $request, Template $template): RedirectResponse
    {
        abort_unless($template->status, 404);

        $fields = $template->fields()->where('status', true)->orderBy('sort_order')->get();

        $rules = [];
        foreach ($fields as $field) {
            $key = 'fields.'.$field->field_name;
            $r = [$field->is_required ? 'required' : 'nullable'];

            switch ($field->field_type) {
                case 'email': $r[] = 'email'; break;
                case 'number': $r[] = 'numeric'; break;
                case 'date': $r[] = 'date'; break;
                case 'image':
                case 'signature':
                    $r[] = 'image';
                    $r[] = 'max:4096';
                    break;
                case 'checkbox':
                    $r[0] = $field->is_required ? 'required' : 'nullable';
                    $r[] = 'array';
                    break;
            }

            if ($field->validation_rules) {
                foreach (explode('|', $field->validation_rules) as $rule) {
                    $rule = trim($rule);
                    if ($rule !== '') $r[] = $rule;
                }
            }

            $rules[$key] = $r;
        }
        $request->validate($rules);

        $data = [];
        foreach ($fields as $field) {
            $key = $field->field_name;
            $val = $request->input('fields.'.$key);

            if (in_array($field->field_type, ['image', 'signature'], true)
                && $request->hasFile('fields.'.$key)) {
                $val = $this->files->upload($request->file('fields.'.$key), 'documents/inputs');
            }

            $data[$key] = $val;
        }

        $document = GeneratedDocument::create([
            'user_id' => auth()->id(),
            'bank_id' => $template->bank_id,
            'template_id' => $template->id,
            'document_number' => $this->makeDocumentNumber($template),
            'form_data' => $data,
            'status' => 'draft',
        ]);

        return redirect()->route('generate.preview', $document);
    }

    public function preview(GeneratedDocument $document): View
    {
        $this->authorizeDocument($document);
        $document->load(['bank', 'template.fields']);

        $rendered = $this->renderHtml($document);

        return view('frontend.preview', compact('document', 'rendered'));
    }

    public function finalize(GeneratedDocument $document): RedirectResponse
    {
        $this->authorizeDocument($document);
        $document->load(['bank', 'template.fields']);

        $rendered = $this->renderHtml($document);
        $path = 'documents/'.$document->document_number.'.html';
        Storage::disk('public')->put($path, $this->wrapPrintable($document, $rendered));

        $document->update([
            'status' => 'generated',
            'generated_file' => $path,
        ]);

        return redirect()->route('generate.preview', $document)->with('success', 'Document finalized.');
    }

    public function print(GeneratedDocument $document): View
    {
        $this->authorizeDocument($document);
        $document->load(['bank', 'template.fields']);

        $rendered = $this->renderHtml($document);

        return view('frontend.print', compact('document', 'rendered'));
    }

    public function download(GeneratedDocument $document): Response
    {
        $this->authorizeDocument($document);

        if (! $document->generated_file || ! Storage::disk('public')->exists($document->generated_file)) {
            abort(404, 'File not found. Finalize the document first.');
        }

        return response(
            Storage::disk('public')->get($document->generated_file),
            200,
            [
                'Content-Type' => 'text/html; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="'.$document->document_number.'.html"',
            ]
        );
    }

    private function authorizeDocument(GeneratedDocument $document): void
    {
        $user = auth()->user();
        $isOwner = $document->user_id === $user->id;
        $isAdmin = $user->hasAnyRole(['Super Admin', 'Admin']);

        abort_unless($isOwner || $isAdmin, 403);
    }

    private function makeDocumentNumber(Template $template): string
    {
        $code = strtoupper($template->bank->bank_code ?? 'DOC');
        return sprintf('%s-%s-%s', $code, now()->format('YmdHis'), strtoupper(Str::random(4)));
    }

    private function renderHtml(GeneratedDocument $document): string
    {
        $html = (string) ($document->template->html_content ?? '');
        $fields = $document->template->fields->keyBy('field_name');

        foreach (($document->form_data ?? []) as $key => $value) {
            $field = $fields->get($key);

            if ($field && in_array($field->field_type, ['image', 'signature'], true) && $value) {
                $url = asset('storage/'.$value);
                $replacement = '<img src="'.e($url).'" alt="" style="max-height:80px;">';
            } else {
                $val = is_array($value) ? implode(', ', $value) : (string) ($value ?? '');
                $replacement = e($val);
            }

            $html = str_replace(['{'.$key.'}', '{{'.$key.'}}'], $replacement, $html);
        }

        return $html;
    }

    private function wrapPrintable(GeneratedDocument $document, string $body): string
    {
        return view('frontend.printable', [
            'document' => $document,
            'rendered' => $body,
        ])->render();
    }
}
