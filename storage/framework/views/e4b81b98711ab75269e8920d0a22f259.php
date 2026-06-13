
<?php
    $cinematicCssFile = public_path('css/cinematic-home.css');
?>
<?php if(is_readable($cinematicCssFile)): ?>
<style id="sv-cinematic-theme">
<?php echo file_get_contents($cinematicCssFile); ?>

</style>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\website\partials\cinematic-inline-css.blade.php ENDPATH**/ ?>