<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneratedDocumentRequest;
use App\Models\Bank;
use App\Models\GeneratedDocument;
use App\Models\Template;
use App\Models\User;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GeneratedDocumentController extends Controller
{
    public function __construct(private FileUploadService $files)
    {
    }

    public function index(Request $request): View
    {
        $documents = GeneratedDocument::query()
            ->with(['user', 'bank', 'template'])
            ->search($request->input('q'))
            ->when($request->input('bank_id'), fn ($q, $v) => $q->where('bank_id', $v))
            ->when($request->input('status'), fn ($q, $v) => $q->where('status', $v))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $banks = Bank::active()->get(['id', 'bank_name']);

        return view('admin.documents.index', compact('documents', 'banks'));
    }

    public function create(): View
    {
        $users = User::active()->get(['id', 'name']);
        $banks = Bank::active()->get(['id', 'bank_name']);
        $templates = Template::active()->get(['id', 'template_name', 'bank_id']);

        return view('admin.documents.create', compact('users', 'banks', 'templates'));
    }

    public function store(GeneratedDocumentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('generated_file')) {
            $data['generated_file'] = $this->files->upload($request->file('generated_file'), 'documents');
        }

        GeneratedDocument::create($data);

        return redirect()->route('admin.documents.index')->with('success', 'Document created successfully.');
    }

    public function show(GeneratedDocument $document): View
    {
        $document->load(['user', 'bank', 'template.fields']);
        return view('admin.documents.show', compact('document'));
    }

    public function edit(GeneratedDocument $document): View
    {
        $users = User::active()->get(['id', 'name']);
        $banks = Bank::active()->get(['id', 'bank_name']);
        $templates = Template::active()->get(['id', 'template_name', 'bank_id']);

        return view('admin.documents.edit', compact('document', 'users', 'banks', 'templates'));
    }

    public function update(GeneratedDocumentRequest $request, GeneratedDocument $document): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('generated_file')) {
            $data['generated_file'] = $this->files->replace($document->generated_file, $request->file('generated_file'), 'documents');
        }

        $document->update($data);

        return redirect()->route('admin.documents.index')->with('success', 'Document updated successfully.');
    }

    public function destroy(GeneratedDocument $document): RedirectResponse
    {
        $this->files->delete($document->generated_file);
        $document->delete();

        return redirect()->route('admin.documents.index')->with('success', 'Document deleted successfully.');
    }
}
