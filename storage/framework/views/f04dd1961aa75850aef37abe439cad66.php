

<?php $__env->startSection('page-title', 'إنشاء مهمة (مدير القسم)'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-6xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">إنشاء مهمة جديدة</h1>
            <p class="text-gray-600">إنشاء مهمة داخل مشاريع قسمك وإسنادها لأعضاء فريقك</p>
        </div>
        <a href="<?php echo e(route('department-manager.dashboard')); ?>" class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-200 shadow-sm">
            العودة للوحة القسم
        </a>
    </div>

    <?php if(session('error')): ?>
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('department-manager.tasks.store')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <?php echo csrf_field(); ?>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">تفاصيل المهمة</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">عنوان المهمة <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="<?php echo e(old('title')); ?>" required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">المشروع <span class="text-red-500">*</span></label>
                    <select name="project_id" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['project_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">اختر المشروع</option>
                        <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($project->id); ?>" <?php if(old('project_id') == $project->id): echo 'selected'; endif; ?>><?php echo e($project->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['project_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">إسناد إلى <span class="text-red-500">*</span></label>
                    <select name="assigned_to" required
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['assigned_to'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">اختر الموظف</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php if(old('assigned_to') == $user->id): echo 'selected'; endif; ?>><?php echo e($user->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['assigned_to'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">وصف المهمة <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="5" required
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('description')); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">الأولوية <span class="text-red-500">*</span></label>
                    <select name="priority" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
                        <?php $__currentLoopData = ['low' => 'منخفضة', 'medium' => 'متوسطة', 'high' => 'عالية', 'urgent' => 'عاجلة']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php if(old('priority','medium') == $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">الحالة <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
                        <?php $__currentLoopData = ['todo' => 'قيد الإعداد', 'in_progress' => 'قيد التنفيذ', 'review' => 'مراجعة', 'completed' => 'مكتملة', 'cancelled' => 'ملغاة']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php if(old('status','todo') == $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ البداية</label>
                    <input type="date" name="start_date" value="<?php echo e(old('start_date')); ?>"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ الاستحقاق <span class="text-red-500">*</span></label>
                    <input type="date" name="due_date" value="<?php echo e(old('due_date')); ?>" required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">ساعات تقديرية</label>
                    <input type="number" step="0.5" min="0" name="estimated_hours" value="<?php echo e(old('estimated_hours')); ?>"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">نسبة الإنجاز (%)</label>
                    <input type="number" min="0" max="100" name="progress_percentage" value="<?php echo e(old('progress_percentage')); ?>"
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">مرفقات</label>
                    <input type="file" name="attachments[]" multiple
                           class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50">
                    <?php $__errorArgs = ['attachments.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <button type="submit" class="bg-blue-600 text-white px-7 py-3 rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-sm">
                إنشاء المهمة
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\department-manager\tasks\create.blade.php ENDPATH**/ ?>