@php
    $clientLoginJs = public_path('js/client-login.js');
@endphp
@if(is_readable($clientLoginJs))
<script id="sv-client-login-script">
{!! file_get_contents($clientLoginJs) !!}
</script>
@endif
