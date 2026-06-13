<?php
    $cinematicJsFile = public_path('js/cinematic-home.js');
?>
<?php if(is_readable($cinematicJsFile)): ?>
<script id="sv-cinematic-script">
<?php echo file_get_contents($cinematicJsFile); ?>

</script>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\website\partials\cinematic-inline-js.blade.php ENDPATH**/ ?>