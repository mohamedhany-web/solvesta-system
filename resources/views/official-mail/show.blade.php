@extends('layouts.app')

@section('page-title', $mail->displaySubject())

@section('content')
<div class="w-full max-w-4xl mx-auto font-tajawal">
    <div class="mb-4 flex items-center justify-between gap-3">
        <a href="{{ route('official-mail.index') }}" class="text-sm text-blue-600 hover:underline">← العودة للوارد</a>
        <span class="text-xs text-gray-500">{{ optional($mail->received_at)->format('Y/m/d H:i') }}</span>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <h1 class="text-xl font-bold text-gray-900 mb-4">{{ $mail->displaySubject() }}</h1>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm mb-6">
            <div><span class="text-gray-500">من:</span> <span class="font-medium">{{ $mail->from_name }} &lt;{{ $mail->from_email }}&gt;</span></div>
            <div><span class="text-gray-500">إلى:</span> <span class="font-medium">{{ $mail->to_email }}</span></div>
            <div><span class="text-gray-500">الاتجاه:</span> {{ $mail->direction === 'outbound' ? 'صادر' : 'وارد' }}</div>
            @if($mail->forwarded)
            <div><span class="text-gray-500">تحويل:</span> نعم — {{ optional($mail->forwarded_at)->format('Y/m/d H:i') }}</div>
            @endif
        </dl>

        <div class="prose max-w-none border-t border-gray-100 pt-4 text-gray-800 overflow-x-auto">
            @if($mail->body_html)
                {!! $mail->body_html !!}
            @else
                <pre class="whitespace-pre-wrap font-sans text-sm">{{ $mail->body_text }}</pre>
            @endif
        </div>
    </div>

    @if($mail->direction === 'inbound')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 mb-3">الرد من النظام</h2>
        <form method="POST" action="{{ route('official-mail.reply', $mail) }}" class="space-y-3">
            @csrf
            <textarea name="body" rows="6" required class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm" placeholder="اكتب ردك هنا...">{{ old('body') }}</textarea>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700">إرسال الرد</button>
        </form>
    </div>
    @endif

    @if($mail->replies->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-3">الردود</h2>
        <div class="space-y-4">
            @foreach($mail->replies as $reply)
            <div class="border border-gray-100 rounded-xl p-4">
                <div class="flex justify-between text-xs text-gray-500 mb-2">
                    <span>إلى {{ $reply->to_email }}</span>
                    <span>{{ optional($reply->received_at)->format('Y/m/d H:i') }}</span>
                </div>
                <div class="text-sm">{!! $reply->body_html ?: nl2br(e($reply->body_text)) !!}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
