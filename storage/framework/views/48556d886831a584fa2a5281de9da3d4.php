<?php
    $statusColors = [
        'open' => 'bg-red-100 text-red-800',
        'in_progress' => 'bg-yellow-100 text-yellow-800',
        'pending_client' => 'bg-orange-100 text-orange-800',
        'resolved' => 'bg-blue-100 text-blue-800',
        'closed' => 'bg-green-100 text-green-800',
    ];
    $priorityColors = [
        'low' => 'bg-green-100 text-green-800',
        'medium' => 'bg-yellow-100 text-yellow-800',
        'high' => 'bg-orange-100 text-orange-800',
        'critical' => 'bg-red-100 text-red-800',
    ];
    $priorityNames = [
        'low' => 'منخفضة',
        'medium' => 'متوسطة',
        'high' => 'عالية',
        'critical' => 'حرجة',
    ];
?>
<div class="overflow-x-auto">
    <table class="w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم التذكرة</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموضوع</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">العميل</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الأولوية</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">المكلف</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">إجراءات</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            <?php $__empty_1 = true; $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-900"><?php echo e($ticket->ticket_number); ?></div>
                    <div class="text-xs text-gray-500"><?php echo e($ticket->created_at->diffForHumans()); ?></div>
                </td>
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-900"><?php echo e(Str::limit($ticket->subject, 45)); ?></div>
                    <div class="text-xs text-gray-500"><?php echo e($ticket->category_name); ?></div>
                </td>
                <td class="px-4 py-3 text-gray-900"><?php echo e($ticket->client?->name ?? '—'); ?></td>
                <td class="px-4 py-3">
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium <?php echo e($priorityColors[$ticket->priority] ?? 'bg-gray-100 text-gray-800'); ?>">
                        <?php echo e($priorityNames[$ticket->priority] ?? $ticket->priority); ?>

                    </span>
                </td>
                <td class="px-4 py-3">
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium <?php echo e($statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-800'); ?>">
                        <?php echo e($ticket->status_name); ?>

                    </span>
                </td>
                <td class="px-4 py-3 text-gray-700"><?php echo e($ticket->assignedTo?->name ?? 'غير مكلف'); ?></td>
                <td class="px-4 py-3">
                    <div class="flex gap-2">
                        <a href="<?php echo e(route('tickets.show', $ticket)); ?>" class="text-blue-600 hover:underline font-semibold">عرض</a>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-tickets')): ?>
                        <a href="<?php echo e(route('tickets.edit', $ticket)); ?>" class="text-green-600 hover:underline font-semibold">تعديل</a>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="7" class="px-4 py-6 text-center text-gray-500">لا توجد تذاكر في هذا القسم</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\tickets\_table.blade.php ENDPATH**/ ?>