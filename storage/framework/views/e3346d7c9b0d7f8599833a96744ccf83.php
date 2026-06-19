

<?php $__env->startSection('page-title', 'تذاكر الدعم'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-full">
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">تذاكر الدعم</h1>
                <p class="text-gray-600 text-sm sm:text-base">منظّمة حسب مشروعك — <?php echo e($client->name); ?></p>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('client.support.tickets.create')); ?>" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 text-sm font-bold">تذكرة جديدة</a>
                <a href="<?php echo e(route('client.dashboard')); ?>" class="bg-gray-600 text-white px-5 py-2.5 rounded-lg hover:bg-gray-700 text-sm font-bold">العودة</a>
            </div>
        </div>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <details class="mb-4 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" open>
            <summary class="cursor-pointer list-none px-5 py-4 bg-slate-50 border-b flex flex-wrap items-center justify-between gap-2">
                <h2 class="text-lg font-bold text-gray-900"><?php echo e($project->name); ?></h2>
                <span class="text-sm font-semibold text-blue-700 bg-blue-50 px-3 py-1 rounded-full"><?php echo e($project->tickets->count()); ?> تذكرة</span>
            </summary>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">الرقم</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">الموضوع</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">الحالة</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">الأولوية</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">عرض</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $__currentLoopData = $project->tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-semibold"><?php echo e($ticket->ticket_number); ?></td>
                            <td class="px-4 py-3"><?php echo e($ticket->subject); ?></td>
                            <td class="px-4 py-3"><?php echo e($ticket->status_name); ?></td>
                            <td class="px-4 py-3"><?php echo e($ticket->priority); ?></td>
                            <td class="px-4 py-3 text-left">
                                <a href="<?php echo e(route('client.support.tickets.show', $ticket)); ?>" class="text-blue-600 font-bold hover:underline">عرض</a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </details>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <?php if($unassignedTickets->isEmpty()): ?>
        <div class="bg-white rounded-xl border border-dashed border-gray-300 p-10 text-center text-gray-500 mb-4">
            لا توجد تذاكر بعد.
        </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if($unassignedTickets->isNotEmpty()): ?>
        <details class="mb-4 bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden" open>
            <summary class="cursor-pointer list-none px-5 py-4 bg-amber-50 border-b flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">تذاكر عامة (بدون مشروع)</h2>
                <span class="text-sm font-semibold text-amber-800"><?php echo e($unassignedTickets->count()); ?></span>
            </summary>
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <tbody class="divide-y divide-gray-100">
                        <?php $__currentLoopData = $unassignedTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-semibold"><?php echo e($ticket->ticket_number); ?></td>
                            <td class="px-4 py-3"><?php echo e($ticket->subject); ?></td>
                            <td class="px-4 py-3"><?php echo e($ticket->status_name); ?></td>
                            <td class="px-4 py-3"><?php echo e($ticket->priority); ?></td>
                            <td class="px-4 py-3 text-left">
                                <a href="<?php echo e(route('client.support.tickets.show', $ticket)); ?>" class="text-blue-600 font-bold hover:underline">عرض</a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </details>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\client-portal\support\tickets\index.blade.php ENDPATH**/ ?>