<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TemplateCategoryRequest;
use App\Models\TemplateCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemplateCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = TemplateCategory::query()
            ->search($request->input('q'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(TemplateCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');

        TemplateCategory::create($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(TemplateCategory $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(TemplateCategoryRequest $request, TemplateCategory $category): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->boolean('status');

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(TemplateCategory $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    public function toggleStatus(TemplateCategory $category): RedirectResponse
    {
        $category->update(['status' => ! $category->status]);

        return back()->with('success', 'Status updated.');
    }
}
