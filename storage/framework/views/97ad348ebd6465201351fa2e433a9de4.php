<?php $__env->startSection('page-title', 'المدفوعات'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إدارة المدفوعات</h1>
                <p class="text-gray-600">تتبع وإدارة جميع المدفوعات الواردة والصادرة</p>
            </div>
            <div class="flex gap-3">
                <a href="<?php echo e(route('payments.create')); ?>" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-200 flex items-center shadow-sm">
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    دفعة جديدة
                </a>
                <button onclick="exportPayments()" class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-all duration-200 flex items-center shadow-sm">
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    تصدير تقرير
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Payments -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl shadow-md border border-blue-200 p-6 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-blue-700 mb-1">إجمالي المدفوعات</p>
                    <p class="text-3xl font-bold text-blue-900"><?php echo e($totalPayments); ?></p>
                    <p class="text-xs text-blue-600 mt-1"><?php echo e($monthlyPayments); ?> هذا الشهر</p>
                </div>
                <div class="p-4 bg-blue-500 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Incoming Payments -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl shadow-md border border-green-200 p-6 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-green-700 mb-1">مدفوعات واردة</p>
                    <p class="text-3xl font-bold text-green-900"><?php echo e($incomingPayments); ?></p>
                    <p class="text-xs text-green-600 mt-1"><?php echo e(number_format($totalIncoming)); ?> ج.م</p>
                </div>
                <div class="p-4 bg-green-500 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Outgoing Payments -->
        <div class="bg-gradient-to-br from-red-50 to-rose-100 rounded-xl shadow-md border border-red-200 p-6 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-red-700 mb-1">مدفوعات صادرة</p>
                    <p class="text-3xl font-bold text-red-900"><?php echo e($outgoingPayments); ?></p>
                    <p class="text-xs text-red-600 mt-1"><?php echo e(number_format($totalOutgoing)); ?> ج.م</p>
                </div>
                <div class="p-4 bg-red-500 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-gradient-to-br from-yellow-50 to-orange-100 rounded-xl shadow-md border border-yellow-200 p-6 hover:shadow-lg transition-all duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-yellow-700 mb-1">معلقة</p>
                    <p class="text-3xl font-bold text-yellow-900"><?php echo e($pendingPayments); ?></p>
                    <p class="text-xs text-yellow-600 mt-1"><?php echo e(number_format($pendingAmount)); ?> ج.م</p>
                </div>
                <div class="p-4 bg-yellow-500 rounded-xl">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                <input type="text" id="search" placeholder="رقم الدفعة أو الوصف..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">النوع</label>
                <select id="type_filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">جميع الأنواع</option>
                    <option value="incoming">وارد</option>
                    <option value="outgoing">صادر</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">طريقة الدفع</label>
                <select id="method_filter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">جميع الطرق</option>
                    <option value="cash">نقدي</option>
                    <option value="bank_transfer">تحويل بنكي</option>
                    <option value="check">شيك</option>
                    <option value="credit_card">بطاقة ائتمان</option>
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

    <!-- Payments Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">قائمة المدفوعات</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">رقم الدفعة</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">التاريخ</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">الوصف</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">المبلغ</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">طريقة الدفع</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">النوع / المرتبط</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900 bg-gray-100 px-2 py-1 rounded inline-block"><?php echo e($payment->payment_number); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($payment->payment_date->format('Y/m/d')); ?>

                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <?php echo e($payment->description); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php
                                $isIncoming = $payment->payment_type === 'invoice' || ($payment->client_id && $payment->payment_type !== 'salary' && $payment->payment_type !== 'expense');
                            ?>
                            <span class="text-sm font-bold <?php echo e($isIncoming ? 'text-green-600' : 'text-red-600'); ?>">
                                <?php echo e($isIncoming ? '+' : '-'); ?><?php echo e(number_format($payment->amount)); ?> <span class="text-xs text-gray-500">ج.م</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php
                                $methodName = match($payment->payment_method) {
                                    'cash' => 'نقدي',
                                    'bank_transfer' => 'تحويل بنكي',
                                    'check' => 'شيك',
                                    'credit_card' => 'بطاقة ائتمان',
                                    'online' => 'دفع إلكتروني',
                                    default => $payment->payment_method
                                };
                            ?>
                            <?php echo e($methodName); ?>

                            <?php if($payment->bankAccount): ?>
                                <div class="text-xs text-gray-400 mt-1"><?php echo e($payment->bankAccount->name); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php
                                $typeName = match($payment->payment_type) {
                                    'invoice' => 'فاتورة',
                                    'salary' => 'راتب',
                                    'expense' => 'مصروف',
                                    'other' => 'أخرى',
                                    default => $payment->payment_type
                                };
                                $isIncoming = $payment->payment_type === 'invoice' || ($payment->client_id && $payment->payment_type !== 'salary' && $payment->payment_type !== 'expense');
                                $typeColor = $isIncoming ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                            ?>
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?php echo e($typeColor); ?>">
                                    <?php echo e($typeName); ?>

                                </span>
                                <?php if($payment->client): ?>
                                <div class="text-xs text-gray-600">
                                    <a href="<?php echo e(route('clients.show', $payment->client)); ?>" class="hover:text-blue-600">
                                        <?php echo e($payment->client->name); ?>

                                    </a>
                                </div>
                                <?php elseif($payment->employee): ?>
                                <div class="text-xs text-gray-600"><?php echo e($payment->employee->name); ?></div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php
                                $statusColor = match($payment->status) {
                                    'completed' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                $statusName = match($payment->status) {
                                    'completed' => 'مكتملة',
                                    'pending' => 'معلقة',
                                    'cancelled' => 'ملغية',
                                    default => $payment->status
                                };
                            ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?php echo e($statusColor); ?>">
                                <?php echo e($statusName); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="<?php echo e(route('payments.show', $payment)); ?>" class="text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1 rounded-lg hover:bg-blue-100 transition-colors duration-200">عرض</a>
                                <?php if($payment->status === 'pending'): ?>
                                <button onclick="markAsCompleted(<?php echo e($payment->id); ?>)" class="text-green-600 hover:text-green-800 bg-green-50 px-3 py-1 rounded-lg hover:bg-green-100 transition-colors duration-200">تأكيد</button>
                                <?php endif; ?>
                                <?php if($payment->status !== 'completed'): ?>
                                <button onclick="deletePayment(<?php echo e($payment->id); ?>)" class="text-red-600 hover:text-red-800 bg-red-50 px-3 py-1 rounded-lg hover:bg-red-100 transition-colors duration-200">حذف</button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="p-4 bg-gray-100 rounded-full mb-4">
                                    <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">لا توجد مدفوعات</h3>
                                <p class="text-gray-600 mb-4">ابدأ بإضافة دفعة جديدة</p>
                                <a href="<?php echo e(route('payments.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm font-medium">
                                    إضافة دفعة جديدة
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($payments->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <?php echo e($payments->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function markAsCompleted(paymentId) {
    if (confirm('هل أنت متأكد من تأكيد هذه الدفعة؟')) {
        fetch(`/payments/${paymentId}/mark-as-completed`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('تم تأكيد الدفعة بنجاح', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message || 'حدث خطأ', 'error');
            }
        })
        .catch(error => {
            showNotification('حدث خطأ في الاتصال', 'error');
        });
    }
}

function deletePayment(paymentId) {
    if (confirm('هل أنت متأكد من حذف هذه الدفعة؟\n\nملاحظة: لا يمكن التراجع عن هذا الإجراء.')) {
        fetch(`/payments/${paymentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('تم حذف الدفعة بنجاح', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message || 'حدث خطأ', 'error');
            }
        })
        .catch(error => {
            showNotification('حدث خطأ في الاتصال', 'error');
        });
    }
}

function exportPayments() {
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views/payments/index.blade.php ENDPATH**/ ?>