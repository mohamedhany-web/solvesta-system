@extends('layouts.app')

@section('page-title', 'تفاصيل العقد')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">تفاصيل العقد</h1>
                <p class="text-gray-600">عرض تفاصيل العقد: {{ $contract->title }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('contracts.edit', $contract) }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-200 flex items-center shadow-sm">
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    تعديل
                </a>
                <a href="{{ route('contracts.index') }}" class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-200 flex items-center shadow-sm">
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    العودة
                </a>
            </div>
        </div>
    </div>

    <!-- Contract Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Information -->
        <div class="lg:col-span-2">
            <!-- Basic Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $contract->title }}</h2>
                        <p class="text-gray-600">رقم العقد: {{ $contract->contract_number }}</p>
                    </div>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                        @if($contract->status == 'draft') bg-gray-100 text-gray-800
                        @elseif($contract->status == 'active') bg-green-100 text-green-800
                        @elseif($contract->status == 'expired') bg-red-100 text-red-800
                        @elseif($contract->status == 'terminated') bg-red-100 text-red-800
                        @elseif($contract->status == 'renewed') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $contract->status_name }}
                    </span>
                </div>

                @if($contract->description)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">الوصف</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $contract->description }}</p>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Contract Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات العقد</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">نوع العقد</p>
                                    <p class="text-sm text-gray-600">{{ $contract->contract_type_name }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">قيمة العقد</p>
                                    <p class="text-sm text-gray-600">{{ number_format($contract->value) }} ج.م</p>
                                </div>
                            </div>
                            @if($contract->project)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">المشروع المرتبط</p>
                                    <p class="text-sm text-gray-600">{{ $contract->project->name }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Timeline Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">الجدول الزمني</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">تاريخ البدء</p>
                                    <p class="text-sm text-gray-600">{{ $contract->start_date->format('Y/m/d') }}</p>
                                </div>
                            </div>
                            @if($contract->end_date)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">تاريخ الانتهاء</p>
                                    <p class="text-sm text-gray-600">{{ $contract->end_date->format('Y/m/d') }}</p>
                                </div>
                            </div>
                            @endif
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">تاريخ الإنشاء</p>
                                    <p class="text-sm text-gray-600">{{ $contract->created_at->format('Y/m/d H:i') }}</p>
                                </div>
                            </div>
                            @if($contract->renewal_notice_days)
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v2H4a2 2 0 01-2-2V5a2 2 0 012-2h6v2H4v12z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">أيام إشعار التجديد</p>
                                    <p class="text-sm text-gray-600">{{ $contract->renewal_notice_days }} يوم</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($contract->terms_conditions)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">الشروط والأحكام</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $contract->terms_conditions }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Related Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Client Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">معلومات العميل</h3>
                        <a href="{{ route('clients.show', $contract->client) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            عرض التفاصيل
                        </a>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <div class="h-10 w-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center ml-3">
                                <span class="text-sm font-medium text-white">{{ substr($contract->client->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $contract->client->name }}</p>
                                <p class="text-sm text-gray-500">{{ $contract->client->company_name ?? 'غير محدد' }}</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            <p>{{ $contract->client->email }}</p>
                            @if($contract->client->phone)
                                <p>{{ $contract->client->phone }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contract Management -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">إدارة العقد</h3>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">أنشأ بواسطة</p>
                                <p class="text-sm text-gray-600">{{ $contract->createdBy->name }}</p>
                            </div>
                        </div>
                        @if($contract->approvedBy)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">معتمد بواسطة</p>
                                <p class="text-sm text-gray-600">{{ $contract->approvedBy->name }}</p>
                            </div>
                        </div>
                        @endif
                        @if($contract->auto_renewal)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-400 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">تجديد تلقائي</p>
                                <p class="text-sm text-gray-600">مفعل</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Quick Stats -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">إحصائيات سريعة</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">قيمة العقد</span>
                        <span class="text-sm font-medium text-gray-900">{{ number_format($contract->value) }} ج.م</span>
                    </div>
                    @if($contract->end_date)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">مدة العقد</span>
                        <span class="text-sm font-medium text-gray-900">{{ $contract->duration_days }} يوم</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">الأيام المتبقية</span>
                        <span class="text-sm font-medium {{ $contract->days_until_expiry < 0 ? 'text-red-600' : ($contract->days_until_expiry <= 30 ? 'text-orange-600' : 'text-gray-900') }}">
                            {{ $contract->days_until_expiry }} يوم
                        </span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">الحالة</span>
                        <span class="text-sm font-medium text-gray-900">{{ $contract->status_name }}</span>
                    </div>
                </div>
            </div>

            <!-- Related Invoices -->
            @if($contract->invoices->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">الفواتير المرتبطة</h3>
                    <span class="text-sm text-gray-500">{{ $contract->invoices->count() }} فاتورة</span>
                </div>
                <div class="space-y-3">
                    @foreach($contract->invoices->take(5) as $invoice)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-purple-600 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $invoice->invoice_number }}</p>
                                <p class="text-xs text-gray-500">{{ number_format($invoice->total_amount, 2) }} ج.م</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($invoice->status == 'paid') bg-green-100 text-green-800
                                @elseif($invoice->status == 'overdue') bg-red-100 text-red-800
                                @elseif($invoice->status == 'sent' || $invoice->status == 'viewed') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @if($invoice->status == 'paid') مدفوع
                                @elseif($invoice->status == 'overdue') متأخر
                                @elseif($invoice->status == 'sent') مرسل
                                @elseif($invoice->status == 'viewed') تم المشاهدة
                                @elseif($invoice->status == 'draft') مسودة
                                @else {{ $invoice->status }}
                                @endif
                            </span>
                            <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-800">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                    @if($contract->invoices->count() > 5)
                    <div class="text-center pt-2">
                        <a href="{{ route('invoices.index', ['contract_id' => $contract->id]) }}" class="text-sm text-blue-600 hover:text-blue-800">
                            عرض جميع الفواتير ({{ $contract->invoices->count() }})
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">الإجراءات</h3>
                <div class="space-y-3">
                    <a href="{{ route('contracts.edit', $contract) }}" class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        تعديل العقد
                    </a>
                    @if($contract->status == 'active')
                        @if($existingInvoice)
                        <a href="{{ route('invoices.show', $existingInvoice) }}" class="w-full flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200">
                            <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            عرض الفاتورة
                        </a>
                        @else
                        <form action="{{ route('contracts.generate-invoice', $contract) }}" method="POST" class="w-full" onsubmit="return confirm('هل تريد إنشاء فاتورة لهذا العقد؟')">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200">
                                <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                إنشاء فاتورة
                            </button>
                        </form>
                        @endif
                    @endif
                    @if(in_array($contract->status, ['active', 'expired']))
                    <form action="{{ route('contracts.renew', $contract) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors duration-200">
                            <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            تجديد العقد
                        </button>
                    </form>
                    @endif
                    <a href="mailto:{{ $contract->client->email }}" class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                        <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        إرسال بريد إلكتروني
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('{{ session('success') }}', 'success');
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('{{ session('error') }}', 'error');
    });
</script>
@endif

@if(session('info'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('{{ session('info') }}', 'info');
    });
</script>
@endif

<script>
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white font-medium transform translate-x-full transition-all duration-300 ${colors[type] || colors.success}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}
</script>
@endsection

