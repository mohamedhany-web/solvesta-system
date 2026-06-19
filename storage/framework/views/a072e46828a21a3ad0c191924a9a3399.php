<?php
    $clientLoginCss = public_path('css/client-login.css');
?>
<?php if(is_readable($clientLoginCss)): ?>
<style id="sv-client-login-theme">
<?php echo file_get_contents($clientLoginCss); ?>

</style>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\client-auth\partials\client-login-inline-css.blade.php ENDPATH**/ ?>