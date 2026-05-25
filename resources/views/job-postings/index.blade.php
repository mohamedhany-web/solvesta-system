@extends('layouts.app')

@section('page-title', 'التوظيف والوظائف')

@section('content')
<div class="w-full">
  <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-3xl font-bold text-gray-900 mb-2">التوظيف والوظائف</h1>
      <p class="text-gray-600">إدارة الوظائف المعروضة على الموقع العام</p>
    </div>
    @can('create-jobs')
    <a href="{{ route('job-postings.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition flex items-center justify-center shadow-sm font-bold">
      <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
      وظيفة جديدة
    </a>
    @endcan
  </div>

  @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
  @endif

  <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث في العنوان..."
             class="w-full px-4 py-2 border border-gray-300 rounded-lg">
      <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
        <option value="">كل الحالات</option>
        <option value="draft" @selected(request('status') === 'draft')>مسودة</option>
        <option value="published" @selected(request('status') === 'published')>منشورة</option>
        <option value="closed" @selected(request('status') === 'closed')>مغلقة</option>
      </select>
      <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-lg font-bold hover:bg-black">تصفية</button>
    </form>
  </div>

  <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">الوظيفة</th>
            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">الحالة</th>
            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">الطلبات</th>
            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">التحديث</th>
            <th class="px-6 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($jobs as $job)
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4">
              <div class="font-bold text-gray-900">{{ $job->title }}</div>
              @if($job->is_featured)<span class="text-xs text-orange-600 font-bold">مميزة</span>@endif
              <div class="text-xs text-gray-500 mt-1">{{ $job->department?->name ?? '—' }} · {{ $job->employmentTypeLabel() }}</div>
            </td>
            <td class="px-6 py-4">
              @php
                $badge = match($job->status) {
                  'published' => 'bg-green-100 text-green-800',
                  'closed' => 'bg-gray-200 text-gray-700',
                  default => 'bg-amber-100 text-amber-800',
                };
              @endphp
              <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badge }}">{{ $job->statusLabelAr() }}</span>
            </td>
            <td class="px-6 py-4 text-sm font-bold text-blue-600">{{ $job->applications_count }}</td>
            <td class="px-6 py-4 text-sm text-gray-600">{{ $job->updated_at->format('Y-m-d') }}</td>
            <td class="px-6 py-4 text-left whitespace-nowrap">
              <a href="{{ route('job-postings.show', $job) }}" class="text-sm font-bold text-gray-700 hover:text-blue-600 ml-3">عرض</a>
              @can('edit-jobs')
              <a href="{{ route('job-postings.edit', $job) }}" class="text-sm font-bold text-blue-600 hover:underline ml-3">تعديل</a>
              @endcan
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="px-6 py-12 text-center text-gray-600">
              لا توجد وظائف. أنشئ وظيفة واضبط حالتها على «منشورة» لتظهر في
              <a href="{{ route('website.careers') }}" target="_blank" class="text-blue-600 font-bold hover:underline">صفحة التوظيف</a>.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($jobs->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">{{ $jobs->links() }}</div>
    @endif
  </div>
</div>
@endsection
