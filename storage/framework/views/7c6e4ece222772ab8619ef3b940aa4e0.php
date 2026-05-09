<?php $__env->startSection('title', 'Fill Document'); ?>

<?php $__env->startSection('steps'); ?>
    <?php echo $__env->make('frontend.partials.steps', ['current' => 3], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php
    $document = $document ?? null;
    $existingData = $document?->form_data ?? [];
    $formAction = $document
        ? route('generate.updateDraft', $document)
        : route('generate.store', $template);
    $formMethod = $document ? 'PUT' : 'POST';
?>

<?php $__env->startPush('styles'); ?>
<style>
    .form-grid-1 {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    .form-grid-4 {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    @media (min-width: 640px) {
        .form-grid-4 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (min-width: 768px) {
        .form-grid-4 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }
    @media (min-width: 1024px) {
        .form-grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    }
    .form-grid-4 > .col-full,
    .form-grid-1 > .col-full { grid-column: 1 / -1; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div x-data="{ showPreview: true }">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900"><?php echo e($template->template_name); ?></h1>
            <p class="text-slate-500">
                <span class="font-medium text-slate-700"><?php echo e($template->bank?->bank_name); ?></span>
                <?php if($template->category): ?> · <?php echo e($template->category->category_name); ?> <?php endif; ?>
                <?php if($document): ?>
                    · <span class="rounded-full bg-amber-100 text-amber-700 text-xs px-2 py-0.5">Resuming draft #<?php echo e($document->document_number); ?></span>
                <?php endif; ?>
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" @click="showPreview = !showPreview"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-white border border-slate-300 text-slate-700 text-sm hover:bg-slate-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                <span x-text="showPreview ? 'Hide Preview' : 'Show Live Preview'"></span>
            </button>
            <a href="<?php echo e(route('generate.templates', $template->bank_id)); ?>" class="text-sm text-slate-600 hover:text-indigo-600">← Change template</a>
        </div>
    </div>

    <?php if($template->fields->isEmpty()): ?>
        <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-lg p-5">
            This template has no input fields configured. Contact your administrator.
        </div>
    <?php else: ?>
        <div class="grid grid-cols-12 gap-6">
            
            <div :class="showPreview ? 'col-span-12 md:col-span-4' : 'col-span-12'">
                <form id="docForm" method="POST" action="<?php echo e($formAction); ?>" enctype="multipart/form-data"
                      class="bg-white border border-slate-200 rounded-lg p-5">
                    <?php echo csrf_field(); ?>
                    <?php if($formMethod === 'PUT'): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

                    <div :class="showPreview ? 'form-grid-1' : 'form-grid-4'">
                        <?php $__currentLoopData = $template->fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $name = 'fields['.$field->field_name.']';
                                $id = 'field_'.$field->field_name;
                                $errKey = 'fields.'.$field->field_name;
                                $existingVal = $existingData[$field->field_name] ?? $field->default_value;
                                $value = old($errKey, $existingVal);
                                $needsFullRow = in_array($field->field_type, ['textarea', 'image', 'signature', 'checkbox', 'radio'], true);
                            ?>

                            <div data-field-name="<?php echo e($field->field_name); ?>"
                                 class="<?php echo e($needsFullRow ? 'col-full' : ''); ?>">
                                <label for="<?php echo e($id); ?>" class="block text-sm font-medium text-slate-700 mb-1">
                                    <?php echo e($field->label); ?>

                                    <?php if($field->is_required): ?> <span class="text-red-500">*</span> <?php endif; ?>
                                </label>

                                <?php switch($field->field_type):
                                    case ('textarea'): ?>
                                        <textarea id="<?php echo e($id); ?>" name="<?php echo e($name); ?>" rows="3"
                                                  data-fname="<?php echo e($field->field_name); ?>" data-ftype="textarea"
                                                  placeholder="<?php echo e($field->placeholder); ?>"
                                                  <?php if($field->is_required): ?> required <?php endif; ?>
                                                  class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"><?php echo e($value); ?></textarea>
                                        <?php break; ?>

                                    <?php case ('select'): ?>
                                        <select id="<?php echo e($id); ?>" name="<?php echo e($name); ?>"
                                                data-fname="<?php echo e($field->field_name); ?>" data-ftype="select"
                                                <?php if($field->is_required): ?> required <?php endif; ?>
                                                class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            <option value="">— Select —</option>
                                            <?php $__currentLoopData = ($field->options ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($opt); ?>" <?php if($value == $opt): echo 'selected'; endif; ?>><?php echo e($opt); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php break; ?>

                                    <?php case ('radio'): ?>
                                        <div class="flex flex-wrap gap-4">
                                            <?php $__currentLoopData = ($field->options ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <label class="inline-flex items-center gap-2 text-sm">
                                                    <input type="radio" name="<?php echo e($name); ?>" value="<?php echo e($opt); ?>"
                                                           data-fname="<?php echo e($field->field_name); ?>" data-ftype="radio"
                                                           <?php if($value == $opt): echo 'checked'; endif; ?>
                                                           <?php if($field->is_required): ?> required <?php endif; ?>
                                                           class="border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                                    <span><?php echo e($opt); ?></span>
                                                </label>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        <?php break; ?>

                                    <?php case ('checkbox'): ?>
                                        <?php
                                            $existingCheck = $existingData[$field->field_name] ?? [];
                                            if (! is_array($existingCheck)) $existingCheck = [$existingCheck];
                                            $checkedValues = (array) old($errKey, $existingCheck);
                                        ?>
                                        <div class="flex flex-wrap gap-4">
                                            <?php $__currentLoopData = ($field->options ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <label class="inline-flex items-center gap-2 text-sm">
                                                    <input type="checkbox" name="<?php echo e($name); ?>[]" value="<?php echo e($opt); ?>"
                                                           data-fname="<?php echo e($field->field_name); ?>" data-ftype="checkbox"
                                                           <?php if(in_array($opt, $checkedValues)): echo 'checked'; endif; ?>
                                                           class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                                    <span><?php echo e($opt); ?></span>
                                                </label>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        <?php break; ?>

                                    <?php case ('image'): ?>
                                    <?php case ('signature'): ?>
                                        <input type="file" id="<?php echo e($id); ?>" name="<?php echo e($name); ?>" accept="image/*"
                                               data-fname="<?php echo e($field->field_name); ?>" data-ftype="<?php echo e($field->field_type); ?>"
                                               <?php if($field->is_required && empty($existingVal)): ?> required <?php endif; ?>
                                               class="block w-full text-sm">
                                        <?php if(! empty($existingVal)): ?>
                                            <div class="mt-2 flex items-center gap-2">
                                                <img src="<?php echo e(asset('storage/'.$existingVal)); ?>" class="h-12 rounded border" alt="">
                                                <span class="text-xs text-slate-500">Existing — upload new only if changing</span>
                                            </div>
                                        <?php endif; ?>
                                        <?php break; ?>

                                    <?php case ('date'): ?>
                                        <input type="date" id="<?php echo e($id); ?>" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>"
                                               data-fname="<?php echo e($field->field_name); ?>" data-ftype="date"
                                               <?php if($field->is_required): ?> required <?php endif; ?>
                                               class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <?php break; ?>

                                    <?php case ('email'): ?>
                                        <input type="email" id="<?php echo e($id); ?>" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>"
                                               data-fname="<?php echo e($field->field_name); ?>" data-ftype="email"
                                               placeholder="<?php echo e($field->placeholder); ?>"
                                               <?php if($field->is_required): ?> required <?php endif; ?>
                                               class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <?php break; ?>

                                    <?php case ('number'): ?>
                                        <input type="number" id="<?php echo e($id); ?>" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>"
                                               data-fname="<?php echo e($field->field_name); ?>" data-ftype="number"
                                               placeholder="<?php echo e($field->placeholder); ?>"
                                               <?php if($field->is_required): ?> required <?php endif; ?>
                                               class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <?php break; ?>

                                    <?php default: ?>
                                        <input type="text" id="<?php echo e($id); ?>" name="<?php echo e($name); ?>" value="<?php echo e($value); ?>"
                                               data-fname="<?php echo e($field->field_name); ?>" data-ftype="text"
                                               placeholder="<?php echo e($field->placeholder); ?>"
                                               <?php if($field->is_required): ?> required <?php endif; ?>
                                               class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <?php endswitch; ?>

                                <?php $__errorArgs = [$errKey];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="mt-6 pt-5 border-t border-slate-200 flex flex-wrap items-center gap-3">
                        <button type="submit" name="action" value="generate"
                                class="inline-flex items-center px-5 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                            Generate Preview →
                        </button>

                        <button type="submit" name="action" value="draft" formnovalidate
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-amber-500 text-white text-sm font-medium hover:bg-amber-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Draft
                        </button>

                        <a href="<?php echo e(route('generate.index')); ?>"
                           class="inline-flex items-center px-4 py-2 rounded-md bg-white border border-slate-300 text-slate-700 text-sm hover:bg-slate-50">
                            Cancel
                        </a>

                        <?php if($document): ?>
                            <span class="text-xs text-slate-500 ml-auto">Last saved: <?php echo e($document->updated_at->diffForHumans()); ?></span>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            
            <div x-show="showPreview" x-cloak class="col-span-12 md:col-span-8">
                <div class="md:sticky md:top-4">
                    <div class="bg-slate-900 text-white text-xs uppercase tracking-wider px-4 py-2 rounded-t-lg flex items-center justify-between">
                        <span>Live Preview</span>
                        <span class="text-slate-400 normal-case">updates as you type</span>
                    </div>
                    <div class="bg-white border border-slate-200 border-t-0 rounded-b-lg shadow-sm overflow-auto"
                         style="max-height: calc(100vh - 200px);">
                        <div id="livePreview" class="p-6 prose max-w-none text-sm">
                            <div class="text-center text-slate-400 py-12">Form bharo, yaha live document banta jayega…</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
(() => {
    const tplHtml = <?php echo json_encode($template->html_content ?? '', 15, 512) ?>;
    const fieldsMeta = <?php echo json_encode($template->fields->pluck('field_type', 'field_name'), 512) ?>;
    const previewEl = document.getElementById('livePreview');
    const formEl = document.getElementById('docForm');
    if (!previewEl || !formEl) return;

    const escapeHtml = (s) => String(s ?? '')
        .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;').replace(/'/g, '&#39;');

    const escapeRegex = (s) => s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');

    const fileDataUrls = {};

    const renderPreview = () => {
        const values = {};

        formEl.querySelectorAll('[data-fname]').forEach((el) => {
            const name = el.dataset.fname;
            const type = el.dataset.ftype;

            if (type === 'image' || type === 'signature') {
                if (fileDataUrls[name]) {
                    values[name] = `<img src="${fileDataUrls[name]}" alt="" style="max-height:80px;border:1px solid #ddd;">`;
                }
                return;
            }
            if (type === 'checkbox') {
                if (!values[name]) values[name] = [];
                if (el.checked) values[name].push(el.value);
                return;
            }
            if (type === 'radio') {
                if (el.checked) values[name] = el.value;
                return;
            }
            values[name] = el.value;
        });

        let html = tplHtml;
        if (!html) {
            previewEl.innerHTML = '<div class="text-center text-slate-400 py-12">Template HTML empty hai.</div>';
            return;
        }

        Object.keys(fieldsMeta).forEach((name) => {
            const type = fieldsMeta[name];
            let val = values[name];

            const isEmpty = val === undefined || val === null || val === '' ||
                            (Array.isArray(val) && val.length === 0);

            const re = new RegExp(`\\{{1,2}\\s*${escapeRegex(name)}\\s*\\}{1,2}`, 'g');

            if (isEmpty) {
                const placeholder = `<span style="background:#fef3c7;color:#92400e;padding:0 4px;border-radius:3px;font-size:90%;">${escapeHtml('{' + name + '}')}</span>`;
                html = html.replace(re, placeholder);
                return;
            }

            let replacement;
            if (type === 'image' || type === 'signature') {
                replacement = val;
            } else if (Array.isArray(val)) {
                replacement = escapeHtml(val.join(', '));
            } else {
                replacement = escapeHtml(val);
            }
            html = html.replace(re, replacement);
        });

        previewEl.innerHTML = html;
    };

    formEl.addEventListener('change', (e) => {
        const t = e.target;
        if (t.type === 'file' && t.dataset.fname) {
            const name = t.dataset.fname;
            if (!t.files || !t.files[0]) {
                delete fileDataUrls[name];
                renderPreview();
                return;
            }
            const reader = new FileReader();
            reader.onload = (ev) => {
                fileDataUrls[name] = ev.target.result;
                renderPreview();
            };
            reader.readAsDataURL(t.files[0]);
        } else {
            renderPreview();
        }
    });

    formEl.addEventListener('input', renderPreview);

    renderPreview();
})();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\laravel\dynamicWord-main\dynamicWord-main\resources\views/frontend/form.blade.php ENDPATH**/ ?>