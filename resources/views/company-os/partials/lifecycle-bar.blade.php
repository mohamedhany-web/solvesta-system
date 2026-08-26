@php
    $engine = app(\App\Services\CompanyOs\WorkflowEngine::class);
    try {
        $engine->ensureLifecycle($entity);
        $entity->loadMissing('lifecycle.currentState', 'lifecycle.definition.states', 'workItems.assignee', 'companyEvents.actor', 'approvalRequests');
    } catch (\Throwable $e) {
        $entity = $entity;
    }
    $lifecycle = $entity->lifecycle;
    $states = $lifecycle?->definition?->states ?? collect();
    $currentKey = $lifecycle?->currentState?->key;
    $transitions = $lifecycle ? $engine->availableTransitions($entity, auth()->user()) : [];
    $openItems = $entity->workItems?->where('status', 'open') ?? collect();
    $pendingApproval = $entity->approvalRequests?->firstWhere('status', 'pending');
    $osType = $type;
@endphp

@if($lifecycle)
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="font-bold text-lg text-gray-900">دورة الحياة</h2>
            <p class="text-sm text-gray-500 mt-0.5">السيستم يفرض الانتقال — الحالات المتاحة فقط تظهر أدناه</p>
        </div>
        <div class="text-left text-sm">
            <span class="font-bold text-gray-900">{{ $lifecycle->currentState->label_ar }}</span>
            @if($lifecycle->sla_due_at && ! $lifecycle->currentState->is_terminal)
                <p class="text-xs mt-1 {{ $lifecycle->isSlaBreached() ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                    SLA: {{ $lifecycle->sla_due_at->diffForHumans() }}
                </p>
            @endif
        </div>
    </div>

    <div class="px-6 py-4 overflow-x-auto">
        <div class="flex items-center gap-2 min-w-max">
            @foreach($states->where('is_side', false) as $state)
                @php $isCurrent = $state->key === $currentKey; @endphp
                <div class="flex items-center gap-2">
                    <div class="px-3 py-1.5 rounded-full text-xs font-bold {{ $isCurrent ? 'bg-indigo-600 text-white' : ($state->sort_order < ($lifecycle->currentState->sort_order ?? 0) && ! $lifecycle->currentState->is_side ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-500') }}">
                        {{ $state->label_ar }}
                    </div>
                    @if(! $loop->last)
                        <span class="text-gray-300">→</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    @if($transitions)
    <div class="px-6 pb-4 flex flex-wrap gap-2">
        @foreach($transitions as $item)
            @php $to = $item['to']; $blocked = $item['blocked']; @endphp
            <form method="POST" action="{{ route('os.transition', ['type' => $osType, 'id' => $entity->id]) }}" class="inline">
                @csrf
                <input type="hidden" name="to_state" value="{{ $to->key }}">
                <button type="submit" @disabled($blocked)
                        class="px-4 py-2 rounded-xl text-sm font-bold border {{ $blocked ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' : ($to->is_side ? 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' : 'bg-indigo-600 text-white border-indigo-600 hover:opacity-90') }}"
                        @if($blocked) title="{{ $blocked }}" @endif>
                    {{ $to->label_ar }}
                </button>
            </form>
        @endforeach
    </div>
    @endif

    @if($pendingApproval)
        <div class="mx-6 mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm">
            <p class="font-bold text-amber-900">موافقة معلّقة — الخطوة {{ $pendingApproval->current_step + 1 }} من {{ count($pendingApproval->steps ?? []) }}</p>
            <p class="text-amber-800 mt-1">{{ $pendingApproval->currentStepDef()['label'] ?? 'اعتماد' }}</p>
            <div class="mt-3 flex gap-2">
                <form method="POST" action="{{ route('os.approvals.decide', $pendingApproval) }}">
                    @csrf
                    <input type="hidden" name="decision" value="approve">
                    <button class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs font-bold">اعتماد</button>
                </form>
                <form method="POST" action="{{ route('os.approvals.decide', $pendingApproval) }}">
                    @csrf
                    <input type="hidden" name="decision" value="reject">
                    <button class="px-3 py-1.5 rounded-lg bg-red-600 text-white text-xs font-bold">رفض</button>
                </form>
            </div>
        </div>
    @endif

    @if($openItems->count())
        <div class="px-6 pb-5">
            <p class="text-xs font-bold text-gray-500 mb-2">بنود عمل مطلوبة</p>
            <ul class="space-y-2">
                @foreach($openItems as $item)
                    <li class="flex items-center justify-between gap-3 rounded-xl border border-gray-100 bg-gray-50 px-3 py-2 text-sm">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $item->title }}</p>
                            <p class="text-xs text-gray-500">{{ $item->assignee?->name ?? 'بدون مسؤول' }}@if($item->due_at) · {{ $item->due_at->format('Y/m/d') }}@endif</p>
                        </div>
                        <form method="POST" action="{{ route('os.work-items.complete', $item) }}">
                            @csrf
                            <button class="text-xs font-bold text-indigo-700 hover:underline">تم</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($entity->companyEvents && $entity->companyEvents->count())
        <div class="px-6 pb-6">
            <p class="text-xs font-bold text-gray-500 mb-2">السجل الزمني</p>
            <ol class="space-y-2 border-r-2 border-gray-100 pr-4">
                @foreach($entity->companyEvents->take(12) as $event)
                    <li class="text-sm">
                        <span class="font-semibold text-gray-800">{{ $event->actorName() }}</span>
                        <span class="text-gray-500">{{ $event->labelAr() }}</span>
                        @if(!empty($event->payload['from_label']))
                            <span class="text-gray-700">{{ $event->payload['from_label'] }} → {{ $event->payload['to_label'] }}</span>
                        @endif
                        <span class="text-xs text-gray-400">{{ $event->created_at->diffForHumans() }}</span>
                    </li>
                @endforeach
            </ol>
        </div>
    @endif
</div>
@endif
