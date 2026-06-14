@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $contract = $sale->contracts()->first();
    $estimation = $sale->costEstimations()->latest()->first();
    $proposal = $sale->proposals()->latest()->first();
    $steps = [
        ['key' => 'qualify', 'label' => 'تأهيل', 'done' => $sale->qualification_status === 'qualified'],
        ['key' => 'estimate', 'label' => 'تقدير', 'done' => $estimation && in_array($estimation->status, ['submitted', 'approved'])],
        ['key' => 'proposal', 'label' => 'عرض', 'done' => $proposal && in_array($proposal->status, ['sent', 'accepted'])],
        ['key' => 'contract', 'label' => 'عقد', 'done' => (bool) $contract],
        ['key' => 'project', 'label' => 'مشروع', 'done' => (bool) $sale->project_id],
    ];
@endphp
<div class="rounded-2xl shadow-lg border border-gray-200 p-6 mb-6 overflow-hidden" style="border-color: {{ $themeColor }}25;">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <div>
            <h2 class="text-lg font-bold text-gray-900 font-tajawal">سلسلة التدفق التجاري</h2>
            <p class="text-sm text-gray-600 font-tajawal">Lead → تأهيل → Pre-Sales → Proposal → عقد → دفعة → مشروع</p>
        </div>
        <div class="flex flex-wrap gap-1">
            @foreach($steps as $i => $step)
                <span class="px-2.5 py-1 rounded-full text-xs font-bold font-tajawal {{ $step['done'] ? 'text-white' : 'bg-gray-100 text-gray-500' }}"
                      @if($step['done']) style="background: {{ $themeColor }};" @endif>
                    {{ $i + 1 }}. {{ $step['label'] }}
                </span>
            @endforeach
        </div>
    </div>

    <div class="flex flex-wrap gap-2 text-xs mb-5 font-tajawal">
        @foreach(['pending'=>'بانتظار التأهيل','qualified'=>'مؤهل','disqualified'=>'غير مؤهل'] as $k=>$l)
            <span class="px-3 py-1 rounded-full {{ $sale->qualification_status===$k ? 'text-white font-bold' : 'bg-gray-100 text-gray-600' }}"
                  @if($sale->qualification_status===$k) style="background: {{ $themeColor }};" @endif>{{ $l }}</span>
        @endforeach
    </div>

    @can('edit-sales')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Qualification --}}
        @if($sale->qualification_status !== 'qualified' && !in_array($sale->stage, ['closed_won','closed_lost']))
        <form method="POST" action="{{ route('workflow.sales.qualify', $sale) }}" class="border border-gray-200 rounded-xl p-4 space-y-2 bg-white">
            @csrf
            <h3 class="font-bold text-sm font-tajawal">تأهيل الفرصة (Discovery)</h3>
            <textarea name="requirement_summary" rows="3" placeholder="ملخص المتطلبات..." class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm">{{ $sale->requirement_summary }}</textarea>
            <button class="bg-emerald-600 text-white px-4 py-2 rounded-xl text-sm font-bold">تأهيل ✓</button>
        </form>
        <form method="POST" action="{{ route('workflow.sales.disqualify', $sale) }}" class="border border-red-100 rounded-xl p-4 space-y-2 bg-white">
            @csrf
            <h3 class="font-bold text-sm text-red-800 font-tajawal">غير مؤهل (Lost)</h3>
            <input name="lost_reason" required placeholder="السبب..." class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm">
            <button class="bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-bold">رفض</button>
        </form>
        @endif

        {{-- Pre-Sales --}}
        @if($sale->qualification_status === 'qualified' && !in_array($sale->stage, ['closed_won','closed_lost']))
        <div class="border rounded-xl p-4 md:col-span-2 bg-white" style="border-color: {{ $themeColor }}30;">
            <h3 class="font-bold text-sm font-tajawal mb-2" style="color: {{ $themeColor }};">Pre-Sales — تقدير وتسعير</h3>
            <div class="flex flex-wrap gap-3 items-center text-sm">
                <a href="{{ route('pre-sales.estimate', $sale) }}" class="px-4 py-2 rounded-xl text-white font-bold"
                   style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                    {{ $estimation ? 'تعديل التقدير' : 'إنشاء تقدير تكلفة' }}
                </a>
                @if($estimation)
                    <span class="text-gray-600">{{ $estimation->reference_code }} — {{ $estimation->status_label }} — {{ number_format($estimation->total_cost) }} ج.م</span>
                @endif
                @if($estimation?->status === 'approved' && !$proposal)
                <form method="POST" action="{{ route('pre-sales.proposals.generate', $sale) }}">@csrf
                    <button class="px-4 py-2 rounded-xl bg-blue-600 text-white font-bold">إصدار Proposal</button>
                </form>
                @endif
                @if($proposal)
                    <a href="{{ route('pre-sales.proposals.show', $proposal) }}" class="px-4 py-2 rounded-xl border font-bold {{ $proposal->status_color }}">
                        {{ $proposal->reference_code }} — {{ $proposal->status_label }}
                    </a>
                @endif
            </div>
        </div>
        @endif

        {{-- Contract --}}
        @if($sale->qualification_status === 'qualified' && !$contract)
        <form method="POST" action="{{ route('workflow.sales.create-contract', $sale) }}" class="border border-blue-100 rounded-xl p-4 md:col-span-2 bg-white">
            @csrf
            <h3 class="font-bold text-sm font-tajawal">إنشاء مسودة عقد</h3>
            <p class="text-xs text-gray-600 mb-2">
                @if($proposal?->status === 'accepted')
                    العميل وافق على العرض — جاهز للتعاقد.
                @else
                    يتطلب موافقة العميل على الـ Proposal أولاً.
                @endif
            </p>
            <button class="px-4 py-2 rounded-xl text-white font-bold text-sm {{ $proposal?->status === 'accepted' ? 'bg-blue-600' : 'bg-gray-400 cursor-not-allowed' }}"
                    @if($proposal?->status !== 'accepted') disabled @endif>إنشاء عقد →</button>
        </form>
        @endif

        @if($contract)
        <div class="border rounded-xl p-4 md:col-span-2 bg-gray-50">
            <p class="text-sm font-bold font-tajawal">العقد: <a href="{{ route('contracts.show', $contract) }}" class="text-blue-600">{{ $contract->contract_number }}</a> — {{ $contract->status }}</p>
            <div class="flex flex-wrap gap-2 mt-3">
                <form method="POST" action="{{ route('workflow.contracts.deposit-invoice', $contract) }}">@csrf
                    <button class="bg-amber-600 text-white px-4 py-2 rounded-xl text-sm font-bold">فاتورة 50% مقدماً</button>
                </form>
                <form method="POST" action="{{ route('workflow.contracts.kickoff', $contract) }}">@csrf
                    <button class="px-4 py-2 rounded-xl text-white text-sm font-bold" style="background: {{ $themeColor }};">بدء المشروع (بعد الدفع)</button>
                </form>
            </div>
        </div>
        @endif
    </div>
    @endcan

    @if($sale->requirement_summary)
        <div class="mt-4 p-4 bg-gray-50 rounded-xl text-sm font-tajawal"><strong>ملخص المتطلبات:</strong> {{ $sale->requirement_summary }}</div>
    @endif
</div>
