@extends('layouts.app')

@section('page-title', 'طلب ميزة أو إبلاغ عن مشكلة')

@section('content')
<div class="w-full max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('client.system-features.index') }}" class="text-sm text-blue-600 font-semibold hover:underline">← العودة</a>
        <h1 class="text-2xl font-bold mt-2">طلب ميزة / خطأ / تحسين</h1>
        <p class="text-gray-600 text-sm">يُصنَّف الطلب ضمن مشروع نظامك. يمكنك اختيار مشروع موجود أو إنشاء مشروع جديد باسم واضح (مثل: متجر إلكتروني، تطبيق موبايل).</p>
    </div>

    <form method="POST" action="{{ route('client.system-features.store') }}" class="bg-white border rounded-xl p-6 space-y-5 shadow-sm">
        @csrf
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 text-sm">
                <ul class="list-disc pr-5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <div>
            <label class="block text-sm font-bold mb-2">المشروع (نظامك)</label>
            @if($projects->isNotEmpty())
            <select name="project_id" id="project_id" class="w-full border rounded-xl px-4 py-2.5 mb-2">
                <option value="">— مشروع جديد (أدخل الاسم أدناه) —</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}" @selected(old('project_id') == $p->id)>{{ $p->name }} ({{ $p->reference_code }})</option>
                @endforeach
            </select>
            @endif
            <input type="text" name="new_project_name" value="{{ old('new_project_name') }}" class="w-full border rounded-xl px-4 py-2.5" placeholder="اسم مشروع جديد (إن لم تختر من القائمة)">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold mb-1">نوع الطلب *</label>
                <select name="type" required class="w-full border rounded-xl px-4 py-2.5">
                    <option value="feature" @selected(old('type') === 'feature')>ميزة جديدة</option>
                    <option value="bug" @selected(old('type') === 'bug')>خطأ / مشكلة</option>
                    <option value="improvement" @selected(old('type') === 'improvement')>تحسين</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1">الأولوية</label>
                <select name="priority" class="w-full border rounded-xl px-4 py-2.5">
                    @foreach(['low'=>'منخفضة','medium'=>'متوسطة','high'=>'عالية','urgent'=>'عاجلة'] as $v=>$l)
                        <option value="{{ $v }}" @selected(old('priority','medium')===$v)>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold mb-1">العنوان *</label>
            <input type="text" name="title" value="{{ old('title') }}" required maxlength="255" class="w-full border rounded-xl px-4 py-2.5" placeholder="مثال: إضافة تقرير مبيعات شهري">
        </div>

        <div>
            <label class="block text-sm font-bold mb-1">التفاصيل *</label>
            <textarea name="description" rows="6" required class="w-full border rounded-xl px-4 py-2.5" placeholder="اشرح المطلوب أو المشكلة بالتفصيل — الخطوات، الصفحة المتأثرة، السلوك المتوقع...">{{ old('description') }}</textarea>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700">إرسال الطلب</button>
    </form>
</div>
@endsection
