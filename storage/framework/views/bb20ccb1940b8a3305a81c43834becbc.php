<?php $s = $clientService ?? null; $prefill = $prefill ?? []; ?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">العميل *</label>
        <select name="client_id" required class="w-full border rounded-xl px-4 py-2.5">
            <option value="">— اختر العميل —</option>
            <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($c->id); ?>" <?php if(old('client_id', $s?->client_id ?? $prefill['client_id'] ?? '') == $c->id): echo 'selected'; endif; ?>><?php echo e($c->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">العقد (اختياري)</label>
        <select name="contract_id" class="w-full border rounded-xl px-4 py-2.5">
            <option value="">— بدون عقد —</option>
            <?php $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($contract->id); ?>" <?php if(old('contract_id', $s?->contract_id ?? $prefill['contract_id'] ?? '') == $contract->id): echo 'selected'; endif; ?>>
                    <?php echo e($contract->contract_number ?? '#'.$contract->id); ?> — <?php echo e($contract->title ?? $contract->client?->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <p class="text-xs text-gray-500 mt-1">اربط الخدمة بعقد موقّع في قسم الشؤون القانونية إن وُجد.</p>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-gray-700 mb-1">اسم الخدمة *</label>
        <input type="text" name="title" value="<?php echo e(old('title', $s?->title)); ?>" required class="w-full border rounded-xl px-4 py-2.5" placeholder="مثال: استضافة وصيانة شهرية">
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-gray-700 mb-1">وصف الخدمة</label>
        <textarea name="description" rows="3" class="w-full border rounded-xl px-4 py-2.5"><?php echo e(old('description', $s?->description)); ?></textarea>
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">المبلغ الشهري *</label>
        <input type="number" step="0.01" min="0" name="monthly_amount" value="<?php echo e(old('monthly_amount', $s?->monthly_amount)); ?>" required class="w-full border rounded-xl px-4 py-2.5">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">يوم الفوترة الشهري (1–28) *</label>
        <input type="number" min="1" max="28" name="billing_day" value="<?php echo e(old('billing_day', $s?->billing_day ?? 1)); ?>" required class="w-full border rounded-xl px-4 py-2.5">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">نسبة الضريبة %</label>
        <input type="number" step="0.01" min="0" max="100" name="tax_rate" value="<?php echo e(old('tax_rate', $s?->tax_rate ?? 0)); ?>" class="w-full border rounded-xl px-4 py-2.5">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">العملة</label>
        <input type="text" name="currency" maxlength="3" value="<?php echo e(old('currency', $s?->currency ?? 'EGP')); ?>" class="w-full border rounded-xl px-4 py-2.5">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">تاريخ البدء *</label>
        <input type="date" name="start_date" value="<?php echo e(old('start_date', $s?->start_date?->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>" required class="w-full border rounded-xl px-4 py-2.5">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">تاريخ الانتهاء</label>
        <input type="date" name="end_date" value="<?php echo e(old('end_date', $s?->end_date?->format('Y-m-d'))); ?>" class="w-full border rounded-xl px-4 py-2.5">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">موعد الفوترة القادم</label>
        <input type="date" name="next_billing_date" value="<?php echo e(old('next_billing_date', $s?->next_billing_date?->format('Y-m-d'))); ?>" class="w-full border rounded-xl px-4 py-2.5">
        <p class="text-xs text-gray-500 mt-1">يُحسب تلقائياً عند تفعيل الخدمة إن تُرك فارغاً.</p>
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">مهلة السداد (أيام بعد إصدار الفاتورة) *</label>
        <input type="number" min="1" max="90" name="payment_terms_days" value="<?php echo e(old('payment_terms_days', $s?->payment_terms_days ?? 14)); ?>" required class="w-full border rounded-xl px-4 py-2.5">
    </div>
    <div>
        <label class="block text-sm font-bold text-gray-700 mb-1">الحالة *</label>
        <select name="status" required class="w-full border rounded-xl px-4 py-2.5">
            <?php $__currentLoopData = ['draft' => 'مسودة', 'active' => 'نشطة', 'paused' => 'موقوفة', 'ended' => 'منتهية']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($val); ?>" <?php if(old('status', $s?->status ?? 'draft') === $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="flex items-center gap-3 pt-6">
        <input type="hidden" name="auto_invoice" value="0">
        <input type="checkbox" name="auto_invoice" value="1" id="auto_invoice" class="rounded" <?php if(old('auto_invoice', $s?->auto_invoice ?? true)): echo 'checked'; endif; ?>>
        <label for="auto_invoice" class="text-sm font-bold text-gray-700">إصدار فواتير شهرية تلقائياً</label>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-bold text-gray-700 mb-1">ملاحظات داخلية</label>
        <textarea name="notes" rows="2" class="w-full border rounded-xl px-4 py-2.5"><?php echo e(old('notes', $s?->notes)); ?></textarea>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views/accounting/client-services/_form.blade.php ENDPATH**/ ?>