@extends('layouts.app')

@section('page-title', $jobPosting->title)

@section('content')
<div class="w-full max-w-4xl mx-auto">
  @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
  @endif

  <div class="mb-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
    <div>
      <h1 class="text-3xl font-bold text-gray-900">{{ $jobPosting->title }}</h1>
      <div class="mt-2 flex flex-wrap gap-2">
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800">{{ $jobPosting->statusLabelAr() }}</span>
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700">{{ $jobPosting->employmentTypeLabel() }}</span>
        @if($jobPosting->is_featured)<span class="px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-800">مميزة</span>@endif
      </div>
    </div>
    <div class="flex flex-wrap gap-2">
      <a href="{{ route('job-postings.applications', $jobPosting) }}" class="bg-gray-900 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-black">
        الطلبات ({{ $jobPosting->applications_count }})
      </a>
      @can('edit-jobs')
      <a href="{{ route('job-postings.edit', $jobPosting) }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-700">تعديل</a>
      @endcan
      <a href="{{ route('website.careers.show', $jobPosting->slug) }}" target="_blank" class="border border-gray-300 px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-50">معاينة الموقع</a>
    </div>
  </div>

  <div class="bg-white rounded-xl border border-gray-200 p-8 space-y-6">
    @if($jobPosting->summary)
    <div><div class="text-xs text-gray-500 mb-1">الملخص</div><p class="text-gray-800">{{ $jobPosting->summary }}</p></div>
    @endif
    <div>
      <div class="text-xs text-gray-500 mb-2">الوصف</div>
      <div class="text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $jobPosting->description }}</div>
    </div>
    @if($jobPosting->requirements)
    <div>
      <div class="text-xs text-gray-500 mb-2">المتطلبات</div>
      <div class="text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $jobPosting->requirements }}</div>
    </div>
    @endif
    <div class="grid grid-cols-2 gap-4 text-sm">
      <div><span class="text-gray-500">الموقع:</span> <span class="font-bold">{{ $jobPosting->location ?? '—' }}</span></div>
      <div><span class="text-gray-500">القسم:</span> <span class="font-bold">{{ $jobPosting->department?->name ?? '—' }}</span></div>
    </div>
  </div>

  @can('delete-jobs')
  <form method="POST" action="{{ route('job-postings.destroy', $jobPosting) }}" class="mt-8" onsubmit="return confirm('حذف هذه الوظيفة وجميع طلباتها؟');">
    @csrf
    @method('DELETE')
    <button type="submit" class="text-red-600 font-bold text-sm hover:underline">حذف الوظيفة</button>
  </form>
  @endcan
</div>
@endsection
