@extends('layouts.app')

@section('page-title', 'مشروع نظام جديد')

@section('content')
<div class="w-full max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">إنشاء مشروع نظام للعميل</h1>
    <form method="POST" action="{{ route('client-system-projects.store') }}" class="bg-white border rounded-xl p-6 space-y-4">
        @csrf
        <div>
            <label class="text-sm font-bold">العميل *</label>
            <select name="client_id" required class="w-full border rounded-xl px-4 py-2.5 mt-1">
                @foreach($clients as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
            </select>
        </div>
        <div>
            <label class="text-sm font-bold">اسم المشروع / النظام *</label>
            <input name="name" required class="w-full border rounded-xl px-4 py-2.5 mt-1" placeholder="مثال: نظام إدارة المخزون">
        </div>
        <div>
            <label class="text-sm font-bold">الوصف</label>
            <textarea name="description" rows="3" class="w-full border rounded-xl px-4 py-2.5 mt-1"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-bold">الحالة</label>
                <select name="status" class="w-full border rounded-xl px-4 py-2.5 mt-1">
                    <option value="active">نشط</option>
                    <option value="on_hold">متوقف</option>
                    <option value="completed">مكتمل</option>
                    <option value="archived">مؤرشف</option>
                </select>
            </div>
            <div>
                <label class="text-sm font-bold">مسند إلى</label>
                <select name="assigned_to" class="w-full border rounded-xl px-4 py-2.5 mt-1">
                    <option value="">—</option>
                    @foreach($users as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach
                </select>
            </div>
        </div>
        <div>
            <label class="text-sm font-bold">ملاحظات داخلية</label>
            <textarea name="admin_notes" rows="2" class="w-full border rounded-xl px-4 py-2.5 mt-1"></textarea>
        </div>
        <button class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold">حفظ</button>
    </form>
</div>
@endsection
