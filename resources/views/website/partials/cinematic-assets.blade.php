{{-- Static files in public/ — no Vite/npm build required --}}
@php
    $base = rtrim(request()->getBaseUrl(), '/');
    $cinematicCss = $base . '/css/cinematic-home.css?v=4';
    $cinematicJs = $base . '/js/cinematic-home.js?v=4';
@endphp
<link rel="stylesheet" href="{{ $cinematicCss }}">
