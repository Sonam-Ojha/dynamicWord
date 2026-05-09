<?php $__env->startSection('title', 'Select Template'); ?>

<?php $__env->startSection('steps'); ?>
    <?php echo $__env->make('frontend.partials.steps', ['current' => 2], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-6 flex items-start justify-between gap-3">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Select Template</h1>
            <p class="text-slate-500">For <span class="font-medium text-slate-700"><?php echo e($bank->bank_name); ?></span> — pick a document template to fill.</p>
        </div>
        <a href="<?php echo e(route('generate.banks')); ?>" class="text-sm text-slate-600 hover:text-indigo-600">← Change bank</a>
    </div>

    <?php if($templates->isEmpty()): ?>
        <div class="bg-white border border-slate-200 rounded-lg p-10 text-center text-slate-500">
            No templates available for this bank yet.
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('generate.form', $template)); ?>"
                   class="group bg-white border border-slate-200 rounded-lg overflow-hidden hover:border-indigo-500 hover:shadow-md transition flex flex-col">
                    <div class="aspect-video bg-slate-100 flex items-center justify-center overflow-hidden">
                        <?php if($template->template_preview): ?>
                            <img src="<?php echo e(asset('storage/'.$template->template_preview)); ?>" class="w-full h-full object-cover" alt="">
                        <?php else: ?>
                            <span class="text-slate-400 text-sm">No preview</span>
                        <?php endif; ?>
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-center justify-between gap-2">
                            <div class="font-semibold text-slate-900 group-hover:text-indigo-600 truncate"><?php echo e($template->template_name); ?></div>
                            <?php if($template->category): ?>
                                <span class="rounded bg-slate-100 text-slate-600 text-xs px-2 py-0.5 shrink-0"><?php echo e($template->category->category_name); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="text-xs text-slate-500 mt-1"><?php echo e($template->template_code); ?></div>
                        <?php if($template->description): ?>
                            <p class="text-sm text-slate-600 mt-2 line-clamp-2"><?php echo e($template->description); ?></p>
                        <?php endif; ?>
                        <div class="mt-auto pt-3 text-xs text-indigo-600 font-medium">Use this template →</div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\laravel\dynamicWord-main\dynamicWord-main\resources\views/frontend/templates.blade.php ENDPATH**/ ?>