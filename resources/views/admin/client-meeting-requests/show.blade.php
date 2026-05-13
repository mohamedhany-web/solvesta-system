@extends('layouts.app')

@section('page-title', 'طلب اجتماع — '.$meetingRequest->reference_code)

@section('content')
<div class="w-full max-w-full">
    <div class="mb-8 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
        <div class="min-w-0 flex-1">
            <p class="text-sm font-mono text-gray-500 mb-1">{{ $meetingRequest->reference_code }}</p>
            <h1 class="text-3xl font-bold text-gray-900 mb-2 leading-tight">{{ $meetingRequest->title }}</h1>
            <p class="text-gray-600 text-sm sm:text-base mb-3">
                عميل:
                <a href="{{ route('clients.show', $meetingRequest->client) }}" class="text-blue-600 font-semibold hover:underline">{{ $meetingRequest->client->name }}</a>
                @if($meetingRequest->client->company_name)
                    <span class="text-gray-500">— {{ $meetingRequest->client->company_name }}</span>
                @endif
            </p>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-bold
                    @if($meetingRequest->status === 'pending') bg-amber-100 text-amber-900
                    @elseif($meetingRequest->status === 'confirmed') bg-blue-100 text-blue-900
                    @elseif($meetingRequest->status === 'completed') bg-green-100 text-green-800
                    @elseif($meetingRequest->status === 'declined') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ $meetingRequest->status_label }}
                </span>
                @if($meetingRequest->assignee)
                    <span class="text-xs text-gray-600 bg-gray-100 px-2.5 py-1 rounded-lg">مسند إلى: {{ $meetingRequest->assignee->name }}</span>
                @endif
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('client-meeting-requests.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-sm font-bold text-gray-800 shadow-sm transition">
                <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                العودة للقائمة
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-semibold text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-100">وصف الطلب</h2>
                <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-5 text-gray-800 leading-relaxed whitespace-pre-wrap text-sm sm:text-base">
                    {{ $meetingRequest->description }}
                </div>
                <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="rounded-xl border border-gray-100 bg-white p-4">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">الموعد المفضل للعميل</span>
                        <p class="mt-1 font-semibold text-gray-900">{{ $meetingRequest->preferred_at->format('Y/m/d H:i') }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-100 bg-white p-4">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">نوع الاجتماع</span>
                        <p class="mt-1 font-semibold text-gray-900">{{ $meetingRequest->meeting_format_label }}</p>
                    </div>
                    @if($meetingRequest->participants_count)
                        <div class="rounded-xl border border-gray-100 bg-white p-4">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">عدد الحضور (تقريبي)</span>
                            <p class="mt-1 font-semibold text-gray-900">{{ $meetingRequest->participants_count }}</p>
                        </div>
                    @endif
                </div>
                @if($meetingRequest->alternative_times)
                    <div class="mt-5 pt-5 border-t border-gray-100">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">أوقات بديلة / ملاحظات</span>
                        <p class="mt-2 text-gray-800 whitespace-pre-wrap text-sm leading-relaxed">{{ $meetingRequest->alternative_times }}</p>
                    </div>
                @endif
            </div>

            @if($meetingRequest->response_message)
                <div class="bg-white rounded-xl shadow-sm border border-green-200 p-6 sm:p-8">
                    <h2 class="text-lg font-bold text-green-900 mb-3">رد يظهر للعميل</h2>
                    <p class="text-green-900 text-sm sm:text-base whitespace-pre-wrap leading-relaxed">{{ $meetingRequest->response_message }}</p>
                </div>
            @endif
        </div>

        <div class="xl:col-span-4 space-y-6">
            @can('edit-tickets')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-1">إدارة الطلب</h2>
                    <p class="text-xs text-gray-500 mb-5">الحالة، الموعد المؤكد، الرابط، والرد للعميل.</p>

                    @if($errors->any())
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-red-800 text-xs space-y-1">
                            @foreach($errors->all() as $err)
                                <div>{{ $err }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('client-meeting-requests.update', $meetingRequest) }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                            <select name="status" id="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="pending" @selected($meetingRequest->status==='pending')>قيد المراجعة</option>
                                <option value="confirmed" @selected($meetingRequest->status==='confirmed')>تم التأكيد</option>
                                <option value="declined" @selected($meetingRequest->status==='declined')>مرفوض</option>
                                <option value="completed" @selected($meetingRequest->status==='completed')>مكتمل</option>
                                <option value="cancelled" @selected($meetingRequest->status==='cancelled')>ملغى</option>
                            </select>
                        </div>

                        <div>
                            <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">تعيين لموظف (اختياري)</label>
                            <select name="assigned_to" id="assigned_to" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">— بدون —</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" @selected($meetingRequest->assigned_to == $u->id)>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-2">موعد الاجتماع المؤكد (اختياري)</label>
                            <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                                   value="{{ old('scheduled_at', $meetingRequest->scheduled_at?->format('Y-m-d\TH:i')) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label for="meeting_link" class="block text-sm font-medium text-gray-700 mb-2">رابط الاجتماع (فيديو)</label>
                            <input type="text" name="meeting_link" id="meeting_link" value="{{ old('meeting_link', $meetingRequest->meeting_link) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                   placeholder="https://...">
                        </div>

                        <div>
                            <label for="location_notes" class="block text-sm font-medium text-gray-700 mb-2">مكان / تعليمات حضورية</label>
                            <textarea name="location_notes" id="location_notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="العنوان أو تعليمات الوصول">{{ old('location_notes', $meetingRequest->location_notes) }}</textarea>
                        </div>

                        <div>
                            <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات داخلية</label>
                            <textarea name="admin_notes" id="admin_notes" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="لا تظهر للعميل">{{ old('admin_notes', $meetingRequest->admin_notes) }}</textarea>
                        </div>

                        <div>
                            <label for="response_message" class="block text-sm font-medium text-gray-700 mb-2">رد للعميل</label>
                            <textarea name="response_message" id="response_message" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="يظهر في بوابة العميل عند الحفظ">{{ old('response_message', $meetingRequest->response_message) }}</textarea>
                        </div>

                        <button type="submit" class="w-full py-3 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            حفظ التحديثات
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-sm text-gray-600">
                    ليس لديك صلاحية تعديل الطلبات — عرض فقط.
                </div>
            @endcan

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-bold text-gray-900 mb-4">معلومات</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-3 border-b border-gray-100 pb-3">
                        <dt class="text-gray-500">تاريخ الإنشاء</dt>
                        <dd class="font-semibold text-gray-900">{{ $meetingRequest->created_at->format('Y/m/d H:i') }}</dd>
                    </div>
                    <div class="flex justify-between gap-3 border-b border-gray-100 pb-3">
                        <dt class="text-gray-500">آخر تحديث</dt>
                        <dd class="font-semibold text-gray-900">{{ $meetingRequest->updated_at->format('Y/m/d H:i') }}</dd>
                    </div>
                    @if($meetingRequest->confirmer)
                        <div class="flex justify-between gap-3">
                            <dt class="text-gray-500">آخر تأكيد بواسطة</dt>
                            <dd class="font-semibold text-gray-900">{{ $meetingRequest->confirmer->name }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
