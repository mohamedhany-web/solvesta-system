

<?php $__env->startSection('page-title', 'دفعة '.$payment->payment_number); ?>

<?php $__env->startSection('content'); ?>
<?php
    $methodName = match($payment->payment_method) {
        'cash' => 'نقدي',
        'bank_transfer' => 'تحويل بنكي',
        'check' => 'شيك',
        'credit_card' => 'بطاقة ائتمان',
        'online' => 'دفع إلكتروني',
        default => $payment->payment_method,
    };
    $typeName = match($payment->payment_type) {
        'invoice' => 'تحصيل فاتورة',
        'salary' => 'راتب',
        'expense' => 'مصروف',
        'other' => 'أخرى',
        default => $payment->payment_type,
    };
    $statusName = match($payment->status) {
        'completed' => 'مكتملة',
        'pending' => 'معلقة',
        'failed' => 'فاشلة',
        'cancelled' => 'ملغية',
        default => $payment->status,
    };
    $statusColor = match($payment->status) {
        'completed' => 'bg-green-100 text-green-800',
        'pending' => 'bg-amber-100 text-amber-800',
        'cancelled', 'failed' => 'bg-red-100 text-red-800',
        default => 'bg-gray-100 text-gray-800',
    };
    $isIncoming = $payment->payment_type === 'invoice' || ($payment->client_id && ! in_array($payment->payment_type, ['salary', 'expense'], true));
    $linkedInvoice = $payment->invoice ?? $payment->projectInvoice;
    $invoiceRoute = $payment->invoice
        ? route('financial-invoices.show', $payment->invoice)
        : ($payment->projectInvoice ? route('invoices.show', $payment->projectInvoice) : null);
?>

<div class="w-full max-w-4xl mx-auto">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="<?php echo e(route('payments.index')); ?>" class="text-sm text-blue-600 font-semibold hover:underline">← المدفوعات</a>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2">تفاصيل الدفعة</h1>
            <p class="text-gray-500 font-mono text-sm mt-1"><?php echo e($payment->payment_number); ?></p>
        </div>
        <span class="inline-flex self-start px-4 py-2 rounded-full text-sm font-bold <?php echo e($statusColor); ?>"><?php echo e($statusName); ?></span>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-8 text-center border-b border-gray-100 <?php echo e($isIncoming ? 'bg-green-50' : 'bg-red-50'); ?>">
            <p class="text-sm text-gray-600 mb-1"><?php echo e($typeName); ?></p>
            <p class="text-4xl font-black <?php echo e($isIncoming ? 'text-green-700' : 'text-red-700'); ?>">
                <?php echo e($isIncoming ? '+' : '−'); ?><?php echo e(number_format($payment->amount, 2)); ?>

                <span class="text-lg font-bold text-gray-600">ج.م</span>
            </p>
            <p class="text-sm text-gray-500 mt-2"><?php echo e($payment->payment_date?->format('Y/m/d')); ?> — <?php echo e($methodName); ?></p>
        </div>

        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-0 divide-y sm:divide-y-0 sm:divide-x divide-x-reverse divide-gray-100">
            <div class="p-5 sm:col-span-2 sm:border-b border-gray-100">
                <dt class="text-xs font-bold text-gray-500 uppercase mb-1">الوصف</dt>
                <dd class="text-gray-900"><?php echo e($payment->description); ?></dd>
            </div>
            <div class="p-5">
                <dt class="text-xs font-bold text-gray-500 uppercase mb-1">نوع الدفعة</dt>
                <dd class="font-semibold text-gray-900"><?php echo e($typeName); ?></dd>
            </div>
            <div class="p-5">
                <dt class="text-xs font-bold text-gray-500 uppercase mb-1">طريقة الدفع</dt>
                <dd class="font-semibold text-gray-900"><?php echo e($methodName); ?></dd>
            </div>
            <?php if($payment->wallet): ?>
            <div class="p-5">
                <dt class="text-xs font-bold text-gray-500 uppercase mb-1">المحفظة</dt>
                <dd>
                    <a href="<?php echo e(route('accounting.wallets.show', $payment->wallet)); ?>" class="font-semibold text-blue-600 hover:underline">
                        <?php echo e($payment->wallet->name); ?>

                    </a>
                </dd>
            </div>
            <?php endif; ?>
            <?php if($payment->reference_number): ?>
            <div class="p-5">
                <dt class="text-xs font-bold text-gray-500 uppercase mb-1">المرجع</dt>
                <dd class="font-mono text-sm"><?php echo e($payment->reference_number); ?></dd>
            </div>
            <?php endif; ?>
            <?php if($payment->client): ?>
            <div class="p-5">
                <dt class="text-xs font-bold text-gray-500 uppercase mb-1">العميل</dt>
                <dd class="font-semibold text-gray-900"><?php echo e($payment->client->name); ?></dd>
            </div>
            <?php endif; ?>
            <?php if($payment->employee): ?>
            <div class="p-5">
                <dt class="text-xs font-bold text-gray-500 uppercase mb-1">الموظف</dt>
                <dd class="font-semibold text-gray-900"><?php echo e($payment->employee->name ?? trim($payment->employee->first_name.' '.$payment->employee->last_name)); ?></dd>
            </div>
            <?php endif; ?>
            <?php if($linkedInvoice && $invoiceRoute): ?>
            <div class="p-5 sm:col-span-2 border-t border-gray-100">
                <dt class="text-xs font-bold text-gray-500 uppercase mb-1">الفاتورة المرتبطة</dt>
                <dd>
                    <a href="<?php echo e($invoiceRoute); ?>" class="font-semibold text-blue-600 hover:underline">
                        <?php echo e($linkedInvoice->invoice_number); ?>

                    </a>
                    <span class="text-xs text-gray-500 mr-2">
                        (<?php echo e($payment->invoice ? 'فاتورة مالية' : 'فاتورة مشروع'); ?>)
                    </span>
                </dd>
            </div>
            <?php endif; ?>
            <?php if($payment->bankAccount): ?>
            <div class="p-5">
                <dt class="text-xs font-bold text-gray-500 uppercase mb-1">حساب بنكي</dt>
                <dd class="text-sm"><?php echo e($payment->bankAccount->name); ?> (<?php echo e($payment->bankAccount->code); ?>)</dd>
            </div>
            <?php endif; ?>
            <?php if($payment->creator): ?>
            <div class="p-5">
                <dt class="text-xs font-bold text-gray-500 uppercase mb-1">سجّلها</dt>
                <dd class="text-sm text-gray-800"><?php echo e($payment->creator->name); ?></dd>
            </div>
            <?php endif; ?>
            <?php if($payment->notes): ?>
            <div class="p-5 sm:col-span-2 border-t border-gray-100 bg-gray-50">
                <dt class="text-xs font-bold text-gray-500 uppercase mb-1">ملاحظات</dt>
                <dd class="text-sm text-gray-700 whitespace-pre-line"><?php echo e($payment->notes); ?></dd>
            </div>
            <?php endif; ?>
        </dl>
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="<?php echo e(route('payments.index')); ?>" class="px-5 py-2.5 border border-gray-300 rounded-xl font-semibold text-gray-700 hover:bg-gray-50">العودة للقائمة</a>
        <?php if($invoiceRoute): ?>
        <a href="<?php echo e($invoiceRoute); ?>" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700">عرض الفاتورة</a>
        <?php endif; ?>
        <?php if($payment->wallet): ?>
        <a href="<?php echo e(route('accounting.wallets.show', $payment->wallet)); ?>" class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl font-semibold hover:bg-emerald-700">عرض المحفظة</a>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\payments\show.blade.php ENDPATH**/ ?>