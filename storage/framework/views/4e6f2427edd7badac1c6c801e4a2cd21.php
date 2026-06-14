<?php $__env->startSection('page-title', 'تعيين فريق المشروع'); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full font-tajawal">
    <div class="mb-6 flex flex-wrap justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">قسم: <?php echo e($project->department?->name); ?></p>
            <h1 class="text-2xl font-bold text-gray-900">تعيين فريق: <?php echo e($project->name); ?></h1>
            <p class="text-sm text-gray-600 mt-1">العميل: <?php echo e($project->client?->name ?? '—'); ?></p>
        </div>
        <a href="<?php echo e(route('department-manager.dashboard')); ?>" class="border border-gray-300 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50">← لوحة القسم</a>
    </div>

    <?php if(session('error')): ?>
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8">
            <form method="POST" action="<?php echo e(route('department-manager.projects.assign-team.update', $project)); ?>" class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="px-6 py-4 border-b bg-gray-50/80">
                    <h2 class="font-bold text-lg">إسناد الفريق</h2>
                    <p class="text-sm text-gray-500 mt-0.5">اختر قائد الفريق (Team Leader) ثم أعضاء الفريق من موظفي القسم</p>
                </div>

                <div class="p-6 space-y-5">
                    <div>
                        <label class="text-xs font-bold text-gray-600 block mb-1.5">قائد الفريق (Team Leader) *</label>
                        <select name="project_manager_id" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['project_manager_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="" disabled <?php if(!old('project_manager_id', $project->project_manager_id)): echo 'selected'; endif; ?>>اختر قائد الفريق...</option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>" <?php if(old('project_manager_id', $project->project_manager_id) == $user->id): echo 'selected'; endif; ?>><?php echo e($user->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['project_manager_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-600 block mb-1.5">أعضاء الفريق</label>
                        <?php $selected = old('team_members', $project->teamMembers->pluck('id')->all()); ?>
                        <select name="team_members[]" multiple class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm min-h-[200px] focus:ring-2 focus:ring-blue-500">
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>" <?php if(is_array($selected) && in_array($user->id, $selected)): echo 'selected'; endif; ?>><?php echo e($user->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="text-xs text-gray-500 mt-2">لا حاجة لإضافة قائد الفريق هنا — يُستبعد تلقائياً. للاختيار المتعدد: Ctrl / Cmd.</p>
                        <?php $__errorArgs = ['team_members'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="px-6 py-4 border-t bg-gray-50/50 flex flex-wrap gap-3">
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-white font-bold shadow-md"
                            style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
                        حفظ تعيين الفريق
                    </button>
                    <a href="<?php echo e(route('projects.show', $project)); ?>" class="px-6 py-2.5 rounded-xl border text-sm font-semibold hover:bg-gray-50">عرض المشروع</a>
                </div>
            </form>
        </div>

        <div class="xl:col-span-4 space-y-4">
            <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5 text-sm text-blue-900">
                <h3 class="font-bold mb-2">مسار العمل</h3>
                <ol class="space-y-2 list-decimal list-inside">
                    <li>أنت كرئيس قسم تستلم المشروع</li>
                    <li>تعيّن <strong>Team Leader</strong> مسؤولاً عن التنفيذ</li>
                    <li>تضيف باقي الفريق من موظفي قسمك</li>
                    <li>توزّع المهام من «إنشاء مهمة + إسناد»</li>
                </ol>
            </div>
            <?php if($users->isEmpty()): ?>
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-900">
                لا يوجد موظفون نشطون مرتبطون بحسابات مستخدمين في هذا القسم.
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\department-manager\projects\assign-team.blade.php ENDPATH**/ ?>