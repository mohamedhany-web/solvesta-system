<?php $__env->startSection('page-title', 'عرض الفاتورة'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('invoices._invoice-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="invoice-page-wrap w-full">
    <div class="mb-6 no-print">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">عرض الفاتورة</h1>
                <p class="text-sm text-gray-600">رقم <?php echo e($invoice->invoice_number); ?></p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="<?php echo e(request()->routeIs('financial-invoices.*') ? route('financial-invoices.index') : route('invoices.index')); ?>" class="bg-gray-600 text-white px-5 py-2.5 rounded-lg hover:bg-gray-700 text-sm font-semibold inline-flex items-center">
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    العودة
                </a>
                <?php
                    $canPay = !in_array($invoice->status, ['paid', 'cancelled']) && ($invoice->balance_due ?? 0) > 0;
                ?>
                <?php if($canPay && (request()->routeIs('financial-invoices.*') || request()->routeIs('invoices.*'))): ?>
                <button type="button" onclick="openPaymentModal()" class="bg-green-600 text-white px-5 py-2.5 rounded-lg hover:bg-green-700 text-sm font-semibold inline-flex items-center">
                    تسجيل دفعة
                </button>
                <?php endif; ?>
                <a href="<?php echo e(request()->routeIs('financial-invoices.*') ? route('financial-invoices.print', $invoice) : route('invoices.print', $invoice)); ?>"
                   target="_blank"
                   class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 text-sm font-semibold inline-flex items-center">
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    طباعة / PDF
                </a>
            </div>
        </div>
    </div>

    <?php echo $__env->make('invoices._document', ['invoice' => $invoice], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>

<?php if(request()->routeIs('financial-invoices.*') || request()->routeIs('invoices.*')): ?>
<?php
    $paid = (float) ($invoice->paid_amount ?? 0);
    $due = (float) ($invoice->balance_due ?? 0);
?>
<div class="no-print invoice-page-wrap mt-8 space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white border rounded-xl p-5 shadow-sm">
            <p class="text-sm text-gray-600">إجمالي الفاتورة</p>
            <p class="text-2xl font-bold"><?php echo e(number_format($invoice->total_amount, 2)); ?> ج.م</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-5">
            <p class="text-sm text-green-700">مدفوع</p>
            <p class="text-2xl font-bold text-green-800"><?php echo e(number_format($paid, 2)); ?> ج.م</p>
        </div>
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
            <p class="text-sm text-amber-700">متبقي</p>
            <p class="text-2xl font-bold text-amber-800"><?php echo e(number_format($due, 2)); ?> ج.م</p>
        </div>
    </div>

    <div class="bg-white border rounded-xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-bold">سجل الدفعات</h2>
            <?php if($due > 0): ?>
            <button type="button" onclick="openPaymentModal()" class="text-sm bg-green-600 text-white px-4 py-2 rounded-lg font-bold">+ دفعة</button>
            <?php endif; ?>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right">التاريخ</th>
                        <th class="px-4 py-3 text-right">المبلغ</th>
                        <th class="px-4 py-3 text-right">المحفظة</th>
                        <th class="px-4 py-3 text-right">الطريقة</th>
                        <th class="px-4 py-3 text-right">المرجع</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php $__empty_1 = true; $__currentLoopData = $invoice->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-4 py-3"><?php echo e($p->payment_date?->format('Y/m/d')); ?></td>
                        <td class="px-4 py-3 font-bold text-green-700"><?php echo e(number_format($p->amount, 2)); ?> ج.م</td>
                        <td class="px-4 py-3"><?php echo e($p->wallet?->name ?? '—'); ?></td>
                        <td class="px-4 py-3"><?php echo e($p->payment_method); ?></td>
                        <td class="px-4 py-3"><?php echo e($p->payment_number); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">لا توجد دفعات بعد</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php echo $__env->make('invoices._payment-modal', ['invoice' => $invoice, 'wallets' => $wallets ?? collect()], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>

<?php if(session('success')): ?>
<script class="no-print">
document.addEventListener('DOMContentLoaded', () => showNotification(<?php echo json_encode(session('success'), 15, 512) ?>, 'success'));
</script>
<?php endif; ?>

<script class="no-print">
function showNotification(message, type) {
    const el = document.createElement('div');
    el.className = 'fixed top-4 right-4 z-[100] px-6 py-3 rounded-lg shadow-lg text-white font-medium ' + (type === 'success' ? 'bg-green-600' : 'bg-red-600');
    el.textContent = message;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 3000);
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\invoices\show.blade.php ENDPATH**/ ?>