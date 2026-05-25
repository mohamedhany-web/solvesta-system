@extends('layouts.app')

@section('page-title', 'وظيفة جديدة')

@section('content')
<div class="w-full max-w-4xl mx-auto">
  <div class="mb-8 flex items-center justify-between">
    <div>
      <h1 class="text-3xl font-bold text-gray-900">وظيفة جديدة</h1>
      <p class="text-gray-600 mt-1">ستظهر في الموقع عند اختيار حالة «منشورة»</p>
    </div>
    <a href="{{ route('job-postings.index') }}" class="text-gray-600 font-bold hover:text-gray-900">رجوع</a>
  </div>

  @if($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 text-red-800 px-4 py-3 text-sm">
      <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <div class="bg-white rounded-xl border border-gray-200 p-8">
    <form action="{{ route('job-postings.store') }}" method="POST" class="space-y-6">
      @csrf
      @include('job-postings._form')
      <button type="submit" class="w-full sm:w-auto bg-blue-600 text-white px-8 py-3 rounded-xl font-extrabold hover:bg-blue-700">حفظ الوظيفة</button>
    </form>
  </div>
</div>
@endsection
