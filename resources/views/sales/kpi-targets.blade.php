@extends('layouts.app')

@section('page-title', 'أهداف KPI — المبيعات')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full px-2 sm:px-0 font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'أهداف KPI — موظفو المبيعات',
        'subtitle' => 'Quota · Targets · واجبات شهرية — B2B Software',
        'icon' => 'chart',
    ])

    @if(session('success'))
    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    <form method="GET" class="flex flex-wrap gap-2 items-center mb-6 bg-white rounded-2xl shadow-sm border border-gray-200 p-4">
        <select name="user_id" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm min-w-[200px] font-tajawal" onchange="this.form.submit()">
            @foreach($salesReps as $rep)
            <option value="{{ $rep->id }}" @selected($selectedUser?->id == $rep->id)>{{ $rep->name }} — {{ $rep->employee?->department?->name ?? '' }}</option>
            @endforeach
        </select>
        <select name="year" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-tajawal">
            @for($y = now()->year; $y >= now()->year - 1; $y--)
            <option value="{{ $y }}" @selected($year == $y)>{{ $y }}</option>
            @endfor
        </select>
        <select name="month" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-tajawal">
            @for($m = 1; $m <= 12; $m++)
            <option value="{{ $m }}" @selected($month == $m)>{{ $m }}</option>
            @endfor
        </select>
        <button type="submit" class="px-5 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm"
                style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">عرض</button>
        <a href="{{ route('sales.dashboard', ['user_id' => $selectedUser?->id, 'year' => $year, 'month' => $month]) }}"
           class="px-5 py-2.5 rounded-xl border border-gray-300 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50">معاينة اللوحة</a>
    </form>

    @if($selectedUser)
    <form method="POST" action="{{ route('sales.kpi-targets.store') }}">
        @csrf
        <input type="hidden" name="user_id" value="{{ $selectedUser->id }}">
        <input type="hidden" name="period_year" value="{{ $year }}">
        <input type="hidden" name="period_month" value="{{ $month }}">

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
                <h2 class="font-bold text-lg">{{ $selectedUser->name }} — {{ $year }}/{{ str_pad((string) $month, 2, '0', STR_PAD_LEFT) }}</h2>
                <p class="text-xs text-gray-500">اترك الهدف فارغاً لحذف التخصيص واستخدام الافتراضي</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead style="background: {{ $themeColor }}08;">
                        <tr class="text-xs text-gray-600">
                            <th class="px-4 py-3 text-right font-bold">KPI</th>
                            <th class="px-4 py-3 text-right font-bold">الهدف</th>
                            <th class="px-4 py-3 text-right font-bold">الوزن %</th>
                            <th class="px-4 py-3 text-right font-bold">واجب شهري (يظهر للموظف)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($metrics as $key => $def)
                        @php $row = $existing->get($key); @endphp
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-4 py-3">
                                <p class="font-bold">{{ $def['label'] }}</p>
                                <p class="text-xs text-gray-500">{{ $def['description'] ?? '' }}</p>
                                <p class="text-xs text-gray-400 mt-1">افتراضي: {{ $defaults[$key] ?? '—' }} {{ $def['unit'] ?? '' }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="targets[{{ $key }}][target_value]" step="any" min="0"
                                       value="{{ old("targets.{$key}.target_value", $row?->target_value ?? $defaults[$key] ?? '') }}"
                                       class="w-full max-w-[140px] border border-gray-200 rounded-xl px-3 py-2 text-sm">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" name="targets[{{ $key }}][weight]" min="0" max="100"
                                       value="{{ old("targets.{$key}.weight", $row?->weight ?? $weights[$key] ?? 10) }}"
                                       class="w-20 border border-gray-200 rounded-xl px-3 py-2 text-sm">
                            </td>
                            <td class="px-4 py-3">
                                <textarea name="targets[{{ $key }}][monthly_duty]" rows="2" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm" placeholder="مثال: إغلاق 2 demo مع enterprise leads">{{ old("targets.{$key}.monthly_duty", $row?->monthly_duty) }}</textarea>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <button type="submit" class="px-6 py-3 rounded-xl text-white font-bold shadow-lg hover:shadow-xl transition"
                style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">حفظ الأهداف والواجبات</button>
    </form>
    @else
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 text-center text-gray-600">
        لا يوجد موظفو مبيعات (دور sales_rep). أنشئ موظفاً وعيّنه لقسم المبيعات.
    </div>
    @endif
</div>
@endsection
