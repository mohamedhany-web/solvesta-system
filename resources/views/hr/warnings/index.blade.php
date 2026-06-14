@extends('layouts.app')

@section('page-title', 'تحذيرات HR')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full px-2 sm:px-0">
    @include('partials.erp-page-header', [
        'title' => 'عقوبات وتحذيرات HR',
        'subtitle' => 'تأخير مهام → خصم KPI → 3 تحذيرات = تحقيق HR',
        'icon' => 'users',
    ])

    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('hr.warnings.index', ['filter'=>'active']) }}" class="px-4 py-2 rounded-xl border text-sm font-bold {{ request('filter')==='active'?'bg-gray-900 text-white':'' }}">نشطة</a>
        <a href="{{ route('hr.warnings.index', ['filter'=>'investigations']) }}" class="px-4 py-2 rounded-xl border text-sm font-bold {{ request('filter')==='investigations'?'bg-red-600 text-white':'' }}">تحقيقات HR</a>
        @can('edit-employees')
        <form method="POST" action="{{ route('hr.warnings.scan-overdue') }}">@csrf
            <button class="px-4 py-2 rounded-xl bg-amber-600 text-white text-sm font-bold">فحص مهام متأخرة</button>
        </form>
        @endcan
    </div>

    <div class="grid grid-cols-3 gap-4 mb-6">
        @foreach([['تحذيرات نشطة',$stats['active'],'#d97706'],['تحقيقات',$stats['investigations'],'#dc2626'],['محلولة هذا الشهر',$stats['resolved_month'],'#059669']] as [$l,$v,$c])
        <div class="bg-white rounded-2xl shadow-lg border p-4 text-center">
            <p class="text-xs text-gray-500">{{ $l }}</p>
            <p class="text-2xl font-bold" style="color:{{ $c }};">{{ $v }}</p>
        </div>
        @endforeach
    </div>

    @can('edit-employees')
    <details class="bg-white rounded-2xl border p-4 mb-6">
        <summary class="font-bold cursor-pointer" style="color:{{ $themeColor }};">+ تحذير يدوي</summary>
        <form method="POST" action="{{ route('hr.warnings.store') }}" class="grid md:grid-cols-2 gap-3 mt-4">
            @csrf
            <select name="user_id" required class="border rounded-xl px-3 py-2 md:col-span-2">
                <option value="">اختر الموظف</option>
                @foreach(\App\Models\User::whereHas('employee')->orderBy('name')->get() as $u)
                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                @endforeach
            </select>
            <select name="type" class="border rounded-xl px-3 py-2">
                <option value="task_delay">تأخير مهمة</option>
                <option value="attendance">حضور</option>
                <option value="conduct">سلوك</option>
                <option value="other">أخرى</option>
            </select>
            <input name="kpi_deduction_points" type="number" step="0.5" value="5" placeholder="خصم KPI" class="border rounded-xl px-3 py-2">
            <input name="reason" required placeholder="السبب..." class="border rounded-xl px-3 py-2 md:col-span-2">
            <button class="md:col-span-2 py-2 rounded-xl text-white font-bold" style="background:{{ $themeColor }};">تسجيل</button>
        </form>
    </details>
    @endcan

    <div class="bg-white rounded-2xl shadow-lg border overflow-hidden">
        <table class="w-full text-sm font-tajawal">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right">المرجع</th>
                    <th class="px-4 py-3 text-right">الموظف</th>
                    <th class="px-4 py-3 text-right">النوع</th>
                    <th class="px-4 py-3 text-right">خصم</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($warnings as $w)
                <tr class="{{ $w->investigation_status==='pending'?'bg-red-50':'' }}">
                    <td class="px-4 py-3 font-mono text-xs">{{ $w->reference_code }}</td>
                    <td class="px-4 py-3 font-bold">{{ $w->user?->name }}</td>
                    <td class="px-4 py-3">{{ $w->type_label }}</td>
                    <td class="px-4 py-3 text-red-700">-{{ $w->kpi_deduction_points }}</td>
                    <td class="px-4 py-3">
                        {{ $w->status_label }}
                        @if($w->investigation_status==='pending')<span class="text-red-600 text-xs font-bold">تحقيق HR</span>@endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @can('edit-employees')
                        @if($w->investigation_status==='pending')
                        <form method="POST" action="{{ route('hr.warnings.investigate', $w) }}" class="inline">@csrf
                            <button class="text-xs text-red-700 font-bold">بدء تحقيق</button>
                        </form>
                        @endif
                        @if($w->status!=='resolved')
                        <form method="POST" action="{{ route('hr.warnings.resolve', $w) }}" class="inline">@csrf
                            <button class="text-xs text-emerald-700 font-bold">حلّ</button>
                        </form>
                        @endif
                        @endcan
                    </td>
                </tr>
                <tr class="bg-gray-50/50"><td colspan="6" class="px-4 py-2 text-xs text-gray-600">{{ $w->reason }}</td></tr>
                @empty
                <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">لا توجد تحذيرات.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $warnings->links() }}</div>
    </div>
</div>
@endsection
