

<?php $__env->startSection('page-title', 'مشاريع أنظمة العملاء'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold">مشاريع أنظمة العملاء</h1>
            <p class="text-gray-600 text-sm">كل مشروع يجمع ميزات وطلبات التطوير والأخطاء المرفوعة من بوابة العميل</p>
        </div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-tickets')): ?>
        <a href="<?php echo e(route('client-system-projects.create')); ?>" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold">+ مشروع جديد</a>
        <?php endif; ?>
    </div>

    <form method="GET" class="bg-white border rounded-xl p-4 mb-6 flex flex-wrap gap-3 items-end">
        <div><label class="text-xs font-bold text-gray-600">بحث</label><input name="search" value="<?php echo e(request('search')); ?>" class="border rounded-lg px-3 py-2 text-sm block"></div>
        <div><label class="text-xs font-bold text-gray-600">العميل</label>
            <select name="client_id" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">الكل</option>
                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($c->id); ?>" <?php if(request('client_id')==$c->id): echo 'selected'; endif; ?>><?php echo e($c->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div><label class="text-xs font-bold text-gray-600">الحالة</label>
            <select name="status" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">الكل</option>
                <?php $__currentLoopData = ['active','on_hold','completed','archived']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($st); ?>" <?php if(request('status')===$st): echo 'selected'; endif; ?>><?php echo e(match($st){'active'=>'نشط','on_hold'=>'متوقف','completed'=>'مكتمل','archived'=>'مؤرشف',default=>$st}); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <button class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-bold">تصفية</button>
    </form>

    <div class="bg-white border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right">المرجع</th>
                    <th class="px-4 py-3 text-right">المشروع</th>
                    <th class="px-4 py-3 text-right">العميل</th>
                    <th class="px-4 py-3 text-right">الطلبات</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs"><?php echo e($project->reference_code); ?></td>
                    <td class="px-4 py-3 font-bold"><?php echo e($project->name); ?></td>
                    <td class="px-4 py-3"><a href="<?php echo e(route('clients.show', $project->client)); ?>" class="text-blue-600 hover:underline"><?php echo e($project->client->name); ?></a></td>
                    <td class="px-4 py-3"><?php echo e($project->features_count); ?></td>
                    <td class="px-4 py-3"><?php echo e($project->status_label); ?></td>
                    <td class="px-4 py-3"><a href="<?php echo e(route('client-system-projects.show', $project)); ?>" class="text-blue-600 font-bold">عرض</a></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">لا توجد مشاريع بعد.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="px-4 py-3"><?php echo e($projects->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\admin\client-system-projects\index.blade.php ENDPATH**/ ?>