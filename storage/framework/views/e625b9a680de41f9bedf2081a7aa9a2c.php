<?php
    $clientLoginJs = public_path('js/client-login.js');
?>
<?php if(is_readable($clientLoginJs)): ?>
<script id="sv-client-login-script">
<?php echo file_get_contents($clientLoginJs); ?>

</script>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\client-auth\partials\client-login-inline-js.blade.php ENDPATH**/ ?>