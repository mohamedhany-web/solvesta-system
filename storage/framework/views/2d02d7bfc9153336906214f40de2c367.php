<?php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $contract = $sale->contracts()->first();
    $estimation = $sale->costEstimations()->latest()->first();
    $proposal = $sale->proposals()->latest()->first();
    $steps = [
        ['key' => 'qualify', 'label' => 'تأهيل', 'done' => $sale->qualification_status === 'qualified'],
        ['key' => 'estimate', 'label' => 'تقدير', 'done' => $estimation && in_array($estimation->status, ['submitted', 'approved'])],
        ['key' => 'proposal', 'label' => 'عرض', 'done' => $proposal && in_array($proposal->status, ['sent', 'accepted'])],
        ['key' => 'contract', 'label' => 'عقد', 'done' => (bool) $contract],
        ['key' => 'project', 'label' => 'مشروع', 'done' => (bool) $sale->project_id],
    ];
?>
<div class="rounded-2xl shadow-lg border border-gray-200 p-6 mb-6 overflow-hidden" style="border-color: <?php echo e($themeColor); ?>25;">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <div>
            <h2 class="text-lg font-bold text-gray-900 font-tajawal">سلسلة التدفق التجاري</h2>
            <p class="text-sm text-gray-600 font-tajawal">Lead → تأهيل → Pre-Sales → Proposal → عقد → دفعة → مشروع</p>
        </div>
        <div class="flex flex-wrap gap-1">
            <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold font-tajawal <?php echo e($step['done'] ? 'text-white' : 'bg-gray-100 text-gray-500'); ?>"
                      <?php if($step['done']): ?> style="background: <?php echo e($themeColor); ?>;" <?php endif; ?>>
                    <?php echo e($i + 1); ?>. <?php echo e($step['label']); ?>

                </span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div class="flex flex-wrap gap-2 text-xs mb-5 font-tajawal">
        <?php $__currentLoopData = ['pending'=>'بانتظار التأهيل','qualified'=>'مؤهل','disqualified'=>'غير مؤهل']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <span class="px-3 py-1 rounded-full <?php echo e($sale->qualification_status===$k ? 'text-white font-bold' : 'bg-gray-100 text-gray-600'); ?>"
                  <?php if($sale->qualification_status===$k): ?> style="background: <?php echo e($themeColor); ?>;" <?php endif; ?>><?php echo e($l); ?></span>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-sales')): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        
        <?php if($sale->qualification_status !== 'qualified' && !in_array($sale->stage, ['closed_won','closed_lost'])): ?>
        <form method="POST" action="<?php echo e(route('workflow.sales.qualify', $sale)); ?>" class="border border-gray-200 rounded-xl p-4 space-y-2 bg-white">
            <?php echo csrf_field(); ?>
            <h3 class="font-bold text-sm font-tajawal">تأهيل الفرصة (Discovery)</h3>
            <textarea name="requirement_summary" rows="3" placeholder="ملخص المتطلبات..." class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm"><?php echo e($sale->requirement_summary); ?></textarea>
            <button class="bg-emerald-600 text-white px-4 py-2 rounded-xl text-sm font-bold">تأهيل ✓</button>
        </form>
        <form method="POST" action="<?php echo e(route('workflow.sales.disqualify', $sale)); ?>" class="border border-red-100 rounded-xl p-4 space-y-2 bg-white">
            <?php echo csrf_field(); ?>
            <h3 class="font-bold text-sm text-red-800 font-tajawal">غير مؤهل (Lost)</h3>
            <input name="lost_reason" required placeholder="السبب..." class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm">
            <button class="bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-bold">رفض</button>
        </form>
        <?php endif; ?>

        
        <?php if($sale->qualification_status === 'qualified' && !in_array($sale->stage, ['closed_won','closed_lost'])): ?>
        <div class="border rounded-xl p-4 md:col-span-2 bg-white" style="border-color: <?php echo e($themeColor); ?>30;">
            <h3 class="font-bold text-sm font-tajawal mb-2" style="color: <?php echo e($themeColor); ?>;">Pre-Sales — تقدير وتسعير</h3>
            <div class="flex flex-wrap gap-3 items-center text-sm">
                <a href="<?php echo e(route('pre-sales.estimate', $sale)); ?>" class="px-4 py-2 rounded-xl text-white font-bold"
                   style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
                    <?php echo e($estimation ? 'تعديل التقدير' : 'إنشاء تقدير تكلفة'); ?>

                </a>
                <?php if($estimation): ?>
                    <span class="text-gray-600"><?php echo e($estimation->reference_code); ?> — <?php echo e($estimation->status_label); ?> — <?php echo e(number_format($estimation->total_cost)); ?> ج.م</span>
                <?php endif; ?>
                <?php if($estimation?->status === 'approved' && !$proposal): ?>
                <form method="POST" action="<?php echo e(route('pre-sales.proposals.generate', $sale)); ?>"><?php echo csrf_field(); ?>
                    <button class="px-4 py-2 rounded-xl bg-blue-600 text-white font-bold">إصدار Proposal</button>
                </form>
                <?php endif; ?>
                <?php if($proposal): ?>
                    <a href="<?php echo e(route('pre-sales.proposals.show', $proposal)); ?>" class="px-4 py-2 rounded-xl border font-bold <?php echo e($proposal->status_color); ?>">
                        <?php echo e($proposal->reference_code); ?> — <?php echo e($proposal->status_label); ?>

                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        
        <?php if($sale->qualification_status === 'qualified' && !$contract): ?>
        <form method="POST" action="<?php echo e(route('workflow.sales.create-contract', $sale)); ?>" class="border border-blue-100 rounded-xl p-4 md:col-span-2 bg-white">
            <?php echo csrf_field(); ?>
            <h3 class="font-bold text-sm font-tajawal">إنشاء مسودة عقد</h3>
            <p class="text-xs text-gray-600 mb-2">
                <?php if($proposal?->status === 'accepted'): ?>
                    العميل وافق على العرض — جاهز للتعاقد.
                <?php else: ?>
                    يتطلب موافقة العميل على الـ Proposal أولاً.
                <?php endif; ?>
            </p>
            <button class="px-4 py-2 rounded-xl text-white font-bold text-sm <?php echo e($proposal?->status === 'accepted' ? 'bg-blue-600' : 'bg-gray-400 cursor-not-allowed'); ?>"
                    <?php if($proposal?->status !== 'accepted'): ?> disabled <?php endif; ?>>إنشاء عقد →</button>
        </form>
        <?php endif; ?>

        <?php if($contract): ?>
        <div class="border rounded-xl p-4 md:col-span-2 bg-gray-50">
            <p class="text-sm font-bold font-tajawal">العقد: <a href="<?php echo e(route('contracts.show', $contract)); ?>" class="text-blue-600"><?php echo e($contract->contract_number); ?></a> — <?php echo e($contract->status); ?></p>
            <div class="flex flex-wrap gap-2 mt-3">
                <form method="POST" action="<?php echo e(route('workflow.contracts.deposit-invoice', $contract)); ?>"><?php echo csrf_field(); ?>
                    <button class="bg-amber-600 text-white px-4 py-2 rounded-xl text-sm font-bold">فاتورة 50% مقدماً</button>
                </form>
                <form method="POST" action="<?php echo e(route('workflow.contracts.kickoff', $contract)); ?>"><?php echo csrf_field(); ?>
                    <button class="px-4 py-2 rounded-xl text-white text-sm font-bold" style="background: <?php echo e($themeColor); ?>;">بدء المشروع (بعد الدفع)</button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if($sale->requirement_summary): ?>
        <div class="mt-4 p-4 bg-gray-50 rounded-xl text-sm font-tajawal"><strong>ملخص المتطلبات:</strong> <?php echo e($sale->requirement_summary); ?></div>
    <?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\sales\partials\workflow.blade.php ENDPATH**/ ?>