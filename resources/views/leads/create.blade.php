@extends('layouts.app')

@section('page-title', 'Lead جديد')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full px-2 sm:px-0">
    @include('partials.erp-page-header', [
        'title' => 'تسجيل Lead جديد',
        'subtitle' => 'إضافة عميل محتمل جديد إلى خط المبيعات',
        'icon' => 'users',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="{{ route('leads.index') }}"
           class="inline-flex items-center px-5 py-2.5 rounded-xl bg-gray-600 text-white text-sm font-bold shadow-sm hover:bg-gray-700 transition">
            <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة للقائمة
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 sm:p-8">
        <form method="POST" action="{{ route('leads.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">الاسم <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('name') border-red-500 @enderror"
                           placeholder="اسم العميل المحتمل">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('email') border-red-500 @enderror"
                           placeholder="email@example.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">الهاتف</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('phone') border-red-500 @enderror"
                           placeholder="+966...">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700 mb-2">الشركة</label>
                    <input type="text" name="company" id="company" value="{{ old('company') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="اسم الشركة">
                </div>

                <div>
                    <label for="service_interest" class="block text-sm font-medium text-gray-700 mb-2">الخدمة / الاهتمام</label>
                    <input type="text" name="service_interest" id="service_interest" value="{{ old('service_interest') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="مثال: CRM، موقع إلكتروني، تطبيق">
                </div>

                <div>
                    <label for="source" class="block text-sm font-medium text-gray-700 mb-2">المصدر <span class="text-red-500">*</span></label>
                    <select name="source" id="source" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('source') border-red-500 @enderror">
                        @foreach(['bd_outreach'=>'تطوير أعمال','referral'=>'إحالة','ads'=>'إعلانات','social_media'=>'سوشيال','website'=>'موقع','event'=>'فعالية','other'=>'أخرى'] as $v => $l)
                            <option value="{{ $v }}" @selected(old('source') === $v)>{{ $l }}</option>
                        @endforeach
                    </select>
                    @error('source')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="estimated_budget" class="block text-sm font-medium text-gray-700 mb-2">ميزانية تقديرية</label>
                    <input type="number" name="estimated_budget" id="estimated_budget" step="0.01" min="0" value="{{ old('estimated_budget') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="0.00">
                </div>

                @unless(\App\Services\SalesRepScope::isRestricted(auth()->user()))
                <div>
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">مسند إلى</label>
                    <select name="assigned_to" id="assigned_to"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        <option value="">— اختر المندوب —</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" @selected(old('assigned_to') == $u->id)>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <input type="hidden" name="assigned_to" value="{{ auth()->id() }}">
                @endunless

                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                    <textarea name="notes" id="notes" rows="5"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                              placeholder="أي تفاصيل إضافية عن الـ Lead...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('leads.index') }}"
                   class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 text-sm font-bold hover:bg-gray-50 transition">
                    إلغاء
                </a>
                <button type="submit"
                        class="px-8 py-3 rounded-xl text-white text-sm font-bold shadow-lg hover:shadow-xl transition"
                        style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                    حفظ Lead
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
