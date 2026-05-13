@extends('layouts.app')

@section('page-title', 'تقارير الخدمة')

@section('content')
<div class="w-full max-w-full">
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">تقارير الخدمة</h1>
                <p class="text-gray-600 text-sm sm:text-base">تقارير يتم إعدادها ورفعها من الإدارة عن الخدمة المقدّمة لك — العميل: {{ $client->name }}</p>
            </div>
            <a href="{{ route('client.dashboard') }}" class="bg-gray-600 text-white px-5 py-2.5 rounded-lg hover:bg-gray-700 transition-all duration-200 text-sm">العودة</a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700">العنوان</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700">التاريخ</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 w-40">الإجراء</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($reports as $report)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 align-top">
                                <div class="font-semibold text-gray-900">{{ $report->title }}</div>
                                @if($report->description)
                                    <p class="text-sm text-gray-600 mt-1 leading-relaxed">{{ \Illuminate\Support\Str::limit($report->description, 200) }}</p>
                                @endif
                                @if($report->original_filename)
                                    <p class="text-xs text-gray-500 mt-1">الملف: {{ $report->original_filename }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">{{ $report->created_at->format('Y/m/d') }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('client.service-reports.download', $report) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
                                    تنزيل
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-gray-500">لا توجد تقارير بعد. ستظهر هنا التقارير التي ترفعها الإدارة عن الخدمة.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reports->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
