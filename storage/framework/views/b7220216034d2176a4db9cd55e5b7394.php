<?php $__env->startSection('page-title', 'تفاصيل المهمة'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('tasks.index')); ?>" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2"><?php echo e($task->title); ?></h1>
                    <p class="text-gray-600">تفاصيل المهمة ومعلوماتها الكاملة</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-tasks')): ?>
                <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-200 flex items-center shadow-sm">
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    تعديل
                </a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-tasks')): ?>
                <form action="<?php echo e(route('tasks.destroy', $task)); ?>" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه المهمة؟');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-xl hover:bg-red-700 transition-all duration-200 flex items-center shadow-sm">
                        <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        حذف
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Task Details Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 ml-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    وصف المهمة
                </h3>
                <div class="prose max-w-none">
                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap"><?php echo e($task->description ?? 'لا يوجد وصف لهذه المهمة'); ?></p>
                </div>
            </div>

            <!-- Progress Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 ml-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    التقدم
                </h3>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">نسبة الإنجاز</span>
                        <span class="text-2xl font-bold text-blue-600"><?php echo e($task->progress_percentage); ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-4 rounded-full transition-all duration-500" style="width: <?php echo e($task->progress_percentage); ?>%"></div>
                    </div>
                </div>
            </div>

            <!-- Time Tracking -->
            <?php if($task->estimated_hours || $task->actual_hours): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 ml-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    تتبع الوقت
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <?php if($task->estimated_hours): ?>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">الوقت المقدر</p>
                        <p class="text-2xl font-bold text-blue-600"><?php echo e($task->estimated_hours); ?></p>
                        <p class="text-xs text-gray-500">ساعة</p>
                    </div>
                    <?php endif; ?>
                    <?php if($task->actual_hours): ?>
                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">الوقت الفعلي</p>
                        <p class="text-2xl font-bold text-green-600"><?php echo e($task->actual_hours); ?></p>
                        <p class="text-xs text-gray-500">ساعة</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Subtasks -->
            <?php if($task->subtasks->count() > 0): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 ml-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    المهام الفرعية (<?php echo e($task->subtasks->count()); ?>)
                </h3>
                <div class="space-y-3">
                    <?php $__currentLoopData = $task->subtasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subtask): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('tasks.show', $subtask)); ?>" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-<?php echo e($subtask->status_color); ?>-500"></div>
                                <span class="font-medium text-gray-900"><?php echo e($subtask->title); ?></span>
                            </div>
                            <span class="px-3 py-1 bg-<?php echo e($subtask->status_color); ?>-100 text-<?php echo e($subtask->status_color); ?>-800 rounded-full text-xs font-medium">
                                <?php echo e($subtask->progress_percentage); ?>%
                            </span>
                        </div>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Comments & Updates Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 ml-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    التحديثات والتعليقات
                </h3>
                
                <!-- Add Comment/Update/Deliverable Form -->
                <form id="commentForm" class="mb-6">
                    <?php echo csrf_field(); ?>
                    <div class="mb-4">
                        <!-- Type Selection -->
                        <div class="mb-3 flex items-center gap-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="update_type" value="update" id="type_update" class="ml-2 text-blue-600 focus:ring-blue-500" checked>
                                <span class="text-sm font-medium text-gray-700">تحديث</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="update_type" value="comment" id="type_comment" class="ml-2 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">تعليق</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="update_type" value="progress_update" id="type_progress" class="ml-2 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">تحديث نسبة الإنجاز</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="update_type" value="file_upload" id="type_file" class="ml-2 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">تسليم ملف</span>
                            </label>
                        </div>

                        <div id="progressWrap" class="hidden mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">نسبة الإنجاز (%)</label>
                            <input type="number" id="progressPercentage" min="0" max="100"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="مثال: 60">
                        </div>

                        <div id="filesWrap" class="hidden mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">ملفات التسليم</label>
                            <input type="file" id="updateAttachments" name="attachments[]" multiple
                                   class="w-full px-4 py-3 border border-dashed border-gray-300 rounded-xl bg-gray-50">
                            <p class="text-xs text-gray-500 mt-2">حد أقصى 10MB لكل ملف</p>
                        </div>

                        <textarea 
                            id="commentText" 
                            name="comment" 
                            rows="3" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                            placeholder="اكتب تحديث أو تعليق هنا..."
                        ></textarea>
                    </div>
                    <button 
                        type="submit" 
                        id="submitButton"
                        class="bg-blue-600 text-white px-6 py-2.5 rounded-xl hover:bg-blue-700 transition-all duration-200 flex items-center gap-2"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        <span id="buttonText">إضافة تحديث</span>
                    </button>
                </form>

                <!-- Comments List -->
                <div class="space-y-4" id="commentsList">
                    <?php $__empty_1 = true; $__currentLoopData = $task->updates->sortByDesc('created_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $update): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-medium flex-shrink-0 <?php echo e($update->type === 'update' ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-blue-500 to-blue-600'); ?>">
                                <?php if($update->type === 'update'): ?>
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                <?php else: ?>
                                    <?php echo e(substr($update->user->name, 0, 1)); ?>

                                <?php endif; ?>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <span class="font-semibold text-gray-900"><?php echo e($update->user->name); ?></span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium mr-2 <?php echo e($update->type === 'update' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'); ?>">
                                            <?php echo e($update->type === 'update' ? 'تحديث' : 'تعليق'); ?>

                                        </span>
                                        <span class="text-xs text-gray-500"><?php echo e($update->created_at->diffForHumans()); ?></span>
                                    </div>
                                </div>
                                <?php if($update->type === 'progress_update'): ?>
                                    <div class="mb-2">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800">
                                            نسبة الإنجاز: <?php echo e(data_get($update->metadata, 'progress_percentage')); ?>%
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if(!empty($update->comment)): ?>
                                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap"><?php echo e($update->comment); ?></p>
                                <?php endif; ?>

                                <?php if(is_array($update->attachments) && count($update->attachments)): ?>
                                    <div class="mt-3 space-y-2">
                                        <?php $__currentLoopData = $update->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a class="block text-sm text-blue-600 hover:underline"
                                               href="<?php echo e(asset('storage/' . ($file['path'] ?? ''))); ?>"
                                               target="_blank" rel="noopener">
                                                <?php echo e($file['name'] ?? 'ملف'); ?>

                                            </a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p>لا توجد تعليقات أو تحديثات</p>
                        <p class="text-sm mt-1">كن أول من يعلق</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status & Priority -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات عامة</h3>
                <div class="space-y-4">
                    <!-- Status -->
                    <div>
                        <label class="text-sm font-medium text-gray-600 block mb-2">الحالة</label>
                        <span class="inline-flex items-center px-4 py-2 bg-<?php echo e($task->status_color); ?>-100 text-<?php echo e($task->status_color); ?>-800 rounded-lg text-sm font-medium">
                            <span class="w-2 h-2 bg-<?php echo e($task->status_color); ?>-500 rounded-full ml-2"></span>
                            <?php switch($task->status):
                                case ('todo'): ?> للتنفيذ <?php break; ?>
                                <?php case ('in_progress'): ?> قيد التنفيذ <?php break; ?>
                                <?php case ('review'): ?> قيد المراجعة <?php break; ?>
                                <?php case ('completed'): ?> مكتمل <?php break; ?>
                                <?php case ('cancelled'): ?> ملغي <?php break; ?>
                            <?php endswitch; ?>
                        </span>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="text-sm font-medium text-gray-600 block mb-2">الأولوية</label>
                        <span class="inline-flex items-center px-4 py-2 bg-<?php echo e($task->priority_color); ?>-100 text-<?php echo e($task->priority_color); ?>-800 rounded-lg text-sm font-medium">
                            <?php switch($task->priority):
                                case ('low'): ?> منخفضة <?php break; ?>
                                <?php case ('medium'): ?> متوسطة <?php break; ?>
                                <?php case ('high'): ?> عالية <?php break; ?>
                                <?php case ('urgent'): ?> عاجلة <?php break; ?>
                            <?php endswitch; ?>
                        </span>
                    </div>

                    <!-- Project -->
                    <div>
                        <label class="text-sm font-medium text-gray-600 block mb-2">المشروع</label>
                        <a href="<?php echo e(route('projects.show', $task->project)); ?>" class="text-blue-600 hover:text-blue-700 font-medium">
                            <?php echo e($task->project->name); ?>

                        </a>
                    </div>

                    <!-- Assigned To -->
                    <div>
                        <label class="text-sm font-medium text-gray-600 block mb-2">المكلف بالمهمة</label>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-medium">
                                <?php echo e(substr($task->assignedTo->name, 0, 1)); ?>

                            </div>
                            <div>
                                <p class="font-medium text-gray-900"><?php echo e($task->assignedTo->name); ?></p>
                                <?php if($task->assignedTo->employee): ?>
                                <p class="text-xs text-gray-500"><?php echo e($task->assignedTo->employee->position); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Created By -->
                    <div>
                        <label class="text-sm font-medium text-gray-600 block mb-2">منشئ المهمة</label>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center text-white font-medium">
                                <?php echo e(substr($task->createdBy->name, 0, 1)); ?>

                            </div>
                            <div>
                                <p class="font-medium text-gray-900"><?php echo e($task->createdBy->name); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($task->created_at->diffForHumans()); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Dates -->
                    <?php if($task->start_date): ?>
                    <div>
                        <label class="text-sm font-medium text-gray-600 block mb-2">تاريخ البدء</label>
                        <div class="flex items-center gap-2 text-gray-900">
                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <?php echo e($task->start_date->format('Y-m-d')); ?>

                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($task->due_date): ?>
                    <div>
                        <label class="text-sm font-medium text-gray-600 block mb-2">تاريخ الاستحقاق</label>
                        <div class="flex items-center gap-2 <?php echo e($task->is_overdue ? 'text-red-600' : 'text-gray-900'); ?>">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <?php echo e($task->due_date->format('Y-m-d')); ?>

                            <?php if($task->is_overdue): ?>
                            <span class="text-xs font-medium">(متأخر)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tags -->
            <?php if($task->tags && count($task->tags) > 0): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">الوسوم</h3>
                <div class="flex flex-wrap gap-2">
                    <?php $__currentLoopData = $task->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        #<?php echo e($tag); ?>

                    </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('commentForm');
    const commentText = document.getElementById('commentText');
    const commentsList = document.getElementById('commentsList');
    const typeUpdate = document.getElementById('type_update');
    const typeComment = document.getElementById('type_comment');
    const typeProgress = document.getElementById('type_progress');
    const typeFile = document.getElementById('type_file');
    const buttonText = document.getElementById('buttonText');
    const progressWrap = document.getElementById('progressWrap');
    const progressPercentage = document.getElementById('progressPercentage');
    const filesWrap = document.getElementById('filesWrap');
    const updateAttachments = document.getElementById('updateAttachments');

    // تحديث نص الزر بناءً على النوع المختار
    function updateButtonText() {
        if (typeUpdate.checked) {
            buttonText.textContent = 'إضافة تحديث';
        } else {
            buttonText.textContent = typeComment.checked ? 'إضافة تعليق' : (typeProgress.checked ? 'تحديث النسبة' : 'رفع التسليم');
        }

        // toggle extra inputs
        progressWrap.classList.toggle('hidden', !typeProgress.checked);
        filesWrap.classList.toggle('hidden', !typeFile.checked);
    }

    typeUpdate.addEventListener('change', updateButtonText);
    typeComment.addEventListener('change', updateButtonText);
    typeProgress.addEventListener('change', updateButtonText);
    typeFile.addEventListener('change', updateButtonText);
    updateButtonText();

    commentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const comment = commentText.value.trim();
        const updateType = typeUpdate.checked ? 'update' : (typeComment.checked ? 'comment' : (typeProgress.checked ? 'progress_update' : 'file_upload'));
        const progress = progressPercentage ? progressPercentage.value : '';

        // Show loading state
        const submitBtn = document.getElementById('submitButton');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>';

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        const formData = new FormData();
        formData.append('type', updateType);
        if (comment) formData.append('comment', comment);
        if (updateType === 'progress_update' && progress) formData.append('progress_percentage', progress);
        if (updateAttachments && updateAttachments.files && updateAttachments.files.length) {
            for (const file of updateAttachments.files) {
                formData.append('attachments[]', file);
            }
        }

        fetch('<?php echo e(route("tasks.updates.store", $task)); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the page to show the new comment/update
                location.reload();
            } else {
                alert(data.error || 'حدث خطأ في الإضافة');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في الإضافة');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\tasks\show.blade.php ENDPATH**/ ?>