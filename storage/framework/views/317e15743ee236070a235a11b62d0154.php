<?php $__env->startSection('page-title', 'Pre-Sales — تقدير وتسعير'); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full px-2 sm:px-0">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'Pre-Sales',
        'subtitle' => 'تقدير التكلفة · إصدار العروض · بين التأهيل والعقد',
        'icon' => 'doc',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <?php $__currentLoopData = [
            ['label' => 'في الطابور', 'value' => $stats['queue'], 'color' => $themeColor],
            ['label' => 'بانتظار تقدير', 'value' => $stats['needs_estimate'], 'color' => '#d97706'],
            ['label' => 'عروض مُرسلة', 'value' => $stats['proposals_sent'], 'color' => '#2563eb'],
            ['label' => 'عروض مقبولة', 'value' => $stats['proposals_accepted'], 'color' => '#059669'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5 hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5">
            <p class="text-xs text-gray-500 mb-1 font-tajawal"><?php echo e($card['label']); ?></p>
            <p class="text-3xl font-bold font-tajawal" style="color: <?php echo e($card['color']); ?>;"><?php echo e($card['value']); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <form method="GET" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6 flex flex-wrap gap-3 items-end">
        <select name="filter" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-tajawal">
            <option value="">كل الفرص المؤهّلة</option>
            <option value="needs_estimate" <?php if(request('filter')==='needs_estimate'): echo 'selected'; endif; ?>>تحتاج تقدير تكلفة</option>
            <option value="needs_proposal" <?php if(request('filter')==='needs_proposal'): echo 'selected'; endif; ?>>جاهزة لإصدار عرض</option>
            <option value="pending_client" <?php if(request('filter')==='pending_client'): echo 'selected'; endif; ?>>بانتظار رد العميل</option>
        </select>
        <button class="px-5 py-2.5 rounded-xl text-white text-sm font-bold font-tajawal"
                style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">تصفية</button>
    </form>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <table class="w-full text-sm font-tajawal">
            <thead style="background: <?php echo e($themeColor); ?>08;">
                <tr>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">العميل / الخدمة</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">مندوب المبيعات</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">التقدير</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">العرض</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">المرحلة</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $est = $sale->costEstimations->sortByDesc('id')->first();
                    $prop = $sale->proposals->sortByDesc('id')->first();
                ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-4">
                        <p class="font-bold text-gray-900"><?php echo e($sale->client?->name); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($sale->product_service); ?></p>
                    </td>
                    <td class="px-4 py-4 text-gray-600"><?php echo e($sale->salesRep?->name ?? '—'); ?></td>
                    <td class="px-4 py-4">
                        <?php if($est): ?>
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700"><?php echo e($est->status_label); ?></span>
                            <p class="text-xs text-gray-500 mt-1"><?php echo e(number_format($est->total_cost)); ?> ج.م</p>
                        <?php else: ?>
                            <span class="text-amber-600 text-xs font-bold">مطلوب</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-4">
                        <?php if($prop): ?>
                            <span class="px-2 py-1 rounded-full text-xs font-bold <?php echo e($prop->status_color); ?>"><?php echo e($prop->status_label); ?></span>
                        <?php else: ?>
                            <span class="text-gray-400 text-xs">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-4 text-gray-600"><?php echo e($sale->stage); ?></td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <a href="<?php echo e(route('pre-sales.estimate', $sale)); ?>" class="font-bold text-sm" style="color: <?php echo e($themeColor); ?>;">تقدير</a>
                        <?php if($prop): ?>
                            · <a href="<?php echo e(route('pre-sales.proposals.show', $prop)); ?>" class="text-blue-600 font-bold text-sm">عرض</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="px-4 py-16 text-center text-gray-500">لا توجد فرص مؤهّلة في طابور Pre-Sales.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="px-4 py-3 border-t"><?php echo e($sales->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\pre-sales\index.blade.php ENDPATH**/ ?>