<?php
/**
 * Solvesta Management System
 * نظام إدارة الشركات - نقطة دخول الدومين الفرعي
 */

// تحديد مسار Laravel
$laravel_path = __DIR__ . '/solvesta';

// التأكد من وجود Laravel
if (!file_exists($laravel_path . '/public/index.php')) {
    die('Laravel application not found. Please check the installation.');
}

// تحميل Laravel
require_once $laravel_path . '/public/index.php';
?>
