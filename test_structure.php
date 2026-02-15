<?php
// اختبار هيكل نظام Solvesta

echo "<h2>🔍 تشخيص نظام Solvesta</h2>";

// التحقق من وجود مجلد solvesta
if (file_exists(__DIR__ . '/solvesta')) {
    echo "✅ مجلد solvesta موجود<br>";
} else {
    echo "❌ مجلد solvesta غير موجود<br>";
}

// التحقق من وجود مجلد public
if (file_exists(__DIR__ . '/solvesta/public')) {
    echo "✅ مجلد public موجود<br>";
} else {
    echo "❌ مجلد public غير موجود<br>";
}

// التحقق من وجود index.php في public
if (file_exists(__DIR__ . '/solvesta/public/index.php')) {
    echo "✅ index.php في public موجود<br>";
} else {
    echo "❌ index.php في public غير موجود<br>";
}

// التحقق من وجود vendor
if (file_exists(__DIR__ . '/solvesta/vendor/autoload.php')) {
    echo "✅ vendor/autoload.php موجود<br>";
} else {
    echo "❌ vendor/autoload.php غير موجود<br>";
}

// التحقق من وجود .env
if (file_exists(__DIR__ . '/solvesta/.env')) {
    echo "✅ ملف .env موجود<br>";
} else {
    echo "❌ ملف .env غير موجود<br>";
}

// التحقق من وجود build
if (file_exists(__DIR__ . '/solvesta/public/build')) {
    echo "✅ مجلد build موجود<br>";
} else {
    echo "❌ مجلد build غير موجود<br>";
}

// عرض محتويات المجلد
echo "<h3>📁 محتويات مجلد solvesta:</h3>";
$files = scandir(__DIR__ . '/solvesta');
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        echo "- $file<br>";
    }
}

echo "<h3>📁 محتويات مجلد public:</h3>";
if (file_exists(__DIR__ . '/solvesta/public')) {
    $public_files = scandir(__DIR__ . '/solvesta/public');
    foreach ($public_files as $file) {
        if ($file != '.' && $file != '..') {
            echo "- $file<br>";
        }
    }
}

// اختبار Laravel
echo "<h3>🧪 اختبار Laravel:</h3>";
try {
    require_once __DIR__ . '/solvesta/vendor/autoload.php';
    echo "✅ Laravel autoload تم تحميله بنجاح<br>";
    
    $app = require_once __DIR__ . '/solvesta/bootstrap/app.php';
    echo "✅ Laravel app تم تحميله بنجاح<br>";
    
    echo "✅ Laravel يعمل بشكل صحيح<br>";
} catch (Exception $e) {
    echo "❌ خطأ في Laravel: " . $e->getMessage() . "<br>";
}
?>












