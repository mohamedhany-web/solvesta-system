@extends('layouts.app')

@section('page-title', 'مشاريع أنظمة العملاء')

@section('content')
<div class="w-full">
    <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold">مشاريع أنظمة العملاء</h1>
            <p class="text-gray-600 text-sm">كل مشروع يجمع ميزات وطلبات التطوير والأخطاء المرفوعة من بوابة العميل</p>
        </div>
        @can('edit-tickets')
        <a href="{{ route('client-system-projects.create') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold">+ مشروع جديد</a>
        @endcan
    </div>

    <form method="GET" class="bg-white border rounded-xl p-4 mb-6 flex flex-wrap gap-3 items-end">
        <div><label class="text-xs font-bold text-gray-600">بحث</label><input name="search" value="{{ request('search') }}" class="border rounded-lg px-3 py-2 text-sm block"></div>
        <div><label class="text-xs font-bold text-gray-600">العميل</label>
            <select name="client_id" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">الكل</option>
                @foreach($clients as $c)<option value="{{ $c->id }}" @selected(request('client_id')==$c->id)>{{ $c->name }}</option>@endforeach
            </select>
        </div>
        <div><label class="text-xs font-bold text-gray-600">الحالة</label>
            <select name="status" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">الكل</option>
                @foreach(['active','on_hold','completed','archived'] as $st)
                    <option value="{{ $st }}" @selected(request('status')===$st)>{{ match($st){'active'=>'نشط','on_hold'=>'متوقف','completed'=>'مكتمل','archived'=>'مؤرشف',default=>$st} }}</option>
                @endforeach
            </select>
        </div>
        <button class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-bold">تصفية</button>
    </form>

    <div class="bg-white border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right">المرجع</th>
                    <th class="px-4 py-3 text-right">المشروع</th>
                    <th class="px-4 py-3 text-right">العميل</th>
                    <th class="px-4 py-3 text-right">الطلبات</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($projects as $project)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $project->reference_code }}</td>
                    <td class="px-4 py-3 font-bold">{{ $project->name }}</td>
                    <td class="px-4 py-3"><a href="{{ route('clients.show', $project->client) }}" class="text-blue-600 hover:underline">{{ $project->client->name }}</a></td>
                    <td class="px-4 py-3">{{ $project->features_count }}</td>
                    <td class="px-4 py-3">{{ $project->status_label }}</td>
                    <td class="px-4 py-3"><a href="{{ route('client-system-projects.show', $project) }}" class="text-blue-600 font-bold">عرض</a></td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">لا توجد مشاريع بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $projects->links() }}</div>
    </div>
</div>
@endsection
