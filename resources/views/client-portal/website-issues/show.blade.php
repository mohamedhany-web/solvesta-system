@extends('layouts.app')

@section('page-title', 'تفاصيل البلاغ')

@section('content')
<div class="w-full max-w-full">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500 mb-1 font-mono">{{ $issue->reference_code }}</p>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $issue->title }}</h1>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex px-3 py-1.5 rounded-full text-sm font-semibold
                @if($issue->status === 'open') bg-red-100 text-red-800
                @elseif($issue->status === 'in_progress') bg-amber-100 text-amber-900
                @elseif($issue->status === 'resolved') bg-green-100 text-green-800
                @else bg-gray-100 text-gray-800
                @endif">
                {{ $issue->status_label }}
            </span>
            @if($issue->status === 'open')
                <form action="{{ route('client.website-issues.destroy', $issue) }}" method="POST" class="inline"
                      onsubmit="return confirm('حذف هذا البلاغ نهائياً؟ لا يمكن التراجع.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition font-semibold">حذف البلاغ</button>
                </form>
            @endif
            <a href="{{ route('client.website-issues.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700 transition">القائمة</a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800 text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-3">الوصف</h2>
                <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $issue->description }}</p>
                @if($issue->page_url)
                    <p class="mt-4 text-sm text-gray-600"><span class="font-semibold">الرابط:</span>
                        <a href="{{ $issue->page_url }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline break-all">{{ $issue->page_url }}</a>
                    </p>
                @endif
            </div>

            @if(!empty($issue->attachments))
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">الصور المرفقة</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($issue->attachments as $idx => $att)
                            <a href="{{ route('client.website-issues.file', [$issue, $idx]) }}" target="_blank" class="block group">
                                <div class="rounded-lg border border-gray-200 overflow-hidden bg-gray-50 aspect-video flex items-center justify-center">
                                    <img src="{{ route('client.website-issues.file', [$issue, $idx]) }}" alt="" class="max-h-64 w-full object-contain group-hover:opacity-95">
                                </div>
                                @if(!empty($att['original']))
                                    <p class="text-xs text-gray-500 mt-1 truncate">{{ $att['original'] }}</p>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-sm">
                <h3 class="font-bold text-gray-900 mb-3">معلومات</h3>
                <dl class="space-y-2 text-gray-700">
                    <div class="flex justify-between gap-2"><dt>تاريخ الإرسال</dt><dd class="font-medium">{{ $issue->created_at->format('Y/m/d H:i') }}</dd></div>
                    <div class="flex justify-between gap-2"><dt>آخر تحديث</dt><dd class="font-medium">{{ $issue->updated_at->format('Y/m/d H:i') }}</dd></div>
                </dl>
            </div>

            @if($issue->resolution_message)
                <div class="bg-green-50 rounded-xl border border-green-200 p-6">
                    <h3 class="font-bold text-green-900 mb-2">رد الإدارة</h3>
                    <p class="text-green-900 text-sm whitespace-pre-wrap leading-relaxed">{{ $issue->resolution_message }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
