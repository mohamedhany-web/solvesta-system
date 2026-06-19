<?php $__env->startSection('page-title', 'عرض سعر — '.$proposal->reference_code); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full px-2 sm:px-0">
    <div class="flex flex-wrap justify-between items-start gap-4 mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="<?php echo e(route('sales.show', $proposal->sale)); ?>" class="text-sm font-bold px-4 py-2 rounded-xl border bg-white hover:shadow-md">← الفرصة</a>
            <a href="<?php echo e(route('pre-sales.estimate', $proposal->sale)); ?>" class="text-sm font-bold px-4 py-2 rounded-xl border bg-white hover:shadow-md">التقدير</a>
        </div>
        <div class="flex flex-wrap gap-2">
            <button onclick="window.print()" class="text-sm font-bold px-4 py-2 rounded-xl border border-gray-200 bg-white hover:shadow-md print:hidden">طباعة / PDF</button>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-sales')): ?>
                <?php if($proposal->status === 'draft'): ?>
                <form method="POST" action="<?php echo e(route('pre-sales.proposals.sent', $proposal)); ?>"><?php echo csrf_field(); ?>
                    <button class="text-sm font-bold px-4 py-2 rounded-xl bg-blue-600 text-white">تسجيل إرسال للعميل</button>
                </form>
                <?php endif; ?>
                <?php if(in_array($proposal->status, ['draft','sent'])): ?>
                <form method="POST" action="<?php echo e(route('pre-sales.proposals.accept', $proposal)); ?>"><?php echo csrf_field(); ?>
                    <button class="text-sm font-bold px-4 py-2 rounded-xl bg-emerald-600 text-white">موافقة العميل ✓</button>
                </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden print:shadow-none print:border-0" id="proposal-doc">
        <div class="p-6 sm:p-10 border-b" style="background: linear-gradient(135deg, <?php echo e($themeColor); ?>12 0%, <?php echo e($themeColor); ?>05 100%);">
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-mono text-gray-500 mb-2"><?php echo e($proposal->reference_code); ?></p>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 font-tajawal"><?php echo e($proposal->title); ?></h1>
                    <p class="text-gray-600 mt-2 font-tajawal">العميل: <strong><?php echo e($proposal->sale->client?->name); ?></strong></p>
                </div>
                <div class="text-left sm:text-right">
                    <span class="inline-flex px-4 py-2 rounded-full text-sm font-bold <?php echo e($proposal->status_color); ?>"><?php echo e($proposal->status_label); ?></span>
                    <p class="text-3xl font-bold mt-3 font-tajawal" style="color: <?php echo e($themeColor); ?>;"><?php echo e(number_format($proposal->total_price)); ?> <span class="text-lg">ج.م</span></p>
                    <?php if($proposal->valid_until): ?>
                        <p class="text-xs text-gray-500 mt-1">صالح حتى <?php echo e($proposal->valid_until->locale('ar')->translatedFormat('d F Y')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-10 space-y-8 font-tajawal text-sm sm:text-base leading-relaxed">
            <?php if($proposal->project_description): ?>
            <section>
                <h2 class="text-lg font-bold mb-3 flex items-center gap-2" style="color: <?php echo e($themeColor); ?>;">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm" style="background: <?php echo e($themeColor); ?>;">1</span>
                    وصف المشروع
                </h2>
                <div class="bg-gray-50 rounded-xl p-5 whitespace-pre-wrap text-gray-700"><?php echo e($proposal->project_description); ?></div>
            </section>
            <?php endif; ?>

            <section>
                <h2 class="text-lg font-bold mb-3 flex items-center gap-2" style="color: <?php echo e($themeColor); ?>;">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm" style="background: <?php echo e($themeColor); ?>;">2</span>
                    النطاق
                </h2>
                <div class="bg-gray-50 rounded-xl p-5 whitespace-pre-wrap text-gray-700"><?php echo e($proposal->scope); ?></div>
            </section>

            <section>
                <h2 class="text-lg font-bold mb-3 flex items-center gap-2" style="color: <?php echo e($themeColor); ?>;">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm" style="background: <?php echo e($themeColor); ?>;">3</span>
                    الجدول الزمني
                </h2>
                <div class="bg-gray-50 rounded-xl p-5 whitespace-pre-wrap text-gray-700"><?php echo e($proposal->timeline); ?></div>
            </section>

            <section>
                <h2 class="text-lg font-bold mb-3 flex items-center gap-2" style="color: <?php echo e($themeColor); ?>;">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm" style="background: <?php echo e($themeColor); ?>;">4</span>
                    تفصيل التكلفة
                </h2>
                <div class="bg-gray-50 rounded-xl p-5 whitespace-pre-wrap text-gray-700 font-mono text-sm"><?php echo e($proposal->pricing_breakdown); ?></div>
            </section>

            <section class="rounded-2xl p-6 border-2" style="border-color: <?php echo e($themeColor); ?>40; background: <?php echo e($themeColor); ?>08;">
                <h2 class="text-lg font-bold mb-2" style="color: <?php echo e($themeColor); ?>;">شروط الدفع</h2>
                <p class="text-gray-800"><?php echo e($proposal->payment_terms); ?></p>
                <p class="mt-4 text-2xl font-bold" style="color: <?php echo e($themeColor); ?>;">الإجمالي: <?php echo e(number_format($proposal->total_price)); ?> جنيه مصري</p>
            </section>
        </div>
    </div>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-sales')): ?>
    <?php if(in_array($proposal->status, ['draft','sent'])): ?>
    <div class="mt-6 bg-white rounded-2xl border border-red-100 p-6 print:hidden">
        <h3 class="font-bold text-red-800 mb-3">رفض العرض</h3>
        <form method="POST" action="<?php echo e(route('pre-sales.proposals.reject', $proposal)); ?>" class="flex flex-wrap gap-3">
            <?php echo csrf_field(); ?>
            <input name="rejection_reason" required placeholder="سبب الرفض..." class="flex-1 min-w-[200px] border rounded-xl px-4 py-2 text-sm">
            <button class="px-5 py-2 rounded-xl bg-red-600 text-white font-bold text-sm">تسجيل الرفض</button>
        </form>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<style>
@media print {
    .sidebar, nav, header, .print\\:hidden { display: none !important; }
    main { padding: 0 !important; }
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\pre-sales\proposal.blade.php ENDPATH**/ ?>