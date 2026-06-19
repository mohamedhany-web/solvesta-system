<?php $__env->startSection('page-title', $user->name); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full font-tajawal">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => $user->name,
        'subtitle' => $user->email,
        'icon' => 'users',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-users')): ?>
        <a href="<?php echo e(route('users.edit', $user)); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-lg hover:opacity-95"
           style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">تعديل</a>
        <?php endif; ?>
        <a href="<?php echo e(route('users.index')); ?>" class="border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50">كل المستخدمين</a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8 space-y-6">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50/80">
                    <h2 class="font-bold text-lg">معلومات الحساب</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">الاسم</dt>
                            <dd class="font-semibold text-gray-900"><?php echo e($user->name); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">البريد</dt>
                            <dd class="font-semibold text-gray-900"><?php echo e($user->email); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">تاريخ الإنشاء</dt>
                            <dd class="text-gray-800"><?php echo e($user->created_at->format('Y/m/d H:i')); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">آخر تحديث</dt>
                            <dd class="text-gray-800"><?php echo e($user->updated_at->format('Y/m/d H:i')); ?></dd>
                        </div>
                    </dl>
                </div>
            </div>

            <?php if($user->employee): ?>
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50/80">
                    <h2 class="font-bold text-lg">بيانات الموظف</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">الرقم التوظيفي</dt>
                            <dd class="font-mono font-semibold"><?php echo e($user->employee->employee_id); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">القسم</dt>
                            <dd class="font-semibold"><?php echo e($user->employee->department?->name ?? '—'); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">المسمى</dt>
                            <dd><?php echo e($user->employee->position); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">الهاتف</dt>
                            <dd><?php echo e($user->employee->phone ?? '—'); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">نوع التوظيف</dt>
                            <dd><?php echo e(match($user->employee->employment_type) {
                                'full_time' => 'دوام كامل',
                                'part_time' => 'دوام جزئي',
                                'contract' => 'عقد',
                                'intern' => 'متدرب',
                                default => $user->employee->employment_type,
                            }); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">الحالة</dt>
                            <dd>
                                <span class="text-xs font-bold px-2 py-1 rounded-full <?php echo e($user->employee->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600'); ?>">
                                    <?php echo e($user->employee->status === 'active' ? 'نشط' : ($user->employee->status === 'inactive' ? 'غير نشط' : 'مفصول')); ?>

                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
            <?php endif; ?>

            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50/80">
                    <h2 class="font-bold text-lg">الأدوار والصلاحيات</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs font-bold text-gray-500 mb-2">الأدوار</p>
                        <div class="flex flex-wrap gap-2">
                            <?php $__empty_1 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <span class="text-xs font-bold px-3 py-1 rounded-full bg-blue-100 text-blue-800"><?php echo e($role->name); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <span class="text-sm text-gray-500">لا توجد أدوار</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if($user->permissions->isNotEmpty()): ?>
                    <div>
                        <p class="text-xs font-bold text-gray-500 mb-2">صلاحيات مباشرة</p>
                        <div class="flex flex-wrap gap-2">
                            <?php $__currentLoopData = $user->permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-emerald-100 text-emerald-800"><?php echo e($permission->name); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 space-y-4">
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-lg text-center">
                <div class="h-20 w-20 mx-auto rounded-2xl flex items-center justify-center text-white text-3xl font-bold mb-4" style="background: <?php echo e($themeColor); ?>;">
                    <?php echo e(mb_substr($user->name, 0, 1)); ?>

                </div>
                <h3 class="font-bold text-lg text-gray-900"><?php echo e($user->name); ?></h3>
                <p class="text-sm text-gray-500 mt-1"><?php echo e($user->email); ?></p>
                <?php if($user->employee?->department): ?>
                    <p class="text-sm text-gray-600 mt-2"><?php echo e($user->employee->department->name); ?></p>
                <?php endif; ?>
            </div>
            <div class="rounded-2xl border border-indigo-100 bg-indigo-50/60 p-5 text-sm">
                <p class="font-bold text-indigo-900 mb-2">النشاط</p>
                <ul class="space-y-2 text-indigo-800">
                    <li>أُنشئ <?php echo e($user->created_at->diffForHumans()); ?></li>
                    <?php if($user->updated_at->ne($user->created_at)): ?>
                    <li>آخر تحديث <?php echo e($user->updated_at->diffForHumans()); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-users')): ?>
            <?php if(!$user->hasRole('super_admin') && $user->id !== auth()->id()): ?>
            <form action="<?php echo e(route('users.destroy', $user)); ?>" method="POST" onsubmit="return confirm('حذف هذا المستخدم؟');">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="w-full py-2.5 rounded-xl border border-red-200 bg-red-50 text-red-700 text-sm font-bold hover:bg-red-100">حذف المستخدم</button>
            </form>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\users\show.blade.php ENDPATH**/ ?>