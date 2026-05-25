@php
    $clientLoginCss = public_path('css/client-login.css');
@endphp
@if(is_readable($clientLoginCss))
<style id="sv-client-login-theme">
{!! file_get_contents($clientLoginCss) !!}
</style>
@endif
