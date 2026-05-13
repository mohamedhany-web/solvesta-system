@extends('layouts.app')

@section('page-title', 'تذكرة '.$ticket->ticket_number)

@section('content')
<div class="w-full max-w-full">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm font-mono text-gray-500 mb-1">{{ $ticket->ticket_number }}</p>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $ticket->subject }}</h1>
            <p class="text-sm text-gray-600 mt-1">الحالة: <span class="font-semibold">{{ $ticket->status_name }}</span> — الأولوية: {{ $ticket->priority }}</p>
        </div>
        <a href="{{ route('client.support.tickets') }}" class="px-4 py-2 rounded-lg bg-gray-600 text-white text-sm hover:bg-gray-700">القائمة</a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 text-sm font-medium">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="font-bold text-gray-900 mb-3">الوصف</h2>
                <p class="text-gray-800 whitespace-pre-wrap text-sm leading-relaxed">{{ $ticket->description }}</p>
            </div>
            @if($ticket->resolution_notes)
                <div class="bg-green-50 rounded-xl border border-green-200 p-6">
                    <h2 class="font-bold text-green-900 mb-2">ملاحظات الحل</h2>
                    <p class="text-green-900 text-sm whitespace-pre-wrap">{{ $ticket->resolution_notes }}</p>
                </div>
            @endif
        </div>
        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-sm">
                <h3 class="font-bold text-gray-900 mb-3">معلومات</h3>
                <dl class="space-y-2 text-gray-700">
                    <div class="flex justify-between gap-2"><dt>التصنيف</dt><dd class="font-medium">{{ $ticket->category_name }}</dd></div>
                    <div class="flex justify-between gap-2"><dt>مسند إلى</dt><dd class="font-medium">{{ optional($ticket->assignedTo)->name ?? '—' }}</dd></div>
                    <div class="flex justify-between gap-2"><dt>تاريخ الإنشاء</dt><dd class="font-medium">{{ $ticket->created_at->format('Y/m/d H:i') }}</dd></div>
                </dl>
            </div>

            @if(in_array($ticket->status, ['resolved', 'closed'], true))
                @if($existingFeedback)
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="font-bold text-gray-900 mb-2">شكراً — تم تسجيل تقييمك</h3>
                        <p class="text-sm text-gray-700">التقييم: {{ $existingFeedback->rating }} / 5</p>
                        @if($existingFeedback->comment)
                            <p class="text-sm text-gray-600 mt-2 whitespace-pre-wrap">{{ $existingFeedback->comment }}</p>
                        @endif
                    </div>
                @else
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="font-bold text-gray-900 mb-3">تقييم سريع</h3>
                        <form method="POST" action="{{ route('client.support.tickets.feedback', $ticket) }}" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">التقييم (1–5)</label>
                                <select name="rating" required class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm">
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تعليق (اختياري)</label>
                                <textarea name="comment" rows="3" maxlength="2000" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-sm" placeholder="ما الذي يمكننا تحسينه؟"></textarea>
                            </div>
                            <button type="submit" class="w-full py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700">إرسال التقييم</button>
                        </form>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
