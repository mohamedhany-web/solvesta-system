

<?php $__env->startSection('page-title', $project->name); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <div class="mb-6 flex flex-wrap justify-between gap-4">
        <div>
            <p class="text-sm font-mono text-gray-500"><?php echo e($project->reference_code); ?></p>
            <h1 class="text-3xl font-bold"><?php echo e($project->name); ?></h1>
            <p class="text-gray-600">عميل: <a href="<?php echo e(route('clients.show', $project->client)); ?>" class="text-blue-600 font-semibold"><?php echo e($project->client->name); ?></a></p>
        </div>
        <a href="<?php echo e(route('client-system-projects.index')); ?>" class="border px-4 py-2 rounded-xl font-semibold">القائمة</a>
    </div>

    <?php if(session('success')): ?><div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg"><?php echo e(session('success')); ?></div><?php endif; ?>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8 space-y-6">
            <div class="bg-white border rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h2 class="font-bold text-lg">الميزات والطلبات (<?php echo e($project->features->count()); ?>)</h2>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right">المرجع</th>
                            <th class="px-4 py-3 text-right">العنوان</th>
                            <th class="px-4 py-3 text-right">النوع</th>
                            <th class="px-4 py-3 text-right">الحالة</th>
                            <th class="px-4 py-3 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <?php $__empty_1 = true; $__currentLoopData = $project->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-mono text-xs"><?php echo e($f->reference_code); ?></td>
                            <td class="px-4 py-3 font-semibold"><?php echo e($f->title); ?></td>
                            <td class="px-4 py-3"><?php echo e($f->type_label); ?></td>
                            <td class="px-4 py-3"><?php echo e($f->status_label); ?></td>
                            <td class="px-4 py-3"><a href="<?php echo e(route('client-system-features.show', $f)); ?>" class="text-blue-600 font-bold">إدارة + توثيق</a></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">لا توجد طلبات في هذا المشروع.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-tickets')): ?>
        <div class="xl:col-span-4">
            <form method="POST" action="<?php echo e(route('client-system-projects.update', $project)); ?>" class="bg-white border rounded-xl p-6 space-y-4">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <h2 class="font-bold">إعدادات المشروع</h2>
                <div>
                    <label class="text-xs font-bold text-gray-600">الاسم</label>
                    <input name="name" value="<?php echo e($project->name); ?>" required class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600">الوصف</label>
                    <textarea name="description" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm"><?php echo e($project->description); ?></textarea>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600">الحالة</label>
                    <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <?php $__currentLoopData = ['active','on_hold','completed','archived']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($st); ?>" <?php if($project->status===$st): echo 'selected'; endif; ?>><?php echo e(match($st){'active'=>'نشط','on_hold'=>'متوقف','completed'=>'مكتمل','archived'=>'مؤرشف',default=>$st}); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600">مسند إلى</label>
                    <select name="assigned_to" class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="">—</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($u->id); ?>" <?php if($project->assigned_to==$u->id): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600">ملاحظات داخلية</label>
                    <textarea name="admin_notes" rows="3" class="w-full border rounded-lg px-3 py-2 text-sm"><?php echo e($project->admin_notes); ?></textarea>
                </div>
                <button class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-bold">حفظ</button>
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\admin\client-system-projects\show.blade.php ENDPATH**/ ?>