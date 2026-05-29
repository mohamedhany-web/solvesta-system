@extends('layouts.app')

@section('page-title', 'خدمات ما بعد البيع')

@section('content')
<div class="w-full">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">خدمات ما بعد البيع</h1>
            <p class="text-gray-600">عقود الخدمة المستمرة، الفوترة الشهرية، والربط ببوابة العميل</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('accounting.guide') }}" class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-semibold hover:bg-gray-50">دليل المحاسبة</a>
            @can('create-finance')
            <a href="{{ route('accounting.client-services.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700">+ خدمة جديدة</a>
            @endcan
        </div>
    </div>

    @if(session('success'))<div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">{{ session('error') }}</div>@endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white border rounded-xl p-6 shadow-sm">
            <p class="text-sm text-gray-600">خدمات نشطة</p>
            <p class="text-3xl font-bold text-emerald-700">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
            <p class="text-sm text-amber-800">مستحقة الفوترة اليوم</p>
            <p class="text-3xl font-bold text-amber-900">{{ $stats['due_today'] }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <p class="text-sm text-blue-800">إجمالي الاشتراكات الشهرية (MRR)</p>
            <p class="text-3xl font-bold text-blue-900">{{ number_format($stats['monthly_mrr'], 2) }} ج.م</p>
        </div>
    </div>

    <form method="GET" class="bg-white border rounded-xl p-4 mb-6 flex flex-wrap gap-3 items-end">
        <div>
            <label class="text-xs font-bold text-gray-600">بحث</label>
            <input type="text" name="q" value="{{ request('q') }}" class="border rounded-lg px-3 py-2 text-sm" placeholder="رقم أو اسم الخدمة">
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600">العميل</label>
            <select name="client_id" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">الكل</option>
                @foreach($clients as $c)
                    <option value="{{ $c->id }}" @selected(request('client_id') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600">الحالة</label>
            <select name="status" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">الكل</option>
                @foreach(['draft','active','paused','ended'] as $st)
                    <option value="{{ $st }}" @selected(request('status') === $st)>{{ match($st){'draft'=>'مسودة','active'=>'نشطة','paused'=>'موقوفة','ended'=>'منتهية',default=>$st} }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-bold">تصفية</button>
    </form>

    <div class="bg-white border rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right">الرقم</th>
                    <th class="px-4 py-3 text-right">الخدمة</th>
                    <th class="px-4 py-3 text-right">العميل</th>
                    <th class="px-4 py-3 text-right">شهرياً</th>
                    <th class="px-4 py-3 text-right">الفوترة القادمة</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($services as $service)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $service->service_number }}</td>
                    <td class="px-4 py-3 font-bold">{{ $service->title }}</td>
                    <td class="px-4 py-3">{{ $service->client?->name }}</td>
                    <td class="px-4 py-3 font-bold text-blue-700">{{ number_format($service->monthly_amount, 2) }} {{ $service->currency }}</td>
                    <td class="px-4 py-3">{{ $service->next_billing_date?->format('Y-m-d') ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded text-xs font-bold
                            @if($service->status==='active') bg-green-100 text-green-800
                            @elseif($service->status==='paused') bg-amber-100 text-amber-800
                            @elseif($service->status==='ended') bg-gray-200 text-gray-700
                            @else bg-slate-100 text-slate-700 @endif">
                            {{ $service->status_name }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('accounting.client-services.show', $service) }}" class="text-blue-600 font-bold hover:underline">عرض</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-12 text-center text-gray-500">لا توجد خدمات مسجّلة بعد.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $services->links() }}</div>
    </div>
</div>
@endsection
