@extends('layouts.app')

@section('page-title', 'تذكرة دعم جديدة')

@section('content')
<div class="w-full max-w-full">
    <div class="mb-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">تذكرة دعم جديدة</h1>
                <p class="text-gray-600 text-sm sm:text-base">العميل: {{ $client->name }}</p>
            </div>
            <a href="{{ route('client.support.tickets') }}" class="bg-gray-600 text-white px-5 py-2.5 rounded-lg hover:bg-gray-700 transition-all duration-200 text-sm">العودة</a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('client.support.tickets.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">الموضوع</label>
                <input type="text" name="subject" value="{{ old('subject') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('subject') border-red-500 @enderror">
                @error('subject') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">الفئة</label>
                    <select name="category" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @enderror">
                        <option value="technical" {{ old('category')=='technical' ? 'selected' : '' }}>تقني</option>
                        <option value="billing" {{ old('category')=='billing' ? 'selected' : '' }}>فواتير</option>
                        <option value="general" {{ old('category')=='general' ? 'selected' : '' }}>عام</option>
                        <option value="bug_report" {{ old('category')=='bug_report' ? 'selected' : '' }}>تقرير خطأ</option>
                        <option value="feature_request" {{ old('category')=='feature_request' ? 'selected' : '' }}>طلب ميزة</option>
                    </select>
                    @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">الأولوية</label>
                    <select name="priority" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('priority') border-red-500 @enderror">
                        <option value="low" {{ old('priority')=='low' ? 'selected' : '' }}>منخفضة</option>
                        <option value="medium" {{ old('priority','medium')=='medium' ? 'selected' : '' }}>متوسطة</option>
                        <option value="high" {{ old('priority')=='high' ? 'selected' : '' }}>عالية</option>
                        <option value="critical" {{ old('priority')=='critical' ? 'selected' : '' }}>حرجة</option>
                    </select>
                    @error('priority') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">وصف المشكلة</label>
                <textarea name="description" rows="8" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-200 text-sm font-semibold">إرسال</button>
            </div>
        </form>
    </div>
</div>
@endsection

