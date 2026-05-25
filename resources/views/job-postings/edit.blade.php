@extends('layouts.app')

@section('page-title', 'تعديل وظيفة')

@section('content')
<div class="w-full max-w-4xl mx-auto">
  <div class="mb-8 flex items-center justify-between">
    <div>
      <h1 class="text-3xl font-bold text-gray-900">تعديل: {{ $jobPosting->title }}</h1>
      <p class="text-gray-600 mt-1 text-sm" dir="ltr">{{ route('website.careers.show', $jobPosting->slug) }}</p>
    </div>
    <a href="{{ route('job-postings.show', $jobPosting) }}" class="text-gray-600 font-bold hover:text-gray-900">رجوع</a>
  </div>

  @if($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 text-red-800 px-4 py-3 text-sm">
      <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <div class="bg-white rounded-xl border border-gray-200 p-8">
    <form action="{{ route('job-postings.update', $jobPosting) }}" method="POST" class="space-y-6">
      @csrf
      @method('PUT')
      @include('job-postings._form', ['jobPosting' => $jobPosting])
      <button type="submit" class="w-full sm:w-auto bg-blue-600 text-white px-8 py-3 rounded-xl font-extrabold hover:bg-blue-700">تحديث</button>
    </form>
  </div>
</div>
@endsection
