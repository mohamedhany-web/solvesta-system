{{-- مضمّن داخل HTML — يعمل حتى لو السيرفر لا يخدم /css/ --}}
@php
    $cinematicCssFile = public_path('css/cinematic-home.css');
@endphp
@if(is_readable($cinematicCssFile))
<style id="sv-cinematic-theme">
{!! file_get_contents($cinematicCssFile) !!}
</style>
@endif
