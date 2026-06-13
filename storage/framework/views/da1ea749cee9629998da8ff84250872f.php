

<?php $__env->startSection('page-title', 'خدمات ما بعد البيع'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">خدمات ما بعد البيع</h1>
            <p class="text-gray-600">عقود الخدمة المستمرة، الفوترة الشهرية، والربط ببوابة العميل</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="<?php echo e(route('accounting.guide')); ?>" class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-semibold hover:bg-gray-50">دليل المحاسبة</a>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-finance')): ?>
            <a href="<?php echo e(route('accounting.client-services.create')); ?>" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700">+ خدمة جديدة</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if(session('success')): ?><div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg"><?php echo e(session('success')); ?></div><?php endif; ?>
    <?php if(session('error')): ?><div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg"><?php echo e(session('error')); ?></div><?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white border rounded-xl p-6 shadow-sm">
            <p class="text-sm text-gray-600">خدمات نشطة</p>
            <p class="text-3xl font-bold text-emerald-700"><?php echo e($stats['active']); ?></p>
        </div>
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
            <p class="text-sm text-amber-800">مستحقة الفوترة اليوم</p>
            <p class="text-3xl font-bold text-amber-900"><?php echo e($stats['due_today']); ?></p>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <p class="text-sm text-blue-800">إجمالي الاشتراكات الشهرية (MRR)</p>
            <p class="text-3xl font-bold text-blue-900"><?php echo e(number_format($stats['monthly_mrr'], 2)); ?> ج.م</p>
        </div>
    </div>

    <form method="GET" class="bg-white border rounded-xl p-4 mb-6 flex flex-wrap gap-3 items-end">
        <div>
            <label class="text-xs font-bold text-gray-600">بحث</label>
            <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="border rounded-lg px-3 py-2 text-sm" placeholder="رقم أو اسم الخدمة">
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600">العميل</label>
            <select name="client_id" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">الكل</option>
                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c->id); ?>" <?php if(request('client_id') == $c->id): echo 'selected'; endif; ?>><?php echo e($c->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600">الحالة</label>
            <select name="status" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">الكل</option>
                <?php $__currentLoopData = ['draft','active','paused','ended']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($st); ?>" <?php if(request('status') === $st): echo 'selected'; endif; ?>><?php echo e(match($st){'draft'=>'مسودة','active'=>'نشطة','paused'=>'موقوفة','ended'=>'منتهية',default=>$st}); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-bold">تصفية</button>
    </form>

    <div class="bg-white border rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right">الرقم</th>
                    <th class="px-4 py-3 text-right">الخدمة</th>
                    <th class="px-4 py-3 text-right">العميل</th>
                    <th class="px-4 py-3 text-right">شهرياً</th>
                    <th class="px-4 py-3 text-right">الفوترة القادمة</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs"><?php echo e($service->service_number); ?></td>
                    <td class="px-4 py-3 font-bold"><?php echo e($service->title); ?></td>
                    <td class="px-4 py-3"><?php echo e($service->client?->name); ?></td>
                    <td class="px-4 py-3 font-bold text-blue-700"><?php echo e(number_format($service->monthly_amount, 2)); ?> <?php echo e($service->currency); ?></td>
                    <td class="px-4 py-3"><?php echo e($service->next_billing_date?->format('Y-m-d') ?? '—'); ?></td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded text-xs font-bold
                            <?php if($service->status==='active'): ?> bg-green-100 text-green-800
                            <?php elseif($service->status==='paused'): ?> bg-amber-100 text-amber-800
                            <?php elseif($service->status==='ended'): ?> bg-gray-200 text-gray-700
                            <?php else: ?> bg-slate-100 text-slate-700 <?php endif; ?>">
                            <?php echo e($service->status_name); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="<?php echo e(route('accounting.client-services.show', $service)); ?>" class="text-blue-600 font-bold hover:underline">عرض</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="px-4 py-12 text-center text-gray-500">لا توجد خدمات مسجّلة بعد.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="px-4 py-3"><?php echo e($services->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\accounting\client-services\index.blade.php ENDPATH**/ ?>