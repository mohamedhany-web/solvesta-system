<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير المهام</title>
    <style>
        @page { size: A4 landscape; margin: 15mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Tajawal', 'Arial', sans-serif; line-height: 1.6; color: #2d3748; background: #ffffff; }
        .report-container { max-width: 297mm; margin: 0 auto; padding: 20px; }
        
        .report-header { border-bottom: 2px solid #1a202c; padding-bottom: 20px; margin-bottom: 30px; }
        .company-name { font-size: 24px; font-weight: 700; color: #1a202c; margin-bottom: 5px; }
        .report-title { font-size: 18px; font-weight: 600; color: #4a5568; margin-bottom: 10px; }
        .report-date { font-size: 12px; color: #718096; }
        
        .summary-box { border: 1px solid #e2e8f0; padding: 20px; margin-bottom: 30px; background: #f7fafc; }
        .summary-title { font-size: 14px; font-weight: 600; color: #1a202c; margin-bottom: 15px; border-bottom: 1px solid #cbd5e0; padding-bottom: 8px; }
        .summary-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .summary-item { text-align: center; }
        .summary-label { font-size: 11px; color: #718096; margin-bottom: 5px; }
        .summary-value { font-size: 18px; font-weight: 600; color: #1a202c; }
        
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 10px; }
        .data-table thead th { background: #2d3748; color: #ffffff; padding: 8px 6px; text-align: right; font-weight: 600; font-size: 10px; border: 1px solid #1a202c; }
        .data-table tbody td { padding: 6px; border: 1px solid #e2e8f0; text-align: right; background: #ffffff; }
        .data-table tbody tr:nth-child(even) td { background: #f7fafc; }
        
        .task-name { font-weight: 600; color: #1a202c; }
        
        .status-badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 9px; font-weight: 500; }
        .status-pending { background: #feebc8; color: #7c2d12; }
        .status-in_progress { background: #bee3f8; color: #2c5282; }
        .status-completed { background: #c6f6d5; color: #22543d; }
        .status-cancelled { background: #fed7d7; color: #742a2a; }
        
        .priority-badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 9px; font-weight: 500; }
        .priority-low { background: #c6f6d5; color: #22543d; }
        .priority-medium { background: #feebc8; color: #7c2d12; }
        .priority-high { background: #fed7d7; color: #742a2a; }
        .priority-urgent { background: #742a2a; color: #fff; }
        
        .report-footer { margin-top: 50px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
        .signature-section { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: 30px; }
        .signature-box { text-align: center; }
        .signature-line { border-top: 1px solid #2d3748; margin-bottom: 8px; padding-top: 40px; }
        .signature-label { font-size: 11px; color: #4a5568; }
        
        @media print { body { background: white; } .report-container { padding: 0; } .avoid-break { page-break-inside: avoid; } }
        .no-data { text-align: center; padding: 40px; color: #718096; font-size: 14px; }
    </style>
</head>
<body>
    <div class="report-container">
        <div class="report-header">
            <div class="company-name"><?php echo e(\App\Helpers\SettingsHelper::getCompanyName()); ?></div>
            <div class="report-title">تقرير المهام</div>
            <div class="report-date">تاريخ التقرير: <?php echo e(now()->format('Y-m-d')); ?> | <?php echo e(now()->format('h:i A')); ?></div>
        </div>
        
        <?php if($tasks->count() > 0): ?>
            <div class="summary-box avoid-break">
                <div class="summary-title">ملخص التقرير</div>
                <div class="summary-grid">
                    <div class="summary-item">
                        <div class="summary-label">إجمالي عدد المهام</div>
                        <div class="summary-value"><?php echo e($summary['total']); ?></div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">نسبة الإنجاز</div>
                        <div class="summary-value"><?php echo e($summary['completion_rate']); ?>%</div>
                    </div>
                </div>
            </div>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">م</th>
                        <th style="width: 25%;">اسم المهمة</th>
                        <th style="width: 18%;">المشروع</th>
                        <th style="width: 15%;">المكلف</th>
                        <th style="width: 10%;">تاريخ البدء</th>
                        <th style="width: 10%;">تاريخ الانتهاء</th>
                        <th style="width: 7%;">الأولوية</th>
                        <th style="width: 10%;">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td class="task-name"><?php echo e($task->title); ?></td>
                        <td><?php echo e($task->project->name ?? '-'); ?></td>
                        <td><?php echo e($task->assignedTo->name ?? '-'); ?></td>
                        <td><?php echo e($task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('Y-m-d') : '-'); ?></td>
                        <td><?php echo e($task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : '-'); ?></td>
                        <td>
                            <span class="priority-badge priority-<?php echo e($task->priority); ?>">
                                <?php if($task->priority === 'low'): ?> منخفضة
                                <?php elseif($task->priority === 'medium'): ?> متوسطة
                                <?php elseif($task->priority === 'high'): ?> عالية
                                <?php elseif($task->priority === 'urgent'): ?> عاجلة
                                <?php else: ?> <?php echo e($task->priority); ?>

                                <?php endif; ?>
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-<?php echo e($task->status); ?>">
                                <?php if($task->status === 'pending'): ?> معلقة
                                <?php elseif($task->status === 'in_progress'): ?> قيد التنفيذ
                                <?php elseif($task->status === 'completed'): ?> مكتملة
                                <?php elseif($task->status === 'cancelled'): ?> ملغاة
                                <?php else: ?> <?php echo e($task->status); ?>

                                <?php endif; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            
            <div class="report-footer avoid-break">
                <div class="signature-section">
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <div class="signature-label">إعداد</div>
                    </div>
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <div class="signature-label">مراجعة</div>
                    </div>
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <div class="signature-label">اعتماد</div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="no-data">
                <p>لا توجد مهام مطابقة للمعايير المحددة</p>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        window.onload = function() { window.print(); }
    </script>
</body>
</html>


<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\reports\tasks-print.blade.php ENDPATH**/ ?>