<?php $__env->startSection('page-title', 'إدارة المستخدمين'); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full font-tajawal">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'إدارة المستخدمين',
        'subtitle' => 'حسابات الدخول، الأدوار، وربط الموظفين بالأقسام',
        'icon' => 'users',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-users')): ?>
        <a href="<?php echo e(route('users.create')); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-lg hover:opacity-95"
           style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            مستخدم جديد
        </a>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <?php $__currentLoopData = [
            ['إجمالي المستخدمين', $stats['total'], $themeColor],
            ['بريد مُفعّل', $stats['verified'], '#059669'],
            ['بانتظار التأكيد', $stats['pending'], '#d97706'],
            ['مديرون', $stats['admins'], '#7c3aed'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5">
            <p class="text-xs text-gray-500"><?php echo e($label); ?></p>
            <p class="text-3xl font-bold mt-1" style="color: <?php echo e($color); ?>;"><?php echo e($val); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <form method="GET" class="bg-white border border-gray-200 rounded-2xl p-4 mb-6 flex flex-wrap gap-3 items-end shadow-sm">
        <div class="flex-1 min-w-[12rem]">
            <label class="text-xs font-bold text-gray-600 block mb-1">بحث</label>
            <input name="search" value="<?php echo e(request('search')); ?>" placeholder="الاسم أو البريد..."
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">الدور</label>
            <select name="role" class="border border-gray-300 rounded-xl px-3 py-2 text-sm min-w-[10rem]">
                <option value="">الكل</option>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($role->name); ?>" <?php if(request('role') === $role->name): echo 'selected'; endif; ?>><?php echo e($role->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">القسم</label>
            <select name="department_id" class="border border-gray-300 rounded-xl px-3 py-2 text-sm min-w-[10rem]">
                <option value="">الكل</option>
                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($department->id); ?>" <?php if(request('department_id') == $department->id): echo 'selected'; endif; ?>><?php echo e($department->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-xl text-sm font-bold">تصفية</button>
        <?php if(request()->hasAny(['search', 'role', 'department_id'])): ?>
        <a href="<?php echo e(route('users.index')); ?>" class="border border-gray-300 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-gray-50">إعادة تعيين</a>
        <?php endif; ?>
    </form>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-900">قائمة المستخدمين <span class="text-gray-400 font-normal text-sm">(<?php echo e($users->total()); ?>)</span></h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">المستخدم</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">البريد</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">القسم</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">الأدوار</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">الحالة</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">تاريخ الإنشاء</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-blue-50/40 transition-colors align-middle">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl flex items-center justify-center text-white font-bold shrink-0" style="background: <?php echo e($themeColor); ?>;">
                                    <?php echo e(mb_substr($user->name, 0, 1)); ?>

                                </div>
                                <div>
                                    <a href="<?php echo e(route('users.show', $user)); ?>" class="font-bold text-gray-900 hover:text-blue-700"><?php echo e($user->name); ?></a>
                                    <?php if($user->employee?->position): ?>
                                        <p class="text-xs text-gray-500"><?php echo e($user->employee->position); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-700"><?php echo e($user->email); ?></td>
                        <td class="px-4 py-3 text-gray-600"><?php echo e($user->employee?->department?->name ?? '—'); ?></td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                <?php $__empty_2 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800"><?php echo e($role->name); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                    <span class="text-gray-400 text-xs">—</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <?php if($user->employee): ?>
                                <span class="text-xs font-bold px-2 py-1 rounded-full <?php echo e($user->employee->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600'); ?>">
                                    <?php echo e($user->employee->status === 'active' ? 'نشط' : ($user->employee->status === 'inactive' ? 'غير نشط' : 'مفصول')); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-xs text-gray-400">بدون موظف</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap"><?php echo e($user->created_at->format('Y/m/d')); ?></td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <a href="<?php echo e(route('users.show', $user)); ?>" class="text-xs font-bold text-blue-600 hover:underline">عرض</a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-users')): ?>
                                <a href="<?php echo e(route('users.edit', $user)); ?>" class="text-xs font-bold text-gray-600 hover:underline">تعديل</a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-users')): ?>
                                <?php if(!$user->hasRole('super_admin')): ?>
                                <form action="<?php echo e(route('users.destroy', $user)); ?>" method="POST" class="inline" onsubmit="return confirm('حذف المستخدم «<?php echo e($user->name); ?>»؟');">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-xs font-bold text-red-600 hover:underline">حذف</button>
                                </form>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-14 text-center text-gray-500">
                            <p class="font-bold text-lg mb-1">لا يوجد مستخدمون</p>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-users')): ?>
                            <a href="<?php echo e(route('users.create')); ?>" class="text-blue-600 font-semibold hover:underline">أضف مستخدماً جديداً</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($users->hasPages()): ?>
        <div class="px-4 py-3 border-t border-gray-100"><?php echo e($users->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\users\index.blade.php ENDPATH**/ ?>