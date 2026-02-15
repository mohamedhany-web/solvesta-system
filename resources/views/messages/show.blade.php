@extends('layouts.app')

@section('page-title', $message->subject)

@section('content')
<div class="w-full max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 sm:p-8 border border-blue-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center">
                    <a href="{{ route('messages.index') }}" 
                       class="h-10 w-10 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center ml-3 transition-colors">
                        <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">{{ $message->subject }}</h1>
                        <p class="text-gray-600 text-base sm:text-lg">
                            من: {{ $message->sender->name }} 
                            @if($message->receiver_id !== auth()->id())
                                إلى: {{ $message->receiver->name }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($message->is_important)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            مهم
                        </span>
                    @endif
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        {{ $message->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                           ($message->priority === 'high' ? 'bg-orange-100 text-orange-800' : 
                           ($message->priority === 'normal' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                        {{ $message->priority_text }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Content -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8 mb-6">
        <!-- Message Header -->
        <div class="flex items-start gap-4 mb-6 pb-6 border-b border-gray-200">
            <!-- Sender Avatar -->
            <div class="flex-shrink-0">
                @if($message->sender->profile_picture)
                    <img src="{{ asset('storage/' . $message->sender->profile_picture) }}" 
                         alt="{{ $message->sender->name }}" 
                         class="h-12 w-12 rounded-full object-cover">
                @else
                    <div class="h-12 w-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-lg font-bold text-white">{{ substr($message->sender->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>

            <!-- Sender Info -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $message->sender->name }}</h3>
                        <p class="text-sm text-gray-600">
                            {{ $message->sender->roles->first()?->name ?? 'موظف' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ $message->created_at->format('Y-m-d H:i') }}</p>
                        @if($message->is_read && $message->read_at)
                            <p class="text-xs text-gray-400">تم القراءة: {{ $message->read_at->diffForHumans() }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Message Body -->
        <div class="prose max-w-none">
            <div class="text-gray-900 whitespace-pre-wrap">{!! nl2br(e($message->body)) !!}</div>
        </div>

        <!-- Message Actions -->
        <div class="flex items-center justify-between mt-6 pt-6 border-t border-gray-200">
            <div class="flex items-center gap-3">
                @if($message->receiver_id === auth()->id())
                    <a href="{{ route('messages.create', ['reply_to' => $message->id]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        رد
                    </a>
                    
                    @if(!$message->is_important)
                        <button onclick="markAsImportant({{ $message->id }})" 
                                class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors">
                            <svg class="h-4 w-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            وضع علامة مهم
                        </button>
                    @endif
                @endif
            </div>

            <div class="flex items-center gap-3">
                <form method="POST" action="{{ route('messages.destroy', $message) }}" 
                      onsubmit="return confirm('هل أنت متأكد من حذف هذه الرسالة؟')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                        <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        حذف
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Replies Section -->
    @if($replies->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">الردود ({{ $replies->count() }})</h3>
        
        <div class="space-y-6">
            @foreach($replies as $reply)
            <div class="border border-gray-200 rounded-lg p-4 {{ $reply->sender_id === auth()->id() ? 'bg-blue-50' : 'bg-gray-50' }}">
                <div class="flex items-start gap-3">
                    <!-- Reply Avatar -->
                    <div class="flex-shrink-0">
                        @if($reply->sender->profile_picture)
                            <img src="{{ asset('storage/' . $reply->sender->profile_picture) }}" 
                                 alt="{{ $reply->sender->name }}" 
                                 class="h-8 w-8 rounded-full object-cover">
                        @else
                            <div class="h-8 w-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-xs font-bold text-white">{{ substr($reply->sender->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Reply Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-semibold text-gray-900">{{ $reply->sender->name }}</h4>
                            <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="text-sm text-gray-700 whitespace-pre-wrap">{!! nl2br(e($reply->body)) !!}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Reply Form -->
    @if($message->receiver_id === auth()->id())
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8 mt-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">إضافة رد</h3>
        
        <form method="POST" action="{{ route('messages.reply', $message) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <textarea name="body" id="reply_body" rows="4" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="اكتب ردك هنا..."></textarea>
                </div>
                <div class="flex items-center justify-end">
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                        <svg class="h-4 w-4 ml-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        إرسال الرد
                    </button>
                </div>
            </div>
        </form>
    </div>
    @endif
</div>

@push('scripts')
<script>
function markAsImportant(messageId) {
    fetch(`/messages/${messageId}/mark-important`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Auto-focus on reply textarea
document.getElementById('reply_body')?.focus();
</script>
@endpush
@endsection