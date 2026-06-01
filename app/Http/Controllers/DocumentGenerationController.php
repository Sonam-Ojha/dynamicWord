<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankBranch;
use App\Models\DocumentDownload;
use App\Models\GeneratedDocument;
use App\Models\Template;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Auth;

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
            ->with(['bank', 'branch', 'template'])
            ->latest()
            ->limit(10)
            ->get();

        return view('frontend.dashboard', compact('stats', 'myDocs'));
    }

    public function myDocuments(Request $request): View
    {
        $userId = auth()->id();

        $documents = GeneratedDocument::query()
            ->where('user_id', $userId)
            ->with(['bank', 'branch', 'template'])
            ->when($request->input('q'), fn ($q, $v) => $q->where('document_number', 'like', "%{$v}%"))
            ->when($request->input('status'), fn ($q, $v) => $q->where('status', $v))
            ->when($request->input('bank_id'), fn ($q, $v) => $q->where('bank_id', $v))
            ->when($request->input('from'), fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($request->input('to'), fn ($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $banks = Bank::active()
            ->whereIn('id', GeneratedDocument::where('user_id', $userId)->distinct('bank_id')->pluck('bank_id'))
            ->orderBy('bank_name')
            ->get(['id', 'bank_name']);

        return view('frontend.documents', compact('documents', 'banks'));
    }

    public function selectBank(): View
    {
        $banks = Bank::active()->orderBy('bank_name')->get();
        return view('frontend.banks', compact('banks'));
    }

    public function selectBranch(Bank $bank): View
    {
        abort_unless($bank->status, 404);

        $branches = $bank->activeBranches()->orderBy('branch_name')->get();

        return view('frontend.branches', compact('bank', 'branches'));
    }

    public function selectTemplate(Bank $bank, BankBranch $branch): View
    {
        abort_unless($bank->status, 404);
        abort_unless($branch->status && $branch->bank_id === $bank->id, 404);

        $templates = $bank->templates()
            ->where('status', true)
            ->where(fn ($q) => $q->whereNull('branch_id')->orWhere('branch_id', $branch->id))
            ->orderBy('template_name')
            ->get();

        return view('frontend.templates', compact('bank', 'branch', 'templates'));
    }

    public function showForm(Template $template): View
    {
        abort_unless($template->status, 404);

        $template->load([
            'bank',
            'fields' => fn ($q) => $q->where('status', true)->orderBy('sort_order'),
        ]);

        $branches = BankBranch::active()->forBank($template->bank_id)->orderBy('branch_name')->get();

        return view('frontend.form', compact('template', 'branches'));
    }

    public function generate(Request $request, Template $template): RedirectResponse
    {
        abort_unless($template->status, 404);

        $fields = $template->fields()->where('status', true)->orderBy('sort_order')->get();
        $isDraft = $request->input('action') === 'draft';

        $this->validateFields($request, $fields, $isDraft);
        $branchId = $this->validateBranch($request, $template->bank_id, $isDraft);

        $data = $this->collectFieldData($request, $fields);

        $document = GeneratedDocument::create([
            'user_id' => auth()->id(),
            'bank_id' => $template->bank_id,
            'branch_id' => $branchId,
            'template_id' => $template->id,
            'document_number' => $this->makeDocumentNumber($template),
            'form_data' => $data,
            'status' => 'draft',
        ]);

        if ($isDraft) {
            return redirect()->route('generate.editDraft', $document)
                ->with('success', 'Draft saved. Aap baad me wapas aakar fill kar sakte ho.');
        }

        return redirect()->route('generate.preview', $document);
    }

    public function editDraft(GeneratedDocument $document): View
    {
        $this->authorizeDocument($document);
        abort_unless($document->status === 'draft', 403, 'Sirf drafts edit ho sakte hain. Ye document already finalized hai.');

        $template = $document->template;
        abort_unless($template && $template->status, 404);

        $template->load([
            'bank',
            'fields' => fn ($q) => $q->where('status', true)->orderBy('sort_order'),
        ]);

        $branches = BankBranch::active()->forBank($template->bank_id)->orderBy('branch_name')->get();

        return view('frontend.form', compact('template', 'document', 'branches'));
    }

    public function updateDraft(Request $request, GeneratedDocument $document): RedirectResponse
    {
        $this->authorizeDocument($document);
        abort_unless($document->status === 'draft', 403);

        $template = $document->template;
        abort_unless($template && $template->status, 404);

        $fields = $template->fields()->where('status', true)->orderBy('sort_order')->get();
        $isDraft = $request->input('action') === 'draft';

        $this->validateFields($request, $fields, $isDraft);
        $branchId = $this->validateBranch($request, $template->bank_id, $isDraft);

        $existing = $document->form_data ?? [];
        $data = $this->collectFieldData($request, $fields, $existing);

        $document->update([
            'form_data' => $data,
            'branch_id' => $branchId ?? $document->branch_id,
        ]);

        if ($isDraft) {
            return redirect()->route('generate.editDraft', $document)->with('success', 'Draft updated.');
        }

        return redirect()->route('generate.preview', $document);
    }

    private function validateBranch(Request $request, int $bankId, bool $isDraft): ?int
    {
        $rule = ['nullable', 'integer', \Illuminate\Validation\Rule::exists('bank_branches', 'id')->where('bank_id', $bankId)];
        if (! $isDraft) {
            $rule[0] = 'required';
        }

        $validated = $request->validate(['branch_id' => $rule]);

        return $validated['branch_id'] ?? null;
    }

    private function validateFields(Request $request, $fields, bool $isDraft): void
    {
        $rules = [];

        foreach ($fields as $field) {
            $key = 'fields.'.$field->field_name;

            if ($isDraft) {
                if (in_array($field->field_type, ['image', 'signature'], true)) {
                    $rules[$key] = ['nullable', 'image', 'max:4096'];
                }
                continue;
            }

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

        if ($rules) {
            $request->validate($rules);
        }
    }

    private function collectFieldData(Request $request, $fields, array $existing = []): array
    {
        $data = $existing;

        foreach ($fields as $field) {
            $key = $field->field_name;
            $isFileType = in_array($field->field_type, ['image', 'signature'], true);

            if ($isFileType) {
                if ($request->hasFile('fields.'.$key)) {
                    if (! empty($existing[$key])) {
                        $this->files->delete($existing[$key]);
                    }
                    $data[$key] = $this->files->upload($request->file('fields.'.$key), 'documents/inputs');
                }
                continue;
            }

            $data[$key] = $request->input('fields.'.$key);
        }

        return $data;
    }

    public function preview(GeneratedDocument $document): View
    {
        $this->authorizeDocument($document);
        $document->load(['bank', 'branch', 'template.fields']);

        $rendered = $this->renderHtml($document);

        return view('frontend.preview', compact('document', 'rendered'));
    }

    public function finalize(GeneratedDocument $document): RedirectResponse
    {
        $this->authorizeDocument($document);
        $document->load(['bank', 'branch', 'template.fields']);

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
        $document->load(['bank', 'branch', 'template.fields']);

        $rendered = $this->renderHtml($document);

        return view('frontend.print', compact('document', 'rendered'));
    }

    public function download(Request $request, GeneratedDocument $document): Response
    {
        $this->authorizeDocument($document);

        if (! $document->generated_file || ! Storage::disk('public')->exists($document->generated_file)) {
            abort(404, 'File not found. Finalize the document first.');
        }

        $format = strtolower((string) $request->input('format', 'html'));
        $format = in_array($format, ['html', 'word', 'doc'], true) ? $format : 'html';

        DocumentDownload::create([
            'user_id' => auth()->id(),
            'document_id' => $document->id,
            'template_id' => $document->template_id,
            'bank_id' => $document->bank_id,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent().' [format:'.$format.']', 0, 500),
        ]);

        $body = Storage::disk('public')->get($document->generated_file);
        $name = $document->document_number;

        if ($format === 'word' || $format === 'doc') {
            return response($this->wrapForWord($body, $name), 200, [
                'Content-Type' => 'application/vnd.ms-word; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="'.$name.'.doc"',
            ]);
        }

        return response($body, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$name.'.html"',
        ]);
    }

    private function wrapForWord(string $html, string $title): string
    {
        $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

        return <<<HTML
<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:w="urn:schemas-microsoft-com:office:word"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{$title}</title>
    <!--[if gte mso 9]>
    <xml>
        <w:WordDocument>
            <w:View>Print</w:View>
            <w:Zoom>100</w:Zoom>
            <w:DoNotOptimizeForBrowser/>
        </w:WordDocument>
    </xml>
    <![endif]-->
    <style>
        @page { size: A4; margin: 1in; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; }
    </style>
</head>
<body>
{$html}
</body>
</html>
HTML;
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

        $branch = $document->branch;
        $branchPlaceholders = [
            'branch_name' => $branch->branch_name ?? '',
            'branch_code' => $branch->branch_code ?? '',
            'ifsc_code' => $branch->ifsc_code ?? '',
            'branch_address' => $branch->address ?? '',
            'branch_city' => $branch->city ?? '',
            'branch_state' => $branch->state ?? '',
            'branch_pincode' => $branch->pincode ?? '',
            'branch_phone' => $branch->phone ?? '',
            'branch_email' => $branch->email ?? '',
            'bank_name' => $document->bank->bank_name ?? '',
            'bank_code' => $document->bank->bank_code ?? '',
        ];
        foreach ($branchPlaceholders as $key => $value) {
            $html = $this->replacePlaceholder($html, $key, e((string) $value));
        }

        foreach (($document->form_data ?? []) as $key => $value) {
            $field = $fields->get($key);

            if ($field && in_array($field->field_type, ['image', 'signature'], true) && $value) {
                $url = asset('storage/'.$value);
                $replacement = '<img src="'.e($url).'" alt="" style="max-height:80px;">';
            } else {
                $val = is_array($value) ? implode(', ', $value) : (string) ($value ?? '');
                $replacement = e($val);
            }

            $html = $this->replacePlaceholder($html, $key, $replacement);
        }

        return $html;
    }

    private function replacePlaceholder(string $html, string $key, string $replacement): string
    {
        $pattern = '/\{\{?\s*'.preg_quote($key, '/').'\s*\}?\}/';

        return preg_replace_callback($pattern, fn () => $replacement, $html) ?? $html;
    }

    private function wrapPrintable(GeneratedDocument $document, string $body): string
    {
        return view('frontend.printable', [
            'document' => $document,
            'rendered' => $body,
        ])->render();
    }
}
