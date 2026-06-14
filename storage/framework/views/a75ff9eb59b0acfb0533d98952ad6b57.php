<?php $__env->startSection('page-title', 'تحذيرات HR'); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full px-2 sm:px-0">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'عقوبات وتحذيرات HR',
        'subtitle' => 'تأخير مهام → خصم KPI → 3 تحذيرات = تحقيق HR',
        'icon' => 'users',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap gap-3 mb-6">
        <a href="<?php echo e(route('hr.warnings.index', ['filter'=>'active'])); ?>" class="px-4 py-2 rounded-xl border text-sm font-bold <?php echo e(request('filter')==='active'?'bg-gray-900 text-white':''); ?>">نشطة</a>
        <a href="<?php echo e(route('hr.warnings.index', ['filter'=>'investigations'])); ?>" class="px-4 py-2 rounded-xl border text-sm font-bold <?php echo e(request('filter')==='investigations'?'bg-red-600 text-white':''); ?>">تحقيقات HR</a>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-employees')): ?>
        <form method="POST" action="<?php echo e(route('hr.warnings.scan-overdue')); ?>"><?php echo csrf_field(); ?>
            <button class="px-4 py-2 rounded-xl bg-amber-600 text-white text-sm font-bold">فحص مهام متأخرة</button>
        </form>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <?php $__currentLoopData = [['تحذيرات نشطة',$stats['active'],'#d97706'],['تحقيقات',$stats['investigations'],'#dc2626'],['محلولة هذا الشهر',$stats['resolved_month'],'#059669']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$l,$v,$c]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl shadow-lg border p-4 text-center">
            <p class="text-xs text-gray-500"><?php echo e($l); ?></p>
            <p class="text-2xl font-bold" style="color:<?php echo e($c); ?>;"><?php echo e($v); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-employees')): ?>
    <details class="bg-white rounded-2xl border p-4 mb-6">
        <summary class="font-bold cursor-pointer" style="color:<?php echo e($themeColor); ?>;">+ تحذير يدوي</summary>
        <form method="POST" action="<?php echo e(route('hr.warnings.store')); ?>" class="grid md:grid-cols-2 gap-3 mt-4">
            <?php echo csrf_field(); ?>
            <select name="user_id" required class="border rounded-xl px-3 py-2 md:col-span-2">
                <option value="">اختر الموظف</option>
                <?php $__currentLoopData = \App\Models\User::whereHas('employee')->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($u->id); ?>"><?php echo e($u->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="type" class="border rounded-xl px-3 py-2">
                <option value="task_delay">تأخير مهمة</option>
                <option value="attendance">حضور</option>
                <option value="conduct">سلوك</option>
                <option value="other">أخرى</option>
            </select>
            <input name="kpi_deduction_points" type="number" step="0.5" value="5" placeholder="خصم KPI" class="border rounded-xl px-3 py-2">
            <input name="reason" required placeholder="السبب..." class="border rounded-xl px-3 py-2 md:col-span-2">
            <button class="md:col-span-2 py-2 rounded-xl text-white font-bold" style="background:<?php echo e($themeColor); ?>;">تسجيل</button>
        </form>
    </details>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-lg border overflow-hidden">
        <table class="w-full text-sm font-tajawal">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right">المرجع</th>
                    <th class="px-4 py-3 text-right">الموظف</th>
                    <th class="px-4 py-3 text-right">النوع</th>
                    <th class="px-4 py-3 text-right">خصم</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $warnings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $w): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="<?php echo e($w->investigation_status==='pending'?'bg-red-50':''); ?>">
                    <td class="px-4 py-3 font-mono text-xs"><?php echo e($w->reference_code); ?></td>
                    <td class="px-4 py-3 font-bold"><?php echo e($w->user?->name); ?></td>
                    <td class="px-4 py-3"><?php echo e($w->type_label); ?></td>
                    <td class="px-4 py-3 text-red-700">-<?php echo e($w->kpi_deduction_points); ?></td>
                    <td class="px-4 py-3">
                        <?php echo e($w->status_label); ?>

                        <?php if($w->investigation_status==='pending'): ?><span class="text-red-600 text-xs font-bold">تحقيق HR</span><?php endif; ?>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-employees')): ?>
                        <?php if($w->investigation_status==='pending'): ?>
                        <form method="POST" action="<?php echo e(route('hr.warnings.investigate', $w)); ?>" class="inline"><?php echo csrf_field(); ?>
                            <button class="text-xs text-red-700 font-bold">بدء تحقيق</button>
                        </form>
                        <?php endif; ?>
                        <?php if($w->status!=='resolved'): ?>
                        <form method="POST" action="<?php echo e(route('hr.warnings.resolve', $w)); ?>" class="inline"><?php echo csrf_field(); ?>
                            <button class="text-xs text-emerald-700 font-bold">حلّ</button>
                        </form>
                        <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr class="bg-gray-50/50"><td colspan="6" class="px-4 py-2 text-xs text-gray-600"><?php echo e($w->reason); ?></td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">لا توجد تحذيرات.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="px-4 py-3"><?php echo e($warnings->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\hr\warnings\index.blade.php ENDPATH**/ ?>