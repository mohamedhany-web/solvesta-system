<?php $__env->startSection('title', 'لوحة التحكم'); ?>
<?php $__env->startSection('page-title', 'لوحة التحكم'); ?>

<?php $__env->startSection('content'); ?>
<?php if(!empty($is_admin_dashboard)): ?>
    <?php echo $__env->make('dashboard.partials.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php else: ?>
    <?php echo $__env->make('dashboard.partials.role-default', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views/dashboard.blade.php ENDPATH**/ ?>