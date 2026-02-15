<?php $__env->startSection('page-title', 'الإشعارات'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 sm:p-8 border border-blue-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 sm:h-12 sm:w-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 mb-1 truncate">الإشعارات</h1>
                        <p class="text-gray-600 text-sm sm:text-base md:text-lg hidden sm:block">تتبع جميع إشعاراتك وتحديثات المشاريع</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <?php if($notifications->where('is_read', false)->count() > 0): ?>
                    <form action="<?php echo e(route('notifications.mark-all-read')); ?>" method="POST" id="markAllReadForm">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-3 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 flex items-center justify-center shadow-lg hover:shadow-xl">
                            <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            تحديد الكل كمقروء
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <!-- Filter Tabs -->
            <div class="flex flex-wrap gap-2">
                <a href="<?php echo e(route('notifications.index', ['filter' => 'all'])); ?>" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo e($filter === 'all' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'); ?>">
                    الكل (<?php echo e($totalCount); ?>)
                </a>
                <a href="<?php echo e(route('notifications.index', ['filter' => 'unread'])); ?>" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo e($filter === 'unread' ? 'bg-red-100 text-red-700' : 'text-gray-600 hover:bg-gray-100'); ?>">
                    غير مقروءة (<?php echo e($unreadCount); ?>)
                </a>
                <a href="<?php echo e(route('notifications.index', ['filter' => 'today'])); ?>" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo e($filter === 'today' ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-100'); ?>">
                    اليوم (<?php echo e($todayCount); ?>)
                </a>
                <a href="<?php echo e(route('notifications.index', ['filter' => 'task'])); ?>" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo e($filter === 'task' ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:bg-gray-100'); ?>">
                    المهام
                </a>
                <a href="<?php echo e(route('notifications.index', ['filter' => 'project'])); ?>" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo e($filter === 'project' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'); ?>">
                    المشاريع
                </a>
                <a href="<?php echo e(route('notifications.index', ['filter' => 'message'])); ?>" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors <?php echo e($filter === 'message' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-600 hover:bg-gray-100'); ?>">
                    الرسائل
                </a>
            </div>

            <!-- Search -->
            <form method="GET" class="flex-1">
                <div class="relative">
                    <input type="text" name="search" value="<?php echo e($search); ?>" 
                           placeholder="البحث في الإشعارات..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <?php if($filter !== 'all'): ?>
                    <input type="hidden" name="filter" value="<?php echo e($filter); ?>">
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <?php if($notifications->count() > 0): ?>
            <div class="divide-y divide-gray-200">
                <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="p-3 sm:p-4 md:p-6 hover:bg-gray-50 transition-colors <?php echo e(!$notification->is_read ? 'bg-blue-50 border-r-4 border-r-blue-500' : ''); ?>">
                    <div class="flex flex-col sm:flex-row items-start gap-3 sm:gap-4">
                        <!-- Icon -->
                        <div class="flex-shrink-0 relative">
                            <div class="h-10 w-10 sm:h-12 sm:w-12 bg-gradient-to-br <?php echo e($notification->type === 'task' ? 'from-green-500 to-emerald-600' : ($notification->type === 'project' ? 'from-blue-500 to-indigo-600' : ($notification->type === 'message' ? 'from-purple-500 to-pink-600' : 'from-gray-500 to-gray-600'))); ?> rounded-xl flex items-center justify-center shadow-lg">
                                <?php if($notification->type === 'task'): ?>
                                    <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                <?php elseif($notification->type === 'project'): ?>
                                    <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                <?php elseif($notification->type === 'message'): ?>
                                    <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                <?php else: ?>
                                    <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <?php if(!$notification->is_read): ?>
                                <div class="absolute -top-1 -right-1 h-3 w-3 sm:h-4 sm:w-4 bg-red-500 rounded-full border-2 border-white animate-pulse"></div>
                            <?php endif; ?>
                        </div>

                        <!-- Notification Content -->
                        <div class="flex-1 min-w-0 w-full sm:w-auto">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="text-xs sm:text-sm font-semibold text-gray-900 truncate">
                                        <?php echo e($notification->title); ?>

                                    </h3>
                                    <?php if(!$notification->is_read): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            جديد
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <span class="text-xs text-gray-500 whitespace-nowrap"><?php echo e($notification->created_at->diffForHumans()); ?></span>
                            </div>
                            
                            <p class="text-xs sm:text-sm text-gray-600 line-clamp-2 mb-3 sm:mb-0">
                                <?php echo e($notification->message); ?>

                            </p>

                            <!-- Actions - Mobile: Show at bottom, Desktop: Show on right -->
                            <div class="flex items-center gap-2 mt-2 sm:hidden">
                                <?php if(!$notification->is_read): ?>
                                    <button onclick="markAsRead(<?php echo e($notification->id); ?>)" 
                                            class="flex-1 px-3 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 text-xs font-medium transition-colors text-center">
                                        قراءة
                                    </button>
                                <?php endif; ?>
                                <form method="POST" action="<?php echo e(route('notifications.destroy', $notification)); ?>" class="inline <?php echo e(!$notification->is_read ? 'flex-1' : ''); ?>" 
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الإشعار؟')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" 
                                            class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 text-xs font-medium transition-colors">
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Actions - Desktop -->
                        <div class="hidden sm:flex items-center gap-2 flex-shrink-0">
                            <?php if(!$notification->is_read): ?>
                                <button onclick="markAsRead(<?php echo e($notification->id); ?>)" 
                                        class="px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 text-xs font-medium transition-colors">
                                    قراءة
                                </button>
                            <?php endif; ?>
                            <form method="POST" action="<?php echo e(route('notifications.destroy', $notification)); ?>" class="inline" 
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الإشعار؟')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" 
                                        class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 text-xs font-medium transition-colors">
                                    حذف
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Pagination -->
            <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-t border-gray-200 overflow-x-auto">
                <?php echo e($notifications->appends(request()->query())->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد إشعارات</h3>
                <p class="mt-1 text-sm text-gray-500">سيتم إشعارك بأي تحديثات جديدة</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const markAllReadForm = document.getElementById('markAllReadForm');
    
    if (markAllReadForm) {
        markAllReadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = '<span class="flex items-center justify-center"><svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></span>';
            
            fetch('<?php echo e(route("notifications.mark-all-read")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                button.disabled = false;
                button.innerHTML = originalText;
            });
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views/notifications/index.blade.php ENDPATH**/ ?>