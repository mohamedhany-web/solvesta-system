@extends('layouts.app')

@section('page-title', 'رسالة جديدة')

@section('content')
<div class="w-full max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 sm:p-8 border border-blue-100">
            <div class="flex items-center">
                <div class="h-12 w-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center ml-3 shadow-lg">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">رسالة جديدة</h1>
                    <p class="text-gray-600 text-base sm:text-lg">إرسال رسالة إلى عضو في الفريق</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Reply to Message -->
    @if($replyTo)
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
        <div class="flex items-start">
            <svg class="h-5 w-5 text-yellow-600 ml-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
            </svg>
            <div class="flex-1">
                <h3 class="text-sm font-medium text-yellow-800">رد على الرسالة</h3>
                <p class="text-sm text-yellow-700 mt-1">
                    من: {{ $replyTo->sender->name }} - {{ $replyTo->subject }}
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Message Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
        <form method="POST" action="{{ route('messages.store') }}">
            @csrf
            
            @if($replyTo)
                <input type="hidden" name="parent_message_id" value="{{ $replyTo->id }}">
            @endif

            <div class="space-y-6">
                <!-- Recipient -->
                <div>
                    <label for="receiver_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        المرسل إليه <span class="text-red-500">*</span>
                    </label>
                    <select name="receiver_id" id="receiver_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('receiver_id') border-red-500 @enderror">
                        <option value="">اختر المرسل إليه</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" 
                                    {{ old('receiver_id', $replyTo->sender_id ?? '') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} 
                                @if($user->roles->first())
                                    - {{ $user->roles->first()->name }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('receiver_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">
                        الموضوع <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="subject" id="subject" required
                           value="{{ old('subject', $replyTo ? 'Re: ' . $replyTo->subject : '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('subject') border-red-500 @enderror"
                           placeholder="موضوع الرسالة">
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Message Type and Priority -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">
                            نوع الرسالة <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('type') border-red-500 @enderror">
                            <option value="direct" {{ old('type') == 'direct' ? 'selected' : '' }}>مباشر</option>
                            <option value="group" {{ old('type') == 'group' ? 'selected' : '' }}>مجموعة</option>
                            <option value="announcement" {{ old('type') == 'announcement' ? 'selected' : '' }}>إعلان</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-semibold text-gray-700 mb-2">
                            الأولوية <span class="text-red-500">*</span>
                        </label>
                        <select name="priority" id="priority" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('priority') border-red-500 @enderror">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>منخفض</option>
                            <option value="normal" {{ old('priority', 'normal') == 'normal' ? 'selected' : '' }}>عادي</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>عالي</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>عاجل</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Message Body -->
                <div>
                    <label for="body" class="block text-sm font-semibold text-gray-700 mb-2">
                        محتوى الرسالة <span class="text-red-500">*</span>
                    </label>
                    <textarea name="body" id="body" rows="8" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('body') border-red-500 @enderror"
                              placeholder="اكتب رسالتك هنا...">{{ old('body') }}</textarea>
                    @error('body')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">يمكنك استخدام HTML البسيط في تنسيق الرسالة</p>
                </div>

                <!-- Important Checkbox -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_important" id="is_important" value="1"
                           {{ old('is_important') ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_important" class="mr-2 text-sm font-medium text-gray-700">
                        رسالة مهمة
                    </label>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('messages.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        إلغاء
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="h-5 w-5 ml-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        إرسال الرسالة
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Auto-focus on subject field
document.getElementById('subject').focus();

// Character counter for message body
const bodyTextarea = document.getElementById('body');
const maxLength = 10000;

bodyTextarea.addEventListener('input', function() {
    const remaining = maxLength - this.value.length;
    const counter = document.getElementById('char-counter') || createCharCounter();
    counter.textContent = `${remaining} حرف متبقي`;
    
    if (remaining < 100) {
        counter.classList.add('text-red-500');
    } else {
        counter.classList.remove('text-red-500');
    }
});

function createCharCounter() {
    const counter = document.createElement('p');
    counter.id = 'char-counter';
    counter.className = 'mt-1 text-sm text-gray-500';
    bodyTextarea.parentNode.appendChild(counter);
    return counter;
}
</script>
@endpush
@endsection