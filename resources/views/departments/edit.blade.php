@extends('layouts.app')

@section('page-title', 'تعديل القسم')

@section('content')
<div class="w-full max-w-5xl mx-auto">
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('departments.show', $department) }}" class="text-gray-600 hover:text-gray-900 flex-shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">تعديل القسم</h1>
        </div>
    </div>

    <form action="{{ route('departments.update', $department) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 lg:p-8">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">اسم القسم</label>
                <input type="text" name="name" value="{{ old('name', $department->name) }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">كود القسم</label>
                <input type="text" name="code" value="{{ old('code', $department->code) }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('code')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">مدير القسم (اختياري)</label>
                <select name="manager_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">اختر مدير القسم</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ old('manager_id', $department->manager_id) == $employee->id ? 'selected' : '' }}>
                            {{ $employee->first_name }} {{ $employee->last_name }} - {{ $employee->position }}
                        </option>
                    @endforeach
                </select>
                @error('manager_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">الموقع</label>
                <input type="text" name="location" value="{{ old('location', $department->location) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('location')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">الهاتف</label>
                <input type="tel" name="phone" value="{{ old('phone', $department->phone) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('phone')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email', $department->email) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">الوصف</label>
                <textarea name="description" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $department->description) }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $department->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <span class="mr-2 text-sm text-gray-700">القسم نشط</span>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-between mt-8">
            <form action="{{ route('departments.destroy', $department) }}" method="POST" 
                  onsubmit="return confirm('هل أنت متأكد من حذف هذا القسم؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 sm:py-3 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                    حذف القسم
                </button>
            </form>
            
            <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4">
                <a href="{{ route('departments.show', $department) }}" class="w-full sm:w-auto px-6 py-2.5 sm:py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-center">
                    إلغاء
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 sm:py-3 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                    حفظ التعديلات
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
