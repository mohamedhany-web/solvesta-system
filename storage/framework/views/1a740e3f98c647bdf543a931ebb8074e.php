<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full font-tajawal">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'لوحة التحكم',
        'subtitle' => 'نظرة تشغيلية — مبيعات · مشاريع · مهام · اتجاهات',
        'icon' => 'chart',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-6 overflow-hidden">
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 divide-x divide-y md:divide-y-0 divide-gray-100 divide-x-reverse">
            <?php $__currentLoopData = $kpis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="px-4 py-4 hover:bg-gray-50/80 transition-colors">
                <p class="text-[11px] text-gray-500 font-medium mb-1"><?php echo e($kpi['label']); ?></p>
                <div class="flex items-end gap-2">
                    <span class="text-2xl font-bold text-gray-900 leading-none"><?php echo e($kpi['value']); ?></span>
                    <?php if($kpi['trend'] !== null): ?>
                        <span class="text-xs font-bold mb-0.5 <?php echo e($kpi['trend'] >= 0 ? 'text-emerald-600' : 'text-red-600'); ?>">
                            <?php echo e($kpi['trend'] >= 0 ? '↑' : '↓'); ?><?php echo e(abs($kpi['trend'])); ?>%
                        </span>
                    <?php endif; ?>
                </div>
                <p class="text-[10px] text-gray-400 mt-1"><?php echo e($kpi['sub']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mb-6">
        
        <div class="xl:col-span-8 space-y-6">
            
            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-1.5 h-5 rounded-full" style="background: <?php echo e($themeColor); ?>;"></span>
                        مسار المبيعات
                    </h2>
                    <div class="flex gap-4 text-xs text-gray-500">
                        <span>Pipeline: <strong class="text-gray-800"><?php echo e(number_format($pipeline['pipeline_value'])); ?> ج.م</strong></span>
                        <span>مغلق: <strong class="text-emerald-700"><?php echo e(number_format($pipeline['won_value'])); ?> ج.م</strong></span>
                    </div>
                </div>
                <?php
                    $funnelMax = max(1, max($charts['sales_funnel']['values']));
                    $funnelColors = ['#94a3b8', '#3b82f6', '#f59e0b', '#059669'];
                ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = array_combine($charts['sales_funnel']['labels'], $charts['sales_funnel']['values']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $pct = round(($val / $funnelMax) * 100); ?>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="w-28 text-gray-600 text-xs shrink-0"><?php echo e($label); ?></span>
                        <div class="flex-1 h-7 bg-gray-100 rounded-lg overflow-hidden relative">
                            <div class="h-full rounded-lg transition-all duration-500 flex items-center justify-end pe-2"
                                 style="width: <?php echo e(max($pct, $val > 0 ? 8 : 0)); ?>%; background: <?php echo e($funnelColors[$loop->index] ?? $themeColor); ?>;">
                                <?php if($val > 0): ?><span class="text-[10px] font-bold text-white"><?php echo e($val); ?></span><?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </section>

            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                    <h3 class="font-bold text-gray-900 mb-1 text-sm">إيرادات مغلقة — 6 أشهر</h3>
                    <p class="text-xs text-gray-400 mb-3">صفقات closed_won</p>
                    <div class="h-52"><canvas id="chartRevenue"></canvas></div>
                </section>
                <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                    <h3 class="font-bold text-gray-900 mb-1 text-sm">نشاط يومي — 30 يوم</h3>
                    <p class="text-xs text-gray-400 mb-3">مشاريع ومهام جديدة</p>
                    <div class="h-52"><canvas id="chartActivity"></canvas></div>
                </section>
                <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                    <h3 class="font-bold text-gray-900 mb-1 text-sm">حالة المشاريع</h3>
                    <div class="h-52 flex items-center justify-center"><canvas id="chartProjectStatus"></canvas></div>
                </section>
                <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                    <h3 class="font-bold text-gray-900 mb-1 text-sm">Leads ومهام مكتملة</h3>
                    <p class="text-xs text-gray-400 mb-3">مقارنة شهرية</p>
                    <div class="h-52"><canvas id="chartLeadsTasks"></canvas></div>
                </section>
            </div>

            
            <?php if($charts['department_load']->isNotEmpty()): ?>
            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h3 class="font-bold text-gray-900 mb-4 text-sm">توزيع الحمل على الأقسام</h3>
                <div class="h-48"><canvas id="chartDepartments"></canvas></div>
            </section>
            <?php endif; ?>
        </div>

        
        <div class="xl:col-span-4 space-y-6">
            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4" style="color: <?php echo e($themeColor); ?>;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    رؤى واتجاهات
                </h3>
                <ul class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $insights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $insight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $insightStyle = match($insight['type']) {
                            'positive' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
                            'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
                            'danger' => 'border-red-200 bg-red-50 text-red-800',
                            default => 'border-blue-200 bg-blue-50 text-blue-800',
                        };
                    ?>
                    <li class="text-xs leading-relaxed px-3 py-2.5 rounded-xl border <?php echo e($insightStyle); ?>"><?php echo e($insight['text']); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="text-xs text-gray-500">لا توجد رؤى جديدة — الوضع مستقر.</li>
                    <?php endif; ?>
                </ul>
            </section>

            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h3 class="font-bold text-gray-900 mb-4 text-sm">تنبيهات فورية</h3>
                <ul class="space-y-2 text-sm">
                    <?php $__currentLoopData = [
                        ['مهام متأخرة', $alerts['overdue_tasks'], 'text-red-700', route('tasks.index', ['status' => 'overdue'])],
                        ['مشاريع متأخرة', $alerts['overdue_projects'], 'text-red-700', route('projects.index')],
                        ['بانتظار تعيين فريق', $alerts['pending_team'], 'text-amber-700', route('projects.index')],
                        ['Blockers مفتوحة', $alerts['open_blockers'], 'text-orange-700', route('pmo.index')],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $count, $color, $url]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                        <a href="<?php echo e($url); ?>" class="text-gray-600 hover:underline text-xs"><?php echo e($label); ?></a>
                        <span class="font-bold <?php echo e($count > 0 ? $color : 'text-gray-400'); ?>"><?php echo e($count); ?></span>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </section>

            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h3 class="font-bold text-gray-900 mb-3 text-sm">آخر Leads</h3>
                <ul class="text-xs space-y-2">
                    <?php $__empty_1 = true; $__currentLoopData = $recent_leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li class="flex justify-between items-center py-1.5 border-b border-gray-50 last:border-0">
                        <a href="<?php echo e(route('leads.show', $lead)); ?>" class="font-semibold hover:underline truncate max-w-[60%]" style="color: <?php echo e($themeColor); ?>;"><?php echo e($lead->name); ?></a>
                        <span class="text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full shrink-0"><?php echo e($lead->status_label); ?></span>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="text-gray-400">لا توجد leads حديثة.</li>
                    <?php endif; ?>
                </ul>
                <a href="<?php echo e(route('leads.index')); ?>" class="inline-block mt-3 text-xs font-bold hover:underline" style="color: <?php echo e($themeColor); ?>;">عرض الكل →</a>
            </section>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-sm">آخر المشاريع</h3>
                <a href="<?php echo e(route('projects.index')); ?>" class="text-xs font-bold" style="color: <?php echo e($themeColor); ?>;">الكل</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-gray-50 text-gray-500">
                        <tr>
                            <th class="text-right px-4 py-2 font-medium">المشروع</th>
                            <th class="text-right px-4 py-2 font-medium">القسم</th>
                            <th class="text-right px-4 py-2 font-medium">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php $__currentLoopData = $recent_projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-4 py-2.5">
                                <a href="<?php echo e(route('projects.show', $project)); ?>" class="font-semibold hover:underline" style="color: <?php echo e($themeColor); ?>;"><?php echo e(Str::limit($project->name, 28)); ?></a>
                            </td>
                            <td class="px-4 py-2.5 text-gray-500"><?php echo e($project->department?->name ?? '—'); ?></td>
                            <td class="px-4 py-2.5"><span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-600"><?php echo e($project->status_name); ?></span></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-sm">آخر المهام</h3>
                <a href="<?php echo e(route('tasks.index')); ?>" class="text-xs font-bold" style="color: <?php echo e($themeColor); ?>;">الكل</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-gray-50 text-gray-500">
                        <tr>
                            <th class="text-right px-4 py-2 font-medium">المهمة</th>
                            <th class="text-right px-4 py-2 font-medium">المسؤول</th>
                            <th class="text-right px-4 py-2 font-medium">الحالة</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php $__currentLoopData = $recent_tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-4 py-2.5">
                                <a href="<?php echo e(route('tasks.show', $task)); ?>" class="font-semibold hover:underline" style="color: <?php echo e($themeColor); ?>;"><?php echo e(Str::limit($task->title, 30)); ?></a>
                                <?php if($task->has_blocker): ?><span class="text-red-500 ms-1">⛔</span><?php endif; ?>
                            </td>
                            <td class="px-4 py-2.5 text-gray-500"><?php echo e($task->assignedTo?->name ?? '—'); ?></td>
                            <td class="px-4 py-2.5 text-gray-600"><?php echo e($task->status); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const theme = <?php echo json_encode($themeColor, 15, 512) ?>;
    const charts = <?php echo json_encode($charts, 15, 512) ?>;
    const defaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { labels: { font: { family: 'Tajawal' }, boxWidth: 12 } } },
        scales: {
            x: { ticks: { font: { family: 'Tajawal', size: 10 }, maxRotation: 45 } },
            y: { ticks: { font: { family: 'Tajawal', size: 10 } }, beginAtZero: true }
        }
    };

    new Chart(document.getElementById('chartRevenue'), {
        type: 'line',
        data: {
            labels: charts.revenue_monthly.labels,
            datasets: [{
                label: 'إيراد (ج.م)',
                data: charts.revenue_monthly.values,
                borderColor: '#059669',
                backgroundColor: 'rgba(5,150,105,0.1)',
                fill: true,
                tension: 0.35,
                pointRadius: 3
            }]
        },
        options: { ...defaults, plugins: { ...defaults.plugins, legend: { display: false } } }
    });

    new Chart(document.getElementById('chartActivity'), {
        type: 'line',
        data: {
            labels: charts.activity_daily.labels,
            datasets: [
                { label: 'مشاريع', data: charts.activity_daily.projects, borderColor: theme, tension: 0.3, pointRadius: 0 },
                { label: 'مهام', data: charts.activity_daily.tasks, borderColor: '#8b5cf6', tension: 0.3, pointRadius: 0 }
            ]
        },
        options: defaults
    });

    new Chart(document.getElementById('chartProjectStatus'), {
        type: 'doughnut',
        data: {
            labels: charts.project_status.labels,
            datasets: [{
                data: charts.project_status.values,
                backgroundColor: ['#94a3b8', theme, '#f59e0b', '#059669', '#ef4444']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'right', labels: { font: { family: 'Tajawal', size: 10 } } } }
        }
    });

    new Chart(document.getElementById('chartLeadsTasks'), {
        type: 'bar',
        data: {
            labels: charts.leads_monthly.labels,
            datasets: [
                { label: 'Leads', data: charts.leads_monthly.values, backgroundColor: theme + '99' },
                { label: 'مهام مكتملة', data: charts.tasks_monthly.values, backgroundColor: '#8b5cf699' }
            ]
        },
        options: defaults
    });

    const deptEl = document.getElementById('chartDepartments');
    if (deptEl && charts.department_load.length) {
        new Chart(deptEl, {
            type: 'bar',
            data: {
                labels: charts.department_load.map(d => d.name),
                datasets: [
                    { label: 'مشاريع', data: charts.department_load.map(d => d.projects), backgroundColor: theme },
                    { label: 'موظفون', data: charts.department_load.map(d => d.employees), backgroundColor: '#64748b' }
                ]
            },
            options: { ...defaults, indexAxis: 'y' }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views/dashboard/partials/admin.blade.php ENDPATH**/ ?>