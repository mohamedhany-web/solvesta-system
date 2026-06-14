@extends('layouts.app')

@section('page-title', 'مشروع نظام جديد')

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $statusLabels = [
        'active' => 'نشط',
        'on_hold' => 'متوقف',
        'completed' => 'مكتمل',
        'archived' => 'مؤرشف',
    ];
@endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'إنشاء مشروع نظام للعميل',
        'subtitle' => 'اربط النظام بالعميل لتجميع طلبات الميزات والتحسينات في مكان واحد',
        'icon' => 'briefcase',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="{{ route('client-system-projects.index') }}" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            كل المشاريع
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <p class="font-bold mb-1">يرجى تصحيح الأخطاء التالية:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8">
            <form method="POST" action="{{ route('client-system-projects.store') }}" class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                @csrf
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
                    <h2 class="font-bold text-lg text-gray-900">بيانات المشروع</h2>
                    <p class="text-sm text-gray-500 mt-0.5">الحقول المطلوبة مُعلّمة بـ *</p>
                </div>

                <div class="p-6 space-y-5">
                    <div>
                        <label class="text-xs font-bold text-gray-600 block mb-1.5">العميل *</label>
                        <select name="client_id" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('client_id') border-red-400 @enderror">
                            <option value="" disabled @selected(!old('client_id'))>اختر العميل...</option>
                            @foreach($clients as $c)
                                <option value="{{ $c->id }}" @selected(old('client_id') == $c->id)>
                                    {{ $c->name }}@if($c->company_name) — {{ $c->company_name }}@endif
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-600 block mb-1.5">اسم المشروع / النظام *</label>
                        <input name="name" value="{{ old('name') }}" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-400 @enderror"
                               placeholder="مثال: نظام إدارة المخزون، بوابة العملاء، تطبيق المبيعات">
                        @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-600 block mb-1.5">الوصف</label>
                        <textarea name="description" rows="4"
                                  class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-400 @enderror"
                                  placeholder="نبذة عن النظام، نطاق العمل، أو ملاحظات للفريق...">{{ old('description') }}</textarea>
                        @error('description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-600 block mb-1.5">حالة المشروع</label>
                            <select name="status" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @foreach($statusLabels as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', 'active') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-600 block mb-1.5">مسند إلى</label>
                            <select name="assigned_to" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">— بدون إسناد —</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" @selected(old('assigned_to') == $u->id)>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-600 block mb-1.5">ملاحظات داخلية</label>
                        <textarea name="admin_notes" rows="3"
                                  class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="ملاحظات للفريق الداخلي فقط — لا تظهر للعميل">{{ old('admin_notes') }}</textarea>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-white font-bold shadow-md hover:opacity-95 transition-opacity"
                            style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        حفظ المشروع
                    </button>
                    <a href="{{ route('client-system-projects.index') }}" class="inline-flex items-center px-6 py-2.5 rounded-xl border border-gray-300 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>

        <div class="xl:col-span-4 space-y-4">
            <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5 shadow-sm">
                <h3 class="font-bold text-blue-900 mb-3">ماذا يحدث بعد الحفظ؟</h3>
                <ol class="space-y-3 text-sm text-blue-800">
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 h-6 w-6 rounded-full bg-blue-200 text-blue-900 text-xs font-bold flex items-center justify-center">١</span>
                        <span>يُنشأ مشروع برمز مرجعي فريد ويظهر في قائمة المشاريع.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 h-6 w-6 rounded-full bg-blue-200 text-blue-900 text-xs font-bold flex items-center justify-center">٢</span>
                        <span>عندما يطلب العميل ميزة أو تحسيناً من بوابته، تُربط الطلبات بهذا المشروع.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="flex-shrink-0 h-6 w-6 rounded-full bg-blue-200 text-blue-900 text-xs font-bold flex items-center justify-center">٣</span>
                        <span>يمكنك متابعة الحالات وتوثيق التحديثات من صفحة المشروع.</span>
                    </li>
                </ol>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-2">نصائح سريعة</h3>
                <ul class="text-sm text-gray-600 space-y-2 leading-relaxed">
                    <li class="flex gap-2">
                        <span class="text-emerald-500">•</span>
                        استخدم اسماً واضحاً يميّز النظام عن مشاريع العميل الأخرى.
                    </li>
                    <li class="flex gap-2">
                        <span class="text-emerald-500">•</span>
                        عيّن مسؤولاً إذا كان هناك فريق تطوير مخصص للمتابعة.
                    </li>
                    <li class="flex gap-2">
                        <span class="text-emerald-500">•</span>
                        المشاريع تُنشأ تلقائياً أيضاً عند أول طلب من بوابة العميل — هذا النموذج للإنشاء اليدوي.
                    </li>
                </ul>
            </div>

            <div class="rounded-2xl border border-indigo-100 bg-indigo-50/60 p-5 text-sm text-indigo-900">
                <p class="font-bold mb-1">حالات المشروع</p>
                <dl class="space-y-2">
                    @foreach($statusLabels as $value => $label)
                        <div class="flex justify-between gap-2">
                            <dt class="font-semibold">{{ $label }}</dt>
                            <dd class="text-indigo-700 text-xs">
                                @switch($value)
                                    @case('active') يستقبل طلبات جديدة @break
                                    @case('on_hold') مؤقتاً متوقف @break
                                    @case('completed') انتهى التطوير @break
                                    @case('archived') للأرشفة فقط @break
                                @endswitch
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
