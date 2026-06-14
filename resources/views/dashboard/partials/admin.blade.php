@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'لوحة التحكم',
        'subtitle' => 'نظرة تشغيلية — مبيعات · مشاريع · مهام · اتجاهات',
        'icon' => 'chart',
    ])

    {{-- شريط KPIs مضغوط --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-6 overflow-hidden">
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 divide-x divide-y md:divide-y-0 divide-gray-100 divide-x-reverse">
            @foreach($kpis as $kpi)
            <div class="px-4 py-4 hover:bg-gray-50/80 transition-colors">
                <p class="text-[11px] text-gray-500 font-medium mb-1">{{ $kpi['label'] }}</p>
                <div class="flex items-end gap-2">
                    <span class="text-2xl font-bold text-gray-900 leading-none">{{ $kpi['value'] }}</span>
                    @if($kpi['trend'] !== null)
                        <span class="text-xs font-bold mb-0.5 {{ $kpi['trend'] >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $kpi['trend'] >= 0 ? '↑' : '↓' }}{{ abs($kpi['trend']) }}%
                        </span>
                    @endif
                </div>
                <p class="text-[10px] text-gray-400 mt-1">{{ $kpi['sub'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mb-6">
        {{-- العمود الرئيسي: Charts --}}
        <div class="xl:col-span-8 space-y-6">
            {{-- Pipeline شريط مرئي --}}
            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-1.5 h-5 rounded-full" style="background: {{ $themeColor }};"></span>
                        مسار المبيعات
                    </h2>
                    <div class="flex gap-4 text-xs text-gray-500">
                        <span>Pipeline: <strong class="text-gray-800">{{ number_format($pipeline['pipeline_value']) }} ج.م</strong></span>
                        <span>مغلق: <strong class="text-emerald-700">{{ number_format($pipeline['won_value']) }} ج.م</strong></span>
                    </div>
                </div>
                @php
                    $funnelMax = max(1, max($charts['sales_funnel']['values']));
                    $funnelColors = ['#94a3b8', '#3b82f6', '#f59e0b', '#059669'];
                @endphp
                <div class="space-y-3">
                    @foreach(array_combine($charts['sales_funnel']['labels'], $charts['sales_funnel']['values']) as $label => $val)
                    @php $pct = round(($val / $funnelMax) * 100); @endphp
                    <div class="flex items-center gap-3 text-sm">
                        <span class="w-28 text-gray-600 text-xs shrink-0">{{ $label }}</span>
                        <div class="flex-1 h-7 bg-gray-100 rounded-lg overflow-hidden relative">
                            <div class="h-full rounded-lg transition-all duration-500 flex items-center justify-end pe-2"
                                 style="width: {{ max($pct, $val > 0 ? 8 : 0) }}%; background: {{ $funnelColors[$loop->index] ?? $themeColor }};">
                                @if($val > 0)<span class="text-[10px] font-bold text-white">{{ $val }}</span>@endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>

            {{-- صف الرسوم الرئيسية --}}
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

            {{-- حمل الأقسام --}}
            @if($charts['department_load']->isNotEmpty())
            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h3 class="font-bold text-gray-900 mb-4 text-sm">توزيع الحمل على الأقسام</h3>
                <div class="h-48"><canvas id="chartDepartments"></canvas></div>
            </section>
            @endif
        </div>

        {{-- العمود الجانبي: Insights + تنبيهات --}}
        <div class="xl:col-span-4 space-y-6">
            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4" style="color: {{ $themeColor }};" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    رؤى واتجاهات
                </h3>
                <ul class="space-y-3">
                    @forelse($insights as $insight)
                    @php
                        $insightStyle = match($insight['type']) {
                            'positive' => 'border-emerald-200 bg-emerald-50 text-emerald-800',
                            'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
                            'danger' => 'border-red-200 bg-red-50 text-red-800',
                            default => 'border-blue-200 bg-blue-50 text-blue-800',
                        };
                    @endphp
                    <li class="text-xs leading-relaxed px-3 py-2.5 rounded-xl border {{ $insightStyle }}">{{ $insight['text'] }}</li>
                    @empty
                    <li class="text-xs text-gray-500">لا توجد رؤى جديدة — الوضع مستقر.</li>
                    @endforelse
                </ul>
            </section>

            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h3 class="font-bold text-gray-900 mb-4 text-sm">تنبيهات فورية</h3>
                <ul class="space-y-2 text-sm">
                    @foreach([
                        ['مهام متأخرة', $alerts['overdue_tasks'], 'text-red-700', route('tasks.index', ['status' => 'overdue'])],
                        ['مشاريع متأخرة', $alerts['overdue_projects'], 'text-red-700', route('projects.index')],
                        ['بانتظار تعيين فريق', $alerts['pending_team'], 'text-amber-700', route('projects.index')],
                        ['Blockers مفتوحة', $alerts['open_blockers'], 'text-orange-700', route('pmo.index')],
                    ] as [$label, $count, $color, $url])
                    <li class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                        <a href="{{ $url }}" class="text-gray-600 hover:underline text-xs">{{ $label }}</a>
                        <span class="font-bold {{ $count > 0 ? $color : 'text-gray-400' }}">{{ $count }}</span>
                    </li>
                    @endforeach
                </ul>
            </section>

            <section class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h3 class="font-bold text-gray-900 mb-3 text-sm">آخر Leads</h3>
                <ul class="text-xs space-y-2">
                    @forelse($recent_leads as $lead)
                    <li class="flex justify-between items-center py-1.5 border-b border-gray-50 last:border-0">
                        <a href="{{ route('leads.show', $lead) }}" class="font-semibold hover:underline truncate max-w-[60%]" style="color: {{ $themeColor }};">{{ $lead->name }}</a>
                        <span class="text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full shrink-0">{{ $lead->status_label }}</span>
                    </li>
                    @empty
                    <li class="text-gray-400">لا توجد leads حديثة.</li>
                    @endforelse
                </ul>
                <a href="{{ route('leads.index') }}" class="inline-block mt-3 text-xs font-bold hover:underline" style="color: {{ $themeColor }};">عرض الكل →</a>
            </section>
        </div>
    </div>

    {{-- جداول النشاط الأخير --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-sm">آخر المشاريع</h3>
                <a href="{{ route('projects.index') }}" class="text-xs font-bold" style="color: {{ $themeColor }};">الكل</a>
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
                        @foreach($recent_projects as $project)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-4 py-2.5">
                                <a href="{{ route('projects.show', $project) }}" class="font-semibold hover:underline" style="color: {{ $themeColor }};">{{ Str::limit($project->name, 28) }}</a>
                            </td>
                            <td class="px-4 py-2.5 text-gray-500">{{ $project->department?->name ?? '—' }}</td>
                            <td class="px-4 py-2.5"><span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">{{ $project->status_name }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-sm">آخر المهام</h3>
                <a href="{{ route('tasks.index') }}" class="text-xs font-bold" style="color: {{ $themeColor }};">الكل</a>
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
                        @foreach($recent_tasks as $task)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-4 py-2.5">
                                <a href="{{ route('tasks.show', $task) }}" class="font-semibold hover:underline" style="color: {{ $themeColor }};">{{ Str::limit($task->title, 30) }}</a>
                                @if($task->has_blocker)<span class="text-red-500 ms-1">⛔</span>@endif
                            </td>
                            <td class="px-4 py-2.5 text-gray-500">{{ $task->assignedTo?->name ?? '—' }}</td>
                            <td class="px-4 py-2.5 text-gray-600">{{ $task->status }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const theme = @json($themeColor);
    const charts = @json($charts);
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
@endpush
