@extends('layouts.app')

@section('page-title', $partner->name)

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full px-2 sm:px-0">
    @include('partials.erp-page-header', [
        'title' => $partner->name,
        'subtitle' => $partner->reference_code.' · '.$partner->company,
        'icon' => 'users',
    ])
    <div class="flex gap-2 mb-6">
        <a href="{{ route('bd.index') }}" class="px-4 py-2 rounded-xl border bg-white text-sm font-bold">← BD</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border p-6 font-tajawal text-sm space-y-2">
            <p><strong>النوع:</strong> {{ $partner->partner_type_label }}</p>
            <p><strong>البريد:</strong> {{ $partner->email ?? '—' }}</p>
            <p><strong>الهاتف:</strong> {{ $partner->phone ?? '—' }}</p>
            <p><strong>مسند إلى:</strong> {{ $partner->assignee?->name }}</p>
            @if($partner->notes)<p class="mt-4 p-3 bg-gray-50 rounded-xl">{{ $partner->notes }}</p>@endif
        </div>
        <div class="bg-white rounded-2xl shadow-lg border p-6">
            <h3 class="font-bold mb-3">الفرص المرتبطة</h3>
            <ul class="space-y-2 text-sm">
                @forelse($partner->opportunities as $o)
                <li class="p-2 bg-gray-50 rounded-lg flex justify-between">
                    <span>{{ $o->title }}</span>
                    <span class="text-xs">{{ $o->status_label }}</span>
                </li>
                @empty
                <li class="text-gray-500">لا توجد فرص بعد.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
