@extends('layouts.app')

@section('page-title', 'بلاغ جديد — مشكلة في الموقع')

@section('content')
<div class="w-full max-w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">تسجيل مشكلة في الموقع</h1>
                <p class="text-gray-600">صف المشكلة وأرفق صوراً (لقطات شاشة) إن أمكن. سيصل البلاغ لفريق الإدارة للمتابعة وتحديث الحالة.</p>
            </div>
            <a href="{{ route('client.website-issues.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-200 flex items-center justify-center shadow-sm shrink-0">
                <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                العودة للقائمة
            </a>
        </div>
    </div>

    <!-- Form — عرض كامل بعرض منطقة المحتوى -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        @if($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('client.website-issues.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="lg:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">عنوان المشكلة <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required maxlength="255"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('title') border-red-500 @enderror"
                           placeholder="مثال: لا يفتح تقرير الخدمة بعد التنزيل">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 ml-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">شرح المشكلة بالتفصيل <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="8" required maxlength="15000"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('description') border-red-500 @enderror"
                              placeholder="اذكر الخطوات التي قمت بها، ما الذي توقعته، وما الذي حدث فعلياً.">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 ml-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="page_url" class="block text-sm font-medium text-gray-700 mb-2">رابط الصفحة (اختياري)</label>
                    <input type="text" name="page_url" id="page_url" value="{{ old('page_url') }}" maxlength="2000"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('page_url') border-red-500 @enderror"
                           placeholder="مثال: {{ url('/client/service-reports') }}">
                    @error('page_url')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 ml-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">صور توضيحية (اختياري — حتى 8 صور)</label>
                    <div class="rounded-xl border-2 border-dashed border-gray-200 bg-gray-50/80 px-4 py-6 transition-colors hover:border-amber-200 hover:bg-amber-50/20 @error('attachments') border-red-300 bg-red-50/50 @enderror @error('attachments.*') border-red-300 bg-red-50/50 @enderror">
                        <input type="file" name="attachments[]" id="attachments" multiple accept="image/jpeg,image/png,image/webp,image/gif"
                               class="block w-full text-sm text-gray-700 file:cursor-pointer file:ml-4 file:py-3 file:px-5 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-700 file:shadow-sm file:transition-colors">
                        <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                            صور فقط (JPEG، PNG، WebP، GIF) — بحد أقصى <span class="font-semibold text-gray-800">5 ميجابايت</span> لكل صورة.
                        </p>
                    </div>
                    @error('attachments')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 ml-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                    @error('attachments.*')
                        <p class="mt-1 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 ml-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('client.website-issues.index') }}" class="text-center px-6 py-3 text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors duration-200 font-medium">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center shadow-sm font-semibold">
                    <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    إرسال البلاغ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
