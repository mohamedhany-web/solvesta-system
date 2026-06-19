<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'title',
    'subtitle' => null,
    'icon' => 'chart',
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'title',
    'subtitle' => null,
    'icon' => 'chart',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $icons = [
        'chart' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        'users' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z',
        'doc' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'briefcase' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        'workspace' => 'M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2',
    ];
    $path = $icons[$icon] ?? $icons['chart'];
?>
<div class="mb-6 sm:mb-8">
    <div class="rounded-2xl p-5 sm:p-6 lg:p-8 shadow-xl border overflow-hidden relative"
         style="background: linear-gradient(135deg, <?php echo e($themeColor); ?>15 0%, <?php echo e($themeColor); ?>05 50%, <?php echo e($themeColor); ?>10 100%); border-color: <?php echo e($themeColor); ?>30;">
        <div class="absolute top-0 left-0 w-full h-full opacity-5 overflow-hidden pointer-events-none">
            <div class="absolute top-8 right-8 w-48 h-48 rounded-full" style="background: <?php echo e($themeColor); ?>;"></div>
            <div class="absolute bottom-8 left-8 w-32 h-32 rounded-full" style="background: <?php echo e($themeColor); ?>;"></div>
        </div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0"
                     style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
                    <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($path); ?>" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 font-tajawal"><?php echo e($title); ?></h1>
                    <?php if($subtitle): ?>
                        <p class="text-sm text-gray-600 mt-1 font-tajawal"><?php echo e($subtitle); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php if(isset($actions)): ?>
                <div class="flex flex-wrap gap-2"><?php echo e($actions); ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\partials\erp-page-header.blade.php ENDPATH**/ ?>