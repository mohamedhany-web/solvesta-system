@php
    $cinematicJsFile = public_path('js/cinematic-home.js');
@endphp
@if(is_readable($cinematicJsFile))
<script id="sv-cinematic-script">
{!! file_get_contents($cinematicJsFile) !!}
</script>
@endif
