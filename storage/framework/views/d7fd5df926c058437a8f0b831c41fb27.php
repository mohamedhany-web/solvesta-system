<?php
    $identity = auth()->user()?->gitIdentity;
    $themeColor = $themeColor ?? \App\Helpers\SettingsHelper::getThemeColor();
?>
<div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
    <h3 class="font-bold text-sm text-gray-900 mb-1 flex items-center gap-2">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
        طلب وصول GitHub
    </h3>
    <p class="text-xs text-gray-500 mb-3">أدخل username والبريد المسجّل على GitHub — ستُرسل طلبك للإدارة لاعتماد صلاحيات الريبو.</p>

    <?php if($identity): ?>
    <span class="inline-flex text-xs font-bold px-2 py-1 rounded-lg mb-3 <?php echo e($identity->statusColor()); ?>"><?php echo e($identity->statusLabel()); ?></span>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('dev-workflow.git-identity')); ?>" class="space-y-3">
        <?php echo csrf_field(); ?>
        <div>
            <label class="text-[10px] font-bold text-gray-600 block mb-1">GitHub Username</label>
            <input name="github_username" value="<?php echo e(old('github_username', $identity?->username)); ?>" required placeholder="username" dir="ltr"
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm">
        </div>
        <div>
            <label class="text-[10px] font-bold text-gray-600 block mb-1">البريد على GitHub</label>
            <input type="email" name="github_email" value="<?php echo e(old('github_email', $identity?->email ?? auth()->user()?->email)); ?>" required dir="ltr"
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm">
        </div>
        <div>
            <label class="text-[10px] font-bold text-gray-600 block mb-1">ملاحظة للإدارة (اختياري)</label>
            <input name="employee_note" value="<?php echo e(old('employee_note', $identity?->employee_note)); ?>" placeholder="مثال: مشروع Stock Management"
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm">
        </div>
        <button type="submit" class="w-full py-2 rounded-xl text-white text-sm font-bold" style="background: <?php echo e($themeColor); ?>;">
            <?php echo e($identity?->status === \App\Models\UserGitIdentity::STATUS_PENDING ? 'تحديث الطلب' : 'إرسال للإدارة'); ?>

        </button>
    </form>

    <?php if($identity?->status === \App\Models\UserGitIdentity::STATUS_APPROVED): ?>
    <p class="text-[10px] text-emerald-700 mt-2">يمكنك إنشاء فروع وPR من المهام — نفس مسار العمل على GitHub.</p>
    <?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\dev-workflow\partials\git-identity-form.blade.php ENDPATH**/ ?>