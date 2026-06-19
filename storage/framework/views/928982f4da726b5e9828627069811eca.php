

<?php $__env->startSection('page-title', $wallet->name); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    
    <div class="mb-6">
        <a href="<?php echo e(route('accounting.wallets.index')); ?>" class="text-sm text-blue-600 font-semibold hover:underline inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            المحافظ والمعاملات
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
        <div class="bg-gradient-to-l from-slate-800 to-slate-900 text-white px-6 py-6 sm:px-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="text-xs font-bold uppercase tracking-wide bg-white/15 px-2.5 py-1 rounded-lg"><?php echo e($wallet->type_name); ?></span>
                        <?php if($wallet->is_active): ?>
                            <span class="text-xs font-bold bg-green-500/90 px-2.5 py-1 rounded-lg">نشطة</span>
                        <?php else: ?>
                            <span class="text-xs font-bold bg-gray-500 px-2.5 py-1 rounded-lg">معطّلة</span>
                        <?php endif; ?>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold"><?php echo e($wallet->name); ?></h1>
                    <p class="text-slate-300 text-sm mt-1"><?php echo e($wallet->currency); ?>

                        <?php if($wallet->bank_name): ?> · <?php echo e($wallet->bank_name); ?><?php endif; ?>
                        <?php if($wallet->account_number): ?> · <?php echo e($wallet->account_number); ?><?php endif; ?>
                    </p>
                </div>
                <div class="text-right lg:text-left">
                    <p class="text-slate-400 text-sm mb-1">الرصيد الحالي</p>
                    <p class="text-3xl sm:text-4xl font-bold text-white"><?php echo e(number_format($wallet->current_balance, 2)); ?> <span class="text-lg font-semibold">ج.م</span></p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 mt-5 pt-5 border-t border-white/20">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-finance')): ?>
                <a href="<?php echo e(route('accounting.wallets.edit', $wallet)); ?>" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-bold transition">تعديل المحفظة</a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-finance')): ?>
                <form method="POST" action="<?php echo e(route('accounting.wallets.destroy', $wallet)); ?>" class="inline"
                      onsubmit="return confirm('<?php echo e($wallet->transactions_count > 0 ? 'سيتم تعطيل المحفظة. متابعة؟' : 'حذف المحفظة نهائياً؟'); ?>')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="px-4 py-2 bg-red-600/90 hover:bg-red-600 text-white rounded-lg text-sm font-bold transition">
                        <?php echo e($wallet->transactions_count > 0 ? 'تعطيل' : 'حذف'); ?>

                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
        <?php if($wallet->notes): ?>
        <div class="px-6 py-3 bg-slate-50 border-t text-sm text-gray-600">
            <span class="font-semibold text-gray-800">ملاحظات:</span> <?php echo e($wallet->notes); ?>

        </div>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <p class="text-sm text-gray-500 mb-1">الرصيد الحالي</p>
            <p class="text-2xl font-bold text-blue-700"><?php echo e(number_format($wallet->current_balance, 2)); ?> ج.م</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-5">
            <p class="text-sm text-green-700 mb-1">إجمالي الوارد</p>
            <p class="text-2xl font-bold text-green-800"><?php echo e(number_format($stats['in'], 2)); ?> ج.م</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-xl p-5">
            <p class="text-sm text-red-700 mb-1">إجمالي الصادر</p>
            <p class="text-2xl font-bold text-red-800"><?php echo e(number_format($stats['out'], 2)); ?> ج.م</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-finance')): ?>
        <div class="xl:col-span-1">
            <?php if(!$wallet->is_active): ?>
            <div class="mb-4 bg-amber-50 border border-amber-200 text-amber-900 px-4 py-3 rounded-xl text-sm">
                المحفظة معطّلة. <a href="<?php echo e(route('accounting.wallets.edit', $wallet)); ?>" class="font-bold underline">فعّلها</a> لتسجيل حركات.
            </div>
            <?php endif; ?>
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sticky top-4">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm">+</span>
                    حركة يدوية
                </h2>
                <form method="POST" action="<?php echo e(route('accounting.wallets.transactions.store', $wallet)); ?>" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">النوع</label>
                        <select name="direction" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="in">إيداع</option>
                            <option value="out">سحب</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">المبلغ (ج.م)</label>
                        <input type="number" name="amount" step="0.01" min="0.01" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">التاريخ</label>
                        <input type="date" name="transaction_date" value="<?php echo e(date('Y-m-d')); ?>" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">مرجع</label>
                        <input type="text" name="reference" placeholder="رقم إيصال / تحويل"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">الوصف</label>
                        <input type="text" name="description" placeholder="وصف الحركة"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition disabled:opacity-50"
                            <?php if(!$wallet->is_active): echo 'disabled'; endif; ?>>تسجيل الحركة</button>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <div class="<?php echo e(auth()->user()?->can('edit-finance') ? 'xl:col-span-2' : 'xl:col-span-3'); ?>">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900">سجل المعاملات</h2>
                    <span class="text-sm text-gray-500"><?php echo e($transactions->total()); ?> حركة</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600">
                                <th class="px-4 py-3 text-right font-semibold">التاريخ</th>
                                <th class="px-4 py-3 text-right font-semibold">النوع</th>
                                <th class="px-4 py-3 text-right font-semibold">المبلغ</th>
                                <th class="px-4 py-3 text-right font-semibold">الرصيد بعد</th>
                                <th class="px-4 py-3 text-right font-semibold">الفاتورة</th>
                                <th class="px-4 py-3 text-right font-semibold">الوصف</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-blue-50/50 transition">
                                <td class="px-4 py-3 whitespace-nowrap"><?php echo e($t->transaction_date->format('Y/m/d')); ?></td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2 py-0.5 rounded-md text-xs font-bold <?php echo e($t->direction === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                        <?php echo e($t->direction_label); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3 font-bold <?php echo e($t->direction === 'in' ? 'text-green-700' : 'text-red-700'); ?>">
                                    <?php echo e($t->direction === 'in' ? '+' : '−'); ?><?php echo e(number_format($t->amount, 2)); ?>

                                </td>
                                <td class="px-4 py-3 font-medium text-gray-800"><?php echo e(number_format($t->balance_after, 2)); ?></td>
                                <td class="px-4 py-3">
                                    <?php if($t->invoice): ?>
                                        <a href="<?php echo e(route('financial-invoices.show', $t->invoice)); ?>" class="text-blue-600 font-semibold hover:underline"><?php echo e($t->invoice->invoice_number); ?></a>
                                    <?php else: ?>
                                        <span class="text-gray-400">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-gray-600 max-w-[200px] truncate" title="<?php echo e($t->description); ?>"><?php echo e($t->description ?: '—'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-4 py-16 text-center text-gray-500">
                                    <p class="font-medium">لا توجد معاملات بعد</p>
                                    <p class="text-sm mt-1">سجّل إيداعاً أو سحباً من النموذج الجانبي</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if($transactions->hasPages()): ?>
                <div class="px-4 py-3 border-t border-gray-100 bg-gray-50"><?php echo e($transactions->links()); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\accounting\wallets\show.blade.php ENDPATH**/ ?>