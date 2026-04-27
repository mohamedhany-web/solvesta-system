@extends('layouts.app')

@section('page-title', 'إنشاء مهمة (مدير القسم)')

@section('content')
<div class="w-full max-w-6xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">إنشاء مهمة جديدة</h1>
            <p class="text-gray-600">إنشاء مهمة داخل مشاريع قسمك وإسنادها لأعضاء فريقك</p>
        </div>
        <a href="{{ route('department-manager.dashboard') }}" class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-200 shadow-sm">
            العودة للوحة القسم
        </a>
    </div>

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('department-manager.tasks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">تفاصيل المهمة</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">عنوان المهمة <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                    @error('title') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">المشروع <span class="text-red-500">*</span></label>
                    <select name="project_id" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('project_id') border-red-500 @enderror">
                        <option value="">اختر المشروع</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>{{ $project->name }}</option>
                        @endforeach
                    </select>
                    @error('project_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">إسناد إلى <span class="text-red-500">*</span></label>
                    <select name="assigned_to" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('assigned_to') border-red-500 @enderror">
                        <option value="">اختر الموظف</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(old('assigned_to') == $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('assigned_to') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">وصف المهمة <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="5" required
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">الأولوية <span class="text-red-500">*</span></label>
                    <select name="priority" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
                        @foreach(['low' => 'منخفضة', 'medium' => 'متوسطة', 'high' => 'عالية', 'urgent' => 'عاجلة'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('priority','medium') == $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">الحالة <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
                        @foreach(['todo' => 'قيد الإعداد', 'in_progress' => 'قيد التنفيذ', 'review' => 'مراجعة', 'completed' => 'مكتملة', 'cancelled' => 'ملغاة'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status','todo') == $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ البداية</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ الاستحقاق <span class="text-red-500">*</span></label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl @error('due_date') border-red-500 @enderror">
                    @error('due_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ساعات تقديرية</label>
                    <input type="number" step="0.5" min="0" name="estimated_hours" value="{{ old('estimated_hours') }}"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">نسبة الإنجاز (%)</label>
                    <input type="number" min="0" max="100" name="progress_percentage" value="{{ old('progress_percentage') }}"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">مرفقات</label>
                    <input type="file" name="attachments[]" multiple
                           class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50">
                    @error('attachments.*') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <button type="submit" class="bg-blue-600 text-white px-7 py-3 rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-sm">
                إنشاء المهمة
            </button>
        </div>
    </form>
</div>
@endsection

