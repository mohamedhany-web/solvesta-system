<!DOCTYPE html>
<html>
<head>
    <title>Test Logo</title>
</head>
<body>
    <h1>Logo Test</h1>
    
    @php
        $logoPath = \App\Helpers\SettingsHelper::getLogoPath();
        $logoUrl = \App\Helpers\SettingsHelper::getLogoUrl();
    @endphp
    
    <h2>Debug Info:</h2>
    <p><strong>Logo Path from DB:</strong> {{ $logoPath ?? 'NULL' }}</p>
    <p><strong>Logo URL:</strong> {{ $logoUrl ?? 'NULL' }}</p>
    
    @if($logoPath)
        <p><strong>Storage Exists:</strong> {{ \Storage::disk('public')->exists($logoPath) ? 'YES' : 'NO' }}</p>
        <p><strong>Full Path:</strong> {{ storage_path('app/public/' . $logoPath) }}</p>
        <p><strong>File Exists:</strong> {{ file_exists(storage_path('app/public/' . $logoPath)) ? 'YES' : 'NO' }}</p>
    @endif
    
    <h2>Logo Display:</h2>
    @if($logoUrl)
        <img src="{{ $logoUrl }}" alt="Logo" style="max-width: 200px; border: 1px solid #ccc;">
        <p>URL: {{ $logoUrl }}</p>
    @else
        <p style="color: red;">Logo URL is NULL</p>
    @endif
</body>
</html>

