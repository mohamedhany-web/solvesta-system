@extends('layouts.app')

@section('page-title', 'ميزات وتحسينات النظام')

@section('content')
<div class="w-full max-w-full">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">ميزات وتحسينات النظام</h1>
            <p class="text-gray-600">اطلب ميزة جديدة أو أبلغ عن مشكلة — تُنظَّم طلباتك ضمن مشاريع نظامك مع سجل توثيقي لكل طلب.</p>
        </div>
        <a href="{{ route('client.system-features.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 shrink-0">+ طلب جديد</a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 text-sm font-medium">{{ session('success') }}</div>
    @endif

    @if($projects->isNotEmpty())
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-10">
        @foreach($projects as $project)
        <div class="bg-white border rounded-xl p-6 shadow-sm">
            <p class="text-xs font-mono text-gray-500">{{ $project->reference_code }}</p>
            <h2 class="text-lg font-bold text-gray-900 mt-1">{{ $project->name }}</h2>
            <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $project->description ?: '—' }}</p>
            <div class="flex flex-wrap gap-2 mt-4 text-xs">
                <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800 font-bold">{{ $project->features_count }} طلب</span>
                <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-700">{{ $project->status_label }}</span>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <div class="bg-white border rounded-xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b"><h2 class="font-bold text-lg">طلباتك الأخيرة</h2></div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right">المرجع</th>
                    <th class="px-4 py-3 text-right">المشروع</th>
                    <th class="px-4 py-3 text-right">العنوان</th>
                    <th class="px-4 py-3 text-right">النوع</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($recentFeatures as $f)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $f->reference_code }}</td>
                    <td class="px-4 py-3">{{ $f->project?->name }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $f->title }}</td>
                    <td class="px-4 py-3">{{ $f->type_label }}</td>
                    <td class="px-4 py-3"><span class="text-xs font-bold px-2 py-0.5 rounded bg-slate-100">{{ $f->status_label }}</span></td>
                    <td class="px-4 py-3"><a href="{{ route('client.system-features.show', $f) }}" class="text-blue-600 font-bold hover:underline">عرض</a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">لا توجد طلبات بعد. <a href="{{ route('client.system-features.create') }}" class="text-blue-600 font-bold">أرسل أول طلب</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
