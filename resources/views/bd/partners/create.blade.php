@extends('layouts.app')

@section('page-title', 'شريك BD جديد')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full px-2 sm:px-0">
    @include('partials.erp-page-header', ['title' => 'شريك جديد', 'subtitle' => 'Business Development', 'icon' => 'users'])
    <form method="POST" action="{{ route('bd.partners.store') }}" class="max-w-2xl bg-white rounded-2xl shadow-lg border p-6 space-y-4 font-tajawal">
        @csrf
        <input name="name" required placeholder="اسم الشخص / الجهة *" class="w-full border rounded-xl px-4 py-2.5">
        <input name="company" placeholder="الشركة" class="w-full border rounded-xl px-4 py-2.5">
        <div class="grid grid-cols-2 gap-3">
            <input name="email" type="email" placeholder="البريد" class="border rounded-xl px-4 py-2.5">
            <input name="phone" placeholder="الهاتف" class="border rounded-xl px-4 py-2.5">
        </div>
        <select name="partner_type" class="w-full border rounded-xl px-4 py-2.5">
            @foreach(['agency'=>'وكالة','vendor'=>'مورّد','referrer'=>'مُحيل','strategic'=>'استراتيجي','other'=>'أخرى'] as $v=>$l)
                <option value="{{ $v }}">{{ $l }}</option>
            @endforeach
        </select>
        <select name="assigned_to" class="w-full border rounded-xl px-4 py-2.5">
            @foreach($users as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach
        </select>
        <textarea name="notes" rows="3" placeholder="ملاحظات..." class="w-full border rounded-xl px-4 py-2.5"></textarea>
        <button class="px-6 py-2.5 rounded-xl text-white font-bold" style="background:{{ $themeColor }};">حفظ</button>
    </form>
</div>
@endsection
