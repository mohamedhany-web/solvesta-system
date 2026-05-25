@php
    $base = rtrim(request()->getBaseUrl(), '/');
    $cinematicJs = $base . '/js/cinematic-home.js?v=4';
@endphp
<script src="{{ $cinematicJs }}" defer></script>
