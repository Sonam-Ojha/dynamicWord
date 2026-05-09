<?php $__env->startSection('title', 'Select Bank'); ?>

<?php $__env->startSection('steps'); ?>
    <?php echo $__env->make('frontend.partials.steps', ['current' => 1], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Select a Bank</h1>
        <p class="text-slate-500">Choose the bank for which you want to generate a document.</p>
    </div>

    <?php if($banks->isEmpty()): ?>
        <div class="bg-white border border-slate-200 rounded-lg p-10 text-center text-slate-500">
            No active banks available. Contact your administrator.
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('generate.templates', $bank)); ?>"
                   class="group bg-white border border-slate-200 rounded-lg p-5 hover:border-indigo-500 hover:shadow-md transition">
                    <div class="flex items-center gap-4">
                        <?php if($bank->logo): ?>
                            <img src="<?php echo e(asset('storage/'.$bank->logo)); ?>" class="w-14 h-14 rounded object-cover" alt="">
                        <?php else: ?>
                            <div class="w-14 h-14 rounded bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xl">
                                <?php echo e(strtoupper(substr($bank->bank_name, 0, 2))); ?>

                            </div>
                        <?php endif; ?>
                        <div class="min-w-0">
                            <div class="font-semibold text-slate-900 group-hover:text-indigo-600 truncate"><?php echo e($bank->bank_name); ?></div>
                            <div class="text-xs text-slate-500"><?php echo e($bank->bank_code); ?></div>
                        </div>
                    </div>
                    <?php if($bank->description): ?>
                        <p class="mt-3 text-sm text-slate-600 line-clamp-2"><?php echo e($bank->description); ?></p>
                    <?php endif; ?>
                    <div class="mt-4 text-xs text-indigo-600 font-medium">Choose →</div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\laravel\dynamicWord-main\dynamicWord-main\resources\views/frontend/banks.blade.php ENDPATH**/ ?>