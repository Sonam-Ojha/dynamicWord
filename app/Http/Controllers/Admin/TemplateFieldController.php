<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TemplateFieldRequest;
use App\Models\Template;
use App\Models\TemplateField;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TemplateFieldController extends Controller
{
    public function index(Template $template): View
    {
        $fields = $template->fields()->paginate(20);

        return view('admin.template_fields.index', compact('template', 'fields'));
    }

    public function create(Template $template): View
    {
        $fieldTypes = TemplateField::fieldTypes();
        return view('admin.template_fields.create', compact('template', 'fieldTypes'));
    }

    public function store(TemplateFieldRequest $request, Template $template): RedirectResponse
    {
        $data = $request->validated();
        $data['template_id'] = $template->id;
        $data['is_required'] = $request->boolean('is_required');
        $data['status'] = $request->boolean('status');
        $data['options'] = $this->normalizeOptions($request->input('options'));

        TemplateField::create($data);

        return redirect()->route('admin.templates.fields.index', $template)
            ->with('success', 'Field created successfully.');
    }

    public function edit(TemplateField $field): View
    {
        $template = $field->template;
        $fieldTypes = TemplateField::fieldTypes();

        return view('admin.template_fields.edit', compact('template', 'field', 'fieldTypes'));
    }

    public function update(TemplateFieldRequest $request, TemplateField $field): RedirectResponse
    {
        $data = $request->validated();
        $data['is_required'] = $request->boolean('is_required');
        $data['status'] = $request->boolean('status');
        $data['options'] = $this->normalizeOptions($request->input('options'));

        $field->update($data);

        return redirect()->route('admin.templates.fields.index', $field->template_id)
            ->with('success', 'Field updated successfully.');
    }

    public function destroy(TemplateField $field): RedirectResponse
    {
        $templateId = $field->template_id;
        $field->delete();

        return redirect()->route('admin.templates.fields.index', $templateId)
            ->with('success', 'Field deleted successfully.');
    }

    public function toggleStatus(TemplateField $field): RedirectResponse
    {
        $field->update(['status' => ! $field->status]);

        return back()->with('success', 'Status updated.');
    }

    public function bulkToggle(Request $request, Template $template): RedirectResponse
    {
        $ids = $request->input('field_ids', []);
        $action = $request->input('bulk_action');

        if (empty($ids) || ! in_array($action, ['enable', 'disable', 'require', 'unrequire', 'delete'], true)) {
            return back()->with('error', 'Select fields and choose an action.');
        }

        $query = $template->fields()->whereIn('id', $ids);
        $count = (clone $query)->count();

        match ($action) {
            'enable' => $query->update(['status' => true]),
            'disable' => $query->update(['status' => false]),
            'require' => $query->update(['is_required' => true]),
            'unrequire' => $query->update(['is_required' => false]),
            'delete' => $query->delete(),
        };

        $verb = match ($action) {
            'enable' => 'shown in form',
            'disable' => 'hidden from form',
            'require' => 'marked required',
            'unrequire' => 'marked optional',
            'delete' => 'deleted',
        };

        return back()->with('success', "{$count} field(s) {$verb}.");
    }

    public function sync(Template $template): View
    {
        $placeholders = $this->extractPlaceholders($template->html_content ?? '');
        $existingFields = $template->fields()->get()->keyBy('field_name');

        $analysis = [];
        foreach ($placeholders as $name) {
            $analysis[] = [
                'name' => $name,
                'suggested_label' => Str::title(str_replace('_', ' ', $name)),
                'suggested_type' => $this->guessType($name),
                'exists' => $existingFields->has($name),
            ];
        }

        $orphans = $existingFields->keys()->diff($placeholders)->values()->all();
        $fieldTypes = TemplateField::fieldTypes();

        return view('admin.template_fields.sync', compact('template', 'analysis', 'orphans', 'fieldTypes'));
    }

    public function bulkSync(Request $request, Template $template): RedirectResponse
    {
        $names = $request->input('placeholders', []);
        $types = $request->input('types', []);
        $required = $request->input('required', []);

        $maxOrder = (int) $template->fields()->max('sort_order');
        $created = 0;
        $skipped = 0;

        foreach ($names as $name) {
            $name = trim((string) $name);
            if ($name === '') continue;

            if ($template->fields()->where('field_name', $name)->exists()) {
                $skipped++;
                continue;
            }

            $template->fields()->create([
                'field_name' => $name,
                'label' => Str::title(str_replace('_', ' ', $name)),
                'field_type' => in_array($types[$name] ?? 'text', array_keys(TemplateField::fieldTypes()), true)
                    ? $types[$name]
                    : 'text',
                'is_required' => ! empty($required[$name]),
                'sort_order' => ++$maxOrder,
                'status' => true,
            ]);
            $created++;
        }

        $msg = "Created {$created} field(s)";
        if ($skipped) $msg .= ", skipped {$skipped} (already exist)";

        return redirect()
            ->route('admin.templates.fields.index', $template)
            ->with('success', $msg.'.');
    }

    private function extractPlaceholders(string $html): array
    {
        preg_match_all('/\{\{?\s*([a-zA-Z_][a-zA-Z0-9_]*)\s*\}?\}/', $html, $matches);
        return array_values(array_unique($matches[1] ?? []));
    }

    private function guessType(string $name): string
    {
        $n = strtolower($name);

        return match (true) {
            str_contains($n, 'email') => 'email',
            str_contains($n, 'date') || str_ends_with($n, '_dob') || $n === 'dob' => 'date',
            str_contains($n, 'amount') || str_contains($n, 'rate') || str_contains($n, 'year')
                || str_contains($n, 'count') || str_ends_with($n, '_no') => 'number',
            str_contains($n, 'signature') => 'signature',
            str_contains($n, 'image') || str_contains($n, 'photo') || str_contains($n, 'logo') => 'image',
            str_contains($n, 'address') || str_contains($n, 'description') || str_contains($n, 'remarks')
                || str_contains($n, 'note') => 'textarea',
            default => 'text',
        };
    }

    private function normalizeOptions(mixed $options): ?array
    {
        if (! $options) {
            return null;
        }

        if (is_string($options)) {
            $options = array_map('trim', explode(',', $options));
        }

        return array_values(array_filter((array) $options, fn ($v) => $v !== null && $v !== ''));
    }
}
