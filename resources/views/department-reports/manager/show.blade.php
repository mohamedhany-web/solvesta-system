@extends('layouts.app')

@section('page-title', 'عرض تقرير')

@section('content')
<div class="w-full max-w-6xl mx-auto">
    <div class="mb-8 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">تقرير القسم</h1>
            <p class="text-gray-600">
                الحالة:
                @if($report->status === 'submitted')
                    <span class="font-bold text-green-700">مُرسل</span>
                @else
                    <span class="font-bold text-gray-800">مسودة</span>
                @endif
                <span class="text-gray-300 mx-2">|</span>
                تاريخ الإنشاء: {{ $report->created_at?->format('Y-m-d H:i') }}
            </p>
        </div>
        <a href="{{ route('department-manager.reports.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-200 shadow-sm">
            رجوع
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8">
                <h2 class="text-lg font-extrabold text-gray-900 mb-4">الملخص</h2>
                <div class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $report->summary }}</div>
            </div>

            @if(is_array($report->kpis) && count($report->kpis))
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8">
                    <h2 class="text-lg font-extrabold text-gray-900 mb-4">KPIs</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($report->kpis as $key => $value)
                            <div class="rounded-xl border border-gray-200 p-4">
                                <div class="text-xs font-bold text-gray-500 uppercase">{{ $key }}</div>
                                <div class="text-xl font-extrabold text-gray-900 mt-2">{{ is_scalar($value) ? $value : json_encode($value) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-extrabold text-gray-900 mb-4">معلومات</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">القسم</span>
                        <span class="font-bold text-gray-900">{{ $report->department?->name ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">المشروع</span>
                        <span class="font-bold text-gray-900">{{ $report->project?->name ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">المنشئ</span>
                        <span class="font-bold text-gray-900">{{ $report->creator?->name ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">الفترة</span>
                        <span class="font-bold text-gray-900">
                            @if($report->period_start || $report->period_end)
                                {{ $report->period_start?->format('Y-m-d') ?? '—' }} → {{ $report->period_end?->format('Y-m-d') ?? '—' }}
                            @else
                                —
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            @if(is_array($report->attachments) && count($report->attachments))
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                    <h3 class="text-lg font-extrabold text-gray-900 mb-4">المرفقات</h3>
                    <div class="space-y-2">
                        @foreach($report->attachments as $file)
                            <a class="block text-blue-600 hover:underline text-sm"
                               href="{{ asset('storage/' . ($file['path'] ?? '')) }}"
                               target="_blank" rel="noopener">
                                {{ $file['name'] ?? 'ملف' }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

