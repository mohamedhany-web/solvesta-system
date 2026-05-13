@extends('layouts.app')

@section('page-title', 'المساعدة')

@section('content')
<div class="w-full max-w-full">
    <div class="mb-6 flex items-center justify-between gap-4">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">مركز المساعدة</h1>
        <a href="{{ route('client.dashboard') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900">العودة للوحة</a>
    </div>
    @include('client-portal.partials.faq')
</div>
@endsection
