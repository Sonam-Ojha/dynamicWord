<?php $__env->startSection('title', 'Profile Settings'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Profile Settings</h1>
        <p class="text-sm text-slate-500">Manage your account information, password and preferences.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg border border-slate-200 p-6 text-center">
                <?php if(auth()->user()->profile_image): ?>
                    <img src="<?php echo e(asset('storage/'.auth()->user()->profile_image)); ?>"
                         class="w-24 h-24 mx-auto rounded-full object-cover border-4 border-white shadow" alt="">
                <?php else: ?>
                    <div class="w-24 h-24 mx-auto rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-3xl font-bold border-4 border-white shadow">
                        <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                    </div>
                <?php endif; ?>

                <h3 class="mt-4 font-semibold text-slate-900 text-lg"><?php echo e(auth()->user()->name); ?></h3>
                <p class="text-sm text-slate-500"><?php echo e(auth()->user()->email); ?></p>

                <div class="mt-3 flex flex-wrap justify-center gap-1.5">
                    <?php $__currentLoopData = auth()->user()->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="rounded-full bg-indigo-50 text-indigo-700 text-xs font-medium px-2.5 py-0.5">
                            <?php echo e($role->name); ?>

                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="mt-5 pt-5 border-t border-slate-100 text-left text-sm space-y-2">
                    <?php if(auth()->user()->phone): ?>
                        <div class="flex items-center gap-2 text-slate-600">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <?php echo e(auth()->user()->phone); ?>

                        </div>
                    <?php endif; ?>
                    <div class="flex items-center gap-2 text-slate-600">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Joined <?php echo e(auth()->user()->created_at->format('M Y')); ?>

                    </div>
                    <div class="flex items-center gap-2 text-emerald-700">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Account active
                    </div>
                </div>
            </div>
        </div>

        
        <div class="lg:col-span-2 space-y-5">

            
            <div class="bg-white rounded-lg border border-slate-200 p-6">
                <div class="flex items-start gap-3 mb-5">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900">Profile Information</h3>
                        <p class="text-sm text-slate-500">Update your name and email address.</p>
                    </div>
                </div>

                <form method="POST" action="<?php echo e(route('profile.update')); ?>" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>

                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Name</label>
                        <input id="name" name="name" type="text" value="<?php echo e(old('name', $user->name)); ?>"
                               required autofocus autocomplete="name"
                               class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input id="email" name="email" type="email" value="<?php echo e(old('email', $user->email)); ?>"
                               required autocomplete="username"
                               class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">
                            Save Changes
                        </button>
                        <?php if(session('status') === 'profile-updated'): ?>
                            <span x-data="{ show: true }" x-show="show" x-transition
                                  x-init="setTimeout(() => show = false, 3000)"
                                  class="text-sm text-emerald-600 font-medium">✓ Saved</span>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            
            <div class="bg-white rounded-lg border border-slate-200 p-6">
                <div class="flex items-start gap-3 mb-5">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900">Change Password</h3>
                        <p class="text-sm text-slate-500">Use a long, random password to stay secure.</p>
                    </div>
                </div>

                <form method="POST" action="<?php echo e(route('password.update')); ?>" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1">Current Password</label>
                        <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                               class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <?php if($errors->updatePassword->get('current_password')): ?>
                            <p class="mt-1 text-xs text-red-600"><?php echo e($errors->updatePassword->first('current_password')); ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">New Password</label>
                        <input id="password" name="password" type="password" autocomplete="new-password"
                               class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <?php if($errors->updatePassword->get('password')): ?>
                            <p class="mt-1 text-xs text-red-600"><?php echo e($errors->updatePassword->first('password')); ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm New Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                               class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <?php if($errors->updatePassword->get('password_confirmation')): ?>
                            <p class="mt-1 text-xs text-red-600"><?php echo e($errors->updatePassword->first('password_confirmation')); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">
                            Update Password
                        </button>
                        <?php if(session('status') === 'password-updated'): ?>
                            <span x-data="{ show: true }" x-show="show" x-transition
                                  x-init="setTimeout(() => show = false, 3000)"
                                  class="text-sm text-emerald-600 font-medium">✓ Saved</span>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            
            <div class="bg-white rounded-lg border border-red-200 p-6"
                 x-data="{ confirming: <?php echo e($errors->userDeletion->isNotEmpty() ? 'true' : 'false'); ?> }">
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900">Delete Account</h3>
                        <p class="text-sm text-slate-500">Permanently delete your account and all data. This cannot be undone.</p>
                    </div>
                </div>

                <button type="button" @click="confirming = true" x-show="!confirming"
                        class="px-4 py-2 rounded-md bg-red-600 text-white text-sm font-semibold hover:bg-red-700">
                    Delete My Account
                </button>

                <form x-show="confirming" x-cloak method="POST" action="<?php echo e(route('profile.destroy')); ?>"
                      class="mt-2 p-4 bg-red-50 border border-red-200 rounded-md space-y-3">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>

                    <p class="text-sm text-red-800">
                        Are you sure? Enter your password to confirm permanent deletion.
                    </p>

                    <input id="delete_password" name="password" type="password" placeholder="Your password"
                           class="block w-full rounded-md border-red-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                    <?php if($errors->userDeletion->get('password')): ?>
                        <p class="text-xs text-red-700"><?php echo e($errors->userDeletion->first('password')); ?></p>
                    <?php endif; ?>

                    <div class="flex items-center gap-2">
                        <button type="submit"
                                class="px-4 py-2 rounded-md bg-red-600 text-white text-sm font-semibold hover:bg-red-700">
                            Yes, Delete Permanently
                        </button>
                        <button type="button" @click="confirming = false"
                                class="px-4 py-2 rounded-md bg-white border border-slate-300 text-slate-700 text-sm hover:bg-slate-50">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\laravel\dynamicWord-main\dynamicWord-main\resources\views/profile/edit.blade.php ENDPATH**/ ?>