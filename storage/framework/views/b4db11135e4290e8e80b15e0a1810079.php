<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Dashboard</h1>
        <p class="text-sm text-slate-500">Welcome back, <?php echo e(auth()->user()->name); ?>.</p>
    </div>

    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg border border-slate-200 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Documents</div>
                    <div class="text-3xl font-bold text-slate-900 mt-2"><?php echo e($stats['total']); ?></div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l5 5v11a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div class="text-xs text-slate-500 mt-3">All time</div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">Generated</div>
                    <div class="text-3xl font-bold text-emerald-600 mt-2"><?php echo e($stats['generated']); ?></div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>
            <div class="text-xs text-slate-500 mt-3">Ready to print/download</div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">Drafts</div>
                    <div class="text-3xl font-bold text-amber-600 mt-2"><?php echo e($stats['drafts']); ?></div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
            </div>
            <div class="text-xs text-slate-500 mt-3">Pending — resume to finish</div>
        </div>

        <?php
            $thisMonth = \App\Models\GeneratedDocument::where('user_id', auth()->id())
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
        ?>
        <div class="bg-white rounded-lg border border-slate-200 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-xs font-medium text-slate-500 uppercase tracking-wider">This Month</div>
                    <div class="text-3xl font-bold text-indigo-600 mt-2"><?php echo e($thisMonth); ?></div>
                </div>
                <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="text-xs text-slate-500 mt-3"><?php echo e(now()->format('F Y')); ?></div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <a href="<?php echo e(route('generate.banks')); ?>"
           class="bg-indigo-600 hover:bg-indigo-700 rounded-lg p-5 text-white transition group">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-sm font-medium text-indigo-100">Generate</div>
                    <div class="text-lg font-bold mt-1">New Document</div>
                    <div class="text-xs text-indigo-100 mt-2">Pick bank → Pick template → Fill form</div>
                </div>
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="opacity-50 group-hover:opacity-100 transition"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </div>
        </a>

        <a href="<?php echo e(route('generate.myDocuments', ['status' => 'draft'])); ?>"
           class="bg-white hover:bg-slate-50 border border-slate-200 rounded-lg p-5 transition group">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-sm font-medium text-amber-600">Continue</div>
                    <div class="text-lg font-bold text-slate-900 mt-1">Resume Draft</div>
                    <div class="text-xs text-slate-500 mt-2"><?php echo e($stats['drafts']); ?> draft(s) waiting</div>
                </div>
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="text-slate-400 group-hover:text-slate-700 transition"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </div>
        </a>

        <a href="<?php echo e(route('generate.myDocuments')); ?>"
           class="bg-white hover:bg-slate-50 border border-slate-200 rounded-lg p-5 transition group">
            <div class="flex items-start justify-between">
                <div>
                    <div class="text-sm font-medium text-emerald-600">View</div>
                    <div class="text-lg font-bold text-slate-900 mt-1">All Documents</div>
                    <div class="text-xs text-slate-500 mt-2">Search & filter your records</div>
                </div>
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="text-slate-400 group-hover:text-slate-700 transition"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </div>
        </a>
    </div>

    
    <div class="bg-white rounded-lg border border-slate-200">
        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
            <h3 class="font-semibold text-slate-900">Recent Documents</h3>
            <a href="<?php echo e(route('generate.myDocuments')); ?>" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                View all →
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3 text-left">Document #</th>
                        <th class="px-5 py-3 text-left">Bank</th>
                        <th class="px-5 py-3 text-left">Template</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Created</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php $__empty_1 = true; $__currentLoopData = $myDocs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3 font-mono text-xs"><?php echo e($doc->document_number); ?></td>
                            <td class="px-5 py-3"><?php echo e($doc->bank?->bank_name); ?></td>
                            <td class="px-5 py-3"><?php echo e($doc->template?->template_name); ?></td>
                            <td class="px-5 py-3">
                                <?php if($doc->status === 'generated'): ?>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 text-emerald-700 px-2 py-0.5 text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Generated
                                    </span>
                                <?php elseif($doc->status === 'archived'): ?>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 text-slate-600 px-2 py-0.5 text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Archived
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 text-amber-700 px-2 py-0.5 text-xs font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Draft
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-3 text-slate-500"><?php echo e($doc->created_at->diffForHumans()); ?></td>
                            <td class="px-5 py-3 text-right space-x-3">
                                <?php if($doc->status === 'draft'): ?>
                                    <a href="<?php echo e(route('generate.editDraft', $doc)); ?>" class="text-amber-600 hover:text-amber-800 font-medium">Resume</a>
                                <?php endif; ?>
                                <a href="<?php echo e(route('generate.preview', $doc)); ?>" class="text-indigo-600 hover:text-indigo-800">View</a>
                                <?php if($doc->status === 'generated'): ?>
                                    <?php if (isset($component)) { $__componentOriginal165529808575b7b9a34521c85616a5c0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal165529808575b7b9a34521c85616a5c0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.download-menu','data' => ['document' => $doc,'size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('download-menu'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['document' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($doc),'size' => 'sm']); ?>
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
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <p class="text-slate-500 mb-3">No documents yet.</p>
                                <a href="<?php echo e(route('generate.banks')); ?>" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    Generate your first document →
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\laravel\dynamicWord-main\dynamicWord-main\resources\views/frontend/dashboard.blade.php ENDPATH**/ ?>