@extends('layouts.app')

@section('page-title', 'تقارير الأقسام (الإدارة)')

@section('content')
<div class="w-full max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">تقارير الأقسام</h1>
        <p class="text-gray-600 mt-2">متابعة التقارير المرفوعة من مديري الأقسام</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">القسم</label>
                <select name="department_id" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
                    <option value="">الكل</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" @selected(request('department_id') == $dept->id)>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">الحالة</label>
                <select name="status" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
                    <option value="">الكل</option>
                    <option value="draft" @selected(request('status') === 'draft')>مسودة</option>
                    <option value="submitted" @selected(request('status') === 'submitted')>مُرسل</option>
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-sm">تصفية</button>
                <a href="{{ route('admin.department-reports.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-200 shadow-sm">إعادة</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">التاريخ</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">القسم</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">المدير</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">المشروع</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">الحالة</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($reports as $report)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $report->created_at?->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $report->department?->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $report->department?->manager?->user?->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $report->project?->name ?? '—' }}</td>
                            <td class="px-6 py-4">
                                @if($report->status === 'submitted')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">مُرسل</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800">مسودة</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-left">
                                <a href="{{ route('admin.department-reports.show', $report) }}" class="text-blue-600 font-bold hover:underline text-sm">عرض</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-600">لا توجد تقارير.</td>
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

