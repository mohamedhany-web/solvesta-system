@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $user = $employee->user;
@endphp
@if($user)
@php
    $kpi = \App\Models\EmployeeKpiPeriod::where('user_id', $user->id)
        ->where('period_year', now()->year)->where('period_month', now()->month)->first();
    $warnings = \App\Models\HrWarning::where('user_id', $user->id)->whereIn('status',['active','escalated'])->count();
@endphp
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">
    <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
        <h2 class="text-lg font-bold font-tajawal" style="color:{{ $themeColor }};">KPI والأداء</h2>
        <a href="{{ route('kpi.show', $user) }}" class="text-sm font-bold text-blue-600">تفاصيل KPI →</a>
    </div>
    @if($kpi)
    <div class="flex flex-wrap gap-4 items-center">
        <div class="text-center">
            <p class="text-4xl font-bold" style="color:{{ $themeColor }};">{{ $kpi->total_score }}%</p>
            <p class="text-xs text-gray-500">{{ $kpi->grade_label }}</p>
        </div>
        <div class="text-sm font-tajawal space-y-1">
            <p>التزام: {{ $kpi->adherence_score }}%</p>
            <p>المهام: {{ $kpi->task_completion_score }}%</p>
            <p>خصومات: {{ $kpi->kpi_deductions }}</p>
        </div>
        @if($warnings)
        <span class="px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs font-bold">{{ $warnings }} تحذير نشط</span>
        @endif
    </div>
    @else
    <p class="text-sm text-gray-500 font-tajawal">لم يُحسب KPI بعد — <a href="{{ route('kpi.index') }}" class="text-blue-600 font-bold">لوحة KPI</a></p>
    @endif
</div>
@endif
