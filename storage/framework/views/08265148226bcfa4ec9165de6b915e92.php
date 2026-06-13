

<?php $__env->startSection('page-title', 'مشروع نظام جديد'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">إنشاء مشروع نظام للعميل</h1>
    <form method="POST" action="<?php echo e(route('client-system-projects.store')); ?>" class="bg-white border rounded-xl p-6 space-y-4">
        <?php echo csrf_field(); ?>
        <div>
            <label class="text-sm font-bold">العميل *</label>
            <select name="client_id" required class="w-full border rounded-xl px-4 py-2.5 mt-1">
                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="text-sm font-bold">اسم المشروع / النظام *</label>
            <input name="name" required class="w-full border rounded-xl px-4 py-2.5 mt-1" placeholder="مثال: نظام إدارة المخزون">
        </div>
        <div>
            <label class="text-sm font-bold">الوصف</label>
            <textarea name="description" rows="3" class="w-full border rounded-xl px-4 py-2.5 mt-1"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-bold">الحالة</label>
                <select name="status" class="w-full border rounded-xl px-4 py-2.5 mt-1">
                    <option value="active">نشط</option>
                    <option value="on_hold">متوقف</option>
                    <option value="completed">مكتمل</option>
                    <option value="archived">مؤرشف</option>
                </select>
            </div>
            <div>
                <label class="text-sm font-bold">مسند إلى</label>
                <select name="assigned_to" class="w-full border rounded-xl px-4 py-2.5 mt-1">
                    <option value="">—</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($u->id); ?>"><?php echo e($u->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div>
            <label class="text-sm font-bold">ملاحظات داخلية</label>
            <textarea name="admin_notes" rows="2" class="w-full border rounded-xl px-4 py-2.5 mt-1"></textarea>
        </div>
        <button class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold">حفظ</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\admin\client-system-projects\create.blade.php ENDPATH**/ ?>