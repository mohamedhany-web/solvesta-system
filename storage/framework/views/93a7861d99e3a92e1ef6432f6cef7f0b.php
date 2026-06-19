<?php $__env->startSection('page-title', 'تفاصيل الموظف'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $fullName = trim($employee->first_name.' '.$employee->last_name);
    $statusLabel = match ($employee->status) {
        'active' => 'نشط',
        'on_leave' => 'في إجازة',
        'inactive' => 'غير نشط',
        'terminated' => 'مفصول',
        default => $employee->status,
    };
    $statusClass = match ($employee->status) {
        'active' => 'bg-emerald-100 text-emerald-800',
        'on_leave' => 'bg-amber-100 text-amber-800',
        'inactive' => 'bg-gray-100 text-gray-700',
        'terminated' => 'bg-red-100 text-red-800',
        default => 'bg-gray-100 text-gray-600',
    };
    $employmentLabel = match ($employee->employment_type) {
        'full_time' => 'دوام كامل',
        'part_time' => 'دوام جزئي',
        'contract' => 'عقد',
        'intern' => 'متدرب',
        default => $employee->employment_type ?? '—',
    };
?>
<div class="w-full max-w-full font-tajawal">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => $fullName,
        'subtitle' => ($employee->position ?? '—').' · '.($employee->department->name ?? 'بدون قسم'),
        'icon' => 'users',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap justify-end gap-3 mb-6 -mt-2">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-employees')): ?>
        <a href="<?php echo e(route('employees.edit', $employee)); ?>"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-sm hover:opacity-95"
           style="background: <?php echo e($themeColor); ?>;">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            تعديل
        </a>
        <?php endif; ?>
        <a href="<?php echo e(route('employees.index')); ?>" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            كل الموظفين
        </a>
    </div>

    <?php if(session('success')): ?>
    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php echo $__env->make('employees.partials.kpi-panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8 space-y-6">
            
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
                    <h2 class="font-bold text-lg text-gray-900">المعلومات الشخصية</h2>
                    <p class="text-sm text-gray-500 mt-0.5">التواصل والعنوان</p>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">الاسم الكامل</dt>
                            <dd class="font-semibold text-gray-900"><?php echo e($fullName); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">البريد الإلكتروني</dt>
                            <dd class="font-semibold text-gray-900" dir="ltr"><?php echo e($employee->email); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">رقم الهاتف</dt>
                            <dd class="flex items-center justify-between gap-2">
                                <span class="font-semibold text-gray-900" dir="ltr"><?php echo e($employee->phone ?? '—'); ?></span>
                                <?php if($employee->phone): ?>
                                <button type="button" onclick="openWhatsAppContact('<?php echo e($employee->phone); ?>', <?php echo \Illuminate\Support\Js::from($fullName)->toHtml() ?>)"
                                        class="p-1.5 rounded-lg text-green-600 hover:bg-green-50 transition" title="واتساب">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/></svg>
                                </button>
                                <?php endif; ?>
                            </dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4 sm:col-span-2">
                            <dt class="text-xs font-bold text-gray-500 mb-1">العنوان</dt>
                            <dd class="text-gray-800"><?php echo e($employee->address ?? '—'); ?></dd>
                        </div>
                    </dl>
                </div>
            </div>

            
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
                    <h2 class="font-bold text-lg text-gray-900">المعلومات الوظيفية</h2>
                    <p class="text-sm text-gray-500 mt-0.5">القسم، الراتب، ونوع التوظيف</p>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">الرقم التوظيفي</dt>
                            <dd class="font-mono font-semibold text-gray-900"><?php echo e($employee->employee_id); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">الحالة</dt>
                            <dd><span class="text-xs font-bold px-2.5 py-1 rounded-full <?php echo e($statusClass); ?>"><?php echo e($statusLabel); ?></span></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">المنصب</dt>
                            <dd class="font-semibold text-gray-900"><?php echo e($employee->position ?? '—'); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">القسم</dt>
                            <dd class="font-semibold text-gray-900"><?php echo e($employee->department->name ?? '—'); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">الراتب الشهري</dt>
                            <dd class="font-semibold text-gray-900"><?php echo e($employee->salary ? number_format($employee->salary).' ج.م' : '—'); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">ساعات العمل اليومية</dt>
                            <dd class="text-gray-800"><?php echo e($employee->daily_hours ?? '—'); ?> ساعة</dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">تاريخ التوظيف</dt>
                            <dd class="text-gray-800"><?php echo e($employee->hire_date?->format('Y/m/d') ?? '—'); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">نوع التوظيف</dt>
                            <dd class="text-gray-800"><?php echo e($employmentLabel); ?></dd>
                        </div>
                    </dl>
                </div>
            </div>

            <?php if($employee->emergency_contact || $employee->emergency_phone): ?>
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
                    <h2 class="font-bold text-lg text-gray-900">جهة الاتصال للطوارئ</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">الاسم</dt>
                            <dd class="text-gray-800"><?php echo e($employee->emergency_contact ?? '—'); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">الهاتف</dt>
                            <dd class="text-gray-800" dir="ltr"><?php echo e($employee->emergency_phone ?? '—'); ?></dd>
                        </div>
                    </dl>
                </div>
            </div>
            <?php endif; ?>

            <?php if($employee->user): ?>
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80 flex items-center justify-between gap-3">
                    <div>
                        <h2 class="font-bold text-lg text-gray-900">حساب المستخدم المرتبط</h2>
                        <p class="text-sm text-gray-500 mt-0.5">بيانات الدخول للنظام</p>
                    </div>
                    <a href="<?php echo e(route('users.show', $employee->user)); ?>" class="text-xs font-bold hover:underline" style="color: <?php echo e($themeColor); ?>;">عرض المستخدم →</a>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">اسم المستخدم</dt>
                            <dd class="font-semibold text-gray-900"><?php echo e($employee->user->name); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">البريد</dt>
                            <dd dir="ltr"><?php echo e($employee->user->email); ?></dd>
                        </div>
                        <?php if($employee->user->roles->isNotEmpty()): ?>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4 sm:col-span-2">
                            <dt class="text-xs font-bold text-gray-500 mb-2">الأدوار</dt>
                            <dd class="flex flex-wrap gap-2">
                                <?php $__currentLoopData = $employee->user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-blue-100 text-blue-800"><?php echo e($role->name); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </dd>
                        </div>
                        <?php endif; ?>
                    </dl>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="xl:col-span-4 space-y-4">
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm text-center">
                <div class="h-20 w-20 mx-auto rounded-2xl flex items-center justify-center text-white text-3xl font-bold mb-4" style="background: <?php echo e($themeColor); ?>;">
                    <?php echo e(mb_substr($employee->first_name, 0, 1)); ?>

                </div>
                <h3 class="font-bold text-lg text-gray-900"><?php echo e($fullName); ?></h3>
                <p class="text-sm text-gray-500 mt-1"><?php echo e($employee->position); ?></p>
                <p class="text-sm text-gray-600 mt-1"><?php echo e($employee->department->name ?? '—'); ?></p>
                <div class="mt-4">
                    <span class="text-xs font-bold px-3 py-1 rounded-full <?php echo e($statusClass); ?>"><?php echo e($statusLabel); ?></span>
                </div>
                <p class="text-xs text-gray-400 mt-3 font-mono" dir="ltr"><?php echo e($employee->employee_id); ?></p>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <p class="font-bold text-gray-900 mb-3 text-sm">إحصائيات سريعة</p>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <dt class="text-gray-500">أيام الحضور</dt>
                        <dd class="font-bold text-gray-900"><?php echo e($stats['total_attendance_days'] ?? 0); ?></dd>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <dt class="text-gray-500">طلبات الإجازة</dt>
                        <dd class="font-bold text-gray-900"><?php echo e($stats['total_leaves'] ?? 0); ?></dd>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <dt class="text-gray-500">إجازات معلّقة</dt>
                        <dd class="font-bold text-amber-700"><?php echo e($stats['pending_leaves'] ?? 0); ?></dd>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <dt class="text-gray-500">إجازات معتمدة</dt>
                        <dd class="font-bold text-emerald-700"><?php echo e($stats['approved_leaves'] ?? 0); ?></dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm space-y-2">
                <p class="font-bold text-gray-900 mb-2 text-sm">إجراءات سريعة</p>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-employees')): ?>
                <a href="<?php echo e(route('employees.edit', $employee)); ?>"
                   class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-bold hover:opacity-95"
                   style="background: <?php echo e($themeColor); ?>;">تعديل البيانات</a>
                <?php endif; ?>
                <a href="<?php echo e(route('attendances.index', ['employee' => $employee->id])); ?>"
                   class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50">سجل الحضور</a>
                <a href="<?php echo e(route('salaries.index', ['employee' => $employee->id])); ?>"
                   class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50">سجل المرتبات</a>
                <?php if($employee->user): ?>
                <a href="<?php echo e(route('kpi.show', $employee->user)); ?>"
                   class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50">تقرير KPI</a>
                <?php endif; ?>
            </div>

            <div class="rounded-2xl border border-indigo-100 bg-indigo-50/60 p-5 text-sm">
                <p class="font-bold text-indigo-900 mb-2">النشاط</p>
                <ul class="space-y-2 text-indigo-800">
                    <li>أُنشئ <?php echo e($employee->created_at->diffForHumans()); ?></li>
                    <?php if($employee->updated_at->ne($employee->created_at)): ?>
                    <li>آخر تحديث <?php echo e($employee->updated_at->diffForHumans()); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\employees\show.blade.php ENDPATH**/ ?>