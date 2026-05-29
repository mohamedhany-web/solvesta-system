@extends('layouts.app')

@section('page-title', 'تعديل خدمة — '.$clientService->title)

@section('content')
<div class="w-full max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('accounting.client-services.show', $clientService) }}" class="text-sm text-blue-600 font-semibold hover:underline">← العودة للتفاصيل</a>
        <h1 class="text-2xl font-bold mt-2">تعديل: {{ $clientService->title }}</h1>
    </div>

    <form method="POST" action="{{ route('accounting.client-services.update', $clientService) }}" class="bg-white border rounded-xl p-6 shadow-sm space-y-6">
        @csrf
        @method('PUT')
        @include('accounting.client-services._form', ['clientService' => $clientService, 'prefill' => []])
        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700">حفظ التعديلات</button>
        </div>
    </form>
</div>
@endsection
