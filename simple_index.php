<?php
// Solvesta Management System - الحل البسيط

// تحديد مسار Laravel
$laravel_path = __DIR__ . '/solvesta';

// التحقق من وجود Laravel
if (!file_exists($laravel_path)) {
    die('<h1>خطأ: مجلد solvesta غير موجود</h1>');
}

if (!file_exists($laravel_path . '/public/index.php')) {
    die('<h1>خطأ: ملف public/index.php غير موجود</h1>');
}

if (!file_exists($laravel_path . '/vendor/autoload.php')) {
    die('<h1>خطأ: ملف vendor/autoload.php غير موجود</h1>');
}

// تحميل Laravel
require_once $laravel_path . '/public/index.php';
?>












