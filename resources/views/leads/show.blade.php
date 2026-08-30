@extends('layouts.app')

@section('page-title', $lead->name)

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
@endphp

<div class="w-full max-w-full font-tajawal">
    <div class="mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 sm:p-6 lg:p-8 border border-blue-100">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="h-12 w-12 sm:h-14 sm:w-14 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                        <svg class="h-6 w-6 sm:h-7 sm:w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="font-mono text-xs text-gray-500">{{ $lead->reference_code }}</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusBadge[$lead->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $lead->status_label }}
                            </span>
                        </div>
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 break-words">{{ $lead->name }}</h1>
                        <p class="text-gray-600 text-sm sm:text-base mt-1">
                            {{ $lead->company ?: 'بدون شركة' }}
                            @if($lead->service_interest)
                                · {{ $lead->service_interest }}
                            @endif
                        </p>
                    </div>
                </div>
                <a href="{{ route('leads.index') }}"
                   class="w-full sm:w-auto bg-gray-600 text-white px-5 py-2.5 sm:px-6 sm:py-3 rounded-lg hover:bg-gray-700 transition-all duration-200 flex items-center justify-center shadow-sm text-sm sm:text-base">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 sm:px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900">التفاصيل</h2>
                </div>
                <div class="p-4 sm:p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach([
                            ['الحالة', $lead->status_label],
                            ['المصدر', $lead->source_label],
                            ['البريد', $lead->email ?: '—'],
                            ['الهاتف', $lead->phone ?: '—'],
                            ['الشركة', $lead->company ?: '—'],
                            ['الخدمة', $lead->service_interest ?: '—'],
                            ['الميزانية', $lead->estimated_budget ? number_format($lead->estimated_budget).' ج.م' : '—'],
                            ['مسند إلى', $lead->assignee?->name ?? '—'],
                            ['أُنشئ بواسطة', $lead->creator?->name ?? '—'],
                            ['تاريخ الإنشاء', $lead->created_at?->format('Y/m/d H:i') ?? '—'],
                        ] as [$label, $value])
                            <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                                <dt class="text-xs font-semibold text-gray-500 mb-1">{{ $label }}</dt>
                                <dd class="text-sm font-bold text-gray-900 break-words">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>

                    @if($lead->notes)
                        <div class="mt-6 rounded-xl border border-gray-100 bg-white px-4 py-4">
                            <h3 class="text-sm font-bold text-gray-900 mb-2">الملاحظات</h3>
                            <p class="text-sm sm:text-base text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $lead->notes }}</p>
                        </div>
                    @endif

                    @if($lead->lost_reason)
                        <div class="mt-4 rounded-xl border border-red-100 bg-red-50 px-4 py-3">
                            <h3 class="text-sm font-bold text-red-800 mb-1">سبب الفقد</h3>
                            <p class="text-sm text-red-700">{{ $lead->lost_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($lead->convertedClient || $lead->convertedSale)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-50 to-green-50 px-4 sm:px-6 py-4 border-b border-emerald-100">
                    <h2 class="text-lg font-bold text-emerald-900">نتائج التحويل</h2>
                </div>
                <div class="p-4 sm:p-6 flex flex-wrap gap-3">
                    @if($lead->convertedClient)
                        <a href="{{ route('clients.show', $lead->convertedClient) }}"
                           class="inline-flex items-center px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-sm font-bold text-gray-800 hover:bg-gray-50 transition">
                            فتح العميل
                        </a>
                    @endif
                    @if($lead->convertedSale)
                        <a href="{{ route('sales.show', $lead->convertedSale) }}"
                           class="inline-flex items-center px-4 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm hover:opacity-95 transition"
                           style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                            فتح فرصة المبيعات
                        </a>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-4">
            @if($lead->status !== 'converted')
                <form method="POST" action="{{ route('leads.update-status', $lead) }}"
                      class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    @csrf
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 sm:px-5 py-4 border-b border-gray-200">
                        <h3 class="font-bold text-gray-900">تحديث الحالة</h3>
                    </div>
                    <div class="p-4 sm:p-5 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">الحالة</label>
                            <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @foreach(['new','contacted','qualified','lost','on_hold'] as $st)
                                    <option value="{{ $st }}" @selected($lead->status === $st)>{{ (new \App\Models\Lead(['status' => $st]))->status_label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">سبب الفقد (إن وُجد)</label>
                            <input type="text" name="lost_reason" value="{{ old('lost_reason', $lead->lost_reason) }}"
                                   placeholder="اختياري"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <button type="submit"
                                class="w-full py-3 rounded-xl bg-gray-900 text-white text-sm font-bold hover:bg-gray-800 transition">
                            حفظ الحالة
                        </button>
                    </div>
                </form>

                @can('create-sales')
                <form method="POST" action="{{ route('leads.convert-to-sale', $lead) }}"
                      class="rounded-xl shadow-sm border overflow-hidden"
                      style="background: {{ $themeColor }}08; border-color: {{ $themeColor }}30;"
                      onsubmit="return confirm('تحويل هذا الـ Lead إلى فرصة مبيعات؟')">
                    @csrf
                    <div class="px-4 sm:px-5 py-4 border-b" style="border-color: {{ $themeColor }}20;">
                        <h3 class="font-bold text-gray-900">تحويل إلى Sales</h3>
                        <p class="text-xs text-gray-600 mt-1">ينشئ عميلاً وفرصة مبيعات تلقائياً</p>
                    </div>
                    <div class="p-4 sm:p-5 space-y-4">
                        @unless(\App\Services\SalesRepScope::isRestricted(auth()->user()))
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">مسند إلى <span class="text-red-500">*</span></label>
                                <select name="assigned_to" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}" @selected($lead->assigned_to == $u->id)>{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="assigned_to" value="{{ auth()->id() }}">
                            <p class="text-xs text-gray-600 bg-white/70 rounded-lg px-3 py-2">ستُسند الفرصة إليك تلقائياً.</p>
                        @endunless
                        <button type="submit"
                                class="w-full py-3 rounded-xl text-white text-sm font-bold shadow-sm hover:opacity-95 transition"
                                style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                            تحويل لـ Sales ←
                        </button>
                    </div>
                </form>
                @endcan
            @else
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="font-bold text-emerald-900">تم التحويل بنجاح</p>
                    </div>
                    <p class="text-sm text-emerald-800 mb-4">هذا الـ Lead تحوّل إلى فرصة مبيعات.</p>
                    @if($lead->convertedSale)
                        <a href="{{ route('sales.show', $lead->convertedSale) }}"
                           class="inline-flex w-full items-center justify-center px-4 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm"
                           style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                            فتح فرصة المبيعات
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
