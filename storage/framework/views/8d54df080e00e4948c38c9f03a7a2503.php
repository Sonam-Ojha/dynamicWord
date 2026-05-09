<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['current' => 1]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['current' => 1]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $steps = [
        1 => 'Bank',
        2 => 'Template',
        3 => 'Form',
        4 => 'Preview',
    ];
?>

<ol class="flex items-center gap-2 text-xs sm:text-sm overflow-x-auto">
    <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $state = $num < $current ? 'done' : ($num === $current ? 'active' : 'todo');
        ?>
        <li class="flex items-center gap-2 shrink-0">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-semibold
                <?php echo e($state === 'done' ? 'bg-green-500 text-white' : ($state === 'active' ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-500')); ?>">
                <?php echo e($num); ?>

            </span>
            <span class="<?php echo e($state === 'todo' ? 'text-slate-400' : 'text-slate-800 font-medium'); ?>"><?php echo e($label); ?></span>
            <?php if(! $loop->last): ?>
                <span class="w-6 h-px bg-slate-300 mx-1"></span>
            <?php endif; ?>
        </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ol>
<?php /**PATH C:\laragon\www\laravel\dynamicWord-main\dynamicWord-main\resources\views/frontend/partials/steps.blade.php ENDPATH**/ ?>