@extends('layouts.app')

@section('page-title', 'تقرير يومي')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full px-2 sm:px-0">
    @include('partials.erp-page-header', [
        'title' => 'تقرير يومي',
        'subtitle' => now()->locale('ar')->translatedFormat('l، d F Y'),
        'icon' => 'doc',
    ])

    <div class="max-w-2xl">
        <form method="POST" action="{{ route('daily-reports.store') }}" class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 space-y-5 font-tajawal">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">التاريخ</label>
                <input type="date" name="report_date" value="{{ old('report_date', today()->format('Y-m-d')) }}" max="{{ today()->format('Y-m-d') }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">المشروع</label>
                <select name="project_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm">
                    <option value="">— عام / بدون مشروع —</option>
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" @selected(old('project_id', $existing?->project_id) == $p->id)>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">المهمة (اختياري)</label>
                <select name="task_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm">
                    <option value="">—</option>
                    @foreach($tasks as $t)
                        <option value="{{ $t->id }}">{{ $t->project?->name }} — {{ $t->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">ماذا أنجزت اليوم؟ *</label>
                <textarea name="work_summary" rows="5" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm"
                          placeholder="وصف العمل، الميزات، الأخطاء التي عالجتها...">{{ old('work_summary', $existing?->work_summary) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">ساعات العمل *</label>
                <input type="number" step="0.25" min="0.25" max="24" name="hours_worked" required
                       value="{{ old('hours_worked', $existing?->hours_worked ?? 8) }}"
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm">
            </div>
            <div class="p-4 rounded-xl border border-amber-100 bg-amber-50/50">
                <label class="flex items-center gap-2 text-sm font-bold text-amber-900">
                    <input type="checkbox" name="has_blocker" value="1" class="rounded" @checked(old('has_blocker', $existing?->has_blocker))>
                    يوجد Blocker يعيق العمل
                </label>
                <textarea name="blocker_description" rows="2" class="w-full mt-2 border border-gray-200 rounded-xl px-3 py-2 text-sm"
                          placeholder="صف العائق...">{{ old('blocker_description', $existing?->blocker_description) }}</textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-2.5 rounded-xl text-white font-bold"
                        style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">حفظ التقرير</button>
                <a href="{{ route('daily-reports.index') }}" class="px-6 py-2.5 rounded-xl border font-bold text-sm">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
