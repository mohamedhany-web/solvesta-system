@extends('layouts.app')

@section('page-title', 'الترقيات والتطوير الوظيفي')

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $pipeline = [
        \App\Models\EmployeePromotion::STATUS_PENDING_TEAM_LEAD => 'Team Lead',
        \App\Models\EmployeePromotion::STATUS_PENDING_DEPT_HEAD => 'رئيس القسم',
        \App\Models\EmployeePromotion::STATUS_PENDING_HR => 'HR / الترقيات',
        \App\Models\EmployeePromotion::STATUS_APPROVED => 'معتمد',
    ];
@endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'الترقيات والتطوير الوظيفي',
        'subtitle' => 'Pipeline: Team Lead → رئيس القسم → HR → اعتماد الترقية',
        'icon' => 'users',
    ])

    @if(session('success'))
    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('hr.promotions.index') }}" class="px-4 py-2 rounded-xl border text-sm font-bold {{ !request('status') ? 'bg-gray-900 text-white' : '' }}">الكل</a>
        @foreach($statusLabels as $key => $label)
        <a href="{{ route('hr.promotions.index', ['status' => $key]) }}"
           class="px-4 py-2 rounded-xl border text-sm font-bold {{ request('status') === $key ? 'bg-indigo-600 text-white' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border p-4 mb-6 overflow-x-auto">
        <p class="text-xs font-bold text-gray-500 mb-3">مسار الترقية</p>
        <div class="flex items-center gap-2 min-w-max text-sm">
            @foreach($pipeline as $status => $label)
                <span class="px-3 py-1.5 rounded-lg border {{ request('status') === $status ? 'bg-indigo-50 border-indigo-300 font-bold' : 'bg-gray-50' }}">{{ $label }}</span>
                @if(!$loop->last)<span class="text-gray-400">→</span>@endif
            @endforeach
        </div>
    </div>

    @can('edit-employees')
    <details class="bg-white rounded-2xl border p-5 mb-6 shadow-sm">
        <summary class="font-bold cursor-pointer" style="color: {{ $themeColor }};">+ طلب ترقية جديد</summary>
        <form method="POST" action="{{ route('hr.promotions.store') }}" class="grid md:grid-cols-2 gap-4 mt-4">
            @csrf
            <div class="md:col-span-2">
                <label class="text-xs font-bold text-gray-600 block mb-1">الموظف</label>
                <select name="employee_id" id="promo_employee_id" required class="w-full border rounded-xl px-3 py-2.5 text-sm" onchange="updatePromotionLevels(this)">
                    <option value="">اختر الموظف</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}"
                                data-level="{{ $emp->career_level }}"
                                data-track="{{ $emp->career_track }}"
                                data-dept="{{ $emp->department?->code }}">
                            {{ $emp->first_name }} {{ $emp->last_name }} — {{ $emp->department?->name }} ({{ $emp->career_level ?? 'بدون مستوى' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-600 block mb-1">المستوى الحالي</label>
                <input type="text" id="promo_from_level" readonly class="w-full border rounded-xl px-3 py-2.5 text-sm bg-gray-50" placeholder="—">
            </div>
            <div>
                <label class="text-xs font-bold text-gray-600 block mb-1">المستوى المطلوب <span class="text-red-500">*</span></label>
                <input type="text" name="to_level" id="promo_to_level" required class="w-full border rounded-xl px-3 py-2.5 text-sm" placeholder="مثال: Senior مبرمج">
            </div>
            <div class="md:col-span-2">
                <label class="text-xs font-bold text-gray-600 block mb-1">المبرر <span class="text-red-500">*</span></label>
                <textarea name="justification" required rows="3" class="w-full border rounded-xl px-3 py-2.5 text-sm" placeholder="أداء KPI، إنجازات، توصية..."></textarea>
            </div>
            <button type="submit" class="md:col-span-2 py-2.5 rounded-xl text-white font-bold" style="background: {{ $themeColor }};">تقديم طلب الترقية</button>
        </form>
    </details>
    @endcan

    <div class="bg-white rounded-2xl shadow-lg border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right">الموظف</th>
                    <th class="px-4 py-3 text-right">من → إلى</th>
                    <th class="px-4 py-3 text-right">KPI</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right">مقدّم الطلب</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($promotions as $promo)
                <tr>
                    <td class="px-4 py-3">
                        <p class="font-bold">{{ $promo->employee?->first_name }} {{ $promo->employee?->last_name }}</p>
                        <p class="text-xs text-gray-500">{{ $promo->employee?->department?->name }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-gray-500">{{ $promo->from_level ?? '—' }}</span>
                        <span class="mx-1">→</span>
                        <span class="font-bold">{{ $promo->to_level }}</span>
                    </td>
                    <td class="px-4 py-3 font-mono">{{ $promo->kpi_score !== null ? number_format($promo->kpi_score, 1).'%' : '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex px-2 py-0.5 rounded-lg text-xs font-bold
                            @if($promo->status === 'approved') bg-green-100 text-green-800
                            @elseif($promo->status === 'rejected') bg-red-100 text-red-800
                            @else bg-amber-100 text-amber-800 @endif">
                            {{ $statusLabels[$promo->status] ?? $promo->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-600">{{ $promo->proposer?->name ?? '—' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if(!in_array($promo->status, ['approved', 'rejected'], true))
                        <form method="POST" action="{{ route('hr.promotions.advance', $promo) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs font-bold px-2 py-1 rounded-lg text-white" style="background: {{ $themeColor }};">تقديم</button>
                        </form>
                        @can('edit-employees')
                        <form method="POST" action="{{ route('hr.promotions.reject', $promo) }}" class="inline mr-1">
                            @csrf
                            <button type="submit" class="text-xs font-bold px-2 py-1 rounded-lg border border-red-300 text-red-700">رفض</button>
                        </form>
                        @endcan
                        @endif
                    </td>
                </tr>
                @if($promo->justification)
                <tr class="bg-gray-50/50">
                    <td colspan="6" class="px-4 py-2 text-xs text-gray-600">{{ $promo->justification }}</td>
                </tr>
                @endif
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">لا توجد طلبات ترقية.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($promotions->hasPages())
        <div class="px-4 py-3 border-t">{{ $promotions->links() }}</div>
        @endif
    </div>
</div>
@push('scripts')
<script>
const deptLevels = @json(collect(\App\Services\DepartmentProfileService::PROFILES)->mapWithKeys(fn($p, $code) => [$code => $p['levels'] ?? []]));
function updatePromotionLevels(select) {
    const opt = select.selectedOptions[0];
    document.getElementById('promo_from_level').value = opt?.dataset.level || '';
    const code = opt?.dataset.dept || '';
    const levels = deptLevels[code] || [];
    const current = opt?.dataset.level || '';
    const idx = levels.indexOf(current);
    const next = idx >= 0 && idx < levels.length - 1 ? levels[idx + 1] : '';
    document.getElementById('promo_to_level').value = next;
}
</script>
@endpush
@endsection
