<?php $__env->startSection('page-title', 'تقدير التكلفة'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $e = $estimation;
    $v = fn($k, $default = '') => old($k, $e?->$k ?? $defaults[$k] ?? $default);
?>
<div class="w-full max-w-full px-2 sm:px-0">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'تقدير التكلفة — Pre-Sales',
        'subtitle' => $sale->client?->name.' · '.$sale->product_service,
        'icon' => 'briefcase',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap gap-2 mb-6">
        <a href="<?php echo e(route('sales.show', $sale)); ?>" class="text-sm font-bold px-4 py-2 rounded-xl border border-gray-200 bg-white hover:shadow-md transition">← الفرصة</a>
        <a href="<?php echo e(route('pre-sales.index')); ?>" class="text-sm font-bold px-4 py-2 rounded-xl border border-gray-200 bg-white hover:shadow-md transition">طابور Pre-Sales</a>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-tajawal"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2">
            <form method="POST" action="<?php echo e(route('pre-sales.estimate.store', $sale)); ?>" class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 space-y-5" id="estimate-form">
                <?php echo csrf_field(); ?>
                <h2 class="text-lg font-bold text-gray-900 font-tajawal border-b pb-3">مدخلات التقدير</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">عدد الشاشات</label>
                        <input type="number" name="screen_count" value="<?php echo e($v('screen_count', 0)); ?>" min="0"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm" id="screen_count">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">عدد المطورين</label>
                        <input type="number" name="developers_count" value="<?php echo e($v('developers_count', 1)); ?>" min="1"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <?php $__currentLoopData = ['dev'=>'تطوير','design'=>'تصميم','qa'=>'اختبار','pm'=>'إدارة مشروع']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">ساعات <?php echo e($label); ?></label>
                        <input type="number" step="0.5" name="<?php echo e($key); ?>_hours" value="<?php echo e($v($key.'_hours', $key==='dev'?120:($key==='design'?40:($key==='qa'?20:10)))); ?>"
                               class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm hours-input" data-role="<?php echo e($key); ?>">
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <?php $__currentLoopData = ['dev'=>500,'design'=>400,'qa'=>350,'pm'=>450]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $def): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">سعر ساعة <?php echo e($key); ?></label>
                        <input type="number" step="1" name="hourly_rate_<?php echo e($key); ?>" value="<?php echo e($v('hourly_rate_'.$key, $def)); ?>"
                               class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm rate-input" data-role="<?php echo e($key); ?>">
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">هامش ربح %</label>
                        <input type="number" step="0.5" name="margin_percent" value="<?php echo e($v('margin_percent', 15)); ?>"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm" id="margin_percent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">المدة (أسابيع)</label>
                        <input type="number" name="duration_weeks" value="<?php echo e($v('duration_weeks', 8)); ?>" min="1"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm" id="duration_weeks">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">نطاق العمل</label>
                    <textarea name="scope_notes" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm"><?php echo e($v('scope_notes')); ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات تقنية</label>
                    <textarea name="technical_notes" rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm"><?php echo e($v('technical_notes')); ?></textarea>
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-white font-bold text-sm"
                            style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">حفظ مسودة</button>
                    <button type="submit" name="submit_for_approval" value="1" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-bold text-sm">إرسال للاعتماد</button>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-sales')): ?>
                    <button type="submit" name="approve_now" value="1" class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-sm">حفظ واعتماد فوري</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 sticky top-4">
                <h3 class="font-bold text-gray-900 mb-4 font-tajawal">ملخص التكلفة</h3>
                <div id="live-summary" class="space-y-2 text-sm font-tajawal text-gray-700">
                    <p>المجموع الفرعي: <strong id="sum-subtotal">—</strong></p>
                    <p>بعد الهامش: <strong id="sum-total" class="text-xl" style="color: <?php echo e($themeColor); ?>;">—</strong></p>
                    <p class="text-xs text-gray-500">إجمالي الساعات: <span id="sum-hours">—</span></p>
                </div>
            </div>

            <?php if($e): ?>
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <p class="text-xs text-gray-500 font-mono mb-2"><?php echo e($e->reference_code); ?></p>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700"><?php echo e($e->status_label); ?></span>
                <p class="mt-3 text-2xl font-bold" style="color: <?php echo e($themeColor); ?>;"><?php echo e(number_format($e->total_cost)); ?> ج.م</p>

                <?php if($e->status === 'submitted' && auth()->user()->can('edit-sales')): ?>
                <form method="POST" action="<?php echo e(route('pre-sales.estimations.approve', $e)); ?>" class="mt-4">
                    <?php echo csrf_field(); ?>
                    <button class="w-full py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-sm">اعتماد التقدير</button>
                </form>
                <?php endif; ?>

                <?php if($e->status === 'approved'): ?>
                <form method="POST" action="<?php echo e(route('pre-sales.proposals.generate', $sale)); ?>" class="mt-4">
                    <?php echo csrf_field(); ?>
                    <button class="w-full py-2.5 rounded-xl text-white font-bold text-sm"
                            style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
                        إصدار Proposal تلقائي →
                    </button>
                </form>
                <?php endif; ?>

                <?php if($e->proposal): ?>
                <a href="<?php echo e(route('pre-sales.proposals.show', $e->proposal)); ?>" class="block mt-3 text-center text-blue-600 font-bold text-sm">عرض Proposal</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if($sale->requirement_summary): ?>
            <div class="bg-gray-50 rounded-2xl border border-gray-200 p-4 text-sm font-tajawal">
                <strong>ملخص المتطلبات:</strong><br><?php echo e($sale->requirement_summary); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
(function() {
    const roles = ['dev','design','qa','pm'];
    function calc() {
        let sub = 0, hours = 0;
        roles.forEach(r => {
            const h = parseFloat(document.querySelector(`[name="${r}_hours"]`)?.value) || 0;
            const rate = parseFloat(document.querySelector(`[name="hourly_rate_${r}"]`)?.value) || 0;
            sub += h * rate;
            hours += h;
        });
        const margin = parseFloat(document.getElementById('margin_percent')?.value) || 0;
        const total = sub * (1 + margin / 100);
        document.getElementById('sum-subtotal').textContent = sub.toLocaleString('ar-EG') + ' ج.م';
        document.getElementById('sum-total').textContent = total.toLocaleString('ar-EG') + ' ج.م';
        document.getElementById('sum-hours').textContent = hours.toLocaleString('ar-EG');
    }
    document.getElementById('estimate-form')?.addEventListener('input', calc);
    document.getElementById('screen_count')?.addEventListener('change', function() {
        const s = parseInt(this.value) || 0;
        if (s > 0) {
            const dev = document.querySelector('[name="dev_hours"]');
            const design = document.querySelector('[name="design_hours"]');
            if (dev && !dev.dataset.touched) dev.value = Math.round(s * 8);
            if (design && !design.dataset.touched) design.value = Math.round(s * 3);
        }
        calc();
    });
    document.querySelectorAll('.hours-input').forEach(el => el.addEventListener('input', () => el.dataset.touched = '1'));
    calc();
})();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\pre-sales\estimate.blade.php ENDPATH**/ ?>