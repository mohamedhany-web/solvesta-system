@extends('layouts.app')

@section('page-title', $feature->reference_code)

@section('content')
<div class="w-full max-w-full">
    <div class="mb-6">
        <p class="text-sm font-mono text-gray-500">{{ $feature->reference_code }}</p>
        <h1 class="text-2xl font-bold">{{ $feature->title }}</h1>
        <p class="text-sm text-gray-600 mt-1">
            مشروع: <a href="{{ route('client-system-projects.show', $feature->project) }}" class="text-blue-600 font-semibold">{{ $feature->project->name }}</a>
            — عميل: <a href="{{ route('clients.show', $feature->project->client) }}" class="text-blue-600">{{ $feature->project->client->name }}</a>
        </p>
    </div>

    @if(session('success'))<div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>@endif

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-7 space-y-6">
            <div class="bg-white border rounded-xl p-6">
                <h2 class="font-bold mb-3">وصف العميل</h2>
                <p class="whitespace-pre-wrap text-gray-800 text-sm leading-relaxed">{{ $feature->description }}</p>
                <p class="text-xs text-gray-500 mt-4">النوع: {{ $feature->type_label }} — الأولوية: {{ $feature->priority_label }} — أُرسل: {{ $feature->created_at->format('Y/m/d H:i') }}</p>
            </div>

            <div class="bg-white border rounded-xl p-6">
                <h2 class="font-bold mb-4">سجل التوثيق والتحديثات</h2>
                @forelse($feature->updates as $update)
                <div class="mb-5 pb-5 border-b last:border-0 {{ $update->visibility === 'internal' ? 'bg-amber-50/50 -mx-2 px-2 rounded-lg' : '' }}">
                    <div class="flex flex-wrap justify-between gap-2 mb-1">
                        <strong>{{ $update->title }}</strong>
                        <span class="text-xs {{ $update->visibility === 'client' ? 'text-green-700' : 'text-amber-700' }}">{{ $update->visibility_label }}</span>
                    </div>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $update->body }}</p>
                    <p class="text-xs text-gray-500 mt-2">{{ $update->created_at->format('Y/m/d H:i') }} — {{ $update->author?->name ?? '—' }}</p>
                    @can('edit-tickets')
                    @if($update->visibility === 'internal')
                    <form method="POST" action="{{ route('client-system-feature-updates.destroy', $update) }}" class="mt-2" onsubmit="return confirm('حذف؟')">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-600 font-bold">حذف</button>
                    </form>
                    @endif
                    @endcan
                </div>
                @empty
                <p class="text-gray-500 text-sm">لا توجد تحديثات. أضف توثيقاً يشرح للعميل ما تم إنجازه أو معالجته.</p>
                @endforelse
            </div>
        </div>

        @can('edit-tickets')
        <div class="xl:col-span-5 space-y-6">
            <form method="POST" action="{{ route('client-system-features.update', $feature) }}" class="bg-white border rounded-xl p-6 space-y-3">
                @csrf @method('PUT')
                <h2 class="font-bold">حالة الطلب</h2>
                <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @foreach(['submitted','reviewing','approved','in_progress','testing','completed','rejected','cancelled'] as $st)
                        <option value="{{ $st }}" @selected($feature->status===$st)>{{ (new \App\Models\ClientSystemFeature(['status'=>$st]))->status_label }}</option>
                    @endforeach
                </select>
                <select name="priority" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @foreach(['low','medium','high','urgent'] as $p)
                        <option value="{{ $p }}" @selected($feature->priority===$p)>{{ (new \App\Models\ClientSystemFeature(['priority'=>$p]))->priority_label }}</option>
                    @endforeach
                </select>
                <select name="assigned_to" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">مسند إلى —</option>
                    @foreach($users as $u)<option value="{{ $u->id }}" @selected($feature->assigned_to==$u->id)>{{ $u->name }}</option>@endforeach
                </select>
                <button class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-bold">حفظ الحالة</button>
            </form>

            <form method="POST" action="{{ route('client-system-features.updates.store', $feature) }}" class="bg-white border-2 border-blue-200 rounded-xl p-6 space-y-3">
                @csrf
                <h2 class="font-bold text-blue-900">إضافة توثيق / شرح</h2>
                <p class="text-xs text-gray-600">اكتب ما حدث للميزة أو الخطأ — يظهر للعميل إذا اخترت «يظهر للعميل».</p>
                <input name="title" required placeholder="عنوان مختصر (مثال: تم إصلاح الخطأ في صفحة التقارير)" class="w-full border rounded-lg px-3 py-2 text-sm">
                <textarea name="body" required rows="5" placeholder="شرح تفصيلي: ماذا كان الخطأ، ماذا فعلتم، كيف يختبر العميل..." class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
                <select name="visibility" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="client">يظهر للعميل — توثيق رسمي</option>
                    <option value="internal">داخلي — لا يظهر للعميل</option>
                </select>
                <button class="w-full bg-emerald-600 text-white py-2.5 rounded-lg font-bold">نشر التحديث</button>
            </form>

            <a href="{{ route('client-system-projects.show', $feature->project) }}" class="block text-center text-sm text-blue-600 font-semibold">← العودة للمشروع</a>
        </div>
        @endcan
    </div>
</div>
@endsection
