# Build Check Script

Write-Host "=======================================" -ForegroundColor Cyan
Write-Host "   Frontend Build Verification" -ForegroundColor Yellow
Write-Host "=======================================" -ForegroundColor Cyan
Write-Host ""

$allGood = $true

# Check build folder
Write-Host "Checking build folder..." -ForegroundColor Yellow
if (Test-Path "public\build") {
    Write-Host "[OK] Build folder exists" -ForegroundColor Green
} else {
    Write-Host "[ERROR] Build folder not found!" -ForegroundColor Red
    $allGood = $false
}

# Check manifest.json
Write-Host ""
Write-Host "Checking manifest.json..." -ForegroundColor Yellow
if (Test-Path "public\build\manifest.json") {
    Write-Host "[OK] manifest.json exists" -ForegroundColor Green
    $manifestSize = (Get-Item "public\build\manifest.json").Length
    Write-Host "     Size: $manifestSize bytes" -ForegroundColor Gray
} else {
    Write-Host "[ERROR] manifest.json not found!" -ForegroundColor Red
    $allGood = $false
}

# Check assets folder
Write-Host ""
Write-Host "Checking assets folder..." -ForegroundColor Yellow
if (Test-Path "public\build\assets") {
    Write-Host "[OK] Assets folder exists" -ForegroundColor Green
    $filesCount = (Get-ChildItem "public\build\assets").Count
    Write-Host "     Files count: $filesCount" -ForegroundColor Gray
} else {
    Write-Host "[ERROR] Assets folder not found!" -ForegroundColor Red
    $allGood = $false
}

# Check CSS file
Write-Host ""
Write-Host "Checking CSS file..." -ForegroundColor Yellow
$cssFiles = Get-ChildItem "public\build\assets\*.css" -ErrorAction SilentlyContinue
if ($cssFiles) {
    Write-Host "[OK] CSS file exists" -ForegroundColor Green
    foreach ($file in $cssFiles) {
        $size = [math]::Round($file.Length / 1KB, 2)
        Write-Host "     $($file.Name) - ${size} KB" -ForegroundColor Gray
    }
} else {
    Write-Host "[ERROR] CSS file not found!" -ForegroundColor Red
    $allGood = $false
}

# Check JS file
Write-Host ""
Write-Host "Checking JavaScript file..." -ForegroundColor Yellow
$jsFiles = Get-ChildItem "public\build\assets\*.js" -ErrorAction SilentlyContinue
if ($jsFiles) {
    Write-Host "[OK] JavaScript file exists" -ForegroundColor Green
    foreach ($file in $jsFiles) {
        $size = [math]::Round($file.Length / 1KB, 2)
        Write-Host "     $($file.Name) - ${size} KB" -ForegroundColor Gray
    }
} else {
    Write-Host "[ERROR] JavaScript file not found!" -ForegroundColor Red
    $allGood = $false
}

# Check ZIP file
Write-Host ""
Write-Host "Checking ZIP file..." -ForegroundColor Yellow
if (Test-Path "frontend_build_production.zip") {
    Write-Host "[OK] ZIP file ready for upload" -ForegroundColor Green
    $zipSize = [math]::Round((Get-Item "frontend_build_production.zip").Length / 1KB, 2)
    Write-Host "     Size: ${zipSize} KB" -ForegroundColor Gray
} else {
    Write-Host "[WARNING] ZIP file not found (optional)" -ForegroundColor Yellow
}

# Calculate total size
Write-Host ""
Write-Host "Statistics:" -ForegroundColor Yellow
$totalSize = (Get-ChildItem "public\build" -Recurse | Measure-Object -Property Length -Sum).Sum
$totalSizeKB = [math]::Round($totalSize / 1KB, 2)
Write-Host "     Total size: ${totalSizeKB} KB" -ForegroundColor Gray

# Final result
Write-Host ""
Write-Host "=======================================" -ForegroundColor Cyan
if ($allGood) {
    Write-Host "   [SUCCESS] Frontend is ready!" -ForegroundColor Green
    Write-Host ""
    Write-Host "   You can now:" -ForegroundColor Yellow
    Write-Host "   1. Upload public/build folder" -ForegroundColor White
    Write-Host "   2. Or upload frontend_build_production.zip" -ForegroundColor White
} else {
    Write-Host "   [ERROR] Issues found - Rebuild needed" -ForegroundColor Red
    Write-Host "   Run: npm run build" -ForegroundColor Yellow
}
Write-Host "=======================================" -ForegroundColor Cyan
Write-Host ""











