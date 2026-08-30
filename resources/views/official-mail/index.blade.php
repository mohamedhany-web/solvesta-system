@extends('layouts.app')

@section('page-title', $selected ? $selected->displaySubject() : 'البريد الرسمي')

@section('content')
@php
    $selectedId = $selected?->id;
    $officialEmail = env('MAIL_FROM_ADDRESS', 'info@s0lvesta.com');
@endphp

<style>
    .gmail-shell {
        flex: 1;
        min-height: 0;
        width: 100%;
        max-width: 100%;
        display: flex;
        flex-direction: column;
        background: #f6f8fc;
        font-family: 'Tajawal', Tahoma, Arial, sans-serif;
    }
    .gmail-toolbar {
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
        padding: .65rem 1rem;
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
    }
    .gmail-body {
        flex: 1;
        min-height: 0;
        display: grid;
        grid-template-columns: 220px minmax(280px, 380px) 1fr;
    }
    @media (max-width: 1100px) {
        .gmail-body { grid-template-columns: 180px minmax(240px, 320px) 1fr; }
    }
    @media (max-width: 900px) {
        .gmail-body { grid-template-columns: 1fr; }
        .gmail-folders { display: none; }
        .gmail-list-pane { {{ $selected ? 'display:none;' : '' }} }
        .gmail-read-pane { {{ $selected ? '' : 'display:none;' }} min-height: 70vh; }
    }
    .gmail-folders {
        background: #f6f8fc;
        border-left: 1px solid #e8eaed;
        padding: .75rem .5rem;
        overflow-y: auto;
    }
    .gmail-folder-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .65rem .85rem;
        border-radius: 9999px;
        font-size: .875rem;
        color: #3c4043;
        margin-bottom: .25rem;
    }
    .gmail-folder-link:hover { background: #e8eaed; }
    .gmail-folder-link.active {
        background: #d3e3fd;
        color: #041e49;
        font-weight: 700;
    }
    .gmail-list-pane {
        background: #fff;
        border-left: 1px solid #e8eaed;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }
    .gmail-list {
        flex: 1;
        overflow-y: auto;
        min-height: 0;
    }
    .gmail-row {
        display: grid;
        grid-template-columns: 18px 1fr auto;
        gap: .5rem;
        align-items: start;
        padding: .7rem .85rem;
        border-bottom: 1px solid #f1f3f4;
        text-decoration: none;
        color: inherit;
        background: #fff;
    }
    .gmail-row:hover { box-shadow: inset 1px 0 0 #dadce0, inset -1px 0 0 #dadce0, 0 1px 2px 0 rgba(60,64,67,.3), 0 1px 3px 1px rgba(60,64,67,.15); z-index: 1; position: relative; }
    .gmail-row.unread { background: #f2f6fc; }
    .gmail-row.unread .gmail-from,
    .gmail-row.unread .gmail-subject { font-weight: 700; color: #202124; }
    .gmail-row.selected { background: #c2e7ff; }
    .gmail-from { font-size: .82rem; color: #202124; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .gmail-subject { font-size: .8rem; color: #3c4043; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px; }
    .gmail-snippet { font-size: .75rem; color: #5f6368; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px; }
    .gmail-date { font-size: .7rem; color: #5f6368; white-space: nowrap; padding-top: 2px; }
    .gmail-read-pane {
        background: #fff;
        display: flex;
        flex-direction: column;
        min-height: 0;
        min-width: 0;
    }
    .gmail-read-header {
        padding: 1rem 1.25rem .75rem;
        border-bottom: 1px solid #f1f3f4;
        flex-shrink: 0;
    }
    .gmail-read-scroll {
        flex: 1;
        overflow-y: auto;
        min-height: 0;
        padding: 0 1.25rem 1.25rem;
    }
    .gmail-iframe-wrap {
        border: 1px solid #e8eaed;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        min-height: 280px;
    }
    .gmail-iframe-wrap iframe {
        width: 100%;
        min-height: 320px;
        border: 0;
        background: #fff;
        display: block;
    }
    .gmail-btn {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .45rem .9rem;
        border-radius: 9999px;
        font-size: .8rem;
        font-weight: 700;
        border: 0;
        cursor: pointer;
        text-decoration: none;
    }
    .gmail-btn-primary { background: #0b57d0; color: #fff; }
    .gmail-btn-primary:hover { background: #0842a0; }
    .gmail-btn-ghost { background: transparent; color: #3c4043; }
    .gmail-btn-ghost:hover { background: #e8eaed; }
    .gmail-search {
        flex: 1;
        min-width: 180px;
        max-width: 480px;
        background: #eaf1fb;
        border-radius: 9999px;
        padding: .55rem 1rem;
        border: 0;
        font-size: .875rem;
    }
    .gmail-search:focus { outline: 2px solid #0b57d0; background: #fff; }
</style>

<div class="gmail-shell">
    <div class="gmail-toolbar">
        <div class="font-bold text-gray-900 text-sm sm:text-base whitespace-nowrap">البريد الرسمي</div>
        <form method="GET" action="{{ route('official-mail.index') }}" class="flex-1 flex gap-2 min-w-[200px]">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="بحث في البريد..." class="gmail-search w-full">
        </form>
        <form method="POST" action="{{ route('official-mail.sync') }}">
            @csrf
            <button type="submit" class="gmail-btn gmail-btn-ghost">مزامنة</button>
        </form>
        <a href="{{ route('official-mail.compose') }}" class="gmail-btn gmail-btn-primary">إنشاء</a>
    </div>

    @if(session('success'))
    <div class="px-4 py-2 bg-green-50 text-green-800 text-sm border-b border-green-100">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="px-4 py-2 bg-red-50 text-red-800 text-sm border-b border-red-100">{{ session('error') }}</div>
    @endif

    <div class="gmail-body">
        {{-- Folders --}}
        <aside class="gmail-folders">
            <a href="{{ route('official-mail.compose') }}" class="gmail-btn gmail-btn-primary w-full justify-center mb-3" style="border-radius:1rem;padding:.7rem 1rem;">إنشاء</a>
            <a href="{{ route('official-mail.index', ['tab' => 'inbox']) }}" class="gmail-folder-link {{ $tab === 'inbox' ? 'active' : '' }}">
                <span>الوارد</span>
                <span class="text-xs">{{ $inboxCount }}</span>
            </a>
            <a href="{{ route('official-mail.index', ['tab' => 'unread']) }}" class="gmail-folder-link {{ $tab === 'unread' ? 'active' : '' }}">
                <span>غير مقروء</span>
                <span class="text-xs">{{ $unreadCount }}</span>
            </a>
            <a href="{{ route('official-mail.index', ['tab' => 'sent']) }}" class="gmail-folder-link {{ $tab === 'sent' ? 'active' : '' }}">
                <span>المرسل</span>
                <span class="text-xs">{{ $sentCount }}</span>
            </a>
            <p class="px-3 mt-4 text-[11px] text-gray-500 leading-relaxed">{{ $officialEmail }}</p>
        </aside>

        {{-- Message list --}}
        <section class="gmail-list-pane">
            <div class="px-3 py-2 text-xs text-gray-500 border-b border-gray-100 flex justify-between">
                <span>{{ $messages->total() }} رسالة</span>
                <span>{{ $messages->firstItem() }}–{{ $messages->lastItem() }}</span>
            </div>
            <div class="gmail-list">
                @forelse($messages as $mail)
                @php
                    $isUnread = ! $mail->is_read && $mail->direction === 'inbound';
                    $isSelected = $selectedId === $mail->id;
                    $fromLabel = $mail->direction === 'outbound'
                        ? 'إلى: '.($mail->to_email ?: '—')
                        : ($mail->from_name ?: $mail->from_email ?: '—');
                @endphp
                <a href="{{ route('official-mail.show', $mail) }}?{{ http_build_query(array_filter(['tab' => $tab, 'search' => request('search'), 'page' => request('page')])) }}"
                   class="gmail-row {{ $isUnread ? 'unread' : '' }} {{ $isSelected ? 'selected' : '' }}">
                    <span class="mt-1.5">
                        @if($isUnread)
                        <span class="block w-2 h-2 rounded-full bg-blue-600"></span>
                        @endif
                    </span>
                    <div class="min-w-0">
                        <div class="gmail-from">{{ $fromLabel }}</div>
                        <div class="gmail-subject">
                            {{ $mail->displaySubject() }}
                            @if($mail->forwarded)<span class="text-[10px] text-amber-700">· محوّل</span>@endif
                        </div>
                        <div class="gmail-snippet">{{ $mail->preview(80) }}</div>
                    </div>
                    <div class="gmail-date">{{ optional($mail->received_at)->format('m/d') }}</div>
                </a>
                @empty
                <div class="p-10 text-center text-gray-500 text-sm">
                    لا توجد رسائل — اضغط مزامنة لجلب الوارد
                </div>
                @endforelse
            </div>
            @if($messages->hasPages())
            <div class="px-3 py-2 border-t border-gray-100 text-xs">{{ $messages->links() }}</div>
            @endif
        </section>

        {{-- Reading pane --}}
        <section class="gmail-read-pane">
            @if($selected)
                <div class="gmail-read-header">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="min-w-0">
                            <a href="{{ route('official-mail.index', ['tab' => $tab, 'search' => request('search')]) }}" class="lg:hidden text-xs text-blue-600 mb-2 inline-block">← القائمة</a>
                            <h1 class="text-xl sm:text-2xl font-normal text-gray-900 leading-snug">{{ $selected->displaySubject() }}</h1>
                        </div>
                        <span class="text-xs text-gray-500 whitespace-nowrap pt-1">{{ optional($selected->received_at)->format('Y/m/d H:i') }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold flex-shrink-0">
                            {{ mb_substr($selected->from_name ?: $selected->from_email ?: '?', 0, 1) }}
                        </div>
                        <div class="min-w-0 text-sm">
                            <div class="font-semibold text-gray-900 truncate">{{ $selected->from_name ?: $selected->from_email }}</div>
                            <div class="text-xs text-gray-500 truncate">إلى {{ $selected->to_email }}</div>
                        </div>
                    </div>
                </div>

                <div class="gmail-read-scroll">
                    <div class="gmail-iframe-wrap mt-4">
                        @if($selected->body_html)
                            @php
                                $mailDoc = '<!DOCTYPE html><html><head><meta charset="utf-8"><base target="_blank"><style>body{margin:16px;font-family:Tahoma,Arial,sans-serif;font-size:14px;line-height:1.6;color:#202124;word-wrap:break-word;overflow-wrap:anywhere}img{max-width:100%!important;height:auto!important}table{max-width:100%!important}a{color:#0b57d0}</style></head><body>'.$selected->body_html.'</body></html>';
                            @endphp
                            <iframe
                                id="mail-body-frame"
                                sandbox="allow-same-origin allow-popups allow-popups-to-escape-sandbox"
                                referrerpolicy="no-referrer"
                                title="محتوى الرسالة"
                                srcdoc="{{ $mailDoc }}"
                            ></iframe>
                        @else
                            <pre class="p-4 whitespace-pre-wrap font-sans text-sm text-gray-800">{{ $selected->body_text }}</pre>
                        @endif
                    </div>

                    @if($selected->replies->isNotEmpty())
                    <div class="mt-6 space-y-3">
                        <h3 class="text-sm font-bold text-gray-700">الردود السابقة</h3>
                        @foreach($selected->replies as $reply)
                        <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                            <div class="flex justify-between text-xs text-gray-500 mb-2">
                                <span>إلى {{ $reply->to_email }}</span>
                                <span>{{ optional($reply->received_at)->format('Y/m/d H:i') }}</span>
                            </div>
                            <div class="text-sm text-gray-800">{!! nl2br(e(strip_tags($reply->body_text ?: $reply->body_html))) !!}</div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if($selected->direction === 'inbound')
                    <div class="mt-6 border border-gray-200 rounded-2xl p-4">
                        <h3 class="text-sm font-bold text-gray-800 mb-2">رد</h3>
                        <form method="POST" action="{{ route('official-mail.reply', $selected) }}" class="space-y-3">
                            @csrf
                            <textarea name="body" rows="5" required class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm" placeholder="اكتب ردك...">{{ old('body') }}</textarea>
                            <button type="submit" class="gmail-btn gmail-btn-primary">إرسال</button>
                        </form>
                    </div>
                    @endif
                </div>
            @else
                <div class="flex-1 flex items-center justify-center text-gray-400 text-sm p-8">
                    اختر رسالة من القائمة لعرضها هنا
                </div>
            @endif
        </section>
    </div>
</div>

<script>
(function () {
    const frame = document.getElementById('mail-body-frame');
    if (!frame) return;
    function resizeFrame() {
        try {
            const doc = frame.contentDocument || frame.contentWindow.document;
            if (!doc || !doc.body) return;
            const h = Math.max(doc.body.scrollHeight, doc.documentElement.scrollHeight, 320);
            frame.style.height = Math.min(h + 24, 4000) + 'px';
        } catch (e) {}
    }
    frame.addEventListener('load', resizeFrame);
    setTimeout(resizeFrame, 300);
    setTimeout(resizeFrame, 1000);
})();
</script>
@endsection
