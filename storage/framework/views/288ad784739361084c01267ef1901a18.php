
<?php
    $base = rtrim(request()->getBaseUrl(), '/');
    $cinematicCss = $base . '/css/cinematic-home.css?v=4';
    $cinematicJs = $base . '/js/cinematic-home.js?v=4';
?>
<link rel="stylesheet" href="<?php echo e($cinematicCss); ?>">
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\website\partials\cinematic-assets.blade.php ENDPATH**/ ?>