<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TemplateRequest;
use App\Models\Bank;
use App\Models\Template;
use App\Models\TemplateCategory;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemplateController extends Controller
{
    public function __construct(private FileUploadService $files)
    {
    }

    public function index(Request $request): View
    {
        $templates = Template::query()
            ->with(['bank', 'category'])
            ->search($request->input('q'))
            ->when($request->input('bank_id'), fn ($q, $v) => $q->where('bank_id', $v))
            ->when($request->input('category_id'), fn ($q, $v) => $q->where('category_id', $v))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $banks = Bank::active()->get(['id', 'bank_name']);
        $categories = TemplateCategory::active()->get(['id', 'category_name']);

        return view('admin.templates.index', compact('templates', 'banks', 'categories'));
    }

    public function create(): View
    {
        $banks = Bank::active()->get(['id', 'bank_name']);
        $categories = TemplateCategory::active()->get(['id', 'category_name']);

        return view('admin.templates.create', compact('banks', 'categories'));
    }

    public function store(TemplateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('template_preview')) {
            $data['template_preview'] = $this->files->upload($request->file('template_preview'), 'templates');
        }

        $template = Template::create($data);

        return redirect()->route('admin.templates.edit', $template)->with('success', 'Template created successfully.');
    }

    public function edit(Template $template): View
    {
        $banks = Bank::active()->get(['id', 'bank_name']);
        $categories = TemplateCategory::active()->get(['id', 'category_name']);

        return view('admin.templates.edit', compact('template', 'banks', 'categories'));
    }

    public function update(TemplateRequest $request, Template $template): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('template_preview')) {
            $data['template_preview'] = $this->files->replace($template->template_preview, $request->file('template_preview'), 'templates');
        }

        $template->update($data);

        return redirect()->route('admin.templates.index')->with('success', 'Template updated successfully.');
    }

    public function destroy(Template $template): RedirectResponse
    {
        $this->files->delete($template->template_preview);
        $template->delete();

        return redirect()->route('admin.templates.index')->with('success', 'Template deleted successfully.');
    }

    public function toggleStatus(Template $template): RedirectResponse
    {
        $template->update(['status' => ! $template->status]);

        return back()->with('success', 'Status updated.');
    }
}
