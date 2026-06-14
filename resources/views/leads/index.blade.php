@extends('layouts.app')

@section('page-title', 'Leads — توليد العملاء')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full px-2 sm:px-0">
    @include('partials.erp-page-header', [
        'title' => 'Leads',
        'subtitle' => 'توليد العملاء المحتملين — Business Development → Sales',
        'icon' => 'users',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        @can('create-sales')
        <a href="{{ route('leads.create') }}" class="px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-lg hover:shadow-xl transition"
           style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">+ Lead جديد</a>
        @endcan
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        @foreach([
            ['جديد', $stats['new'], $themeColor],
            ['مؤهل', $stats['qualified'], '#2563eb'],
            ['تحوّل لمبيعات', $stats['converted'], '#059669'],
        ] as [$label, $val, $color])
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5 text-center hover:shadow-xl transition-all duration-300">
            <p class="text-gray-500 text-sm font-tajawal">{{ $label }}</p>
            <p class="text-3xl font-bold font-tajawal mt-1" style="color: {{ $color }};">{{ $val }}</p>
        </div>
        @endforeach
    </div>

    <form method="GET" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6 flex flex-wrap gap-3 items-end">
        <input name="search" value="{{ request('search') }}" placeholder="بحث..." class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-tajawal">
        <select name="status" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-tajawal">
            <option value="">كل الحالات</option>
            @foreach(['new','contacted','qualified','converted','lost','on_hold'] as $st)
                <option value="{{ $st }}" @selected(request('status')===$st)>{{ (new \App\Models\Lead(['status'=>$st]))->status_label }}</option>
            @endforeach
        </select>
        <button class="px-5 py-2.5 rounded-xl text-white text-sm font-bold"
                style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">تصفية</button>
    </form>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <table class="w-full text-sm font-tajawal">
            <thead style="background: {{ $themeColor }}08;">
                <tr>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">المرجع</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">الاسم</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">الشركة</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">الخدمة</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">المصدر</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">الحالة</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($leads as $lead)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $lead->reference_code }}</td>
                    <td class="px-4 py-3 font-semibold text-gray-900">{{ $lead->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $lead->company ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $lead->service_interest ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $lead->source_label }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs bg-gray-100">{{ $lead->status_label }}</span></td>
                    <td class="px-4 py-3"><a href="{{ route('leads.show', $lead) }}" class="font-bold" style="color: {{ $themeColor }};">فتح</a></td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-16 text-center text-gray-500">لا توجد Leads بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t">{{ $leads->links() }}</div>
    </div>
</div>
@endsection
