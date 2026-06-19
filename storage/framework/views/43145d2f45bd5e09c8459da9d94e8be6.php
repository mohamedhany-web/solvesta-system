<?php
    $base = rtrim(request()->getBaseUrl(), '/');
    $cinematicJs = $base . '/js/cinematic-home.js?v=4';
?>
<script src="<?php echo e($cinematicJs); ?>" defer></script>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\website\partials\cinematic-js.blade.php ENDPATH**/ ?>