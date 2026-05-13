@extends('layouts.app')

@section('page-title', 'طلبات الاجتماعات')

@section('content')
<div class="w-full max-w-full">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">طلبات الاجتماعات</h1>
            <p class="text-gray-600 text-sm sm:text-base">حدّد موضوع الاجتماع والوقت المناسب لك. سيتواصل معك الفريق لتأكيد الموعد أو اقتراح بديل.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('client.meeting-requests.create') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition text-sm font-semibold">طلب جديد</a>
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
                        <th class="px-6 py-3 text-right font-semibold text-gray-700">الموضوع</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-700">الحالة</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-700">الموعد المفضل</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700 w-28">تفاصيل</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($meetingRequests as $mr)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono font-semibold text-gray-900">{{ $mr->reference_code }}</td>
                            <td class="px-6 py-4 text-gray-900">{{ $mr->title }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold
                                    @if($mr->status === 'pending') bg-amber-100 text-amber-900
                                    @elseif($mr->status === 'confirmed') bg-blue-100 text-blue-900
                                    @elseif($mr->status === 'completed') bg-green-100 text-green-800
                                    @elseif($mr->status === 'declined') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $mr->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">{{ $mr->preferred_at->format('Y/m/d H:i') }}</td>
                            <td class="px-6 py-4 text-left">
                                <a href="{{ route('client.meeting-requests.show', $mr) }}" class="text-blue-600 hover:text-blue-800 font-semibold">عرض</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">لا توجد طلبات بعد. استخدم «طلب جديد» لجدولة اجتماع مع الفريق.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($meetingRequests->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $meetingRequests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
