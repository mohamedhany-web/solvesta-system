<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-finance')): ?>
<div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
    <div class="flex flex-wrap justify-between items-center gap-3 mb-5">
        <h2 class="text-lg font-bold font-tajawal" style="color: <?php echo e($themeColor); ?>;">المالية — المشروع</h2>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-projects')): ?>
            <?php if($financials['can_create_delivery'] && !$financials['delivery_invoice']): ?>
            <form method="POST" action="<?php echo e(route('projects.finance.delivery-invoice', $project)); ?>"><?php echo csrf_field(); ?>
                <button class="px-4 py-2 rounded-xl bg-emerald-600 text-white text-sm font-bold">فاتورة تسليم 50%</button>
            </form>
            <?php elseif($financials['delivery_invoice']): ?>
            <a href="<?php echo e(route('invoices.show', $financials['delivery_invoice'])); ?>" class="px-4 py-2 rounded-xl border font-bold text-sm text-blue-600">
                فاتورة التسليم — <?php echo e($financials['delivery_invoice']->status); ?>

            </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-6 text-center text-sm font-tajawal">
        <div class="rounded-xl p-4 border" style="background: <?php echo e($themeColor); ?>08; border-color: <?php echo e($themeColor); ?>25;">
            <p class="text-xs text-gray-500">قيمة العقد</p>
            <p class="text-xl font-bold"><?php echo e(number_format($financials['contract_value'])); ?></p>
        </div>
        <div class="rounded-xl p-4 bg-emerald-50 border border-emerald-100">
            <p class="text-xs text-gray-500">محصّل</p>
            <p class="text-xl font-bold text-emerald-700"><?php echo e(number_format($financials['revenue_paid'])); ?></p>
        </div>
        <div class="rounded-xl p-4 bg-amber-50 border border-amber-100">
            <p class="text-xs text-gray-500">مستحقات</p>
            <p class="text-xl font-bold text-amber-700"><?php echo e(number_format($financials['outstanding'])); ?></p>
        </div>
        <div class="rounded-xl p-4 bg-red-50 border border-red-100">
            <p class="text-xs text-gray-500">مصروفات</p>
            <p class="text-xl font-bold text-red-700"><?php echo e(number_format($financials['expenses'])); ?></p>
        </div>
        <div class="rounded-xl p-4 border border-indigo-100 bg-indigo-50/50">
            <p class="text-xs text-gray-500">ربح تقديري</p>
            <p class="text-xl font-bold <?php echo e($financials['profit'] >= 0 ? 'text-indigo-700' : 'text-red-700'); ?>"><?php echo e(number_format($financials['profit'])); ?></p>
            <p class="text-xs text-gray-500"><?php echo e($financials['margin_percent']); ?>% هامش</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 text-sm font-tajawal">
        <?php if($financials['deposit_invoice']): ?>
        <div class="p-3 rounded-xl bg-gray-50 border">
            <strong>دفعة مقدمة:</strong>
            <a href="<?php echo e(route('invoices.show', $financials['deposit_invoice'])); ?>" class="text-blue-600"><?php echo e($financials['deposit_invoice']->invoice_number); ?></a>
            — <?php echo e($financials['deposit_invoice']->status); ?>

        </div>
        <?php endif; ?>
        <?php if($financials['delivery_invoice']): ?>
        <div class="p-3 rounded-xl bg-gray-50 border">
            <strong>عند التسليم:</strong>
            <a href="<?php echo e(route('invoices.show', $financials['delivery_invoice'])); ?>" class="text-blue-600"><?php echo e($financials['delivery_invoice']->invoice_number); ?></a>
            — <?php echo e($financials['delivery_invoice']->status); ?>

        </div>
        <?php endif; ?>
    </div>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-finance')): ?>
    <details class="mb-4">
        <summary class="cursor-pointer font-bold text-sm mb-2" style="color: <?php echo e($themeColor); ?>;">+ تسجيل مصروف للمشروع</summary>
        <form method="POST" action="<?php echo e(route('projects.finance.expenses', $project)); ?>" class="grid grid-cols-1 md:grid-cols-2 gap-3 p-4 bg-gray-50 rounded-xl mt-2">
            <?php echo csrf_field(); ?>
            <input name="description" required placeholder="وصف المصروف" class="border rounded-lg px-3 py-2 md:col-span-2">
            <input type="number" step="0.01" name="amount" required placeholder="المبلغ" class="border rounded-lg px-3 py-2">
            <input type="date" name="expense_date" value="<?php echo e(today()->format('Y-m-d')); ?>" class="border rounded-lg px-3 py-2">
            <select name="expense_category" class="border rounded-lg px-3 py-2">
                <option value="software">برمجيات</option>
                <option value="professional_fees">رسوم مهنية</option>
                <option value="travel">سفر</option>
                <option value="salaries">رواتب/مستحقات</option>
                <option value="other">أخرى</option>
            </select>
            <select name="payment_method" class="border rounded-lg px-3 py-2">
                <option value="bank_transfer">تحويل بنكي</option>
                <option value="cash">نقدي</option>
            </select>
            <button class="md:col-span-2 py-2 rounded-lg text-white font-bold" style="background:<?php echo e($themeColor); ?>;">تسجيل المصروف</button>
        </form>
    </details>
    <?php endif; ?>

    <?php if($projectExpenses->count()): ?>
    <h3 class="font-bold text-sm mb-2 font-tajawal">آخر مصروفات المشروع</h3>
    <ul class="text-sm space-y-2 font-tajawal">
        <?php $__currentLoopData = $projectExpenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li class="flex justify-between py-2 px-3 bg-gray-50 rounded-lg">
            <span><?php echo e($exp->description); ?> <span class="text-xs text-gray-500">(<?php echo e($exp->expense_date->format('Y/m/d')); ?>)</span></span>
            <span class="font-bold text-red-700"><?php echo e(number_format($exp->amount)); ?> — <?php echo e($exp->status); ?></span>
        </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\projects\partials\finance-panel.blade.php ENDPATH**/ ?>