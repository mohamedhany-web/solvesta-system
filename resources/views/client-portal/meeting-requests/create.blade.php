@extends('layouts.app')

@section('page-title', 'طلب اجتماع جديد')

@section('content')
<div class="w-full max-w-full">
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">طلب اجتماع مع الفريق</h1>
                <p class="text-gray-600">اذكر موضوع الاجتماع والوقت الذي يناسبك. يمكنك إضافة أوقات بديلة في حقل منفصل.</p>
            </div>
            <a href="{{ route('client.meeting-requests.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-200 flex items-center justify-center shadow-sm shrink-0">
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

        <form action="{{ route('client.meeting-requests.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="lg:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">موضوع الاجتماع <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required maxlength="255"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('title') border-red-500 @enderror"
                           placeholder="مثال: مراجعة خطة التطوير الربعية">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">ما الذي تريد مناقشته؟ <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="7" required maxlength="15000"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('description') border-red-500 @enderror"
                              placeholder="نقاط الأجندة، الأسئلة، أو أي معلومات تساعد الفريق على الاستعداد.">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="preferred_at" class="block text-sm font-medium text-gray-700 mb-2">الموعد المفضل <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="preferred_at" id="preferred_at" value="{{ old('preferred_at') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('preferred_at') border-red-500 @enderror">
                    @error('preferred_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">يجب أن يكون الموعد في المستقبل.</p>
                </div>

                <div>
                    <label for="meeting_format" class="block text-sm font-medium text-gray-700 mb-2">نوع الاجتماع <span class="text-red-500">*</span></label>
                    <select name="meeting_format" id="meeting_format" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('meeting_format') border-red-500 @enderror">
                        <option value="either" @selected(old('meeting_format', 'either') === 'either')>ما يناسب الفريق (عن بُعد أو حضوري)</option>
                        <option value="online" @selected(old('meeting_format') === 'online')>عن بُعد (فيديو)</option>
                        <option value="in_person" @selected(old('meeting_format') === 'in_person')>حضوري</option>
                    </select>
                    @error('meeting_format')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="participants_count" class="block text-sm font-medium text-gray-700 mb-2">عدد الحضور (تقريبي)</label>
                    <input type="number" name="participants_count" id="participants_count" value="{{ old('participants_count') }}" min="1" max="500"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('participants_count') border-red-500 @enderror"
                           placeholder="اختياري">
                    @error('participants_count')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="alternative_times" class="block text-sm font-medium text-gray-700 mb-2">أوقات بديلة أو ملاحظات الجدولة</label>
                    <textarea name="alternative_times" id="alternative_times" rows="4" maxlength="5000"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error('alternative_times') border-red-500 @enderror"
                              placeholder="مثال: متاح يوم الأحد صباحاً، أو أيام الأسبوع بعد الساعة 3 مساءً.">{{ old('alternative_times') }}</textarea>
                    @error('alternative_times')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button type="submit" class="inline-flex justify-center items-center px-8 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition shadow-sm">
                    إرسال الطلب
                </button>
                <a href="{{ route('client.meeting-requests.index') }}" class="text-center px-6 py-3 text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition font-medium">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
