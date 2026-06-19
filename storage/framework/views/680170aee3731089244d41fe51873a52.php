

<?php $__env->startSection('page-title', 'الترقيات والتطوير الوظيفي'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $pipeline = [
        \App\Models\EmployeePromotion::STATUS_PENDING_TEAM_LEAD => 'Team Lead',
        \App\Models\EmployeePromotion::STATUS_PENDING_DEPT_HEAD => 'رئيس القسم',
        \App\Models\EmployeePromotion::STATUS_PENDING_HR => 'HR / الترقيات',
        \App\Models\EmployeePromotion::STATUS_APPROVED => 'معتمد',
    ];
?>
<div class="w-full max-w-full font-tajawal">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'الترقيات والتطوير الوظيفي',
        'subtitle' => 'Pipeline: Team Lead → رئيس القسم → HR → اعتماد الترقية',
        'icon' => 'users',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if(session('success')): ?>
    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="flex flex-wrap gap-2 mb-6">
        <a href="<?php echo e(route('hr.promotions.index')); ?>" class="px-4 py-2 rounded-xl border text-sm font-bold <?php echo e(!request('status') ? 'bg-gray-900 text-white' : ''); ?>">الكل</a>
        <?php $__currentLoopData = $statusLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('hr.promotions.index', ['status' => $key])); ?>"
           class="px-4 py-2 rounded-xl border text-sm font-bold <?php echo e(request('status') === $key ? 'bg-indigo-600 text-white' : ''); ?>"><?php echo e($label); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="bg-white rounded-2xl border p-4 mb-6 overflow-x-auto">
        <p class="text-xs font-bold text-gray-500 mb-3">مسار الترقية</p>
        <div class="flex items-center gap-2 min-w-max text-sm">
            <?php $__currentLoopData = $pipeline; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="px-3 py-1.5 rounded-lg border <?php echo e(request('status') === $status ? 'bg-indigo-50 border-indigo-300 font-bold' : 'bg-gray-50'); ?>"><?php echo e($label); ?></span>
                <?php if(!$loop->last): ?><span class="text-gray-400">→</span><?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-employees')): ?>
    <details class="bg-white rounded-2xl border p-5 mb-6 shadow-sm">
        <summary class="font-bold cursor-pointer" style="color: <?php echo e($themeColor); ?>;">+ طلب ترقية جديد</summary>
        <form method="POST" action="<?php echo e(route('hr.promotions.store')); ?>" class="grid md:grid-cols-2 gap-4 mt-4">
            <?php echo csrf_field(); ?>
            <div class="md:col-span-2">
                <label class="text-xs font-bold text-gray-600 block mb-1">الموظف</label>
                <select name="employee_id" id="promo_employee_id" required class="w-full border rounded-xl px-3 py-2.5 text-sm" onchange="updatePromotionLevels(this)">
                    <option value="">اختر الموظف</option>
                    <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($emp->id); ?>"
                                data-level="<?php echo e($emp->career_level); ?>"
                                data-track="<?php echo e($emp->career_track); ?>"
                                data-dept="<?php echo e($emp->department?->code); ?>">
                            <?php echo e($emp->first_name); ?> <?php echo e($emp->last_name); ?> — <?php echo e($emp->department?->name); ?> (<?php echo e($emp->career_level ?? 'بدون مستوى'); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-600 block mb-1">المستوى الحالي</label>
                <input type="text" id="promo_from_level" readonly class="w-full border rounded-xl px-3 py-2.5 text-sm bg-gray-50" placeholder="—">
            </div>
            <div>
                <label class="text-xs font-bold text-gray-600 block mb-1">المستوى المطلوب <span class="text-red-500">*</span></label>
                <input type="text" name="to_level" id="promo_to_level" required class="w-full border rounded-xl px-3 py-2.5 text-sm" placeholder="مثال: Senior مبرمج">
            </div>
            <div class="md:col-span-2">
                <label class="text-xs font-bold text-gray-600 block mb-1">المبرر <span class="text-red-500">*</span></label>
                <textarea name="justification" required rows="3" class="w-full border rounded-xl px-3 py-2.5 text-sm" placeholder="أداء KPI، إنجازات، توصية..."></textarea>
            </div>
            <button type="submit" class="md:col-span-2 py-2.5 rounded-xl text-white font-bold" style="background: <?php echo e($themeColor); ?>;">تقديم طلب الترقية</button>
        </form>
    </details>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-lg border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right">الموظف</th>
                    <th class="px-4 py-3 text-right">من → إلى</th>
                    <th class="px-4 py-3 text-right">KPI</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">مقدّم الطلب</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $promotions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-4 py-3">
                        <p class="font-bold"><?php echo e($promo->employee?->first_name); ?> <?php echo e($promo->employee?->last_name); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($promo->employee?->department?->name); ?></p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-gray-500"><?php echo e($promo->from_level ?? '—'); ?></span>
                        <span class="mx-1">→</span>
                        <span class="font-bold"><?php echo e($promo->to_level); ?></span>
                    </td>
                    <td class="px-4 py-3 font-mono"><?php echo e($promo->kpi_score !== null ? number_format($promo->kpi_score, 1).'%' : '—'); ?></td>
                    <td class="px-4 py-3">
                        <span class="inline-flex px-2 py-0.5 rounded-lg text-xs font-bold
                            <?php if($promo->status === 'approved'): ?> bg-green-100 text-green-800
                            <?php elseif($promo->status === 'rejected'): ?> bg-red-100 text-red-800
                            <?php else: ?> bg-amber-100 text-amber-800 <?php endif; ?>">
                            <?php echo e($statusLabels[$promo->status] ?? $promo->status); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-600"><?php echo e($promo->proposer?->name ?? '—'); ?></td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <?php if(!in_array($promo->status, ['approved', 'rejected'], true)): ?>
                        <form method="POST" action="<?php echo e(route('hr.promotions.advance', $promo)); ?>" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-xs font-bold px-2 py-1 rounded-lg text-white" style="background: <?php echo e($themeColor); ?>;">تقديم</button>
                        </form>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-employees')): ?>
                        <form method="POST" action="<?php echo e(route('hr.promotions.reject', $promo)); ?>" class="inline mr-1">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-xs font-bold px-2 py-1 rounded-lg border border-red-300 text-red-700">رفض</button>
                        </form>
                        <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php if($promo->justification): ?>
                <tr class="bg-gray-50/50">
                    <td colspan="6" class="px-4 py-2 text-xs text-gray-600"><?php echo e($promo->justification); ?></td>
                </tr>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">لا توجد طلبات ترقية.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if($promotions->hasPages()): ?>
        <div class="px-4 py-3 border-t"><?php echo e($promotions->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->startPush('scripts'); ?>
<script>
const deptLevels = <?php echo json_encode(collect(\App\Services\DepartmentProfileService::PROFILES)->mapWithKeys(fn($p, $code) => [$code => $p['levels'] ?? []]), 512) ?>;
function updatePromotionLevels(select) {
    const opt = select.selectedOptions[0];
    document.getElementById('promo_from_level').value = opt?.dataset.level || '';
    const code = opt?.dataset.dept || '';
    const levels = deptLevels[code] || [];
    const current = opt?.dataset.level || '';
    const idx = levels.indexOf(current);
    const next = idx >= 0 && idx < levels.length - 1 ? levels[idx + 1] : '';
    document.getElementById('promo_to_level').value = next;
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\hr\promotions\index.blade.php ENDPATH**/ ?>