@extends('layouts.app')

@section('page-title', $feature->title)

@section('content')
<div class="w-full max-w-4xl mx-auto">
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-sm font-mono text-gray-500">{{ $feature->reference_code }}</p>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $feature->title }}</h1>
            <p class="text-sm text-gray-600 mt-1">المشروع: <strong>{{ $feature->project->name }}</strong> — {{ $feature->type_label }}</p>
        </div>
        <span class="px-3 py-1.5 rounded-full text-sm font-bold bg-blue-100 text-blue-900">{{ $feature->status_label }}</span>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border rounded-xl p-6">
                <h2 class="font-bold mb-3">وصف الطلب</h2>
                <p class="text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $feature->description }}</p>
            </div>

            <div class="bg-white border rounded-xl p-6">
                <h2 class="font-bold mb-4 flex items-center gap-2">
                    <span>سجل التوثيق والمتابعة</span>
                    <span class="text-xs font-normal text-gray-500">({{ $feature->clientVisibleUpdates->count() }})</span>
                </h2>
                @forelse($feature->clientVisibleUpdates as $update)
                <article class="border-r-4 border-blue-500 pr-4 mb-6 last:mb-0 pb-6 last:pb-0 border-b last:border-b-0 border-gray-100">
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                        <h3 class="font-bold text-gray-900">{{ $update->title }}</h3>
                        <time class="text-xs text-gray-500">{{ $update->created_at->format('Y/m/d H:i') }}</time>
                    </div>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed">{{ $update->body }}</p>
                    @if($update->author)
                        <p class="text-xs text-gray-500 mt-2">— فريق {{ \App\Helpers\SettingsHelper::getCompanyName() }}</p>
                    @endif
                </article>
                @empty
                <p class="text-gray-500 text-sm">لم يُضف توثيق بعد. سيظهر هنا شرح ما تم إنجازه أو معالجته لكل طلب.</p>
                @endforelse
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white border rounded-xl p-5 text-sm space-y-2">
                <div class="flex justify-between"><span class="text-gray-500">الأولوية</span><span class="font-bold">{{ $feature->priority_label }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">تاريخ الإرسال</span><span>{{ $feature->created_at->format('Y/m/d') }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">آخر تحديث</span><span>{{ $feature->updated_at->format('Y/m/d') }}</span></div>
            </div>
            <a href="{{ route('client.system-features.index') }}" class="block text-center border rounded-xl py-2.5 font-semibold text-gray-700 hover:bg-gray-50">كل الطلبات</a>
            <a href="{{ route('client.system-features.create') }}" class="block text-center bg-blue-600 text-white rounded-xl py-2.5 font-bold hover:bg-blue-700">طلب جديد</a>
        </div>
    </div>
</div>
@endsection
