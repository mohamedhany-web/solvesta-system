@extends('layouts.app')

@section('page-title', $project->name)

@section('content')
<div class="w-full">
    <div class="mb-6 flex flex-wrap justify-between gap-4">
        <div>
            <p class="text-sm font-mono text-gray-500">{{ $project->reference_code }}</p>
            <h1 class="text-3xl font-bold">{{ $project->name }}</h1>
            <p class="text-gray-600">عميل: <a href="{{ route('clients.show', $project->client) }}" class="text-blue-600 font-semibold">{{ $project->client->name }}</a></p>
        </div>
        <a href="{{ route('client-system-projects.index') }}" class="border px-4 py-2 rounded-xl font-semibold">القائمة</a>
    </div>

    @if(session('success'))<div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>@endif

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8 space-y-6">
            <div class="bg-white border rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h2 class="font-bold text-lg">الميزات والطلبات ({{ $project->features->count() }})</h2>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right">المرجع</th>
                            <th class="px-4 py-3 text-right">العنوان</th>
                            <th class="px-4 py-3 text-right">النوع</th>
                            <th class="px-4 py-3 text-right">الحالة</th>
                            <th class="px-4 py-3 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($project->features as $f)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-mono text-xs">{{ $f->reference_code }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $f->title }}</td>
                            <td class="px-4 py-3">{{ $f->type_label }}</td>
                            <td class="px-4 py-3">{{ $f->status_label }}</td>
                            <td class="px-4 py-3"><a href="{{ route('client-system-features.show', $f) }}" class="text-blue-600 font-bold">إدارة + توثيق</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">لا توجد طلبات في هذا المشروع.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @can('edit-tickets')
        <div class="xl:col-span-4">
            <form method="POST" action="{{ route('client-system-projects.update', $project) }}" class="bg-white border rounded-xl p-6 space-y-4">
                @csrf @method('PUT')
                <h2 class="font-bold">إعدادات المشروع</h2>
                <div>
                    <label class="text-xs font-bold text-gray-600">الاسم</label>
                    <input name="name" value="{{ $project->name }}" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600">الوصف</label>
                    <textarea name="description" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm">{{ $project->description }}</textarea>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600">الحالة</label>
                    <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm">
                        @foreach(['active','on_hold','completed','archived'] as $st)
                            <option value="{{ $st }}" @selected($project->status===$st)>{{ match($st){'active'=>'نشط','on_hold'=>'متوقف','completed'=>'مكتمل','archived'=>'مؤرشف',default=>$st} }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600">مسند إلى</label>
                    <select name="assigned_to" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">—</option>
                        @foreach($users as $u)<option value="{{ $u->id }}" @selected($project->assigned_to==$u->id)>{{ $u->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600">ملاحظات داخلية</label>
                    <textarea name="admin_notes" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm">{{ $project->admin_notes }}</textarea>
                </div>
                <button class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-bold">حفظ</button>
            </form>
        </div>
        @endcan
    </div>
</div>
@endsection
