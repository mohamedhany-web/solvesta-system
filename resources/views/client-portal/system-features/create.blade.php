@extends('layouts.app')

@section('page-title', 'طلب ميزة أو تحسين')

@section('content')
<div class="w-full max-w-full">
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">طلب ميزة أو تحسين</h1>
                <p class="text-gray-600">يُصنَّف الطلب ضمن مشروع نظامك مع سجل متابعة وتوثيق. لبلاغات الأعطال في الموقع استخدم <a href="{{ route('client.website-issues.create') }}" class="text-blue-600 font-semibold hover:underline">بلاغ عن الموقع</a>.</p>
            </div>
            <a href="{{ route('client.system-features.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-200 flex items-center justify-center shadow-sm shrink-0">
                <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                العودة للقائمة
            </a>
        </div>
    </div>

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

        <form method="POST" action="{{ route('client.system-features.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">المشروع (نظامك) <span class="text-red-500">*</span></label>
                    @if($projects->isNotEmpty())
                        <select name="project_id" id="project_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-3 @error('project_id') border-red-500 @enderror">
                            <option value="">— مشروع جديد (أدخل الاسم أدناه) —</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}" @selected(old('project_id') == $p->id)>{{ $p->name }} ({{ $p->reference_code }})</option>
                            @endforeach
                        </select>
                    @endif
                    <input type="text" name="new_project_name" value="{{ old('new_project_name') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('new_project_name') border-red-500 @enderror"
                           placeholder="اسم مشروع جديد (إن لم تختر من القائمة)">
                    @error('project_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نوع الطلب <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror">
                        <option value="feature" @selected(old('type', 'feature') === 'feature')>ميزة جديدة</option>
                        <option value="improvement" @selected(old('type') === 'improvement')>تحسين</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الأولوية</label>
                    <select name="priority" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach(['low' => 'منخفضة', 'medium' => 'متوسطة', 'high' => 'عالية', 'urgent' => 'عاجلة'] as $v => $l)
                            <option value="{{ $v }}" @selected(old('priority', 'medium') === $v)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">العنوان <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required maxlength="255"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                           placeholder="مثال: إضافة تقرير مبيعات شهري">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">التفاصيل <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="8" required maxlength="15000"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="اشرح المطلوب أو التحسين المقترح — السياق، الفائدة المتوقعة، وأي تفاصيل تساعد الفريق على الفهم...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('client.system-features.index') }}" class="text-center px-6 py-3 text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors duration-200 font-medium">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center shadow-sm font-semibold">
                    <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    إرسال الطلب
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
