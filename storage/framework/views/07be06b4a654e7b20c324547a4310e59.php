<?php
    $themeColor = $themeColor ?? \App\Helpers\SettingsHelper::getThemeColor();
    $compact = $compact ?? false;
    $pendingCount = $pendingCount ?? $accessRequests->where('status', \App\Models\UserGitIdentity::STATUS_PENDING)->count();
?>
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden <?php echo e($compact ? '' : 'mb-6'); ?>">
    <div class="px-4 py-3 border-b border-gray-100 flex flex-wrap justify-between items-center gap-2 <?php echo e($compact ? '' : 'px-5 py-4'); ?>">
        <div>
            <h3 class="font-bold text-sm text-gray-900 flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                طلبات وصول GitHub
                <?php if($pendingCount > 0): ?>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800"><?php echo e($pendingCount); ?> بانتظارك</span>
                <?php endif; ?>
            </h3>
            <?php if (! ($compact)): ?>
            <p class="text-xs text-gray-500 mt-1">اعتماد الطلب يرسل دعوات collaborator تلقائياً على مستودعات مشاريع الموظف.</p>
            <?php endif; ?>
        </div>
        <a href="<?php echo e(route('github.index', ['tab' => 'access'])); ?>" class="text-[10px] font-bold hover:underline shrink-0" style="color: <?php echo e($themeColor); ?>;">صفحة GitHub الكاملة</a>
    </div>

    <div class="divide-y divide-gray-100 <?php echo e($compact ? 'max-h-[320px]' : 'max-h-[50vh]'); ?> overflow-y-auto">
        <?php $__empty_1 = true; $__currentLoopData = $accessRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $employee = $req->user?->employee;
            $deptName = $employee?->department?->name;
        ?>
        <div class="px-4 py-3 hover:bg-gray-50/50 <?php echo e($compact ? '' : 'px-5 py-4'); ?>">
            <div class="flex flex-wrap justify-between gap-2 items-start">
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-0.5">
                        <p class="font-bold text-xs text-gray-900"><?php echo e($req->user?->name ?? '—'); ?></p>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg <?php echo e($req->statusColor()); ?>"><?php echo e($req->statusLabel()); ?></span>
                    </div>
                    <p class="text-[11px] text-gray-500">
                        <?php if($deptName && !$compact): ?><span><?php echo e($deptName); ?></span><span class="text-gray-300 mx-1">·</span><?php endif; ?>
                        <span class="font-mono" dir="ltr"><?php echo e($req->username); ?></span>
                        <?php if (! ($compact)): ?><span class="text-gray-300 mx-1">·</span><span dir="ltr"><?php echo e($req->email); ?></span><?php endif; ?>
                    </p>
                    <?php if($req->employee_note && !$compact): ?>
                    <p class="text-[11px] text-gray-600 mt-1 bg-gray-50 rounded-lg px-2 py-1"><?php echo e($req->employee_note); ?></p>
                    <?php endif; ?>
                    <p class="text-[10px] text-gray-400 mt-0.5"><?php echo e($req->created_at?->diffForHumans()); ?></p>
                </div>
                <div class="flex flex-wrap gap-1.5 shrink-0">
                    <?php if($req->status === \App\Models\UserGitIdentity::STATUS_PENDING): ?>
                    <form method="POST" action="<?php echo e(route('github.access.approve', $req)); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="px-2.5 py-1 rounded-lg text-white text-[10px] font-bold" style="background: <?php echo e($themeColor); ?>;">اعتماد</button>
                    </form>
                    <form method="POST" action="<?php echo e(route('github.access.reject', $req)); ?>" class="inline" onsubmit="return confirm('رفض طلب GitHub؟');">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="px-2.5 py-1 rounded-lg border border-red-200 text-red-700 text-[10px] font-bold hover:bg-red-50">رفض</button>
                    </form>
                    <?php elseif($req->status === \App\Models\UserGitIdentity::STATUS_APPROVED): ?>
                    <form method="POST" action="<?php echo e(route('github.access.resync', $req)); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="px-2.5 py-1 rounded-lg border border-gray-200 text-[10px] font-bold text-gray-700 hover:bg-gray-50">مزامنة</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="px-4 py-8 text-center text-gray-500 text-xs">لا توجد طلبات وصول حالياً.</div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\dev-workflow\partials\git-access-requests.blade.php ENDPATH**/ ?>