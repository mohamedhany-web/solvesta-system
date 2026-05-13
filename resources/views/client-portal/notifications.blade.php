@extends('layouts.app')

@section('page-title', 'الإشعارات')

@section('content')
<div class="w-full max-w-full">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">إشعاراتك</h1>
            <p class="text-gray-600 text-sm">تحديثات التقارير، البلاغات، الاجتماعات، التذاكر والفواتير.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <form method="POST" action="{{ route('client.notifications.read-all') }}">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl border border-gray-300 text-sm font-semibold text-gray-800 hover:bg-gray-50">تعليم الكل كمقروء</button>
            </form>
            <a href="{{ route('client.dashboard') }}" class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800">اللوحة</a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 text-sm font-medium">{{ session('success') }}</div>
    @endif

    <div class="space-y-3">
        @forelse($notifications as $n)
            <div class="bg-white rounded-xl border {{ $n->read_at ? 'border-gray-200' : 'border-blue-200 ring-1 ring-blue-100' }} p-5 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div class="min-w-0">
                    <p class="font-bold text-gray-900">{{ $n->title }}</p>
                    @if($n->body)
                        <p class="text-sm text-gray-600 mt-1">{{ $n->body }}</p>
                    @endif
                    <p class="text-xs text-gray-400 mt-2">{{ $n->created_at->format('Y/m/d H:i') }}</p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    @if($n->action_url)
                        <a href="{{ $n->action_url }}" class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">عرض</a>
                    @endif
                    @if(! $n->read_at)
                        <form method="POST" action="{{ route('client.notifications.read', ['clientNotification' => $n]) }}">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50">تم القراءة</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center text-gray-500">لا توجد إشعارات بعد.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $notifications->links() }}</div>
</div>
@endsection
