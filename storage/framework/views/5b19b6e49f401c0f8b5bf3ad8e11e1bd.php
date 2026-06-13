<?php
    $wallet = $wallet ?? null;
    $isEdit = (bool) $wallet;
    $section = $section ?? 'all';
    $showBasic = in_array($section, ['all', 'basic'], true);
    $showBank = in_array($section, ['all', 'bank'], true);
    $showExtra = in_array($section, ['all', 'extra'], true);
    $inputClass = 'w-full border border-gray-300 rounded-lg px-3 py-2.5 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500';
    $labelClass = 'block text-sm font-semibold text-gray-700 mb-1.5';
?>

<?php if($showBasic): ?>
<div>
    <label class="<?php echo e($labelClass); ?>">اسم المحفظة <span class="text-red-500">*</span></label>
    <input type="text" name="name" value="<?php echo e(old('name', $wallet?->name)); ?>" required
           class="<?php echo e($inputClass); ?>" placeholder="مثال: البنك الأهلي — حساب جاري">
    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<div>
    <label class="<?php echo e($labelClass); ?>">النوع <span class="text-red-500">*</span></label>
    <select name="type" required class="<?php echo e($inputClass); ?>">
        <?php $__currentLoopData = ['cash' => 'نقدية / خزينة', 'bank' => 'حساب بنكي', 'transfer' => 'تحويل / محفظة إلكترونية', 'other' => 'أخرى']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($val); ?>" <?php if(old('type', $wallet?->type ?? 'cash') === $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<div>
    <label class="<?php echo e($labelClass); ?>">العملة</label>
    <input type="text" name="currency" maxlength="3" value="<?php echo e(old('currency', $wallet?->currency ?? 'EGP')); ?>"
           class="<?php echo e($inputClass); ?>" dir="ltr">
    <?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<?php if(!$isEdit): ?>
<div class="<?php echo e(($useGrid ?? false) ? 'md:col-span-2' : ''); ?>">
    <label class="<?php echo e($labelClass); ?>">رصيد افتتاحي</label>
    <input type="number" name="opening_balance" step="0.01" min="0" value="<?php echo e(old('opening_balance', 0)); ?>"
           class="<?php echo e($inputClass); ?>" placeholder="0.00">
    <?php $__errorArgs = ['opening_balance'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<?php elseif($section === 'basic'): ?>
<div class="md:col-span-2">
    <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 font-bold text-sm">ج.م</div>
        <div>
            <p class="font-bold text-gray-900">الرصيد الحالي: <?php echo e(number_format($wallet->current_balance, 2)); ?> <?php echo e($wallet->currency); ?></p>
            <p class="text-xs text-gray-600 mt-1">لا يُعدَّل الرصيد من هنا. استخدم <strong>إيداع / سحب</strong> من <a href="<?php echo e(route('accounting.wallets.show', $wallet)); ?>" class="text-blue-600 underline">صفحة المحفظة</a>.</p>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<?php if($showBank): ?>
<div>
    <label class="<?php echo e($labelClass); ?>">اسم البنك</label>
    <input type="text" name="bank_name" value="<?php echo e(old('bank_name', $wallet?->bank_name)); ?>"
           class="<?php echo e($inputClass); ?>" placeholder="اسم البنك">
    <?php $__errorArgs = ['bank_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<div>
    <label class="<?php echo e($labelClass); ?>">رقم الحساب</label>
    <input type="text" name="account_number" value="<?php echo e(old('account_number', $wallet?->account_number)); ?>"
           class="<?php echo e($inputClass); ?>" placeholder="رقم الحساب أو IBAN" dir="ltr">
    <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
<?php endif; ?>

<?php if($showExtra): ?>
<div>
    <label class="<?php echo e($labelClass); ?>">ملاحظات</label>
    <textarea name="notes" rows="3" class="<?php echo e($inputClass); ?>" placeholder="ملاحظات داخلية عن المحفظة"><?php echo e(old('notes', $wallet?->notes)); ?></textarea>
    <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<?php if($isEdit): ?>
<div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl bg-gray-50">
    <div>
        <p class="text-sm font-bold text-gray-900">حالة المحفظة</p>
        <p class="text-xs text-gray-500 mt-0.5">المحافظ المعطّلة لا تظهر عند تسجيل دفعات الفواتير</p>
    </div>
    <label class="inline-flex items-center gap-2 cursor-pointer shrink-0">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1"
               class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
               <?php if(old('is_active', $wallet->is_active)): echo 'checked'; endif; ?>>
        <span class="text-sm font-semibold text-gray-800">نشطة</span>
    </label>
</div>
<?php endif; ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\accounting\wallets\_form-fields.blade.php ENDPATH**/ ?>