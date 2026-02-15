@extends('layouts.app')

@section('page-title', 'إدارة الفواتير')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إدارة الفواتير</h1>
                <p class="text-gray-600">إدارة وتتبع الفواتير والمدفوعات المالية</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ request()->routeIs('financial-invoices.*') ? route('financial-invoices.create') : route('invoices.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-200 flex items-center shadow-sm">
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    فاتورة جديدة
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Invoices -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl shadow-md border border-blue-200 p-6 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-blue-700 mb-1">إجمالي الفواتير</p>
                    <p class="text-3xl font-bold text-blue-900">{{ $totalInvoices }}</p>
                    <p class="text-xs text-blue-600 mt-1">جميع الفواتير</p>
                </div>
                <div class="p-4 bg-blue-500 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Paid Invoices -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl shadow-md border border-green-200 p-6 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-green-700 mb-1">الفواتير المدفوعة</p>
                    <p class="text-3xl font-bold text-green-900">{{ $paidInvoices }}</p>
                    <p class="text-xs text-green-600 mt-1">{{ $totalInvoices > 0 ? round(($paidInvoices / $totalInvoices) * 100) : 0 }}% من الإجمالي</p>
                </div>
                <div class="p-4 bg-green-500 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Invoices -->
        <div class="bg-gradient-to-br from-orange-50 to-red-100 rounded-xl shadow-md border border-orange-200 p-6 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-orange-700 mb-1">الفواتير المعلقة</p>
                    <p class="text-3xl font-bold text-orange-900">{{ $pendingInvoices }}</p>
                    <p class="text-xs text-orange-600 mt-1">{{ number_format($pendingAmount) }} ج.م</p>
                </div>
                <div class="p-4 bg-orange-500 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-purple-50 to-pink-100 rounded-xl shadow-md border border-purple-200 p-6 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-purple-700 mb-1">إجمالي الإيرادات</p>
                    <p class="text-3xl font-bold text-purple-900">{{ number_format($totalRevenue) }}</p>
                    <p class="text-xs text-purple-600 mt-1">{{ number_format($monthlyRevenue) }} ج.م هذا الشهر</p>
                </div>
                <div class="p-4 bg-purple-500 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                <input type="text" id="search" placeholder="رقم الفاتورة أو العميل..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                <select id="status_filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">جميع الحالات</option>
                    <option value="draft">مسودة</option>
                    <option value="sent">مرسل</option>
                    <option value="viewed">تم المشاهدة</option>
                    <option value="paid">مدفوع</option>
                    <option value="overdue">متأخر</option>
                    <option value="cancelled">ملغي</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
                <input type="date" id="date_from" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">إلى تاريخ</label>
                <input type="date" id="date_to" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">قائمة الفواتير</h3>
                <div class="flex items-center gap-2">
                    <button onclick="exportInvoices()" class="text-sm px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors duration-200">
                        <svg class="w-4 h-4 inline ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        تصدير
                    </button>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">رقم الفاتورة</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">العميل</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">المشروع</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">المبلغ</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">المدفوع</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">التاريخ</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($invoices as $invoice)
                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900 bg-gray-100 px-2 py-1 rounded inline-block">{{ $invoice->invoice_number }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($invoice->client)
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center ml-3">
                                    <span class="text-sm font-medium text-white">{{ mb_substr($invoice->client->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $invoice->client->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $invoice->client->email ?? '-' }}</div>
                                </div>
                            </div>
                            @else
                            <span class="text-sm text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $invoice->project ? $invoice->project->name : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-900">{{ number_format($invoice->total_amount) }} <span class="text-xs text-gray-500">ج.م</span></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-green-600">{{ number_format($invoice->paid_amount) }} <span class="text-xs text-gray-500">ج.م</span></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColor = match($invoice->status) {
                                    'paid' => 'bg-green-100 text-green-800',
                                    'sent' => 'bg-blue-100 text-blue-800',
                                    'viewed' => 'bg-yellow-100 text-yellow-800',
                                    'overdue' => 'bg-red-100 text-red-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                $statusName = match($invoice->status) {
                                    'draft' => 'مسودة',
                                    'sent' => 'مرسل',
                                    'viewed' => 'تم المشاهدة',
                                    'paid' => 'مدفوع',
                                    'overdue' => 'متأخر',
                                    'cancelled' => 'ملغي',
                                    default => $invoice->status
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                {{ $statusName }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($invoice->invoice_date)
                                {{ $invoice->invoice_date instanceof \Carbon\Carbon ? $invoice->invoice_date->format('Y/m/d') : \Carbon\Carbon::parse($invoice->invoice_date)->format('Y/m/d') }}
                            @elseif($invoice->issue_date)
                                {{ $invoice->issue_date instanceof \Carbon\Carbon ? $invoice->issue_date->format('Y/m/d') : \Carbon\Carbon::parse($invoice->issue_date)->format('Y/m/d') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="{{ request()->routeIs('financial-invoices.*') ? route('financial-invoices.show', $invoice) : route('invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1 rounded-lg hover:bg-blue-100 transition-colors duration-200">عرض</a>
                                @if($invoice->status !== 'paid')
                                <button onclick="markAsPaid({{ $invoice->id }})" class="text-green-600 hover:text-green-800 bg-green-50 px-3 py-1 rounded-lg hover:bg-green-100 transition-colors duration-200">تحديد كمدفوع</button>
                                @endif
                                @if($invoice->status === 'draft')
                                <button onclick="deleteInvoice({{ $invoice->id }})" class="text-red-600 hover:text-red-800 bg-red-50 px-3 py-1 rounded-lg hover:bg-red-100 transition-colors duration-200">حذف</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="p-4 bg-gray-100 rounded-full mb-4">
                                    <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">لا توجد فواتير</h3>
                                <p class="text-gray-600 mb-4">ابدأ بإنشاء فاتورة جديدة</p>
                                <a href="{{ route('invoices.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm font-medium">
                                    إضافة فاتورة جديدة
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($invoices->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>
</div>

<script>
var invoicesBasePath = '{{ request()->routeIs("financial-invoices.*") ? "financial-invoices" : "invoices" }}';
function markAsPaid(invoiceId) {
    if (confirm('هل أنت متأكد من تحديد هذه الفاتورة كمدفوعة؟')) {
        fetch(`/${invoicesBasePath}/${invoiceId}/mark-as-paid`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => { throw new Error(data.message || 'حدث خطأ'); });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showNotification('تم تحديث حالة الفاتورة بنجاح', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message || 'حدث خطأ', 'error');
            }
        })
        .catch(error => {
            showNotification(error.message || 'حدث خطأ في الاتصال', 'error');
        });
    }
}

function deleteInvoice(invoiceId) {
    if (confirm('هل أنت متأكد من حذف هذه الفاتورة؟\n\nملاحظة: لا يمكن التراجع عن هذا الإجراء.')) {
        fetch(`/${invoicesBasePath}/${invoiceId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'حدث خطأ');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showNotification('تم حذف الفاتورة بنجاح', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message || 'حدث خطأ', 'error');
            }
        })
        .catch(error => {
            showNotification(error.message || 'حدث خطأ في الاتصال', 'error');
        });
    }
}

function exportInvoices() {
    alert('وظيفة التصدير قيد التطوير');
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        notification.style.transition = 'all 0.3s';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
</script>
@endsection
