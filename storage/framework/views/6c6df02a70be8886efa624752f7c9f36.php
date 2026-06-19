<?php $__env->startSection('page-title', $lead->reference_code); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full px-2 sm:px-0">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => $lead->name,
        'subtitle' => $lead->reference_code.' · '.$lead->company.' — '.$lead->service_interest,
        'icon' => 'users',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="<?php echo e(route('leads.index')); ?>" class="border border-gray-200 px-4 py-2 rounded-xl text-sm font-semibold bg-white hover:shadow-md transition">القائمة</a>
    </div>

    <?php if(session('success')): ?><div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg"><?php echo e(session('success')); ?></div><?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <h2 class="font-bold mb-3">التفاصيل</h2>
                <dl class="grid grid-cols-2 gap-3 text-sm">
                    <div><dt class="text-gray-500">الحالة</dt><dd class="font-bold"><?php echo e($lead->status_label); ?></dd></div>
                    <div><dt class="text-gray-500">المصدر</dt><dd><?php echo e($lead->source_label); ?></dd></div>
                    <div><dt class="text-gray-500">البريد</dt><dd><?php echo e($lead->email ?? '—'); ?></dd></div>
                    <div><dt class="text-gray-500">الهاتف</dt><dd><?php echo e($lead->phone ?? '—'); ?></dd></div>
                    <div><dt class="text-gray-500">الميزانية</dt><dd><?php echo e($lead->estimated_budget ? number_format($lead->estimated_budget) : '—'); ?></dd></div>
                    <div><dt class="text-gray-500">مسند إلى</dt><dd><?php echo e($lead->assignee?->name ?? '—'); ?></dd></div>
                </dl>
                <?php if($lead->notes): ?><p class="mt-4 text-sm text-gray-700 whitespace-pre-wrap"><?php echo e($lead->notes); ?></p><?php endif; ?>
            </div>
        </div>

        <div class="space-y-4">
            <?php if($lead->status !== 'converted'): ?>
            <form method="POST" action="<?php echo e(route('leads.update-status', $lead)); ?>" class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5 space-y-3">
                <?php echo csrf_field(); ?>
                <h3 class="font-bold">تحديث الحالة</h3>
                <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <?php $__currentLoopData = ['new','contacted','qualified','lost','on_hold']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($st); ?>" <?php if($lead->status===$st): echo 'selected'; endif; ?>><?php echo e((new \App\Models\Lead(['status'=>$st]))->status_label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <input name="lost_reason" placeholder="سبب الفقد (إن وُجد)" class="w-full border rounded-lg px-3 py-2 text-sm">
                <button class="w-full bg-gray-900 text-white py-2 rounded-lg text-sm font-bold">حفظ</button>
            </form>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-sales')): ?>
            <form method="POST" action="<?php echo e(route('leads.convert-to-sale', $lead)); ?>" class="rounded-2xl shadow-lg border p-5 space-y-3" style="background: <?php echo e($themeColor); ?>08; border-color: <?php echo e($themeColor); ?>30;">
                <?php echo csrf_field(); ?>
                <h3 class="font-bold text-blue-900">تحويل إلى Sales</h3>
                <p class="text-xs text-blue-800">ينشئ عميلاً وفرصة مبيعات تلقائياً.</p>
                <select name="assigned_to" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($u->id); ?>"><?php echo e($u->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button class="w-full py-2.5 rounded-xl font-bold text-white" style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">تحويل لـ Sales →</button>
            </form>
            <?php endif; ?>
            <?php else: ?>
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5 text-sm">
                <p class="font-bold text-emerald-900">تم التحويل</p>
                <?php if($lead->convertedSale): ?>
                    <a href="<?php echo e(route('sales.show', $lead->convertedSale)); ?>" class="text-blue-600 font-bold mt-2 inline-block">فتح فرصة المبيعات</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\leads\show.blade.php ENDPATH**/ ?>