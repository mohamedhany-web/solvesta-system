<?php $__env->startSection('page-title', 'Business Development'); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full px-2 sm:px-0">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'Business Development',
        'subtitle' => 'شركاء · فرص · تحويل إلى Leads',
        'icon' => 'users',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap gap-3 mb-6">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-sales')): ?>
        <a href="<?php echo e(route('bd.partners.create')); ?>" class="px-5 py-2.5 rounded-xl text-white font-bold text-sm" style="background:linear-gradient(135deg,<?php echo e($themeColor); ?> 0%,<?php echo e($themeColor); ?>dd 100%);">+ شريك</a>
        <?php endif; ?>
        <a href="<?php echo e(route('leads.index')); ?>" class="px-4 py-2 rounded-xl border bg-white text-sm font-bold">Leads</a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <?php $__currentLoopData = [['شركاء',$stats['partners']],['فرص مفتوحة',$stats['opportunities']],['تحوّلت لـ Lead',$stats['converted']],['قيمة Pipeline',number_format($stats['pipeline_value'])]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => [$l,$v]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl shadow-lg border p-4 text-center">
            <p class="text-xs text-gray-500 font-tajawal"><?php echo e($l); ?></p>
            <p class="text-2xl font-bold mt-1" style="color:<?php echo e($i===3?$themeColor:'#111'); ?>;"><?php echo e($v); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-lg border overflow-hidden">
            <div class="px-5 py-4 border-b font-bold font-tajawal" style="background:<?php echo e($themeColor); ?>08;">الشركاء</div>
            <ul class="divide-y text-sm font-tajawal">
                <?php $__empty_1 = true; $__currentLoopData = $partners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <li class="px-4 py-3 flex justify-between hover:bg-gray-50">
                    <div>
                        <a href="<?php echo e(route('bd.partners.show', $p)); ?>" class="font-bold" style="color:<?php echo e($themeColor); ?>;"><?php echo e($p->name); ?></a>
                        <p class="text-xs text-gray-500"><?php echo e($p->company); ?> · <?php echo e($p->partner_type_label); ?></p>
                    </div>
                    <span class="text-xs"><?php echo e($p->opportunities_count); ?> فرصة</span>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <li class="px-4 py-8 text-center text-gray-500">لا يوجد شركاء بعد.</li>
                <?php endif; ?>
            </ul>
            <div class="px-4 py-2"><?php echo e($partners->links('pagination::tailwind', ['pageName'=>'partners_page'])); ?></div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border overflow-hidden">
            <div class="px-5 py-4 border-b font-bold font-tajawal">الفرص (Opportunities)</div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-sales')): ?>
            <form method="POST" action="<?php echo e(route('bd.opportunities.store')); ?>" class="p-4 border-b bg-gray-50 space-y-2 text-sm">
                <?php echo csrf_field(); ?>
                <input name="title" required placeholder="عنوان الفرصة..." class="w-full border rounded-lg px-3 py-2">
                <div class="grid grid-cols-2 gap-2">
                    <select name="partner_id" class="border rounded-lg px-3 py-2">
                        <option value="">شريك (اختياري)</option>
                        <?php $__currentLoopData = \App\Models\BdPartner::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ptr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($ptr->id); ?>"><?php echo e($ptr->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <input name="estimated_value" type="number" placeholder="القيمة التقديرية" class="border rounded-lg px-3 py-2">
                </div>
                <button class="w-full py-2 rounded-lg text-white font-bold" style="background:<?php echo e($themeColor); ?>;">+ فرصة</button>
            </form>
            <?php endif; ?>
            <ul class="divide-y text-sm font-tajawal">
                <?php $__empty_1 = true; $__currentLoopData = $opportunities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <li class="px-4 py-3">
                    <div class="flex justify-between gap-2">
                        <div>
                            <strong><?php echo e($o->title); ?></strong>
                            <p class="text-xs text-gray-500"><?php echo e($o->reference_code); ?> · <?php echo e($o->status_label); ?></p>
                        </div>
                        <span class="font-bold"><?php echo e($o->estimated_value ? number_format($o->estimated_value) : '—'); ?></span>
                    </div>
                    <?php if($o->status!=='converted' && $o->status!=='lost'): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-sales')): ?>
                    <form method="POST" action="<?php echo e(route('bd.opportunities.convert', $o)); ?>" class="mt-2"><?php echo csrf_field(); ?>
                        <button class="text-xs font-bold text-blue-600">تحويل إلى Lead →</button>
                    </form>
                    <?php endif; ?>
                    <?php elseif($o->lead_id): ?>
                    <a href="<?php echo e(route('leads.show', $o->lead_id)); ?>" class="text-xs text-emerald-600 font-bold">عرض Lead</a>
                    <?php endif; ?>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <li class="px-4 py-8 text-center text-gray-500">لا توجد فرص.</li>
                <?php endif; ?>
            </ul>
            <div class="px-4 py-2"><?php echo e($opportunities->links('pagination::tailwind', ['pageName'=>'opportunities_page'])); ?></div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views/bd/index.blade.php ENDPATH**/ ?>