@extends('layouts.app')

@section('page-title', $project->name)

@section('content')
@php
    $statusColors = [
        'submitted' => 'bg-slate-100 text-slate-800',
        'reviewing' => 'bg-amber-100 text-amber-900',
        'approved' => 'bg-blue-100 text-blue-900',
        'in_progress' => 'bg-indigo-100 text-indigo-900',
        'testing' => 'bg-violet-100 text-violet-900',
        'completed' => 'bg-emerald-100 text-emerald-900',
        'rejected' => 'bg-red-100 text-red-900',
        'cancelled' => 'bg-gray-100 text-gray-600',
    ];
@endphp
<div class="w-full max-w-full">
    <div class="mb-6 flex flex-wrap justify-between gap-4">
        <div>
            <p class="text-sm font-mono text-gray-500">{{ $project->reference_code }}</p>
            <h1 class="text-3xl font-bold text-gray-900">{{ $project->name }}</h1>
            <p class="text-gray-600 mt-1">
                عميل:
                <a href="{{ route('clients.show', $project->client) }}" class="text-blue-600 font-semibold hover:underline">{{ $project->client->name }}</a>
            </p>
            @if($project->description)
                <p class="text-sm text-gray-500 mt-2 max-w-2xl">{{ $project->description }}</p>
            @endif
        </div>
        <a href="{{ route('client-system-projects.index') }}" class="shrink-0 border border-gray-300 px-4 py-2 rounded-xl font-semibold text-sm hover:bg-gray-50">← كل المشاريع</a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <div class="mb-4 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-900">
        <strong>الخطوة التالية:</strong> غيّر <strong>حالة الطلب</strong> من القائمة أدناه (تصل للعميل تلقائياً)، أو اضغط <strong>توثيق كامل</strong> لإضافة شرح مفصل يظهر في بوابة العميل.
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8 space-y-6">
            <div class="bg-white border rounded-xl overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b flex flex-wrap justify-between items-center gap-2">
                    <h2 class="font-bold text-lg">الميزات والتحسينات المطلوبة ({{ $project->features->count() }})</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">المرجع</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">العنوان</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">النوع</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">الأولوية</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">الحالة</th>
                                @can('edit-tickets')
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">تحديث سريع</th>
                                @endcan
                                <th class="px-4 py-3 text-right font-semibold text-gray-600"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($project->features as $f)
                            <tr class="hover:bg-gray-50 align-top">
                                <td class="px-4 py-3 font-mono text-xs text-gray-500 whitespace-nowrap">{{ $f->reference_code }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-900 max-w-xs">
                                    {{ $f->title }}
                                    <p class="text-xs text-gray-500 font-normal mt-1 line-clamp-2">{{ Str::limit($f->description, 80) }}</p>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $f->type_label }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ $f->priority_label }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="text-xs font-bold px-2 py-1 rounded-full {{ $statusColors[$f->status] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $f->status_label }}
                                    </span>
                                </td>
                                @can('edit-tickets')
                                <td class="px-4 py-3 min-w-[11rem]">
                                    <form method="POST" action="{{ route('client-system-features.update', $f) }}" class="flex flex-col gap-1.5">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="priority" value="{{ $f->priority }}">
                                        <input type="hidden" name="assigned_to" value="{{ $f->assigned_to ?? '' }}">
                                        <input type="hidden" name="redirect_to" value="{{ route('client-system-projects.show', $project) }}">
                                        <select name="status" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-xs focus:ring-2 focus:ring-blue-500">
                                            @foreach(['submitted','reviewing','approved','in_progress','testing','completed','rejected','cancelled'] as $st)
                                                <option value="{{ $st }}" @selected($f->status === $st)>{{ (new \App\Models\ClientSystemFeature(['status' => $st]))->status_label }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="w-full bg-gray-900 text-white text-xs font-bold py-1.5 rounded-lg hover:bg-gray-800">حفظ الحالة</button>
                                    </form>
                                </td>
                                @endcan
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <a href="{{ route('client-system-features.show', $f) }}" class="text-blue-600 font-bold hover:underline text-xs">توثيق كامل ←</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ auth()->user()?->can('edit-tickets') ? 7 : 6 }}" class="px-4 py-10 text-center text-gray-500">
                                    لا توجد طلبات في هذا المشروع بعد.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @can('edit-tickets')
        <div class="xl:col-span-4">
            <form method="POST" action="{{ route('client-system-projects.update', $project) }}" class="bg-white border rounded-xl p-6 space-y-4 shadow-sm">
                @csrf
                @method('PUT')
                <h2 class="font-bold text-gray-900">إعدادات المشروع</h2>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1">الاسم</label>
                    <input name="name" value="{{ $project->name }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1">الوصف</label>
                    <textarea name="description" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm">{{ $project->description }}</textarea>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1">حالة المشروع</label>
                    <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm">
                        @foreach(['active','on_hold','completed','archived'] as $st)
                            <option value="{{ $st }}" @selected($project->status === $st)>{{ match($st) {
                                'active' => 'نشط',
                                'on_hold' => 'متوقف',
                                'completed' => 'مكتمل',
                                'archived' => 'مؤرشف',
                                default => $st,
                            } }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1">مسند إلى</label>
                    <select name="assigned_to" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">—</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" @selected($project->assigned_to == $u->id)>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1">ملاحظات داخلية</label>
                    <textarea name="admin_notes" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ $project->admin_notes }}</textarea>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-bold hover:bg-blue-700">حفظ إعدادات المشروع</button>
            </form>
        </div>
        @endcan
    </div>
</div>
@endsection
