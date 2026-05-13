@extends('layouts.app')

@section('page-title', 'بلاغ عميل — '.$websiteIssue->reference_code)

@section('content')
<div class="w-full max-w-full">
    <!-- Page header -->
    <div class="mb-8 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
        <div class="min-w-0 flex-1">
            <p class="text-sm font-mono text-gray-500 mb-1">{{ $websiteIssue->reference_code }}</p>
            <h1 class="text-3xl font-bold text-gray-900 mb-2 leading-tight">{{ $websiteIssue->title }}</h1>
            <p class="text-gray-600 text-sm sm:text-base mb-3">
                عميل:
                <a href="{{ route('clients.show', $websiteIssue->client) }}" class="text-blue-600 font-semibold hover:underline">{{ $websiteIssue->client->name }}</a>
                @if($websiteIssue->client->company_name)
                    <span class="text-gray-500">— {{ $websiteIssue->client->company_name }}</span>
                @endif
            </p>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-bold
                    @if($websiteIssue->status === 'open') bg-red-100 text-red-800
                    @elseif($websiteIssue->status === 'in_progress') bg-amber-100 text-amber-900
                    @elseif($websiteIssue->status === 'resolved') bg-green-100 text-green-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ $websiteIssue->status_label }}
                </span>
                @if($websiteIssue->assignee)
                    <span class="text-xs text-gray-600 bg-gray-100 px-2.5 py-1 rounded-lg">مسند إلى: {{ $websiteIssue->assignee->name }}</span>
                @endif
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('client-website-issues.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-sm font-bold text-gray-800 shadow-sm transition">
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
        {{-- المحتوى الرئيسي --}}
        <div class="xl:col-span-8 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-100">وصف المشكلة</h2>
                <div class="rounded-xl border border-gray-200 bg-gray-50/50 p-5 text-gray-800 leading-relaxed whitespace-pre-wrap text-sm sm:text-base">
                    {{ $websiteIssue->description }}
                </div>
                @if($websiteIssue->page_url)
                    <div class="mt-5 pt-5 border-t border-gray-100">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wide">رابط الصفحة</span>
                        <p class="mt-1">
                            <a href="{{ $websiteIssue->page_url }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline break-all text-sm font-medium">{{ $websiteIssue->page_url }}</a>
                        </p>
                    </div>
                @endif
            </div>

            @if($websiteIssue->resolution_message)
                <div class="bg-white rounded-xl shadow-sm border border-green-200 p-6 sm:p-8">
                    <h2 class="text-lg font-bold text-green-900 mb-3">رد يظهر للعميل</h2>
                    <p class="text-green-900 text-sm sm:text-base whitespace-pre-wrap leading-relaxed">{{ $websiteIssue->resolution_message }}</p>
                </div>
            @endif

            @if(!empty($websiteIssue->attachments))
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-100">الصور المرفقة</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($websiteIssue->attachments as $idx => $att)
                            <a href="{{ route('client-website-issues.file', [$websiteIssue, $idx]) }}" target="_blank" rel="noopener" class="group block">
                                <div class="rounded-xl border border-gray-200 overflow-hidden bg-gray-50 aspect-video flex items-center justify-center shadow-sm group-hover:border-blue-300 transition">
                                    <img src="{{ route('client-website-issues.file', [$websiteIssue, $idx]) }}" alt="" class="max-h-64 w-full object-contain">
                                </div>
                                @if(!empty($att['original']))
                                    <p class="text-xs text-gray-500 mt-2 truncate px-1">{{ $att['original'] }}</p>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- الشريط الجانبي — إدارة + معلومات --}}
        <div class="xl:col-span-4 space-y-6">
            @can('edit-tickets')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-1">إدارة البلاغ</h2>
                    <p class="text-xs text-gray-500 mb-5">تحديث الحالة والتعيين والرد للعميل.</p>

                    @if($errors->any())
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-red-800 text-xs space-y-1">
                            @foreach($errors->all() as $err)
                                <div>{{ $err }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('client-website-issues.update', $websiteIssue) }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                            <select name="status" id="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="open" @selected($websiteIssue->status==='open')>مفتوح</option>
                                <option value="in_progress" @selected($websiteIssue->status==='in_progress')>قيد المعالجة</option>
                                <option value="resolved" @selected($websiteIssue->status==='resolved')>تم الحل</option>
                                <option value="closed" @selected($websiteIssue->status==='closed')>مغلق</option>
                            </select>
                        </div>

                        <div>
                            <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">تعيين لموظف (اختياري)</label>
                            <select name="assigned_to" id="assigned_to" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">— بدون —</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" @selected($websiteIssue->assigned_to == $u->id)>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات داخلية</label>
                            <textarea name="admin_notes" id="admin_notes" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="لا تظهر للعميل">{{ old('admin_notes', $websiteIssue->admin_notes) }}</textarea>
                        </div>

                        <div>
                            <label for="resolution_message" class="block text-sm font-medium text-gray-700 mb-2">رد للعميل</label>
                            <textarea name="resolution_message" id="resolution_message" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="يظهر في بوابة العميل عند الحفظ">{{ old('resolution_message', $websiteIssue->resolution_message) }}</textarea>
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
                    ليس لديك صلاحية تعديل البلاغات — عرض فقط.
                </div>
            @endcan

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-bold text-gray-900 mb-4">معلومات</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-3 border-b border-gray-100 pb-3">
                        <dt class="text-gray-500">تاريخ الإنشاء</dt>
                        <dd class="font-semibold text-gray-900">{{ $websiteIssue->created_at->format('Y/m/d H:i') }}</dd>
                    </div>
                    <div class="flex justify-between gap-3 border-b border-gray-100 pb-3">
                        <dt class="text-gray-500">آخر تحديث</dt>
                        <dd class="font-semibold text-gray-900">{{ $websiteIssue->updated_at->format('Y/m/d H:i') }}</dd>
                    </div>
                    @if($websiteIssue->resolved_at)
                        <div class="flex justify-between gap-3 border-b border-gray-100 pb-3">
                            <dt class="text-gray-500">تاريخ الحل</dt>
                            <dd class="font-semibold text-gray-900">{{ $websiteIssue->resolved_at->format('Y/m/d H:i') }}</dd>
                        </div>
                        <div class="flex justify-between gap-3">
                            <dt class="text-gray-500">حُل بواسطة</dt>
                            <dd class="font-semibold text-gray-900">{{ $websiteIssue->resolver?->name ?? '—' }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
