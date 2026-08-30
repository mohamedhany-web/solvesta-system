@extends('layouts.app')

@section('page-title', 'Lead جديد')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp

<div class="w-full max-w-full font-tajawal">
    <div class="mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 sm:p-6 lg:p-8 border border-blue-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="h-12 w-12 sm:h-14 sm:w-14 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                        <svg class="h-6 w-6 sm:h-7 sm:w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-1">تسجيل Lead جديد</h1>
                        <p class="text-gray-600 text-sm sm:text-base">إضافة عميل محتمل جديد إلى خط المبيعات</p>
                    </div>
                </div>
                <a href="{{ route('leads.index') }}"
                   class="w-full sm:w-auto bg-gray-600 text-white px-5 py-2.5 sm:px-6 sm:py-3 rounded-lg hover:bg-gray-700 transition-all duration-200 flex items-center justify-center shadow-sm text-sm sm:text-base">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <ul class="list-disc pr-5 space-y-1 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden w-full">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 sm:px-6 lg:px-8 py-4 sm:py-5 border-b border-gray-200">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900">بيانات العميل المحتمل</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-1">املأ الحقول الأساسية ثم احفظ</p>
        </div>

        <form method="POST" action="{{ route('leads.store') }}" class="p-4 sm:p-6 lg:p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <div class="lg:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        الاسم <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('name') border-red-500 @enderror"
                           placeholder="اسم العميل المحتمل">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('email') border-red-500 @enderror"
                           placeholder="email@example.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">الهاتف</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="01xxxxxxxxx">
                </div>

                <div>
                    <label for="company" class="block text-sm font-semibold text-gray-700 mb-2">الشركة</label>
                    <input type="text" name="company" id="company" value="{{ old('company') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="اسم الشركة">
                </div>

                <div>
                    <label for="service_interest" class="block text-sm font-semibold text-gray-700 mb-2">الخدمة / الاهتمام</label>
                    <input type="text" name="service_interest" id="service_interest" value="{{ old('service_interest') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="مثال: CRM، موقع، تطبيق">
                </div>

                <div>
                    <label for="source" class="block text-sm font-semibold text-gray-700 mb-2">
                        المصدر <span class="text-red-500">*</span>
                    </label>
                    <select name="source" id="source" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                        @foreach(['bd_outreach'=>'تطوير أعمال','referral'=>'إحالة','ads'=>'إعلانات','social_media'=>'سوشيال','website'=>'موقع','event'=>'فعالية','other'=>'أخرى'] as $v => $l)
                            <option value="{{ $v }}" @selected(old('source') === $v)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="estimated_budget" class="block text-sm font-semibold text-gray-700 mb-2">ميزانية تقديرية</label>
                    <input type="number" name="estimated_budget" id="estimated_budget" step="0.01" min="0" value="{{ old('estimated_budget') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="0.00">
                </div>

                @unless(\App\Services\SalesRepScope::isRestricted(auth()->user()))
                <div>
                    <label for="assigned_to" class="block text-sm font-semibold text-gray-700 mb-2">مسند إلى</label>
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

                <div class="lg:col-span-2">
                    <label for="notes" class="block text-sm font-semibold text-gray-700 mb-2">ملاحظات</label>
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
