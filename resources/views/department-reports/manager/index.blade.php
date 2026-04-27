@extends('layouts.app')

@section('page-title', 'تقارير القسم')

@section('content')
<div class="w-full max-w-7xl mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">تقارير القسم</h1>
            <p class="text-gray-600 mt-2">ارفع تقارير دورية للإدارة مع مرفقات وملخص أداء</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('department-manager.reports.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-sm">
                إنشاء تقرير
            </a>
            <a href="{{ route('department-manager.dashboard') }}" class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-200 shadow-sm">
                لوحة القسم
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-lg font-extrabold text-gray-900">سجل التقارير</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">التاريخ</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">المشروع</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">الحالة</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($reports as $report)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $report->created_at?->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-semibold">
                                {{ $report->project?->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($report->status === 'submitted')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">مُرسل</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800">مسودة</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-left">
                                <a href="{{ route('department-manager.reports.show', $report) }}" class="text-blue-600 font-bold hover:underline text-sm">عرض</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-600">لا توجد تقارير بعد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection

