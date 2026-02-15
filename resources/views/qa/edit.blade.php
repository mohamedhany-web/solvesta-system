@extends('layouts.app')

@section('page-title', 'تعديل الاختبار')

@section('content')
<div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl p-6 text-white mb-6 shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold mb-2">تعديل الاختبار</h1>
            <p class="text-purple-100">تعديل بيانات الاختبار: {{ $qa->test_number }}</p>
        </div>
        <a href="{{ route('qa.index') }}" class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-all duration-200 flex items-center">
            <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة
        </a>
    </div>
</div>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('qa.update', $qa) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات الاختبار</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم الاختبار</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $qa->name) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('name') border-red-500 @enderror">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">{{ old('description', $qa->description) }}</textarea>
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">نوع الاختبار</label>
                        <select name="type" id="type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('type') border-red-500 @enderror">
                            <option value="functional" {{ old('type', $qa->type) == 'functional' ? 'selected' : '' }}>وظيفي</option>
                            <option value="unit" {{ old('type', $qa->type) == 'unit' ? 'selected' : '' }}>وحدة</option>
                            <option value="integration" {{ old('type', $qa->type) == 'integration' ? 'selected' : '' }}>تكامل</option>
                            <option value="performance" {{ old('type', $qa->type) == 'performance' ? 'selected' : '' }}>أداء</option>
                            <option value="security" {{ old('type', $qa->type) == 'security' ? 'selected' : '' }}>أمان</option>
                            <option value="usability" {{ old('type', $qa->type) == 'usability' ? 'selected' : '' }}>سهولة استخدام</option>
                        </select>
                        @error('type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                        <select name="status" id="status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('status') border-red-500 @enderror">
                            <option value="pending" {{ old('status', $qa->status) == 'pending' ? 'selected' : '' }}>معلق</option>
                            <option value="running" {{ old('status', $qa->status) == 'running' ? 'selected' : '' }}>قيد التنفيذ</option>
                            <option value="passed" {{ old('status', $qa->status) == 'passed' ? 'selected' : '' }}>نجح</option>
                            <option value="failed" {{ old('status', $qa->status) == 'failed' ? 'selected' : '' }}>فشل</option>
                            <option value="skipped" {{ old('status', $qa->status) == 'skipped' ? 'selected' : '' }}>تم التخطي</option>
                        </select>
                        @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">الأولوية</label>
                        <select name="priority" id="priority" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 @error('priority') border-red-500 @enderror">
                            <option value="low" {{ old('priority', $qa->priority) == 'low' ? 'selected' : '' }}>منخفضة</option>
                            <option value="medium" {{ old('priority', $qa->priority) == 'medium' ? 'selected' : '' }}>متوسطة</option>
                            <option value="high" {{ old('priority', $qa->priority) == 'high' ? 'selected' : '' }}>عالية</option>
                            <option value="critical" {{ old('priority', $qa->priority) == 'critical' ? 'selected' : '' }}>حرجة</option>
                        </select>
                        @error('priority')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 space-x-reverse pt-6 border-t border-gray-200">
                <a href="{{ route('qa.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    إلغاء
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 border border-transparent rounded-lg hover:bg-purple-700">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

