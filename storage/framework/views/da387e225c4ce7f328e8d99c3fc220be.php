

<?php $__env->startSection('page-title', 'خدماتي واشتراكاتي'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-extrabold font-cairo text-gray-900">خدماتي واشتراكاتي</h1>
        <p class="text-gray-600 text-sm mt-1">عقود الخدمة المستمرة (استضافة، صيانة، دعم…) والفواتير الشهرية المرتبطة بها.</p>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <article class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 sm:p-6 border-b border-gray-100 flex flex-wrap items-start justify-between gap-3">
            <div>
                <div class="text-xs text-gray-500 font-mono"><?php echo e($service->service_number); ?></div>
                <h2 class="text-lg font-extrabold text-gray-900 mt-1"><?php echo e($service->title); ?></h2>
                <?php if($service->description): ?>
                    <p class="text-sm text-gray-600 mt-2"><?php echo e($service->description); ?></p>
                <?php endif; ?>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold
                <?php if($service->status === 'active'): ?> bg-emerald-100 text-emerald-800
                <?php elseif($service->status === 'paused'): ?> bg-amber-100 text-amber-800
                <?php else: ?> bg-gray-100 text-gray-700 <?php endif; ?>">
                <?php echo e($service->status_name); ?>

            </span>
        </div>
        <div class="p-5 sm:p-6 grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
            <div>
                <div class="text-gray-500 text-xs">الاشتراك الشهري</div>
                <div class="font-extrabold text-lg" style="color: var(--brand)"><?php echo e(number_format($service->monthly_amount, 2)); ?> <?php echo e($service->currency); ?></div>
            </div>
            <div>
                <div class="text-gray-500 text-xs">يوم الفوترة</div>
                <div class="font-bold">يوم <?php echo e($service->billing_day); ?></div>
            </div>
            <div>
                <div class="text-gray-500 text-xs">الفاتورة القادمة</div>
                <div class="font-bold"><?php echo e($service->next_billing_date?->format('Y-m-d') ?? '—'); ?></div>
            </div>
            <div>
                <div class="text-gray-500 text-xs">بداية الخدمة</div>
                <div class="font-bold"><?php echo e($service->start_date?->format('Y-m-d')); ?></div>
            </div>
        </div>
        <?php if($service->financialInvoices->isNotEmpty()): ?>
        <div class="px-5 sm:px-6 pb-5">
            <h3 class="text-xs font-bold text-gray-500 uppercase mb-2">آخر الفواتير</h3>
            <ul class="divide-y border rounded-xl overflow-hidden">
                <?php $__currentLoopData = $service->financialInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="flex flex-wrap items-center justify-between gap-2 px-4 py-3 bg-gray-50 text-sm">
                    <span class="font-mono"><?php echo e($inv->invoice_number); ?></span>
                    <span><?php echo e($inv->invoice_date?->format('Y-m-d')); ?></span>
                    <span class="font-bold"><?php echo e(number_format($inv->total_amount, 2)); ?> <?php echo e($inv->currency ?? 'EGP'); ?></span>
                    <span class="text-xs font-bold <?php echo e($inv->payment_status === 'paid' ? 'text-emerald-700' : 'text-red-700'); ?>">
                        <?php echo e($inv->payment_status === 'paid' ? 'مدفوعة' : 'مستحقة'); ?>

                    </span>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <a href="<?php echo e(route('client.invoices')); ?>" class="inline-block mt-3 text-sm font-semibold text-blue-700 hover:underline">عرض كل الفواتير والدفع ←</a>
        </div>
        <?php endif; ?>
    </article>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="bg-white border rounded-2xl p-12 text-center text-gray-500">
        <p>لا توجد خدمات أو اشتراكات مسجّلة لحسابك حالياً.</p>
        <p class="text-sm mt-2">عند تفعيل عقد خدمة من قبل فريقنا، ستظهر هنا مع الفواتير الشهرية.</p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\client-portal\services.blade.php ENDPATH**/ ?>