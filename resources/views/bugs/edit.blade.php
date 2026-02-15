@extends('layouts.app')

@section('page-title', 'تعديل الخطأ')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-red-600 to-red-700 rounded-xl p-6 text-white mb-6 shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold mb-2">تعديل الخطأ</h1>
            <p class="text-red-100">تعديل بيانات الخطأ: {{ $bug->bug_number }}</p>
        </div>
        <a href="{{ route('bugs.index') }}" class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-all duration-200 flex items-center">
            <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة
        </a>
    </div>
</div>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('bugs.update', $bug) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Bug Information -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات الخطأ</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Title -->
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">عنوان الخطأ</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $bug->title) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('title') border-red-500 @enderror"
                               placeholder="مثال: خطأ في تسجيل الدخول">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">وصف الخطأ</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('description') border-red-500 @enderror"
                                  placeholder="وصف تفصيلي للخطأ">{{ old('description', $bug->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Project -->
                    <div>
                        <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">المشروع</label>
                        <select name="project_id" id="project_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('project_id') border-red-500 @enderror">
                            <option value="">اختر المشروع</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id', $bug->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Assigned To -->
                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">تكليف إلى</label>
                        <select name="assigned_to" id="assigned_to" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('assigned_to') border-red-500 @enderror">
                            <option value="">اختر المطور</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to', $bug->assigned_to) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Severity -->
                    <div>
                        <label for="severity" class="block text-sm font-medium text-gray-700 mb-2">درجة الخطورة</label>
                        <select name="severity" id="severity" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('severity') border-red-500 @enderror">
                            <option value="low" {{ old('severity', $bug->severity) == 'low' ? 'selected' : '' }}>منخفض</option>
                            <option value="medium" {{ old('severity', $bug->severity) == 'medium' ? 'selected' : '' }}>متوسط</option>
                            <option value="high" {{ old('severity', $bug->severity) == 'high' ? 'selected' : '' }}>عالي</option>
                            <option value="critical" {{ old('severity', $bug->severity) == 'critical' ? 'selected' : '' }}>حرج</option>
                        </select>
                        @error('severity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">الأولوية</label>
                        <select name="priority" id="priority" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('priority') border-red-500 @enderror">
                            <option value="low" {{ old('priority', $bug->priority) == 'low' ? 'selected' : '' }}>منخفضة</option>
                            <option value="medium" {{ old('priority', $bug->priority) == 'medium' ? 'selected' : '' }}>متوسطة</option>
                            <option value="high" {{ old('priority', $bug->priority) == 'high' ? 'selected' : '' }}>عالية</option>
                            <option value="urgent" {{ old('priority', $bug->priority) == 'urgent' ? 'selected' : '' }}>عاجلة</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                        <select name="status" id="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('status') border-red-500 @enderror">
                            <option value="open" {{ old('status', $bug->status) == 'open' ? 'selected' : '' }}>مفتوح</option>
                            <option value="in_progress" {{ old('status', $bug->status) == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                            <option value="testing" {{ old('status', $bug->status) == 'testing' ? 'selected' : '' }}>قيد الاختبار</option>
                            <option value="resolved" {{ old('status', $bug->status) == 'resolved' ? 'selected' : '' }}>محلول</option>
                            <option value="closed" {{ old('status', $bug->status) == 'closed' ? 'selected' : '' }}>مغلق</option>
                            <option value="duplicate" {{ old('status', $bug->status) == 'duplicate' ? 'selected' : '' }}>مكرر</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Environment Information -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">بيئة التشغيل</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Environment -->
                    <div>
                        <label for="environment" class="block text-sm font-medium text-gray-700 mb-2">البيئة</label>
                        <input type="text" name="environment" id="environment" value="{{ old('environment', $bug->environment) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                               placeholder="مثال: Production">
                        @error('environment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Browser -->
                    <div>
                        <label for="browser" class="block text-sm font-medium text-gray-700 mb-2">المتصفح</label>
                        <input type="text" name="browser" id="browser" value="{{ old('browser', $bug->browser) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                               placeholder="مثال: Chrome 120">
                        @error('browser')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Operating System -->
                    <div>
                        <label for="operating_system" class="block text-sm font-medium text-gray-700 mb-2">نظام التشغيل</label>
                        <input type="text" name="operating_system" id="operating_system" value="{{ old('operating_system', $bug->operating_system) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                               placeholder="مثال: Windows 11">
                        @error('operating_system')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Detailed Information -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات تفصيلية</h3>
                <div class="space-y-4">
                    <!-- Expected Result -->
                    <div>
                        <label for="expected_result" class="block text-sm font-medium text-gray-700 mb-2">النتيجة المتوقعة</label>
                        <textarea name="expected_result" id="expected_result" rows="2" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                  placeholder="ماذا كان متوقعاً أن يحدث؟">{{ old('expected_result', $bug->expected_result) }}</textarea>
                        @error('expected_result')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actual Result -->
                    <div>
                        <label for="actual_result" class="block text-sm font-medium text-gray-700 mb-2">النتيجة الفعلية</label>
                        <textarea name="actual_result" id="actual_result" rows="2" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                  placeholder="ماذا حدث فعلياً؟">{{ old('actual_result', $bug->actual_result) }}</textarea>
                        @error('actual_result')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Resolution Notes -->
                    <div>
                        <label for="resolution_notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات الحل</label>
                        <textarea name="resolution_notes" id="resolution_notes" rows="2" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                  placeholder="كيف تم حل المشكلة؟">{{ old('resolution_notes', $bug->resolution_notes) }}</textarea>
                        @error('resolution_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 space-x-reverse pt-6 border-t border-gray-200">
                <a href="{{ route('bugs.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    إلغاء
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

