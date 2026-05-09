<?php $__env->startSection('title', 'Preview Document'); ?>

<?php $__env->startSection('steps'); ?>
    <?php echo $__env->make('frontend.partials.steps', ['current' => 4], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Preview Document</h1>
            <p class="text-slate-500">
                Document #<span class="font-medium text-slate-700"><?php echo e($document->document_number); ?></span>
                · <?php echo e($document->bank?->bank_name); ?> · <?php echo e($document->template?->template_name); ?>

                ·
                <span class="rounded-full px-2 py-0.5 text-xs
                    <?php echo e($document->status === 'generated' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'); ?>">
                    <?php echo e(ucfirst($document->status)); ?>

                </span>
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <?php if($document->status === 'draft'): ?>
                <a href="<?php echo e(route('generate.editDraft', $document)); ?>"
                   class="inline-flex items-center px-3 py-2 rounded-md bg-amber-500 text-white text-sm font-medium hover:bg-amber-600">
                    ← Continue Editing
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('generate.form', $document->template_id)); ?>"
                   class="inline-flex items-center px-3 py-2 rounded-md bg-white border border-slate-300 text-slate-700 text-sm hover:bg-slate-50">
                    New from this template
                </a>
            <?php endif; ?>

            <?php if($document->status !== 'generated'): ?>
                <form method="POST" action="<?php echo e(route('generate.finalize', $document)); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="inline-flex items-center px-3 py-2 rounded-md bg-green-600 text-white text-sm font-medium hover:bg-green-700">
                        Finalize Document
                    </button>
                </form>
            <?php endif; ?>

            <a href="<?php echo e(route('generate.print', $document)); ?>" target="_blank"
               class="inline-flex items-center px-3 py-2 rounded-md bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                Print
            </a>

            <?php if($document->status === 'generated'): ?>
                <?php if (isset($component)) { $__componentOriginal165529808575b7b9a34521c85616a5c0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal165529808575b7b9a34521c85616a5c0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.download-menu','data' => ['document' => $document]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('download-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['document' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($document)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal165529808575b7b9a34521c85616a5c0)): ?>
<?php $attributes = $__attributesOriginal165529808575b7b9a34521c85616a5c0; ?>
<?php unset($__attributesOriginal165529808575b7b9a34521c85616a5c0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal165529808575b7b9a34521c85616a5c0)): ?>
<?php $component = $__componentOriginal165529808575b7b9a34521c85616a5c0; ?>
<?php unset($__componentOriginal165529808575b7b9a34521c85616a5c0); ?>
<?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
        <div class="bg-slate-50 border-b border-slate-200 px-5 py-3 text-xs text-slate-500">
            Live preview — placeholders replaced with your input.
        </div>
        <div class="p-8 prose max-w-none">
            <?php echo $rendered; ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\laravel\dynamicWord-main\dynamicWord-main\resources\views/frontend/preview.blade.php ENDPATH**/ ?>