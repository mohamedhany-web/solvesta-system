

<?php $__env->startSection('page-title', $feature->reference_code); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-full">
    <div class="mb-6">
        <p class="text-sm font-mono text-gray-500"><?php echo e($feature->reference_code); ?></p>
        <h1 class="text-2xl font-bold"><?php echo e($feature->title); ?></h1>
        <p class="text-sm text-gray-600 mt-1">
            مشروع: <a href="<?php echo e(route('client-system-projects.show', $feature->project)); ?>" class="text-blue-600 font-semibold"><?php echo e($feature->project->name); ?></a>
            — عميل: <a href="<?php echo e(route('clients.show', $feature->project->client)); ?>" class="text-blue-600"><?php echo e($feature->project->client->name); ?></a>
        </p>
    </div>

    <?php if(session('success')): ?><div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg"><?php echo e(session('success')); ?></div><?php endif; ?>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-7 space-y-6">
            <div class="bg-white border rounded-xl p-6">
                <h2 class="font-bold mb-3">وصف العميل</h2>
                <p class="whitespace-pre-wrap text-gray-800 text-sm leading-relaxed"><?php echo e($feature->description); ?></p>
                <p class="text-xs text-gray-500 mt-4">النوع: <?php echo e($feature->type_label); ?> — الأولوية: <?php echo e($feature->priority_label); ?> — أُرسل: <?php echo e($feature->created_at->format('Y/m/d H:i')); ?></p>
            </div>

            <div class="bg-white border rounded-xl p-6">
                <h2 class="font-bold mb-4">سجل التوثيق والتحديثات</h2>
                <?php $__empty_1 = true; $__currentLoopData = $feature->updates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $update): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="mb-5 pb-5 border-b last:border-0 <?php echo e($update->visibility === 'internal' ? 'bg-amber-50/50 -mx-2 px-2 rounded-lg' : ''); ?>">
                    <div class="flex flex-wrap justify-between gap-2 mb-1">
                        <strong><?php echo e($update->title); ?></strong>
                        <span class="text-xs <?php echo e($update->visibility === 'client' ? 'text-green-700' : 'text-amber-700'); ?>"><?php echo e($update->visibility_label); ?></span>
                    </div>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap"><?php echo e($update->body); ?></p>
                    <p class="text-xs text-gray-500 mt-2"><?php echo e($update->created_at->format('Y/m/d H:i')); ?> — <?php echo e($update->author?->name ?? '—'); ?></p>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-tickets')): ?>
                    <?php if($update->visibility === 'internal'): ?>
                    <form method="POST" action="<?php echo e(route('client-system-feature-updates.destroy', $update)); ?>" class="mt-2" onsubmit="return confirm('حذف؟')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="text-xs text-red-600 font-bold">حذف</button>
                    </form>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-500 text-sm">لا توجد تحديثات. أضف توثيقاً يشرح للعميل ما تم إنجازه أو معالجته.</p>
                <?php endif; ?>
            </div>
        </div>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-tickets')): ?>
        <div class="xl:col-span-5 space-y-6">
            <form method="POST" action="<?php echo e(route('client-system-features.update', $feature)); ?>" class="bg-white border rounded-xl p-6 space-y-3">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <h2 class="font-bold">حالة الطلب</h2>
                <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <?php $__currentLoopData = ['submitted','reviewing','approved','in_progress','testing','completed','rejected','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($st); ?>" <?php if($feature->status===$st): echo 'selected'; endif; ?>><?php echo e((new \App\Models\ClientSystemFeature(['status'=>$st]))->status_label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <select name="priority" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <?php $__currentLoopData = ['low','medium','high','urgent']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($p); ?>" <?php if($feature->priority===$p): echo 'selected'; endif; ?>><?php echo e((new \App\Models\ClientSystemFeature(['priority'=>$p]))->priority_label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <select name="assigned_to" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="">مسند إلى —</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($u->id); ?>" <?php if($feature->assigned_to==$u->id): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-bold">حفظ الحالة</button>
            </form>

            <form method="POST" action="<?php echo e(route('client-system-features.updates.store', $feature)); ?>" class="bg-white border-2 border-blue-200 rounded-xl p-6 space-y-3">
                <?php echo csrf_field(); ?>
                <h2 class="font-bold text-blue-900">إضافة توثيق / شرح</h2>
                <p class="text-xs text-gray-600">اكتب ما حدث للميزة أو الخطأ — يظهر للعميل إذا اخترت «يظهر للعميل».</p>
                <input name="title" required placeholder="عنوان مختصر (مثال: تم إصلاح الخطأ في صفحة التقارير)" class="w-full border rounded-lg px-3 py-2 text-sm">
                <textarea name="body" required rows="5" placeholder="شرح تفصيلي: ماذا كان الخطأ، ماذا فعلتم، كيف يختبر العميل..." class="w-full border rounded-lg px-3 py-2 text-sm"></textarea>
                <select name="visibility" class="w-full border rounded-lg px-3 py-2 text-sm">
                    <option value="client">يظهر للعميل — توثيق رسمي</option>
                    <option value="internal">داخلي — لا يظهر للعميل</option>
                </select>
                <button class="w-full bg-emerald-600 text-white py-2.5 rounded-lg font-bold">نشر التحديث</button>
            </form>

            <a href="<?php echo e(route('client-system-projects.show', $feature->project)); ?>" class="block text-center text-sm text-blue-600 font-semibold">← العودة للمشروع</a>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\admin\client-system-features\show.blade.php ENDPATH**/ ?>