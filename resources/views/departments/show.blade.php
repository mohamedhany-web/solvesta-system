@extends('layouts.app')

@section('page-title', 'تفاصيل القسم - '.$department->name)

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $modules = $department->effectiveSidebarModules();
    $deptHead = $department->manager?->user;
@endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => $department->name,
        'subtitle' => $department->code.($department->parent ? ' · تابع لـ '.$department->parent->name : ''),
        'icon' => 'briefcase',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6 -mt-2">
        @can('edit-departments')
        <a href="{{ route('departments.edit', $department) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm" style="background: {{ $themeColor }}">
            تعديل إعدادات القسم
        </a>
        @endcan
        @if($deptHead)
        <a href="{{ route('department-manager.dashboard') }}" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            لوحة رئيس القسم
        </a>
        @endif
        <a href="{{ route('departments.index') }}" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
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
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-6">
        @foreach([
            ['الموظفون', $stats['total_employees'], 'text-blue-700'],
            ['قادة الفرق', $stats['team_leads'], 'text-emerald-700'],
            ['مشاريع نشطة', $stats['active_projects'], 'text-indigo-700'],
            ['بانتظار فريق', $stats['pending_team_projects'], 'text-amber-700'],
            ['مهام معلقة', $stats['pending_tasks'], 'text-orange-700'],
        ] as [$label, $value, $color])
        <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm text-center">
            <p class="text-xs text-gray-500 mb-1">{{ $label }}</p>
            <p class="text-2xl font-bold {{ $color }}">{{ $value }}</p>
        </div>
        @endforeach
    </div>

    @can('edit-departments')
    <div class="rounded-2xl border border-indigo-100 bg-indigo-50/60 p-5 mb-6 text-sm text-indigo-900">
        <p class="font-bold mb-1">إعداد القسم للإدارة</p>
        <p>من هنا تُضاف الموظفون، يُعيَّن <strong>رئيس القسم</strong>، ويُبنى <strong>هيكل الفريق</strong> وفِرق المشاريع. رئيس القسم يستلم كل شيء جاهزاً ويبدأ بتوزيع المهام من لوحته.</p>
    </div>
    @endcan

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8 space-y-6">

            {{-- هيكل الفريق --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="font-bold text-lg text-gray-900">هيكل الفريق والتدرج الوظيفي</h2>
                        <p class="text-sm text-gray-500 mt-0.5">موظف → Team Lead → رئيس القسم → الإدارة العليا</p>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700">{{ $department->employees->count() }} موظف</span>
                </div>

                @if($department->employees->isEmpty())
                <div class="p-10 text-center text-gray-500">لا يوجد موظفون — أضف موظفين من اللوحة الجانبية.</div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50/80">
                            <tr>
                                <th class="px-4 py-3 text-right font-bold text-gray-600">الموظف</th>
                                <th class="px-4 py-3 text-right font-bold text-gray-600">المسمى</th>
                                <th class="px-4 py-3 text-right font-bold text-gray-600">Team Lead</th>
                                <th class="px-4 py-3 text-right font-bold text-gray-600">المشرف</th>
                                @can('edit-departments')<th class="px-4 py-3 text-right font-bold text-gray-600">إعداد</th>@endcan
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($department->employees as $emp)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-900">{{ $emp->first_name }} {{ $emp->last_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $emp->career_level ?? '—' }}</p>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $emp->position }}</td>
                                <td class="px-4 py-3">
                                    @if($emp->is_team_lead)
                                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-1 rounded-lg">قائد فريق</span>
                                    @else
                                    <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-700">{{ $emp->supervisor?->name ?? ($deptHead?->name ?? 'رئيس القسم') }}</td>
                                @can('edit-departments')
                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route('departments.team.update', [$department, $emp]) }}" class="flex flex-wrap items-center gap-2">
                                        @csrf @method('PUT')
                                        <label class="inline-flex items-center gap-1 text-xs whitespace-nowrap">
                                            <input type="checkbox" name="is_team_lead" value="1" @checked($emp->is_team_lead) class="rounded">
                                            TL
                                        </label>
                                        <select name="supervisor_user_id" class="border border-gray-200 rounded-lg px-2 py-1 text-xs min-w-[120px]">
                                            <option value="">رئيس القسم</option>
                                            @foreach($supervisorOptions as $u)
                                                @if($u->id !== $emp->user_id)
                                                <option value="{{ $u->id }}" @selected($emp->supervisor_user_id == $u->id)>{{ $u->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button type="submit" class="text-xs font-bold px-2 py-1 rounded-lg text-white" style="background:{{ $themeColor }}">حفظ</button>
                                    </form>
                                </td>
                                @endcan
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            {{-- المشاريع وفِرقها --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
                    <h2 class="font-bold text-lg text-gray-900">مشاريع القسم وفِرق التنفيذ</h2>
                    <p class="text-sm text-gray-500 mt-0.5">تعيين Team Leader وأعضاء كل مشروع — يصل الجاهز لرئيس القسم للتوزيع</p>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($department->projects as $project)
                    <div class="p-5">
                        <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
                            <div>
                                <a href="{{ route('projects.show', $project) }}" class="font-bold text-gray-900 hover:underline">{{ $project->name }}</a>
                                <p class="text-xs text-gray-500 mt-1">{{ $project->client?->name ?? 'بدون عميل' }} · {{ $project->tasks->count() }} مهمة</p>
                            </div>
                            @if($project->project_manager_id)
                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-1 rounded-lg">فريق مُعيَّن</span>
                            @else
                            <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2 py-1 rounded-lg">بانتظار تعيين الفريق</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mb-2">
                            قائد الفريق: <strong>{{ $project->projectManager?->name ?? '—' }}</strong>
                            @if($project->teamMembers->isNotEmpty())
                            · الأعضاء: {{ $project->teamMembers->pluck('name')->join('، ') }}
                            @endif
                        </p>
                        @can('edit-departments')
                        <details class="mt-2">
                            <summary class="text-xs font-bold cursor-pointer" style="color:{{ $themeColor }}">تعيين / تعديل فريق المشروع</summary>
                            <form method="POST" action="{{ route('departments.projects.team', [$department, $project]) }}" class="grid sm:grid-cols-2 gap-3 mt-3 p-4 rounded-xl bg-gray-50 border">
                                @csrf @method('PUT')
                                <div class="sm:col-span-2">
                                    <label class="text-xs font-bold text-gray-600 block mb-1">قائد الفريق (Team Leader)</label>
                                    <select name="project_manager_id" required class="w-full border rounded-xl px-3 py-2 text-sm">
                                        <option value="">اختر...</option>
                                        @foreach($supervisorOptions as $u)
                                        <option value="{{ $u->id }}" @selected($project->project_manager_id == $u->id)>{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="text-xs font-bold text-gray-600 block mb-1">أعضاء الفريق</label>
                                    @php $selected = $project->teamMembers->pluck('id')->all(); @endphp
                                    <select name="team_members[]" multiple class="w-full border rounded-xl px-3 py-2 text-sm min-h-[100px]">
                                        @foreach($supervisorOptions as $u)
                                        <option value="{{ $u->id }}" @selected(in_array($u->id, $selected))>{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="sm:col-span-2 py-2 rounded-xl text-white text-sm font-bold" style="background:{{ $themeColor }}">حفظ فريق المشروع</button>
                            </form>
                        </details>
                        @endcan
                    </div>
                    @empty
                    <div class="p-10 text-center text-gray-500">لا توجد مشاريع مرتبطة بهذا القسم.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 space-y-4">
            {{-- رئيس القسم --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-4">رئيس القسم</h3>
                @if($department->manager)
                <div class="text-center mb-4">
                    <div class="h-16 w-16 mx-auto rounded-2xl flex items-center justify-center text-white text-xl font-bold mb-2" style="background:{{ $themeColor }}">
                        {{ mb_substr($department->manager->first_name, 0, 1) }}
                    </div>
                    <p class="font-bold">{{ $department->manager->first_name }} {{ $department->manager->last_name }}</p>
                    <p class="text-sm text-gray-500">{{ $department->manager->position }}</p>
                </div>
                @else
                <p class="text-sm text-amber-700 bg-amber-50 rounded-xl p-3 mb-4">لم يُعيَّن رئيس قسم بعد — عيّنه لتسليم الفريق له.</p>
                @endif
                @can('edit-departments')
                <form method="POST" action="{{ route('departments.set-manager', $department) }}" class="space-y-2">
                    @csrf
                    <select name="manager_id" class="w-full border rounded-xl px-3 py-2 text-sm">
                        <option value="">بدون رئيس قسم</option>
                        @foreach($department->employees as $emp)
                        <option value="{{ $emp->id }}" @selected($department->manager_id == $emp->id)>{{ $emp->first_name }} {{ $emp->last_name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full py-2 rounded-xl text-white text-sm font-bold" style="background:{{ $themeColor }}">تعيين رئيس القسم</button>
                </form>
                @endcan
            </div>

            @can('edit-departments')
            {{-- إضافة موظف --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-3">إضافة موظف للقسم</h3>
                <form method="POST" action="{{ route('departments.assign-employee', $department) }}" class="space-y-3">
                    @csrf
                    <select name="employee_id" required class="w-full border rounded-xl px-3 py-2 text-sm">
                        <option value="">اختر موظفاً...</option>
                        @forelse($availableEmployees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} — {{ $emp->department?->name ?? 'بدون قسم' }}</option>
                        @empty
                        <option value="" disabled>كل الموظفين النشطين في أقسام أخرى أو أضف موظفاً جديداً</option>
                        @endforelse
                    </select>
                    <button type="submit" class="w-full py-2 rounded-xl text-white text-sm font-bold" style="background:{{ $themeColor }}">إضافة للقسم</button>
                </form>
                <a href="{{ route('employees.create', ['department_id' => $department->id]) }}" class="block text-center text-xs font-bold mt-3 hover:underline" style="color:{{ $themeColor }}">+ إنشاء موظف جديد</a>
            </div>

            @if($unassignedProjects->isNotEmpty())
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-3">ربط مشروع بالقسم</h3>
                <form method="POST" action="{{ route('departments.assign-project', $department) }}" class="space-y-3">
                    @csrf
                    <select name="project_id" required class="w-full border rounded-xl px-3 py-2 text-sm">
                        <option value="">اختر مشروعاً...</option>
                        @foreach($unassignedProjects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full py-2 rounded-xl border text-sm font-bold hover:bg-gray-50">ربط المشروع</button>
                </form>
            </div>
            @endif
            @endcan

            {{-- معلومات القسم --}}
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm text-sm space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-500">الحالة</span>
                    <span class="font-bold {{ $department->is_active ? 'text-green-700' : 'text-red-700' }}">{{ $department->is_active ? 'نشط' : 'متوقف' }}</span>
                </div>
                @if($department->default_position)
                <div class="flex justify-between"><span class="text-gray-500">المسمى الافتراضي</span><span class="font-semibold">{{ $department->default_position }}</span></div>
                @endif
                @if($department->kpi_profile)
                <div class="flex justify-between gap-2"><span class="text-gray-500 shrink-0">KPI</span><span class="text-xs font-semibold text-left">{{ (\App\Services\DepartmentProfileService::kpiProfileLabels())[$department->kpi_profile] ?? $department->kpi_profile }}</span></div>
                @endif
                @if(count($modules) > 0)
                <div class="pt-2 border-t">
                    <p class="text-xs font-bold text-gray-500 mb-2">السايدبار</p>
                    <div class="flex flex-wrap gap-1">
                        @foreach($modules as $m)
                        <span class="text-xs px-2 py-0.5 rounded bg-slate-100">{{ \App\Models\Department::SIDEBAR_MODULES[$m] ?? $m }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            @can('edit-departments')
            @if($department->employees->isNotEmpty() && ($otherDepartments ?? collect())->isNotEmpty())
            <div class="rounded-2xl border border-red-100 bg-red-50/40 p-5 text-sm">
                <p class="font-bold text-red-800 mb-2">نقل موظف من القسم</p>
                <form method="POST" action="{{ route('departments.remove-employee', [$department, $department->employees->first()]) }}" id="remove-emp-form" class="space-y-2" onsubmit="return confirm('نقل الموظف من القسم؟')">
                    @csrf
                    <select class="w-full border rounded-xl px-3 py-2 text-sm" onchange="document.getElementById('remove-emp-form').action=this.value">
                        @foreach($department->employees as $emp)
                        <option value="{{ route('departments.remove-employee', [$department, $emp]) }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                        @endforeach
                    </select>
                    <select name="target_department_id" required class="w-full border rounded-xl px-3 py-2 text-sm">
                        @foreach($otherDepartments as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full py-2 rounded-xl text-sm font-bold text-red-700 border border-red-200 hover:bg-red-50">نقل لقسم آخر</button>
                </form>
            </div>
            @endif
            @endcan
        </div>
    </div>
</div>
@endsection
