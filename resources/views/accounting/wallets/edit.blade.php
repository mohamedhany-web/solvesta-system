@extends('layouts.app')

@section('page-title', 'تعديل المحفظة')

@section('content')
<div class="w-full max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('accounting.wallets.index') }}" class="text-sm text-blue-600 font-semibold hover:underline inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            المحافظ والمعاملات
        </a>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">
            <p class="font-bold mb-1">يرجى تصحيح الأخطاء التالية:</p>
            <ul class="list-disc list-inside space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
        <div class="bg-gradient-to-l from-slate-800 to-slate-900 text-white px-6 py-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <p class="text-slate-400 text-xs font-semibold uppercase tracking-wide mb-1">تعديل المحفظة</p>
                    <h1 class="text-xl sm:text-2xl font-bold">{{ $wallet->name }}</h1>
                    <p class="text-slate-300 text-sm mt-1">{{ $wallet->type_name }}</p>
                </div>
                <div class="text-right sm:text-left shrink-0">
                    <p class="text-slate-400 text-xs mb-0.5">الرصيد الحالي</p>
                    <p class="text-2xl font-bold">{{ number_format($wallet->current_balance, 2) }} <span class="text-sm font-medium">{{ $wallet->currency }}</span></p>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('accounting.wallets.update', $wallet) }}" class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        @csrf
        @method('PUT')

        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wide">البيانات الأساسية</h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
            @include('accounting.wallets._form-fields', ['wallet' => $wallet, 'useGrid' => true, 'section' => 'basic'])
        </div>

        <div class="px-6 py-5 border-y border-gray-100 bg-gray-50">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wide">البيانات البنكية</h2>
            <p class="text-xs text-gray-500 mt-0.5">اختياري — للحسابات البنكية ومحافظ التحويل</p>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
            @include('accounting.wallets._form-fields', ['wallet' => $wallet, 'useGrid' => true, 'section' => 'bank'])
        </div>

        <div class="px-6 py-5 border-y border-gray-100 bg-gray-50">
            <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wide">إعدادات إضافية</h2>
        </div>
        <div class="p-6 space-y-5">
            @include('accounting.wallets._form-fields', ['wallet' => $wallet, 'section' => 'extra'])
        </div>

        <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row gap-3">
            <button type="submit" class="flex-1 sm:flex-none sm:min-w-[180px] bg-blue-600 text-white py-3 px-6 rounded-xl font-bold hover:bg-blue-700 transition shadow-sm">
                حفظ التعديلات
            </button>
            <a href="{{ route('accounting.wallets.show', $wallet) }}" class="flex-1 sm:flex-none text-center py-3 px-6 border border-gray-300 rounded-xl font-semibold text-gray-700 bg-white hover:bg-gray-50 transition">
                إلغاء
            </a>
            <a href="{{ route('accounting.wallets.show', $wallet) }}" class="sm:mr-auto text-center py-3 px-4 text-sm font-semibold text-blue-600 hover:underline">
                عرض المحفظة ←
            </a>
        </div>
    </form>
</div>
@endsection
