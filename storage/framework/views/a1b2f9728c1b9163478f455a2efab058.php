

<?php $__env->startSection('page-title', $clientService->title); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-4 mb-8">
        <div>
            <a href="<?php echo e(route('accounting.client-services.index')); ?>" class="text-sm text-blue-600 font-semibold hover:underline">← جميع الخدمات</a>
            <h1 class="text-3xl font-bold text-gray-900 mt-2"><?php echo e($clientService->title); ?></h1>
            <p class="text-gray-500 font-mono text-sm"><?php echo e($clientService->service_number); ?></p>
        </div>
        <div class="flex flex-wrap gap-2">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-finance')): ?>
            <?php if($clientService->status === 'active'): ?>
            <form method="POST" action="<?php echo e(route('accounting.client-services.generate-invoice', $clientService)); ?>" onsubmit="return confirm('إصدار فاتورة لهذا الشهر الآن؟')">
                <?php echo csrf_field(); ?>
                <button type="submit" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-emerald-700">إصدار فاتورة الآن</button>
            </form>
            <?php endif; ?>
            <a href="<?php echo e(route('accounting.client-services.edit', $clientService)); ?>" class="px-5 py-2.5 border rounded-xl font-bold hover:bg-gray-50">تعديل</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if(session('success')): ?><div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg"><?php echo e(session('success')); ?></div><?php endif; ?>
    <?php if(session('error')): ?><div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg"><?php echo e(session('error')); ?></div><?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white border rounded-xl p-6 shadow-sm space-y-4">
            <h2 class="font-bold text-lg border-b pb-2">تفاصيل الاشتراك</h2>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div><dt class="text-gray-500">العميل</dt><dd class="font-bold"><?php echo e($clientService->client?->name); ?></dd></div>
                <div><dt class="text-gray-500">العقد</dt><dd class="font-bold"><?php if($clientService->contract): ?><a href="<?php echo e(route('contracts.show', $clientService->contract)); ?>" class="text-blue-600 hover:underline"><?php echo e($clientService->contract->contract_number ?? '#'.$clientService->contract_id); ?></a><?php else: ?> — <?php endif; ?></dd></div>
                <div><dt class="text-gray-500">المبلغ الشهري</dt><dd class="font-bold text-blue-700"><?php echo e(number_format($clientService->monthly_amount, 2)); ?> <?php echo e($clientService->currency); ?></dd></div>
                <div><dt class="text-gray-500">يوم الفوترة</dt><dd class="font-bold">يوم <?php echo e($clientService->billing_day); ?> من كل شهر</dd></div>
                <div><dt class="text-gray-500">الفوترة التلقائية</dt><dd><?php echo e($clientService->auto_invoice ? 'مفعّلة' : 'معطّلة'); ?></dd></div>
                <div><dt class="text-gray-500">موعد الفوترة القادم</dt><dd class="font-bold"><?php echo e($clientService->next_billing_date?->format('Y-m-d') ?? '—'); ?></dd></div>
                <div><dt class="text-gray-500">من — إلى</dt><dd><?php echo e($clientService->start_date?->format('Y-m-d')); ?> <?php if($clientService->end_date): ?>— <?php echo e($clientService->end_date->format('Y-m-d')); ?><?php endif; ?></dd></div>
                <div><dt class="text-gray-500">مهلة السداد</dt><dd><?php echo e($clientService->payment_terms_days); ?> يوماً بعد الإصدار</dd></div>
            </dl>
            <?php if($clientService->description): ?>
                <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-4"><?php echo e($clientService->description); ?></p>
            <?php endif; ?>
        </div>
        <div class="bg-white border rounded-xl p-6 shadow-sm">
            <p class="text-sm text-gray-500 mb-1">الحالة</p>
            <p class="text-2xl font-bold mb-4"><?php echo e($clientService->status_name); ?></p>
            <p class="text-xs text-gray-500">يظهر للعميل في بوابة «خدماتي واشتراكاتي» مع سجل الفواتير المرتبطة.</p>
        </div>
    </div>

    <div class="bg-white border rounded-xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-bold">فواتير الخدمة</h2>
            <a href="<?php echo e(route('financial-invoices.index', ['client_id' => $clientService->client_id])); ?>" class="text-sm text-blue-600 font-semibold">كل فواتير العميل</a>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right">رقم الفاتورة</th>
                    <th class="px-4 py-3 text-right">التاريخ</th>
                    <th class="px-4 py-3 text-right">الاستحقاق</th>
                    <th class="px-4 py-3 text-right">المبلغ</th>
                    <th class="px-4 py-3 text-right">الدفع</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $clientService->financialInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-4 py-3 font-mono"><?php echo e($inv->invoice_number); ?></td>
                    <td class="px-4 py-3"><?php echo e($inv->invoice_date?->format('Y-m-d')); ?></td>
                    <td class="px-4 py-3"><?php echo e($inv->due_date?->format('Y-m-d')); ?></td>
                    <td class="px-4 py-3 font-bold"><?php echo e(number_format($inv->total_amount, 2)); ?></td>
                    <td class="px-4 py-3">
                        <span class="text-xs font-bold px-2 py-0.5 rounded <?php echo e($inv->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                            <?php echo e($inv->payment_status === 'paid' ? 'مدفوعة' : 'غير مدفوعة'); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3"><a href="<?php echo e(route('financial-invoices.show', $inv)); ?>" class="text-blue-600 font-bold">عرض</a></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">لم تُصدر فواتير بعد.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\accounting\client-services\show.blade.php ENDPATH**/ ?>