@extends('layouts.app')

@section('page-title', 'بلاغات مشاكل الموقع')

@section('content')
<div class="w-full max-w-full">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">بلاغات مشاكل الموقع</h1>
            <p class="text-gray-600 text-sm sm:text-base">سجّل مشكلة واجهتها في الموقع أو البوابة مع إمكانية إرفاق لقطات شاشة. يتابعها فريق الدعم ويحدّث الحالة.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('client.website-issues.create') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition text-sm font-semibold">بلاغ جديد</a>
            <a href="{{ route('client.dashboard') }}" class="bg-gray-600 text-white px-5 py-2.5 rounded-lg hover:bg-gray-700 transition text-sm">العودة</a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right font-semibold text-gray-700">المرجع</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-700">العنوان</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-700">الحالة</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-700">التاريخ</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700 w-28">تفاصيل</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($issues as $issue)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono font-semibold text-gray-900">{{ $issue->reference_code }}</td>
                            <td class="px-6 py-4 text-gray-900">{{ $issue->title }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold
                                    @if($issue->status === 'open') bg-red-100 text-red-800
                                    @elseif($issue->status === 'in_progress') bg-amber-100 text-amber-900
                                    @elseif($issue->status === 'resolved') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $issue->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">{{ $issue->created_at->format('Y/m/d') }}</td>
                            <td class="px-6 py-4 text-left">
                                <a href="{{ route('client.website-issues.show', $issue) }}" class="text-blue-600 hover:text-blue-800 font-semibold">عرض</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">لا توجد بلاغات بعد. استخدم «بلاغ جديد» عند مواجهة أي مشكلة.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($issues->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $issues->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
