@extends('layouts.app')



@section('page-title', 'لوحة المدير التنفيذية')



@section('content')

@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp

<div class="w-full max-w-full px-2 sm:px-0">

    @include('partials.erp-page-header', [

        'title' => 'لوحة CEO',

        'subtitle' => 'المبيعات · المالية · المشاريع · العمليات — نظرة تنفيذية دون تفاصيل المهام',

        'icon' => 'chart',

    ])



    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="{{ route('executive.finance') }}" class="px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-lg"
           style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">التقارير المالية التفصيلية →</a>
    </div>

    <section class="mb-8">

        <h2 class="text-lg font-bold mb-4 font-tajawal text-gray-900">المبيعات والـ Pipeline</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-8 gap-3 text-sm">

            @foreach([

                ['Leads جديدة', $pipeline['leads_new'], ''],

                ['Leads مؤهلة', $pipeline['leads_qualified'], '#2563eb'],

                ['فرص مفتوحة', $pipeline['sales_open'], ''],

                ['مؤهّلة', $pipeline['sales_qualified'], $themeColor],

                ['عروض', $pipeline['proposal'], '#d97706'],

                ['تفاوض', $pipeline['negotiation'], '#ea580c'],

                ['صفقات مغلقة', $pipeline['closed_won'], '#059669'],

                ['خاسرة', $pipeline['closed_lost'], '#dc2626'],

            ] as [$label, $val, $color])

            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-4 text-center hover:shadow-xl transition-all duration-300">

                <p class="text-gray-500 text-xs font-tajawal mb-1">{{ $label }}</p>

                <p class="text-2xl font-bold font-tajawal" @if($color) style="color: {{ $color }};" @endif>{{ $val }}</p>

            </div>

            @endforeach

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">

            <div class="rounded-2xl p-5 border shadow-lg" style="background: linear-gradient(135deg, {{ $themeColor }}10 0%, {{ $themeColor }}05 100%); border-color: {{ $themeColor }}25;">

                <p class="text-sm text-gray-600 font-tajawal">قيمة الـ Pipeline</p>

                <p class="text-3xl font-bold font-tajawal" style="color: {{ $themeColor }};">{{ number_format($pipeline['pipeline_value']) }} ج.م</p>

            </div>

            <div class="rounded-2xl p-5 border border-emerald-100 shadow-lg bg-gradient-to-r from-emerald-50 to-green-50">

                <p class="text-sm text-gray-600 font-tajawal">إيرادات مغلقة</p>

                <p class="text-3xl font-bold text-emerald-700 font-tajawal">{{ number_format($pipeline['won_value']) }} ج.م</p>

            </div>

            @if(isset($presales))

            <div class="rounded-2xl p-5 border border-blue-100 shadow-lg bg-blue-50/50">

                <p class="text-sm text-gray-600 font-tajawal">عروض مُرسلة</p>

                <p class="text-3xl font-bold text-blue-700 font-tajawal">{{ $presales['sent'] }}</p>

            </div>

            <div class="rounded-2xl p-5 border border-indigo-100 shadow-lg bg-indigo-50/50">

                <p class="text-sm text-gray-600 font-tajawal">عروض مقبولة</p>

                <p class="text-3xl font-bold text-indigo-700 font-tajawal">{{ $presales['accepted'] }}</p>

            </div>

            <div class="rounded-2xl p-5 border shadow-lg" style="border-color: {{ $themeColor }}30; background: {{ $themeColor }}08;">

                <p class="text-sm text-gray-600 font-tajawal">ربح تقديري</p>

                <p class="text-3xl font-bold font-tajawal" style="color: {{ $themeColor }};">{{ number_format($financeSummary['estimated_profit'] ?? 0) }}</p>

            </div>

            @endif

        </div>

    </section>



    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <section class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">

            <h2 class="font-bold mb-4 font-tajawal flex items-center gap-2">

                <span class="w-2 h-6 rounded-full" style="background: {{ $themeColor }};"></span>

                المالية

            </h2>

            <p class="text-sm text-gray-600 font-tajawal">محصّل</p>

            <p class="text-2xl font-bold text-emerald-700 mb-3 font-tajawal">{{ number_format($finance['revenue_paid']) }}</p>

            <p class="text-sm text-gray-600 font-tajawal">مستحقات</p>

            <p class="text-2xl font-bold text-amber-700 font-tajawal">{{ number_format($finance['outstanding']) }}</p>

        </section>

        <section class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">

            <h2 class="font-bold mb-4 font-tajawal flex items-center gap-2">

                <span class="w-2 h-6 rounded-full" style="background: {{ $themeColor }};"></span>

                المشاريع

            </h2>

            <ul class="space-y-3 text-sm font-tajawal">

                <li class="flex justify-between"><span>نشطة</span><strong>{{ $projects['active'] }}</strong></li>

                <li class="flex justify-between"><span>مكتملة</span><strong>{{ $projects['completed'] }}</strong></li>

                <li class="flex justify-between text-red-700"><span>متأخرة</span><strong>{{ $projects['overdue'] }}</strong></li>

                @if(($projects['blocked_payment'] ?? 0) > 0)

                <li class="flex justify-between text-amber-700"><span>بانتظار دفع</span><strong>{{ $projects['blocked_payment'] }}</strong></li>

                @endif

            </ul>

        </section>

        <section class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">

            <h2 class="font-bold mb-4 font-tajawal flex items-center gap-2">

                <span class="w-2 h-6 rounded-full" style="background: {{ $themeColor }};"></span>

                العمليات

            </h2>

            <ul class="space-y-3 text-sm font-tajawal">

                <li class="flex justify-between"><span>مهام مفتوحة</span><strong>{{ $operations['open_tasks'] }}</strong></li>

                <li class="flex justify-between text-red-700"><span>مهام متأخرة</span><strong>{{ $operations['overdue_tasks'] }}</strong></li>
                <li class="flex justify-between text-amber-700"><span>Blockers</span><strong>{{ $operations['open_blockers'] ?? 0 }}</strong></li>
                <li class="flex justify-between"><span>حضور اليوم</span><strong>{{ $operations['attendance_today'] }}</strong></li>
                @if(isset($performance))
                <li class="flex justify-between"><span>متوسط KPI</span><strong style="color:{{ $themeColor }};">{{ $performance['avg_kpi'] }}%</strong></li>
                <li class="flex justify-between text-red-700"><span>تحقيقات HR</span><strong>{{ $performance['hr_investigations'] }}</strong></li>
                @endif

            </ul>

        </section>

    </div>



    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">

            <h3 class="font-bold mb-4 font-tajawal">آخر Leads</h3>

            <ul class="text-sm space-y-3 font-tajawal">

                @foreach($recentLeads as $l)

                    <li class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">

                        <a href="{{ route('leads.show', $l) }}" class="font-semibold hover:underline" style="color: {{ $themeColor }};">{{ $l->name }}</a>

                        <span class="text-gray-500 text-xs px-2 py-1 bg-gray-100 rounded-full">{{ $l->status_label }}</span>

                    </li>

                @endforeach

            </ul>

        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">

            <h3 class="font-bold mb-4 font-tajawal">آخر صفقات مغلقة</h3>

            <ul class="text-sm space-y-3 font-tajawal">

                @forelse($recentWins as $s)

                    <li class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">

                        <span>{{ $s->client?->name }} — {{ Str::limit($s->product_service, 30) }}</span>

                        <strong style="color: {{ $themeColor }};">{{ number_format($s->amount) }}</strong>

                    </li>

                @empty

                    <li class="text-gray-500">لا توجد صفقات مغلقة بعد.</li>

                @endforelse

            </ul>

        </div>

    </div>

</div>

@endsection

