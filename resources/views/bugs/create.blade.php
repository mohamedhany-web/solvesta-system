@extends('layouts.app')

@section('page-title', 'تقرير خطأ جديد')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp

<div class="w-full max-w-full font-tajawal">
    <div class="mb-6">
        <div class="bg-gradient-to-r from-red-50 to-orange-50 rounded-xl p-5 sm:p-6 lg:p-8 border border-red-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="h-12 w-12 sm:h-14 sm:w-14 bg-gradient-to-br from-red-600 to-red-700 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                        <svg class="h-6 w-6 sm:h-7 sm:w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-1">تقرير خطأ جديد</h1>
                        <p class="text-gray-600 text-sm sm:text-base">سجّل الخطأ البرمجي مع تفاصيل البيئة والأولوية</p>
                    </div>
                </div>
                <a href="{{ route('bugs.index') }}"
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

    <form action="{{ route('bugs.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 sm:px-6 lg:px-8 py-4 sm:py-5 border-b border-gray-200">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900">معلومات الخطأ</h3>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">العنوان، الوصف، المشروع، والتكليف</p>
            </div>
            <div class="p-4 sm:p-6 lg:p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    <div class="lg:col-span-2">
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                            عنوان الخطأ <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('title') border-red-500 @enderror"
                               placeholder="مثال: خطأ في تسجيل الدخول">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                            وصف الخطأ <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" id="description" rows="4" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('description') border-red-500 @enderror"
                                  placeholder="وصف تفصيلي للخطأ وما يظهر للمستخدم">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="project_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            المشروع <span class="text-red-500">*</span>
                        </label>
                        <select name="project_id" id="project_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 @error('project_id') border-red-500 @enderror">
                            <option value="">اختر المشروع</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>{{ $project->name }}</option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="assigned_to" class="block text-sm font-semibold text-gray-700 mb-2">تكليف إلى</label>
                        <select name="assigned_to" id="assigned_to"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="">اختر المطور</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(old('assigned_to') == $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="severity" class="block text-sm font-semibold text-gray-700 mb-2">
                            درجة الخطورة <span class="text-red-500">*</span>
                        </label>
                        <select name="severity" id="severity" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="low" @selected(old('severity') === 'low')>منخفض</option>
                            <option value="medium" @selected(old('severity', 'medium') === 'medium')>متوسط</option>
                            <option value="high" @selected(old('severity') === 'high')>عالي</option>
                            <option value="critical" @selected(old('severity') === 'critical')>حرج</option>
                        </select>
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-semibold text-gray-700 mb-2">
                            الأولوية <span class="text-red-500">*</span>
                        </label>
                        <select name="priority" id="priority" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="low" @selected(old('priority') === 'low')>منخفضة</option>
                            <option value="medium" @selected(old('priority', 'medium') === 'medium')>متوسطة</option>
                            <option value="high" @selected(old('priority') === 'high')>عالية</option>
                            <option value="urgent" @selected(old('priority') === 'urgent')>عاجلة</option>
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                            الحالة <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200">
                            <option value="open" @selected(old('status', 'open') === 'open')>مفتوح</option>
                            <option value="in_progress" @selected(old('status') === 'in_progress')>قيد التنفيذ</option>
                            <option value="testing" @selected(old('status') === 'testing')>قيد الاختبار</option>
                            <option value="resolved" @selected(old('status') === 'resolved')>محلول</option>
                            <option value="closed" @selected(old('status') === 'closed')>مغلق</option>
                            <option value="duplicate" @selected(old('status') === 'duplicate')>مكرر</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 sm:px-6 lg:px-8 py-4 sm:py-5 border-b border-gray-200">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900">بيئة التشغيل</h3>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">اختياري — يساعد الفريق على إعادة إنتاج الخطأ</p>
            </div>
            <div class="p-4 sm:p-6 lg:p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                    <div>
                        <label for="environment" class="block text-sm font-semibold text-gray-700 mb-2">البيئة</label>
                        <input type="text" name="environment" id="environment" value="{{ old('environment') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                               placeholder="مثال: Production">
                    </div>
                    <div>
                        <label for="browser" class="block text-sm font-semibold text-gray-700 mb-2">المتصفح</label>
                        <input type="text" name="browser" id="browser" value="{{ old('browser') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                               placeholder="مثال: Chrome 120">
                    </div>
                    <div>
                        <label for="operating_system" class="block text-sm font-semibold text-gray-700 mb-2">نظام التشغيل</label>
                        <input type="text" name="operating_system" id="operating_system" value="{{ old('operating_system') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                               placeholder="مثال: Windows 11">
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 sm:px-6 lg:px-8 py-4 sm:py-5 border-b border-gray-200">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900">النتائج</h3>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">المتوقع مقابل ما حدث فعلياً</p>
            </div>
            <div class="p-4 sm:p-6 lg:p-8 space-y-6">
                <div>
                    <label for="expected_result" class="block text-sm font-semibold text-gray-700 mb-2">النتيجة المتوقعة</label>
                    <textarea name="expected_result" id="expected_result" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                              placeholder="ماذا كان متوقعاً أن يحدث؟">{{ old('expected_result') }}</textarea>
                </div>
                <div>
                    <label for="actual_result" class="block text-sm font-semibold text-gray-700 mb-2">النتيجة الفعلية</label>
                    <textarea name="actual_result" id="actual_result" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200"
                              placeholder="ماذا حدث فعلياً؟">{{ old('actual_result') }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6">
            <div class="flex flex-wrap items-center justify-end gap-3">
                <a href="{{ route('bugs.index') }}"
                   class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 text-sm font-bold hover:bg-gray-50 transition">
                    إلغاء
                </a>
                <button type="submit"
                        class="px-8 py-3 rounded-xl text-white text-sm font-bold shadow-lg hover:shadow-xl transition"
                        style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);">
                    تسجيل الخطأ
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
