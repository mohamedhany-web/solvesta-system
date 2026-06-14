<?php $__env->startSection('page-title', 'Lead جديد'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-3xl">
    <h1 class="text-2xl font-bold mb-6">تسجيل Lead جديد</h1>
    <form method="POST" action="<?php echo e(route('leads.store')); ?>" class="bg-white border rounded-xl p-6 space-y-4">
        <?php echo csrf_field(); ?>
        <div><label class="text-sm font-bold">الاسم *</label><input name="name" value="<?php echo e(old('name')); ?>" required class="w-full border rounded-lg px-3 py-2 mt-1"></div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="text-sm font-bold">البريد</label><input name="email" type="email" value="<?php echo e(old('email')); ?>" class="w-full border rounded-lg px-3 py-2 mt-1"></div>
            <div><label class="text-sm font-bold">الهاتف</label><input name="phone" value="<?php echo e(old('phone')); ?>" class="w-full border rounded-lg px-3 py-2 mt-1"></div>
        </div>
        <div><label class="text-sm font-bold">الشركة</label><input name="company" value="<?php echo e(old('company')); ?>" class="w-full border rounded-lg px-3 py-2 mt-1"></div>
        <div><label class="text-sm font-bold">الخدمة / الاهتمام</label><input name="service_interest" value="<?php echo e(old('service_interest')); ?>" placeholder="مثال: CRM" class="w-full border rounded-lg px-3 py-2 mt-1"></div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-bold">المصدر *</label>
                <select name="source" required class="w-full border rounded-lg px-3 py-2 mt-1">
                    <?php $__currentLoopData = ['bd_outreach'=>'تطوير أعمال','referral'=>'إحالة','ads'=>'إعلانات','social_media'=>'سوشيال','website'=>'موقع','event'=>'فعالية','other'=>'أخرى']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($v); ?>" <?php if(old('source')===$v): echo 'selected'; endif; ?>><?php echo e($l); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div><label class="text-sm font-bold">ميزانية تقديرية</label><input name="estimated_budget" type="number" step="0.01" value="<?php echo e(old('estimated_budget')); ?>" class="w-full border rounded-lg px-3 py-2 mt-1"></div>
        </div>
        <div><label class="text-sm font-bold">ملاحظات</label><textarea name="notes" rows="4" class="w-full border rounded-lg px-3 py-2 mt-1"><?php echo e(old('notes')); ?></textarea></div>
        <div>
            <label class="text-sm font-bold">مسند إلى</label>
            <select name="assigned_to" class="w-full border rounded-lg px-3 py-2 mt-1">
                <option value="">—</option>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($u->id); ?>" <?php if(old('assigned_to')==$u->id): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <button class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-bold">حفظ</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\leads\create.blade.php ENDPATH**/ ?>