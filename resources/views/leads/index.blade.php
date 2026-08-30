@extends('layouts.app')

@section('page-title', 'العملاء المحتملون')

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $statusBadge = [
        'new' => 'bg-blue-100 text-blue-800',
        'contacted' => 'bg-indigo-100 text-indigo-800',
        'qualified' => 'bg-purple-100 text-purple-800',
        'converted' => 'bg-emerald-100 text-emerald-800',
        'lost' => 'bg-red-100 text-red-800',
        'on_hold' => 'bg-amber-100 text-amber-800',
    ];
    $kpiCards = [
        ['الكل', $stats['total'], $themeColor, 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
        ['جديد', $stats['new'], '#2563eb', 'M12 4v16m8-8H4'],
        ['تم التواصل', $stats['contacted'], '#4f46e5', 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
        ['مؤهل', $stats['qualified'], '#7c3aed', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['تحوّل لمبيعات', $stats['converted'], '#059669', 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
        ['مفقود', $stats['lost'], '#dc2626', 'M6 18L18 6M6 6l12 12'],
    ];
@endphp

<div class="w-full max-w-full font-tajawal">
    <div class="mb-6 sm:mb-8">
        <div class="rounded-2xl p-5 sm:p-6 lg:p-8 shadow-xl border overflow-hidden relative"
             style="background: linear-gradient(135deg, {{ $themeColor }}15 0%, {{ $themeColor }}05 50%, {{ $themeColor }}10 100%); border-color: {{ $themeColor }}30;">
            <div class="absolute inset-0 opacity-5 pointer-events-none overflow-hidden">
                <div class="absolute top-8 right-8 w-48 h-48 rounded-full" style="background: {{ $themeColor }};"></div>
                <div class="absolute bottom-8 left-8 w-32 h-32 rounded-full" style="background: {{ $themeColor }};"></div>
            </div>
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="h-14 w-14 rounded-2xl flex items-center justify-center shadow-xl flex-shrink-0"
                         style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                        <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">العملاء المحتملون</h1>
                        <p class="text-sm sm:text-base text-gray-600 mt-1">توليد ومتابعة Leads — من التطوير إلى المبيعات</p>
                    </div>
                </div>
                @can('create-sales')
                <a href="{{ route('leads.create') }}"
                   class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 sm:px-6 sm:py-3 rounded-xl text-white text-sm font-bold shadow-md hover:shadow-lg transition"
                   style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Lead جديد
                </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-4 mb-6">
        @foreach($kpiCards as [$label, $val, $color, $icon])
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-5 hover:shadow-lg transition-all duration-300 group">
                <div class="flex items-center justify-between gap-2 mb-3">
                    <p class="text-xs sm:text-sm text-gray-500 font-medium">{{ $label }}</p>
                    <div class="p-2 rounded-xl shadow-sm group-hover:scale-110 transition-transform"
                         style="background: linear-gradient(135deg, {{ $color }} 0%, {{ $color }}cc 100%);">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
                        </svg>
                    </div>
                </div>
                <p class="text-2xl sm:text-3xl font-bold" style="color: {{ $color }};">{{ $val }}</p>
            </div>
        @endforeach
    </div>

    <form method="GET" action="{{ route('leads.index') }}"
          class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-5 mb-6">
        <div class="flex flex-col lg:flex-row gap-3 lg:items-end">
            <div class="flex-1">
                <label class="block text-xs font-bold text-gray-500 mb-1.5">بحث</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="الاسم، الشركة، البريد، أو المرجع..."
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <div class="w-full lg:w-52">
                <label class="block text-xs font-bold text-gray-500 mb-1.5">الحالة</label>
                <select name="status" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">كل الحالات</option>
                    @foreach(['new','contacted','qualified','converted','lost','on_hold'] as $st)
                        <option value="{{ $st }}" @selected(request('status') === $st)>{{ (new \App\Models\Lead(['status' => $st]))->status_label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full lg:w-52">
                <label class="block text-xs font-bold text-gray-500 mb-1.5">المصدر</label>
                <select name="source" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">كل المصادر</option>
                    @foreach(['bd_outreach'=>'تطوير أعمال','referral'=>'إحالة','ads'=>'إعلانات','social_media'=>'سوشيال','website'=>'موقع','event'=>'فعالية','other'=>'أخرى'] as $v => $l)
                        <option value="{{ $v }}" @selected(request('source') === $v)>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 lg:flex-none px-6 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm hover:opacity-95 transition"
                        style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                    تصفية
                </button>
                @if(request()->hasAny(['search', 'status', 'source']))
                    <a href="{{ route('leads.index') }}"
                       class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">
                        مسح
                    </a>
                @endif
            </div>
        </div>
    </form>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[720px]">
                <thead style="background: {{ $themeColor }}08;">
                    <tr>
                        <th class="px-4 py-3.5 text-right font-bold text-gray-700">المرجع</th>
                        <th class="px-4 py-3.5 text-right font-bold text-gray-700">الاسم</th>
                        <th class="px-4 py-3.5 text-right font-bold text-gray-700">الشركة</th>
                        <th class="px-4 py-3.5 text-right font-bold text-gray-700">الخدمة</th>
                        <th class="px-4 py-3.5 text-right font-bold text-gray-700">المصدر</th>
                        <th class="px-4 py-3.5 text-right font-bold text-gray-700">مسند إلى</th>
                        <th class="px-4 py-3.5 text-right font-bold text-gray-700">الحالة</th>
                        <th class="px-4 py-3.5 text-right font-bold text-gray-700"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($leads as $lead)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3.5 font-mono text-xs text-gray-500 whitespace-nowrap">{{ $lead->reference_code }}</td>
                            <td class="px-4 py-3.5">
                                <a href="{{ route('leads.show', $lead) }}" class="font-bold text-gray-900 hover:underline" style="color: inherit;">
                                    {{ $lead->name }}
                                </a>
                                @if($lead->email)
                                    <p class="text-xs text-gray-400 mt-0.5 truncate max-w-[180px]">{{ $lead->email }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-gray-600">{{ $lead->company ?: '—' }}</td>
                            <td class="px-4 py-3.5 text-gray-600">{{ $lead->service_interest ?: '—' }}</td>
                            <td class="px-4 py-3.5 text-gray-600">{{ $lead->source_label }}</td>
                            <td class="px-4 py-3.5 text-gray-600">{{ $lead->assignee?->name ?? '—' }}</td>
                            <td class="px-4 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusBadge[$lead->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $lead->status_label }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-left whitespace-nowrap">
                                <a href="{{ route('leads.show', $lead) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold text-white shadow-sm hover:opacity-95 transition"
                                   style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                                    عرض
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-16 text-center">
                                <div class="mx-auto h-14 w-14 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                    <svg class="h-7 w-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                </div>
                                <h3 class="text-base font-bold text-gray-900">لا توجد Leads بعد</h3>
                                <p class="mt-1 text-sm text-gray-500">ابدأ بتسجيل عميل محتمل جديد</p>
                                @can('create-sales')
                                <a href="{{ route('leads.create') }}"
                                   class="inline-flex mt-4 px-5 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm"
                                   style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                                    + Lead جديد
                                </a>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($leads->hasPages())
            <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                {{ $leads->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
