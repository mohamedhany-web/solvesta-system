<?php $__env->startSection('page-title', 'شريك BD جديد'); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full px-2 sm:px-0">
    <?php echo $__env->make('partials.erp-page-header', ['title' => 'شريك جديد', 'subtitle' => 'Business Development', 'icon' => 'users'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <form method="POST" action="<?php echo e(route('bd.partners.store')); ?>" class="max-w-2xl bg-white rounded-2xl shadow-lg border p-6 space-y-4 font-tajawal">
        <?php echo csrf_field(); ?>
        <input name="name" required placeholder="اسم الشخص / الجهة *" class="w-full border rounded-xl px-4 py-2.5">
        <input name="company" placeholder="الشركة" class="w-full border rounded-xl px-4 py-2.5">
        <div class="grid grid-cols-2 gap-3">
            <input name="email" type="email" placeholder="البريد" class="border rounded-xl px-4 py-2.5">
            <input name="phone" placeholder="الهاتف" class="border rounded-xl px-4 py-2.5">
        </div>
        <select name="partner_type" class="w-full border rounded-xl px-4 py-2.5">
            <?php $__currentLoopData = ['agency'=>'وكالة','vendor'=>'مورّد','referrer'=>'مُحيل','strategic'=>'استراتيجي','other'=>'أخرى']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($v); ?>"><?php echo e($l); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="assigned_to" class="w-full border rounded-xl px-4 py-2.5">
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($u->id); ?>"><?php echo e($u->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <textarea name="notes" rows="3" placeholder="ملاحظات..." class="w-full border rounded-xl px-4 py-2.5"></textarea>
        <button class="px-6 py-2.5 rounded-xl text-white font-bold" style="background:<?php echo e($themeColor); ?>;">حفظ</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\bd\partners\create.blade.php ENDPATH**/ ?>