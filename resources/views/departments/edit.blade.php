@extends('layouts.app')

@section('page-title', 'تعديل القسم')

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $modules = $department->effectiveSidebarModules();
@endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'تعديل القسم',
        'subtitle' => $department->name.' · '.$department->code,
        'icon' => 'briefcase',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6 -mt-2">
        <a href="{{ route('departments.show', $department) }}" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            عرض القسم
        </a>
        <a href="{{ route('departments.index') }}" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            كل الأقسام
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    @if($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <p class="font-bold mb-1">يرجى تصحيح الأخطاء:</p>
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8">
            @include('departments.partials.form', [
                'action' => route('departments.update', $department),
                'method' => 'PUT',
                'submitLabel' => 'حفظ التعديلات',
                'cancelUrl' => route('departments.show', $department),
                'department' => $department,
                'employees' => $employees,
                'parentDepartments' => $parentDepartments,
                'themeColor' => $themeColor,
            ])
        </div>
        <div class="xl:col-span-4 space-y-4">
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-12 w-12 rounded-xl flex items-center justify-center text-white font-bold text-lg" style="background: {{ $themeColor }};">
                        {{ mb_substr($department->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">{{ $department->name }}</p>
                        <p class="text-xs font-mono text-gray-500" dir="ltr">{{ $department->code }}</p>
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">الحالة</span>
                        <span class="font-semibold {{ $department->is_active ? 'text-green-700' : 'text-red-700' }}">
                            {{ $department->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </div>
                    @if($department->parent)
                    <div class="flex justify-between">
                        <span class="text-gray-500">القسم الرئيسي</span>
                        <span class="font-semibold text-gray-800">{{ $department->parent->name }}</span>
                    </div>
                    @endif
                    @if($department->default_position)
                    <div class="flex justify-between">
                        <span class="text-gray-500">المسمى الافتراضي</span>
                        <span class="font-semibold text-gray-800">{{ $department->default_position }}</span>
                    </div>
                    @endif
                    @if($department->kpi_profile)
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500 shrink-0">KPI</span>
                        <span class="font-semibold text-gray-800 text-left text-xs">
                            {{ (\App\Services\DepartmentProfileService::kpiProfileLabels())[$department->kpi_profile] ?? $department->kpi_profile }}
                        </span>
                    </div>
                    @endif
                </div>
                @if(count($modules) > 0)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs font-bold text-gray-500 mb-2">وحدات السايدبار</p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($modules as $module)
                            <span class="inline-flex px-2 py-0.5 rounded-lg text-xs bg-slate-100 text-slate-700">
                                {{ \App\Models\Department::SIDEBAR_MODULES[$module] ?? $module }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="rounded-2xl border border-amber-100 bg-amber-50/60 p-5 text-sm text-amber-900">
                <p class="font-bold mb-2">تنبيه</p>
                <p class="leading-relaxed">تغيير كود القسم أو وحدات السايدبار يؤثر على صلاحيات الموظفين المرتبطين بهذا القسم فور حفظ التعديلات.</p>
            </div>

            @can('delete-departments')
            <div class="bg-white border border-red-100 rounded-2xl p-5 shadow-sm">
                <p class="font-bold text-red-800 mb-2">منطقة الخطر</p>
                <p class="text-xs text-gray-600 mb-4">لا يمكن الحذف إذا وُجد موظفون أو مشاريع أو أقسام فرعية.</p>
                <form action="{{ route('departments.destroy', $department) }}" method="POST"
                      onsubmit="return confirm('هل أنت متأكد من حذف هذا القسم؟ لا يمكن التراجع.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-red-600 hover:bg-red-700">
                        حذف القسم
                    </button>
                </form>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection
