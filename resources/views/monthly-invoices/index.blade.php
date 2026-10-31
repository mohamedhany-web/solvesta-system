@extends('layouts.app')

@section('page-title', 'الفواتير الشهرية')

@section('content')
<div class="w-full">
    <div class="mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">الفواتير الشهرية</h1>
            <p class="text-gray-600">فواتير اشتراكات خدمات ما بعد البيع والمتطلبات المرتبطة بها</p>
        </div>
        <a href="{{ route('invoices.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-200 inline-flex items-center shadow-sm font-semibold">
            <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            إصدار فاتورة شهرية
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl shadow-md border border-blue-200 p-6">
            <p class="text-sm font-semibold text-blue-700 mb-1">إجمالي الفواتير</p>
            <p class="text-3xl font-bold text-blue-900">{{ $totalInvoices }}</p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl shadow-md border border-green-200 p-6">
            <p class="text-sm font-semibold text-green-700 mb-1">مدفوعة</p>
            <p class="text-3xl font-bold text-green-900">{{ $paidInvoices }}</p>
        </div>
        <div class="bg-gradient-to-br from-orange-50 to-red-100 rounded-xl shadow-md border border-orange-200 p-6">
            <p class="text-sm font-semibold text-orange-700 mb-1">معلقة</p>
            <p class="text-3xl font-bold text-orange-900">{{ $pendingInvoices }}</p>
            <p class="text-xs text-orange-600 mt-1">{{ number_format($pendingAmount, 2) }} ج.م</p>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-pink-100 rounded-xl shadow-md border border-purple-200 p-6">
            <p class="text-sm font-semibold text-purple-700 mb-1">إيرادات محصّلة</p>
            <p class="text-3xl font-bold text-purple-900">{{ number_format($totalRevenue, 2) }} <span class="text-base">ج.م</span></p>
            <p class="text-xs text-purple-600 mt-1">{{ number_format($monthlyRevenue, 2) }} ج.م هذا الشهر</p>
        </div>
    </div>

    <form method="GET" class="bg-white rounded-xl border border-gray-200 p-4 mb-6 grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">العميل</label>
            <select name="client_id" class="w-full border-gray-300 rounded-lg">
                <option value="">الكل</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" @selected(request('client_id') == $client->id)>{{ $client->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">الخدمة</label>
            <select name="client_service_id" class="w-full border-gray-300 rounded-lg">
                <option value="">الكل</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}" @selected(request('client_service_id') == $service->id)>{{ $service->title }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
            <select name="status" class="w-full border-gray-300 rounded-lg">
                <option value="">الكل</option>
                @foreach(['draft'=>'مسودة','sent'=>'مرسلة','partial'=>'جزئية','paid'=>'مدفوعة','overdue'=>'متأخرة','cancelled'=>'ملغاة'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">الشهر</label>
            <input type="month" name="month" value="{{ request('month') }}" class="w-full border-gray-300 rounded-lg">
        </div>
        <div class="flex items-end gap-2">
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold">تصفية</button>
            <a href="{{ route('invoices.index') }}" class="px-4 py-2 rounded-lg border text-gray-700">إعادة</a>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم الفاتورة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">العميل</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الخدمة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap font-mono text-sm font-semibold">{{ $invoice->invoice_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $invoice->client?->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $invoice->clientService?->title ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $invoice->invoice_date?->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold">{{ number_format($invoice->total_amount, 2) }} ج.م</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-bold rounded-full
                                    {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : ($invoice->status === 'partial' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ $invoice->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2 space-x-reverse">
                                <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1 rounded-lg">عرض</a>
                                @if($invoice->status !== 'paid' && $invoice->balance_due > 0)
                                    <a href="{{ route('invoices.show', $invoice) }}?pay=1" class="text-green-700 hover:text-green-900 bg-green-50 px-3 py-1 rounded-lg font-semibold">دفعة</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                لا توجد فواتير شهرية بعد.
                                <div class="mt-4">
                                    <a href="{{ route('invoices.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium">إصدار أول فاتورة</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($invoices->hasPages())
            <div class="px-6 py-4 border-t">{{ $invoices->links() }}</div>
        @endif
    </div>
</div>
@endsection
