@extends('layouts.app')

@section('page-title', 'ميزات وتحسينات العملاء')

@section('content')
<div class="w-full max-w-full">
    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">ميزات وتحسينات العملاء</h1>
            <p class="text-gray-600 text-sm mt-2 max-w-2xl leading-relaxed">
                اختر <strong>مشروع العميل</strong> لعرض الميزات والتحسينات المطلوبة، ثم حدّث الحالة وأضف توثيقاً يظهر للعميل.
            </p>
        </div>
        @can('edit-tickets')
        <a href="{{ route('client-system-projects.create') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold shrink-0">+ مشروع جديد</a>
        @endcan
    </div>

    <div class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
        <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3">
            <span class="font-bold text-blue-900">١</span>
            <span class="text-blue-800 mr-1">اختر المشروع من الجدول</span>
        </div>
        <div class="rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-3">
            <span class="font-bold text-indigo-900">٢</span>
            <span class="text-indigo-800 mr-1">شاهد الطلبات داخل المشروع</span>
        </div>
        <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3">
            <span class="font-bold text-emerald-900">٣</span>
            <span class="text-emerald-800 mr-1">غيّر الحالة ووثّق للعميل</span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <form method="GET" class="bg-white border rounded-xl p-4 mb-6 flex flex-wrap gap-3 items-end">
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">بحث</label>
            <input name="search" value="{{ request('search') }}" placeholder="اسم المشروع أو المرجع..." class="border rounded-lg px-3 py-2 text-sm min-w-[12rem]">
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">العميل</label>
            <select name="client_id" class="border rounded-lg px-3 py-2 text-sm min-w-[10rem]">
                <option value="">الكل</option>
                @foreach($clients as $c)
                    <option value="{{ $c->id }}" @selected(request('client_id') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">حالة المشروع</label>
            <select name="status" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">الكل</option>
                @foreach(['active','on_hold','completed','archived'] as $st)
                    <option value="{{ $st }}" @selected(request('status') === $st)>{{ match($st) {
                        'active' => 'نشط',
                        'on_hold' => 'متوقف',
                        'completed' => 'مكتمل',
                        'archived' => 'مؤرشف',
                        default => $st,
                    } }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-bold">تصفية</button>
        <a href="{{ route('client-system-projects.index') }}" class="border border-gray-300 px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50">إعادة تعيين</a>
    </form>

    <div class="bg-white border rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">المرجع</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">مشروع العميل</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">العميل</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">الطلبات</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">قيد المتابعة</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">حالة المشروع</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($projects as $project)
                <tr class="hover:bg-blue-50/40 transition-colors">
                    <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $project->reference_code }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('client-system-projects.show', $project) }}" class="font-bold text-gray-900 hover:text-blue-700">{{ $project->name }}</a>
                        @if($project->description)
                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $project->description }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('clients.show', $project->client) }}" class="text-blue-600 hover:underline">{{ $project->client->name }}</a>
                    </td>
                    <td class="px-4 py-3 font-semibold">{{ $project->features_count }}</td>
                    <td class="px-4 py-3">
                        @if($project->open_features_count > 0)
                            <span class="inline-flex items-center rounded-full bg-amber-100 text-amber-900 px-2.5 py-0.5 text-xs font-bold">{{ $project->open_features_count }} مفتوح</span>
                        @else
                            <span class="text-gray-400 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $project->status_label }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('client-system-projects.show', $project) }}" class="inline-flex items-center gap-1 bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-blue-700">
                            فتح المشروع
                            <svg class="w-3.5 h-3.5 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-gray-500">
                        لا توجد مشاريع بعد. تُنشأ تلقائياً عندما يطلب العميل ميزة أو تحسيناً من بوابته.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">{{ $projects->links() }}</div>
    </div>
</div>
@endsection
