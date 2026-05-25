@extends('layouts.app')

@section('page-title', 'طلبات التوظيف')

@section('content')
<div class="w-full max-w-7xl mx-auto">
  <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
      <h1 class="text-3xl font-bold text-gray-900">طلبات التوظيف</h1>
      <p class="text-gray-600 mt-2">الوظيفة: <span class="font-semibold text-gray-900">{{ $jobPosting->title }}</span></p>
    </div>
    <a href="{{ route('job-postings.show', $jobPosting) }}" class="bg-gray-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-gray-700">رجوع</a>
  </div>

  @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl">{{ session('success') }}</div>
  @endif

  <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">الاسم</th>
            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">البريد</th>
            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">الهاتف</th>
            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">CV</th>
            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">الحالة</th>
            <th class="px-6 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($applications as $a)
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $a->full_name }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ $a->email }}</td>
            <td class="px-6 py-4 text-sm text-gray-700">{{ $a->phone ?? '—' }}</td>
            <td class="px-6 py-4 text-sm">
              @if($a->cv_path)
                <a class="text-blue-600 font-bold hover:underline" href="{{ asset('storage/'.$a->cv_path) }}" target="_blank" rel="noopener">تحميل</a>
              @else — @endif
            </td>
            <td class="px-6 py-4 text-sm font-bold">{{ $a->statusLabelAr() }}</td>
            <td class="px-6 py-4">
              @can('edit-jobs')
              <form method="POST" action="{{ route('job-postings.applications.status', $a) }}" class="flex items-center gap-2">
                @csrf
                @method('PATCH')
                <select name="status" class="px-3 py-2 rounded-lg border border-gray-200 text-sm">
                  @php
                    $statusLabels = [
                      'pending' => 'جديد',
                      'reviewing' => 'قيد المراجعة',
                      'shortlisted' => 'قائمة مختصرة',
                      'rejected' => 'مرفوض',
                      'hired' => 'تم التوظيف',
                    ];
                  @endphp
                  @foreach(\App\Models\JobApplication::STATUSES as $st)
                    <option value="{{ $st }}" @selected($a->status === $st)>{{ $statusLabels[$st] ?? $st }}</option>
                  @endforeach
                </select>
                <button class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-bold">حفظ</button>
              </form>
              @endcan
            </td>
          </tr>
          @if($a->message)
          <tr><td colspan="6" class="px-6 pb-4 text-sm text-gray-700 whitespace-pre-wrap bg-gray-50">{{ $a->message }}</td></tr>
          @endif
          @empty
          <tr>
            <td colspan="6" class="px-6 py-10 text-center text-gray-600">
              لا توجد طلبات بعد.
              <a href="{{ route('website.careers.show', $jobPosting->slug) }}" target="_blank" class="text-blue-600 font-bold">رابط التقديم على الموقع</a>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($applications->hasPages())<div class="px-6 py-4">{{ $applications->links() }}</div>@endif
  </div>
</div>
@endsection
