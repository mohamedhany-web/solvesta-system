<?php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $user = $employee->user;
?>
<?php if($user): ?>
<?php
    $kpi = \App\Models\EmployeeKpiPeriod::where('user_id', $user->id)
        ->where('period_year', now()->year)->where('period_month', now()->month)->first();
    $warnings = \App\Models\HrWarning::where('user_id', $user->id)->whereIn('status',['active','escalated'])->count();
?>
<div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
    <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
        <h2 class="text-lg font-bold font-tajawal" style="color:<?php echo e($themeColor); ?>;">KPI والأداء</h2>
        <a href="<?php echo e(route('kpi.show', $user)); ?>" class="text-sm font-bold text-blue-600">تفاصيل KPI →</a>
    </div>
    <?php if($kpi): ?>
    <div class="flex flex-wrap gap-4 items-center">
        <div class="text-center">
            <p class="text-4xl font-bold" style="color:<?php echo e($themeColor); ?>;"><?php echo e($kpi->total_score); ?>%</p>
            <p class="text-xs text-gray-500"><?php echo e($kpi->grade_label); ?></p>
        </div>
        <div class="text-sm font-tajawal space-y-1">
            <p>التزام: <?php echo e($kpi->adherence_score); ?>%</p>
            <p>المهام: <?php echo e($kpi->task_completion_score); ?>%</p>
            <p>خصومات: <?php echo e($kpi->kpi_deductions); ?></p>
        </div>
        <?php if($warnings): ?>
        <span class="px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs font-bold"><?php echo e($warnings); ?> تحذير نشط</span>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <p class="text-sm text-gray-500 font-tajawal">لم يُحسب KPI بعد — <a href="<?php echo e(route('kpi.index')); ?>" class="text-blue-600 font-bold">لوحة KPI</a></p>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\employees\partials\kpi-panel.blade.php ENDPATH**/ ?>