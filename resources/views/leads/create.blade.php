@extends('layouts.app')

@section('page-title', 'Lead جديد')

@section('content')
<div class="w-full max-w-3xl">
    <h1 class="text-2xl font-bold mb-6">تسجيل Lead جديد</h1>
    <form method="POST" action="{{ route('leads.store') }}" class="bg-white border rounded-xl p-6 space-y-4">
        @csrf
        <div><label class="text-sm font-bold">الاسم *</label><input name="name" value="{{ old('name') }}" required class="w-full border rounded-lg px-3 py-2 mt-1"></div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="text-sm font-bold">البريد</label><input name="email" type="email" value="{{ old('email') }}" class="w-full border rounded-lg px-3 py-2 mt-1"></div>
            <div><label class="text-sm font-bold">الهاتف</label><input name="phone" value="{{ old('phone') }}" class="w-full border rounded-lg px-3 py-2 mt-1"></div>
        </div>
        <div><label class="text-sm font-bold">الشركة</label><input name="company" value="{{ old('company') }}" class="w-full border rounded-lg px-3 py-2 mt-1"></div>
        <div><label class="text-sm font-bold">الخدمة / الاهتمام</label><input name="service_interest" value="{{ old('service_interest') }}" placeholder="مثال: CRM" class="w-full border rounded-lg px-3 py-2 mt-1"></div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-bold">المصدر *</label>
                <select name="source" required class="w-full border rounded-lg px-3 py-2 mt-1">
                    @foreach(['bd_outreach'=>'تطوير أعمال','referral'=>'إحالة','ads'=>'إعلانات','social_media'=>'سوشيال','website'=>'موقع','event'=>'فعالية','other'=>'أخرى'] as $v=>$l)
                        <option value="{{ $v }}" @selected(old('source')===$v)>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div><label class="text-sm font-bold">ميزانية تقديرية</label><input name="estimated_budget" type="number" step="0.01" value="{{ old('estimated_budget') }}" class="w-full border rounded-lg px-3 py-2 mt-1"></div>
        </div>
        <div><label class="text-sm font-bold">ملاحظات</label><textarea name="notes" rows="4" class="w-full border rounded-lg px-3 py-2 mt-1">{{ old('notes') }}</textarea></div>
        <div>
            <label class="text-sm font-bold">مسند إلى</label>
            <select name="assigned_to" class="w-full border rounded-lg px-3 py-2 mt-1">
                <option value="">—</option>
                @foreach($users as $u)<option value="{{ $u->id }}" @selected(old('assigned_to')==$u->id)>{{ $u->name }}</option>@endforeach
            </select>
        </div>
        <button class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-bold">حفظ</button>
    </form>
</div>
@endsection
