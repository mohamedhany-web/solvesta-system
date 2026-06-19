<?php $__env->startSection('page-title', 'KPI — '.$user->name); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full px-2 sm:px-0">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'KPI — '.$user->name,
        'subtitle' => 'الفترة '.$period->period_label.' · '.$period->grade_label,
        'icon' => 'chart',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex gap-2 mb-6">
        <a href="<?php echo e(route('kpi.index', ['year'=>$year,'month'=>$month])); ?>" class="px-4 py-2 rounded-xl border bg-white text-sm font-bold">← القائمة</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border p-6">
            <div class="text-center mb-6">
                <p class="text-6xl font-bold" style="color:<?php echo e($themeColor); ?>;"><?php echo e($period->total_score); ?>%</p>
                <p class="text-gray-500 font-tajawal">خصومات: <?php echo e($period->kpi_deductions); ?> نقطة</p>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm font-tajawal">
                <?php $__currentLoopData = [
                    ['التزام (حضور)', $period->adherence_score, $weights['adherence']],
                    ['إنجاز المهام', $period->task_completion_score, $weights['task_completion']],
                    ['جودة الكود/العمل', $period->quality_score, $weights['quality']],
                    ['تقييم Team Lead', $period->team_lead_rating ?? 0, $weights['team_lead']],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $score, $weight]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="p-4 rounded-xl bg-gray-50 border">
                    <p class="text-gray-600"><?php echo e($label); ?> <span class="text-xs">(<?php echo e($weight); ?>%)</span></p>
                    <p class="text-2xl font-bold mt-1"><?php echo e($score); ?>%</p>
                    <div class="h-2 bg-gray-200 rounded-full mt-2"><div class="h-full rounded-full" style="width:<?php echo e(min(100,$score)); ?>%;background:<?php echo e($themeColor); ?>;"></div></div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-employees')): ?>
        <div class="bg-white rounded-2xl shadow-lg border p-6">
            <h3 class="font-bold mb-4 font-tajawal">تقييم Team Lead</h3>
            <form method="POST" action="<?php echo e(route('kpi.rate', $user)); ?>" class="space-y-3">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="year" value="<?php echo e($year); ?>">
                <input type="hidden" name="month" value="<?php echo e($month); ?>">
                <input type="number" name="team_lead_rating" min="0" max="100" step="1" required
                       value="<?php echo e($period->team_lead_rating ?? ''); ?>" placeholder="0-100"
                       class="w-full border rounded-xl px-4 py-2.5">
                <textarea name="notes" rows="3" placeholder="ملاحظات..." class="w-full border rounded-xl px-3 py-2 text-sm"><?php echo e($period->notes); ?></textarea>
                <button class="w-full py-2.5 rounded-xl text-white font-bold" style="background:<?php echo e($themeColor); ?>;">حفظ التقييم</button>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <?php if($history->count()): ?>
    <div class="bg-white rounded-2xl shadow-lg border p-6">
        <h3 class="font-bold mb-4 font-tajawal">السجل التاريخي</h3>
        <ul class="space-y-2 text-sm font-tajawal">
            <?php $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="flex justify-between py-2 border-b">
                <span><?php echo e($h->period_label); ?></span>
                <strong style="color:<?php echo e($themeColor); ?>;"><?php echo e($h->total_score); ?>% — <?php echo e($h->grade_label); ?></strong>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\kpi\show.blade.php ENDPATH**/ ?>