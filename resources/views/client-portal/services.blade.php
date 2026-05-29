@extends('layouts.client')

@section('page-title', 'خدماتي واشتراكاتي')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-extrabold font-cairo text-gray-900">خدماتي واشتراكاتي</h1>
        <p class="text-gray-600 text-sm mt-1">عقود الخدمة المستمرة (استضافة، صيانة، دعم…) والفواتير الشهرية المرتبطة بها.</p>
    </div>

    @forelse($services as $service)
    <article class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 sm:p-6 border-b border-gray-100 flex flex-wrap items-start justify-between gap-3">
            <div>
                <div class="text-xs text-gray-500 font-mono">{{ $service->service_number }}</div>
                <h2 class="text-lg font-extrabold text-gray-900 mt-1">{{ $service->title }}</h2>
                @if($service->description)
                    <p class="text-sm text-gray-600 mt-2">{{ $service->description }}</p>
                @endif
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold
                @if($service->status === 'active') bg-emerald-100 text-emerald-800
                @elseif($service->status === 'paused') bg-amber-100 text-amber-800
                @else bg-gray-100 text-gray-700 @endif">
                {{ $service->status_name }}
            </span>
        </div>
        <div class="p-5 sm:p-6 grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
            <div>
                <div class="text-gray-500 text-xs">الاشتراك الشهري</div>
                <div class="font-extrabold text-lg" style="color: var(--brand)">{{ number_format($service->monthly_amount, 2) }} {{ $service->currency }}</div>
            </div>
            <div>
                <div class="text-gray-500 text-xs">يوم الفوترة</div>
                <div class="font-bold">يوم {{ $service->billing_day }}</div>
            </div>
            <div>
                <div class="text-gray-500 text-xs">الفاتورة القادمة</div>
                <div class="font-bold">{{ $service->next_billing_date?->format('Y-m-d') ?? '—' }}</div>
            </div>
            <div>
                <div class="text-gray-500 text-xs">بداية الخدمة</div>
                <div class="font-bold">{{ $service->start_date?->format('Y-m-d') }}</div>
            </div>
        </div>
        @if($service->financialInvoices->isNotEmpty())
        <div class="px-5 sm:px-6 pb-5">
            <h3 class="text-xs font-bold text-gray-500 uppercase mb-2">آخر الفواتير</h3>
            <ul class="divide-y border rounded-xl overflow-hidden">
                @foreach($service->financialInvoices as $inv)
                <li class="flex flex-wrap items-center justify-between gap-2 px-4 py-3 bg-gray-50 text-sm">
                    <span class="font-mono">{{ $inv->invoice_number }}</span>
                    <span>{{ $inv->invoice_date?->format('Y-m-d') }}</span>
                    <span class="font-bold">{{ number_format($inv->total_amount, 2) }} {{ $inv->currency ?? 'EGP' }}</span>
                    <span class="text-xs font-bold {{ $inv->payment_status === 'paid' ? 'text-emerald-700' : 'text-red-700' }}">
                        {{ $inv->payment_status === 'paid' ? 'مدفوعة' : 'مستحقة' }}
                    </span>
                </li>
                @endforeach
            </ul>
            <a href="{{ route('client.invoices') }}" class="inline-block mt-3 text-sm font-semibold text-blue-700 hover:underline">عرض كل الفواتير والدفع ←</a>
        </div>
        @endif
    </article>
    @empty
    <div class="bg-white border rounded-2xl p-12 text-center text-gray-500">
        <p>لا توجد خدمات أو اشتراكات مسجّلة لحسابك حالياً.</p>
        <p class="text-sm mt-2">عند تفعيل عقد خدمة من قبل فريقنا، ستظهر هنا مع الفواتير الشهرية.</p>
    </div>
    @endforelse
</div>
@endsection
