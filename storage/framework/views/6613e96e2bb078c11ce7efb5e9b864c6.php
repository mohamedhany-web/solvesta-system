<?php $__env->startSection('page-title', 'لوحة المدير التنفيذية'); ?>



<?php $__env->startSection('content'); ?>

<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>

<div class="w-full max-w-full px-2 sm:px-0">

    <?php echo $__env->make('partials.erp-page-header', [

        'title' => 'لوحة CEO',

        'subtitle' => 'المبيعات · المالية · المشاريع · العمليات — نظرة تنفيذية دون تفاصيل المهام',

        'icon' => 'chart',

    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>



    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="<?php echo e(route('executive.finance')); ?>" class="px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-lg"
           style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">التقارير المالية التفصيلية →</a>
    </div>

    <section class="mb-8">

        <h2 class="text-lg font-bold mb-4 font-tajawal text-gray-900">المبيعات والـ Pipeline</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-8 gap-3 text-sm">

            <?php $__currentLoopData = [

                ['Leads جديدة', $pipeline['leads_new'], ''],

                ['Leads مؤهلة', $pipeline['leads_qualified'], '#2563eb'],

                ['فرص مفتوحة', $pipeline['sales_open'], ''],

                ['مؤهّلة', $pipeline['sales_qualified'], $themeColor],

                ['عروض', $pipeline['proposal'], '#d97706'],

                ['تفاوض', $pipeline['negotiation'], '#ea580c'],

                ['صفقات مغلقة', $pipeline['closed_won'], '#059669'],

                ['خاسرة', $pipeline['closed_lost'], '#dc2626'],

            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-4 text-center hover:shadow-xl transition-all duration-300">

                <p class="text-gray-500 text-xs font-tajawal mb-1"><?php echo e($label); ?></p>

                <p class="text-2xl font-bold font-tajawal" <?php if($color): ?> style="color: <?php echo e($color); ?>;" <?php endif; ?>><?php echo e($val); ?></p>

            </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">

            <div class="rounded-2xl p-5 border shadow-lg" style="background: linear-gradient(135deg, <?php echo e($themeColor); ?>10 0%, <?php echo e($themeColor); ?>05 100%); border-color: <?php echo e($themeColor); ?>25;">

                <p class="text-sm text-gray-600 font-tajawal">قيمة الـ Pipeline</p>

                <p class="text-3xl font-bold font-tajawal" style="color: <?php echo e($themeColor); ?>;"><?php echo e(number_format($pipeline['pipeline_value'])); ?> ج.م</p>

            </div>

            <div class="rounded-2xl p-5 border border-emerald-100 shadow-lg bg-gradient-to-r from-emerald-50 to-green-50">

                <p class="text-sm text-gray-600 font-tajawal">إيرادات مغلقة</p>

                <p class="text-3xl font-bold text-emerald-700 font-tajawal"><?php echo e(number_format($pipeline['won_value'])); ?> ج.م</p>

            </div>

            <?php if(isset($presales)): ?>

            <div class="rounded-2xl p-5 border border-blue-100 shadow-lg bg-blue-50/50">

                <p class="text-sm text-gray-600 font-tajawal">عروض مُرسلة</p>

                <p class="text-3xl font-bold text-blue-700 font-tajawal"><?php echo e($presales['sent']); ?></p>

            </div>

            <div class="rounded-2xl p-5 border border-indigo-100 shadow-lg bg-indigo-50/50">

                <p class="text-sm text-gray-600 font-tajawal">عروض مقبولة</p>

                <p class="text-3xl font-bold text-indigo-700 font-tajawal"><?php echo e($presales['accepted']); ?></p>

            </div>

            <div class="rounded-2xl p-5 border shadow-lg" style="border-color: <?php echo e($themeColor); ?>30; background: <?php echo e($themeColor); ?>08;">

                <p class="text-sm text-gray-600 font-tajawal">ربح تقديري</p>

                <p class="text-3xl font-bold font-tajawal" style="color: <?php echo e($themeColor); ?>;"><?php echo e(number_format($financeSummary['estimated_profit'] ?? 0)); ?></p>

            </div>

            <?php endif; ?>

        </div>

    </section>



    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <section class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">

            <h2 class="font-bold mb-4 font-tajawal flex items-center gap-2">

                <span class="w-2 h-6 rounded-full" style="background: <?php echo e($themeColor); ?>;"></span>

                المالية

            </h2>

            <p class="text-sm text-gray-600 font-tajawal">محصّل</p>

            <p class="text-2xl font-bold text-emerald-700 mb-3 font-tajawal"><?php echo e(number_format($finance['revenue_paid'])); ?></p>

            <p class="text-sm text-gray-600 font-tajawal">مستحقات</p>

            <p class="text-2xl font-bold text-amber-700 font-tajawal"><?php echo e(number_format($finance['outstanding'])); ?></p>

        </section>

        <section class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">

            <h2 class="font-bold mb-4 font-tajawal flex items-center gap-2">

                <span class="w-2 h-6 rounded-full" style="background: <?php echo e($themeColor); ?>;"></span>

                المشاريع

            </h2>

            <ul class="space-y-3 text-sm font-tajawal">

                <li class="flex justify-between"><span>نشطة</span><strong><?php echo e($projects['active']); ?></strong></li>

                <li class="flex justify-between"><span>مكتملة</span><strong><?php echo e($projects['completed']); ?></strong></li>

                <li class="flex justify-between text-red-700"><span>متأخرة</span><strong><?php echo e($projects['overdue']); ?></strong></li>

                <?php if(($projects['blocked_payment'] ?? 0) > 0): ?>

                <li class="flex justify-between text-amber-700"><span>بانتظار دفع</span><strong><?php echo e($projects['blocked_payment']); ?></strong></li>

                <?php endif; ?>

            </ul>

        </section>

        <section class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">

            <h2 class="font-bold mb-4 font-tajawal flex items-center gap-2">

                <span class="w-2 h-6 rounded-full" style="background: <?php echo e($themeColor); ?>;"></span>

                العمليات

            </h2>

            <ul class="space-y-3 text-sm font-tajawal">

                <li class="flex justify-between"><span>مهام مفتوحة</span><strong><?php echo e($operations['open_tasks']); ?></strong></li>

                <li class="flex justify-between text-red-700"><span>مهام متأخرة</span><strong><?php echo e($operations['overdue_tasks']); ?></strong></li>
                <li class="flex justify-between text-amber-700"><span>Blockers</span><strong><?php echo e($operations['open_blockers'] ?? 0); ?></strong></li>
                <li class="flex justify-between"><span>حضور اليوم</span><strong><?php echo e($operations['attendance_today']); ?></strong></li>
                <?php if(isset($performance)): ?>
                <li class="flex justify-between"><span>متوسط KPI</span><strong style="color:<?php echo e($themeColor); ?>;"><?php echo e($performance['avg_kpi']); ?>%</strong></li>
                <li class="flex justify-between text-red-700"><span>تحقيقات HR</span><strong><?php echo e($performance['hr_investigations']); ?></strong></li>
                <?php endif; ?>

            </ul>

        </section>

    </div>



    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">

            <h3 class="font-bold mb-4 font-tajawal">آخر Leads</h3>

            <ul class="text-sm space-y-3 font-tajawal">

                <?php $__currentLoopData = $recentLeads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <li class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">

                        <a href="<?php echo e(route('leads.show', $l)); ?>" class="font-semibold hover:underline" style="color: <?php echo e($themeColor); ?>;"><?php echo e($l->name); ?></a>

                        <span class="text-gray-500 text-xs px-2 py-1 bg-gray-100 rounded-full"><?php echo e($l->status_label); ?></span>

                    </li>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </ul>

        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">

            <h3 class="font-bold mb-4 font-tajawal">آخر صفقات مغلقة</h3>

            <ul class="text-sm space-y-3 font-tajawal">

                <?php $__empty_1 = true; $__currentLoopData = $recentWins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <li class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">

                        <span><?php echo e($s->client?->name); ?> — <?php echo e(Str::limit($s->product_service, 30)); ?></span>

                        <strong style="color: <?php echo e($themeColor); ?>;"><?php echo e(number_format($s->amount)); ?></strong>

                    </li>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <li class="text-gray-500">لا توجد صفقات مغلقة بعد.</li>

                <?php endif; ?>

            </ul>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\executive\dashboard.blade.php ENDPATH**/ ?>