# Script للتحقق من سلامة الـ Build

Write-Host "═══════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host "   فحص جاهزية الفرونت إند للرفع" -ForegroundColor Yellow
Write-Host "═══════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host ""

$allGood = $true

# 1. التحقق من وجود مجلد build
Write-Host "🔍 فحص مجلد build..." -ForegroundColor Yellow
if (Test-Path "public\build") {
    Write-Host "✅ مجلد build موجود" -ForegroundColor Green
} else {
    Write-Host "❌ مجلد build غير موجود!" -ForegroundColor Red
    $allGood = $false
}

# 2. التحقق من manifest.json
Write-Host ""
Write-Host "🔍 فحص manifest.json..." -ForegroundColor Yellow
if (Test-Path "public\build\manifest.json") {
    Write-Host "✅ manifest.json موجود" -ForegroundColor Green
    $manifestSize = (Get-Item "public\build\manifest.json").Length
    Write-Host "   الحجم: $manifestSize bytes" -ForegroundColor Gray
} else {
    Write-Host "❌ manifest.json غير موجود!" -ForegroundColor Red
    $allGood = $false
}

# 3. التحقق من مجلد assets
Write-Host ""
Write-Host "🔍 فحص مجلد assets..." -ForegroundColor Yellow
if (Test-Path "public\build\assets") {
    Write-Host "✅ مجلد assets موجود" -ForegroundColor Green
    
    # عدد الملفات
    $filesCount = (Get-ChildItem "public\build\assets").Count
    Write-Host "   عدد الملفات: $filesCount" -ForegroundColor Gray
} else {
    Write-Host "❌ مجلد assets غير موجود!" -ForegroundColor Red
    $allGood = $false
}

# 4. التحقق من ملف CSS
Write-Host ""
Write-Host "🔍 فحص ملف CSS..." -ForegroundColor Yellow
$cssFiles = Get-ChildItem "public\build\assets\*.css" -ErrorAction SilentlyContinue
if ($cssFiles) {
    Write-Host "✅ ملف CSS موجود" -ForegroundColor Green
    foreach ($file in $cssFiles) {
        $size = [math]::Round($file.Length / 1KB, 2)
        Write-Host "   $($file.Name) - ${size} KB" -ForegroundColor Gray
    }
} else {
    Write-Host "❌ ملف CSS غير موجود!" -ForegroundColor Red
    $allGood = $false
}

# 5. التحقق من ملف JS
Write-Host ""
Write-Host "🔍 فحص ملف JavaScript..." -ForegroundColor Yellow
$jsFiles = Get-ChildItem "public\build\assets\*.js" -ErrorAction SilentlyContinue
if ($jsFiles) {
    Write-Host "✅ ملف JavaScript موجود" -ForegroundColor Green
    foreach ($file in $jsFiles) {
        $size = [math]::Round($file.Length / 1KB, 2)
        Write-Host "   $($file.Name) - ${size} KB" -ForegroundColor Gray
    }
} else {
    Write-Host "❌ ملف JavaScript غير موجود!" -ForegroundColor Red
    $allGood = $false
}

# 6. التحقق من الملف المضغوط
Write-Host ""
Write-Host "🔍 فحص الملف المضغوط..." -ForegroundColor Yellow
if (Test-Path "frontend_build_production.zip") {
    Write-Host "✅ ملف ZIP موجود وجاهز للرفع" -ForegroundColor Green
    $zipSize = [math]::Round((Get-Item "frontend_build_production.zip").Length / 1KB, 2)
    Write-Host "   الحجم: ${zipSize} KB" -ForegroundColor Gray
} else {
    Write-Host "⚠️  ملف ZIP غير موجود (اختياري)" -ForegroundColor Yellow
}

# 7. حساب الحجم الإجمالي
Write-Host ""
Write-Host "📊 الإحصائيات:" -ForegroundColor Yellow
$totalSize = (Get-ChildItem "public\build" -Recurse | Measure-Object -Property Length -Sum).Sum
$totalSizeKB = [math]::Round($totalSize / 1KB, 2)
Write-Host "   الحجم الإجمالي: ${totalSizeKB} KB" -ForegroundColor Gray

# النتيجة النهائية
Write-Host ""
Write-Host "═══════════════════════════════════════════════════" -ForegroundColor Cyan
if ($allGood) {
    Write-Host "   ✅ الفرونت إند جاهز للرفع!" -ForegroundColor Green
    Write-Host ""
    Write-Host "   📦 يمكنك الآن:" -ForegroundColor Yellow
    Write-Host "   1. رفع مجلد public/build كاملاً" -ForegroundColor White
    Write-Host "   2. أو رفع ملف frontend_build_production.zip" -ForegroundColor White
    Write-Host ""
    Write-Host "   📄 راجع ملف: كيفية_الرفع.txt" -ForegroundColor Cyan
} else {
    Write-Host "   ❌ يوجد مشاكل - أعد عمل Build" -ForegroundColor Red
    Write-Host "   قم بتشغيل: npm run build" -ForegroundColor Yellow
}
Write-Host "═══════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host ""











