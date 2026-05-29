@extends('layouts.app')

@section('page-title', 'تذاكر الدعم الفني')

@section('content')
<div class="w-full">
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">تذاكر الدعم الفني</h1>
                <p class="text-gray-600">منظّمة حسب المشروع — كل مشروع وتذاكره الداعمة</p>
            </div>
            @can('create-tickets')
            <a href="{{ route('tickets.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition flex items-center justify-center shadow-sm font-bold">
                <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                تذكرة جديدة
            </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 mb-1">إجمالي التذاكر</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 mb-1">مفتوحة</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $stats['open'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 mb-1">محلولة / مغلقة</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['closed'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm text-gray-600 mb-1">أولوية عالية</p>
            <p class="text-3xl font-bold text-red-600">{{ $stats['high_priority'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('tickets.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث…" class="px-3 py-2 border border-gray-300 rounded-lg text-sm lg:col-span-2">
            <select name="client_id" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">كل العملاء</option>
                @foreach($clients as $c)
                    <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            <select name="project_id" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">كل المشاريع</option>
                @foreach($allProjects as $p)
                    <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }} @if($p->client) ({{ $p->client->name }}) @endif</option>
                @endforeach
            </select>
            <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">كل الحالات</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>مفتوح</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                <option value="pending_client" {{ request('status') == 'pending_client' ? 'selected' : '' }}>انتظار العميل</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>محلول</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>مغلق</option>
            </select>
            <div class="flex gap-2">
                <select name="priority" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="">الأولوية</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>منخفضة</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>متوسطة</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>عالية</option>
                    <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>حرجة</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold">تصفية</button>
            </div>
        </form>
        @if(request()->hasAny(['search','status','priority','client_id','project_id']))
            <div class="mt-3">
                <a href="{{ route('tickets.index') }}" class="text-sm text-blue-600 font-semibold hover:underline">إلغاء التصفية</a>
            </div>
        @endif
    </div>

    @php
        $statusLabels = ['planning'=>'تخطيط','active'=>'نشط','on_hold'=>'معلق','completed'=>'مكتمل','cancelled'=>'ملغى'];
    @endphp

    @forelse($projects as $project)
        @php
            $openCount = $project->tickets->whereIn('status', ['open','in_progress','pending_client'])->count();
        @endphp
        <details class="mb-4 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden group" open>
            <summary class="cursor-pointer list-none px-5 py-4 bg-gradient-to-l from-slate-50 to-white border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="h-11 w-11 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold shrink-0">
                        {{ mb_substr($project->name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-lg font-bold text-gray-900 truncate">{{ $project->name }}</h2>
                        <p class="text-sm text-gray-500">
                            العميل: <span class="font-semibold text-gray-700">{{ $project->client?->name ?? '—' }}</span>
                            @if($project->projectManager)
                                · مدير المشروع: {{ $project->projectManager->name }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 text-sm">
                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 font-semibold">
                        {{ $statusLabels[$project->status] ?? $project->status }}
                    </span>
                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 font-semibold">
                        {{ $project->tickets->count() }} تذكرة
                    </span>
                    @if($openCount > 0)
                    <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 font-semibold">
                        {{ $openCount }} مفتوحة
                    </span>
                    @endif
                    <a href="{{ route('projects.show', $project) }}" class="px-3 py-1 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 font-semibold" onclick="event.stopPropagation()">المشروع</a>
                </div>
            </summary>
            <div class="p-2">
                @include('tickets._table', ['tickets' => $project->tickets])
            </div>
        </details>
    @empty
        <div class="mb-4 bg-white rounded-xl border border-dashed border-gray-300 p-8 text-center text-gray-500">
            لا توجد مشاريع بتذاكر مطابقة للتصفية الحالية.
        </div>
    @endforelse

    @if($unassignedTickets->isNotEmpty())
        <details class="mb-4 bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden" open>
            <summary class="cursor-pointer list-none px-5 py-4 bg-amber-50 border-b border-amber-100 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">تذاكر غير مرتبطة بمشروع</h2>
                    <p class="text-sm text-gray-600">عامة أو لم يُحدَّد لها مشروع بعد</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-amber-200 text-amber-900 font-semibold text-sm">{{ $unassignedTickets->count() }} تذكرة</span>
            </summary>
            <div class="p-2">
                @include('tickets._table', ['tickets' => $unassignedTickets])
            </div>
        </details>
    @endif

    @if($projects->isEmpty() && $unassignedTickets->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center text-gray-500">
            <p class="text-lg font-bold mb-2">لا توجد تذاكر</p>
            <p class="text-sm mb-4">أنشئ تذكرة جديدة واربطها بمشروع العميل لتنظيم الدعم.</p>
            @can('create-tickets')
            <a href="{{ route('tickets.create') }}" class="inline-flex bg-blue-600 text-white px-6 py-2 rounded-lg font-bold">تذكرة جديدة</a>
            @endcan
        </div>
    @endif
</div>
@endsection
