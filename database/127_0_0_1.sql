-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- مضيف: 127.0.0.1:3306
-- وقت الجيل: 27 أبريل 2026 الساعة 14:59
-- إصدار الخادم: 11.8.6-MariaDB-log
-- نسخة PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- قاعدة بيانات: `u985487065_solvesta`
--
CREATE DATABASE IF NOT EXISTS `u985487065_solvesta` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `u985487065_solvesta`;

-- --------------------------------------------------------

--
-- بنية الجدول `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `code`, `type`, `parent_id`, `description`, `balance`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'الأصول المتداولة', '1000', 'asset', NULL, 'الأصول التي يمكن تحويلها إلى نقد خلال سنة واحدة', 0.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(2, 'النقدية', '1100', 'asset', 1, 'النقدية المتاحة في الخزينة', 50000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(3, 'البنوك', '1200', 'asset', 1, 'الودائع البنكية والحسابات الجارية', 150000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(4, 'العملاء', '1300', 'asset', 1, 'المبالغ المستحقة على العملاء', 75000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(5, 'المخزون', '1400', 'asset', 1, 'البضائع والمخزون المتاح للبيع', 100000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(6, 'الأصول الثابتة', '2000', 'asset', NULL, 'الأصول طويلة الأجل', 0.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(7, 'المعدات', '2100', 'asset', 6, 'المعدات والآلات', 200000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(8, 'المباني', '2200', 'asset', 6, 'المباني والمكاتب', 500000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(9, 'المركبات', '2300', 'asset', 6, 'المركبات والسيارات', 80000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(10, 'الخصوم المتداولة', '3000', 'liability', NULL, 'الخصوم المستحقة خلال سنة واحدة', 0.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(11, 'الموردون', '3100', 'liability', 10, 'المبالغ المستحقة للموردين', 45000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(12, 'الرواتب المستحقة', '3200', 'liability', 10, 'الرواتب والاستحقاقات المستحقة للموظفين', 25000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(13, 'الضرائب المستحقة', '3300', 'liability', 10, 'الضرائب والرسوم المستحقة', 15000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(14, 'الخصوم طويلة الأجل', '4000', 'liability', NULL, 'الخصوم المستحقة لأكثر من سنة', 0.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(15, 'القروض البنكية', '4100', 'liability', 14, 'القروض البنكية طويلة الأجل', 300000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(16, 'حقوق الملكية', '5000', 'equity', NULL, 'حقوق المالكين في الشركة', 0.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(17, 'رأس المال', '5100', 'equity', 16, 'رأس المال المدفوع', 500000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(18, 'الأرباح المحتجزة', '5200', 'equity', 16, 'الأرباح المحتجزة من السنوات السابقة', 85000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(19, 'الإيرادات', '6000', 'revenue', NULL, 'إيرادات الشركة', 0.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(20, 'إيرادات المبيعات', '6100', 'revenue', 19, 'إيرادات مبيعات المنتجات والخدمات', 350000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(21, 'إيرادات الخدمات', '6200', 'revenue', 19, 'إيرادات تقديم الخدمات', 120000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(22, 'إيرادات أخرى', '6300', 'revenue', 19, 'إيرادات أخرى متنوعة', 15000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(23, 'المصروفات', '7000', 'expense', NULL, 'مصروفات التشغيل', 0.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(24, 'مصروفات الرواتب', '7100', 'expense', 23, 'رواتب وأجور الموظفين', 180000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(25, 'مصروفات الإيجار', '7200', 'expense', 23, 'إيجار المكاتب والمباني', 24000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(26, 'مصروفات الكهرباء والمياه', '7300', 'expense', 23, 'فواتير الكهرباء والمياه', 12000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(27, 'مصروفات التسويق', '7400', 'expense', 23, 'مصروفات التسويق والإعلان', 15000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(28, 'مصروفات النقل', '7500', 'expense', 23, 'مصروفات النقل والشحن', 8000.00, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:23'),
(29, 'مصروفات أخرى', '7600', 'expense', 23, 'مصروفات أخرى متنوعة', 6000.00, 1, '2025-10-10 14:38:23', '2025-10-10 14:38:23');

-- --------------------------------------------------------

--
-- بنية الجدول `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `model_type` varchar(50) NOT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `old_values` text DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `assets`
--

CREATE TABLE `assets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `asset_tag` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `purchase_price` decimal(15,2) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `location` varchar(255) DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `asset_maintenance`
--

CREATE TABLE `asset_maintenance` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `asset_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `scheduled_date` date NOT NULL,
  `completed_date` date DEFAULT NULL,
  `cost` decimal(15,2) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'scheduled',
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `check_in` datetime DEFAULT NULL,
  `check_out` datetime DEFAULT NULL,
  `break_start` datetime DEFAULT NULL,
  `break_end` datetime DEFAULT NULL,
  `break_duration_minutes` int(11) DEFAULT NULL,
  `total_hours` decimal(8,2) DEFAULT NULL,
  `overtime_hours` int(11) NOT NULL DEFAULT 0,
  `status` enum('present','absent','late','half_day','leave') NOT NULL DEFAULT 'present',
  `current_status` enum('working','on_break','completed') DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `check_in_location` varchar(255) DEFAULT NULL,
  `check_out_location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `attendances`
--

INSERT INTO `attendances` (`id`, `employee_id`, `date`, `check_in`, `check_out`, `break_start`, `break_end`, `break_duration_minutes`, `total_hours`, `overtime_hours`, `status`, `current_status`, `notes`, `check_in_location`, `check_out_location`, `created_at`, `updated_at`) VALUES
(45, 1, '2025-11-04', '2025-11-04 17:53:32', '2025-11-04 23:30:39', '2025-11-04 17:53:41', NULL, NULL, -5.62, 0, 'present', 'completed', NULL, NULL, NULL, '2025-11-04 17:53:32', '2025-11-04 23:30:39'),
(46, 1, '2025-11-05', '2025-11-05 06:57:23', NULL, '2025-11-05 07:19:50', NULL, NULL, NULL, 0, 'present', 'on_break', NULL, NULL, NULL, '2025-11-05 06:57:23', '2025-11-05 07:19:50'),
(47, 1, '2025-11-09', '2025-11-09 14:32:44', NULL, NULL, NULL, NULL, NULL, 0, 'late', 'working', NULL, NULL, NULL, '2025-11-09 14:32:44', '2025-11-09 14:32:44'),
(48, 1, '2025-11-22', '2025-11-22 01:13:37', NULL, NULL, NULL, NULL, NULL, 0, 'present', 'working', NULL, NULL, NULL, '2025-11-22 01:13:37', '2025-11-22 01:13:37'),
(49, 1, '2025-11-25', '2025-11-25 11:34:52', NULL, NULL, NULL, NULL, NULL, 0, 'late', 'working', NULL, NULL, NULL, '2025-11-25 11:34:52', '2025-11-25 11:34:52'),
(50, 1, '2025-12-12', '2025-12-12 21:17:49', NULL, NULL, NULL, NULL, NULL, 0, 'late', 'working', NULL, NULL, NULL, '2025-12-12 21:17:49', '2025-12-12 21:17:49'),
(51, 1, '2025-12-20', '2025-12-20 19:14:52', NULL, '2025-12-20 23:13:18', NULL, NULL, NULL, 0, 'late', 'on_break', NULL, NULL, NULL, '2025-12-20 19:14:52', '2025-12-20 23:13:18'),
(52, 1, '2025-12-21', '2025-12-21 10:55:43', NULL, NULL, NULL, NULL, NULL, 0, 'late', 'working', NULL, NULL, NULL, '2025-12-21 10:55:43', '2025-12-21 10:55:43'),
(53, 1, '2025-12-26', '2025-12-26 19:30:05', NULL, NULL, NULL, NULL, NULL, 0, 'late', 'working', NULL, NULL, NULL, '2025-12-26 19:30:05', '2025-12-26 19:30:05');

-- --------------------------------------------------------

--
-- بنية الجدول `bank_reconciliations`
--

CREATE TABLE `bank_reconciliations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank_account_id` bigint(20) UNSIGNED NOT NULL,
  `reconciliation_date` date NOT NULL,
  `bank_statement_balance` decimal(10,2) NOT NULL,
  `book_balance` decimal(10,2) NOT NULL,
  `outstanding_deposits` decimal(10,2) NOT NULL DEFAULT 0.00,
  `outstanding_checks` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bank_charges` decimal(10,2) NOT NULL DEFAULT 0.00,
  `interest_earned` decimal(10,2) NOT NULL DEFAULT 0.00,
  `adjusted_balance` decimal(10,2) NOT NULL,
  `difference` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(50) NOT NULL DEFAULT 'in_progress',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `budgets`
--

CREATE TABLE `budgets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `budget_name` varchar(255) NOT NULL,
  `budget_type` varchar(50) NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_budget` decimal(15,2) NOT NULL,
  `allocated_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `spent_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(12,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `budget_items`
--

CREATE TABLE `budget_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `budget_id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `allocated_amount` decimal(12,2) NOT NULL,
  `spent_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `bugs`
--

CREATE TABLE `bugs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bug_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `reported_by` bigint(20) UNSIGNED NOT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `severity` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `status` enum('open','in_progress','testing','resolved','closed','duplicate') NOT NULL DEFAULT 'open',
  `environment` varchar(255) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `operating_system` varchar(255) DEFAULT NULL,
  `steps_to_reproduce` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`steps_to_reproduce`)),
  `expected_result` text DEFAULT NULL,
  `actual_result` text DEFAULT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `resolution_date` timestamp NULL DEFAULT NULL,
  `resolution_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-01194b1fbc45423677867eccdf4d7f255ea3a5c9', 'i:1;', 1763774869),
('laravel-cache-01194b1fbc45423677867eccdf4d7f255ea3a5c9:timer', 'i:1763774869;', 1763774869),
('laravel-cache-06c2b96a427b9124987e8624dbd4cdbd53ea984c', 'i:1;', 1762699608),
('laravel-cache-06c2b96a427b9124987e8624dbd4cdbd53ea984c:timer', 'i:1762699608;', 1762699608),
('laravel-cache-074be5db6e58b968c518b42649f344a4b61d59d1', 'i:1;', 1777119472),
('laravel-cache-074be5db6e58b968c518b42649f344a4b61d59d1:timer', 'i:1777119472;', 1777119472),
('laravel-cache-122a7278bd876e76ed194465e7822b96a8ea9df1', 'i:1;', 1762460208),
('laravel-cache-122a7278bd876e76ed194465e7822b96a8ea9df1:timer', 'i:1762460208;', 1762460208),
('laravel-cache-31228f87210c4f027ef9d434639e1b229c45e0fd', 'i:1;', 1766357610),
('laravel-cache-31228f87210c4f027ef9d434639e1b229c45e0fd:timer', 'i:1766357610;', 1766357610),
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1777119185),
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1777119185;', 1777119185),
('laravel-cache-37a338c71aa3c5aa796c19a7d016a09147788c67', 'i:1;', 1768400689),
('laravel-cache-37a338c71aa3c5aa796c19a7d016a09147788c67:timer', 'i:1768400689;', 1768400689),
('laravel-cache-37ee669a9cbd89f628cdd33f176c809523f10a9a', 'i:1;', 1765438573),
('laravel-cache-37ee669a9cbd89f628cdd33f176c809523f10a9a:timer', 'i:1765438573;', 1765438573),
('laravel-cache-43df4f011d25ef3acc1ef9046524c43a752f1fa8', 'i:1;', 1767290417),
('laravel-cache-43df4f011d25ef3acc1ef9046524c43a752f1fa8:timer', 'i:1767290417;', 1767290417),
('laravel-cache-462e90e3250edf45828f5082856c901f1c029c47', 'i:1;', 1764664288),
('laravel-cache-462e90e3250edf45828f5082856c901f1c029c47:timer', 'i:1764664288;', 1764664288),
('laravel-cache-56a9405090b31be0bd6fb987a2900cbbb2e3299a', 'i:1;', 1766695577),
('laravel-cache-56a9405090b31be0bd6fb987a2900cbbb2e3299a:timer', 'i:1766695577;', 1766695577),
('laravel-cache-5b21e44b329385aca3ab1c24b67eae095ca4c356', 'i:1;', 1766947626),
('laravel-cache-5b21e44b329385aca3ab1c24b67eae095ca4c356:timer', 'i:1766947626;', 1766947626),
('laravel-cache-77de68daecd823babbb58edb1c8e14d7106e83bb', 'i:1;', 1766798982),
('laravel-cache-77de68daecd823babbb58edb1c8e14d7106e83bb:timer', 'i:1766798982;', 1766798982),
('laravel-cache-8e86cd66a3cdeaf6f65fcc7d9f68cd79b6bda569', 'i:1;', 1762331598),
('laravel-cache-8e86cd66a3cdeaf6f65fcc7d9f68cd79b6bda569:timer', 'i:1762331598;', 1762331598),
('laravel-cache-9321b1dfb8b4c745513a12a1483a0834553023e6', 'i:1;', 1762474266),
('laravel-cache-9321b1dfb8b4c745513a12a1483a0834553023e6:timer', 'i:1762474266;', 1762474266),
('laravel-cache-9d65006aa8d8fe13daef636c7a68e86579e11b65', 'i:1;', 1775672282),
('laravel-cache-9d65006aa8d8fe13daef636c7a68e86579e11b65:timer', 'i:1775672282;', 1775672282),
('laravel-cache-a4755a25405a28691696d2aff699fd0b92152a28', 'i:2;', 1765574556),
('laravel-cache-a4755a25405a28691696d2aff699fd0b92152a28:timer', 'i:1765574556;', 1765574556),
('laravel-cache-ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4', 'i:1;', 1762335879),
('laravel-cache-ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4:timer', 'i:1762335879;', 1762335879),
('laravel-cache-c623affd3df7b0fab881a4d221f7b9c691093429', 'i:1;', 1771147564),
('laravel-cache-c623affd3df7b0fab881a4d221f7b9c691093429:timer', 'i:1771147564;', 1771147564),
('laravel-cache-c6402b51fa057cc34a0ff5df57c7f2002b468c2d', 'i:1;', 1764071288),
('laravel-cache-c6402b51fa057cc34a0ff5df57c7f2002b468c2d:timer', 'i:1764071288;', 1764071288),
('laravel-cache-d1b07b2212d0fd280d30da34bfaa201dceb8dbfb', 'i:1;', 1772318021),
('laravel-cache-d1b07b2212d0fd280d30da34bfaa201dceb8dbfb:timer', 'i:1772318021;', 1772318021),
('laravel-cache-failed_auth:0ee991f11e3bf3881b5c419b562b3000', 'i:3;', 1766314144),
('laravel-cache-failed_auth:0ee991f11e3bf3881b5c419b562b3000:timer', 'i:1766314144;', 1766314144),
('laravel-cache-failed_auth:4e80b6e4cedd26255a1d273e4f229427', 'i:1;', 1765573717),
('laravel-cache-failed_auth:4e80b6e4cedd26255a1d273e4f229427:timer', 'i:1765573717;', 1765573717),
('laravel-cache-login:blocked:0ee991f11e3bf3881b5c419b562b3000:timer', 'i:1766314144;', 1766314144),
('laravel-cache-login:blocked:4e80b6e4cedd26255a1d273e4f229427', 'i:1;', 1765573717),
('laravel-cache-login:blocked:4e80b6e4cedd26255a1d273e4f229427:timer', 'i:1765573717;', 1765573717),
('laravel-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:92:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:10:\"view-users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:12:\"create-users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:10:\"edit-users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:12:\"delete-users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:14:\"view-employees\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;i:4;i:8;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:16:\"create-employees\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:14:\"edit-employees\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:16:\"delete-employees\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:13:\"view-projects\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:15:\"create-projects\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:8;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:13:\"edit-projects\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:8;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:15:\"delete-projects\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:10:\"view-tasks\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:12:\"create-tasks\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:8;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:10:\"edit-tasks\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:7:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:8;i:5;i:10;i:6;i:11;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:12:\"delete-tasks\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:12:\"view-clients\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:8:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:7;i:5;i:8;i:6;i:9;i:7;i:11;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:14:\"create-clients\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:7;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:12:\"edit-clients\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:7;i:4;i:8;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:14:\"delete-clients\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:10:\"view-sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:7;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:12:\"create-sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:7;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:10:\"edit-sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:7;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:12:\"delete-sales\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:12:\"view-finance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:6;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:14:\"create-finance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:6;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:12:\"edit-finance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:6;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:14:\"delete-finance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:6;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:12:\"view-reports\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;i:4;i:6;i:5;i:8;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:16:\"generate-reports\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;i:4;i:6;i:5;i:8;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:14:\"view-dashboard\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:11:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:4;i:4;i:5;i:5;i:6;i:6;i:7;i:7;i:8;i:8;i:9;i:9;i:10;i:10;i:11;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:13:\"view-settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:13:\"edit-settings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:17:\"view-all-projects\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:8;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:17:\"view-own-projects\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:4;i:2;i:10;i:3;i:11;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:14:\"view-all-tasks\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:8;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:14:\"view-own-tasks\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:4;i:2;i:10;i:3;i:11;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:16:\"approve-expenses\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:6;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:15:\"view-attendance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:17:\"create-attendance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:15:\"edit-attendance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:17:\"delete-attendance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:11:\"view-leaves\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:13:\"create-leaves\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:11:\"edit-leaves\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:13:\"delete-leaves\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:46;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:14:\"approve-leaves\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:47;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:13:\"view-salaries\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:5;i:3;i:6;}}i:48;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:15:\"create-salaries\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:49;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:13:\"edit-salaries\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:50;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:15:\"delete-salaries\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:51;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:13:\"view-invoices\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:6;i:3;i:7;}}i:52;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:15:\"create-invoices\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:6;i:3;i:7;}}i:53;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:13:\"edit-invoices\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:6;}}i:54;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:15:\"delete-invoices\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:55;a:4:{s:1:\"a\";i:56;s:1:\"b\";s:14:\"view-contracts\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:6;i:3;i:7;}}i:56;a:4:{s:1:\"a\";i:57;s:1:\"b\";s:16:\"create-contracts\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:7;}}i:57;a:4:{s:1:\"a\";i:58;s:1:\"b\";s:14:\"edit-contracts\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:58;a:4:{s:1:\"a\";i:59;s:1:\"b\";s:16:\"delete-contracts\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:59;a:4:{s:1:\"a\";i:60;s:1:\"b\";s:9:\"view-bugs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:8;i:4;i:9;i:5;i:10;}}i:60;a:4:{s:1:\"a\";i:61;s:1:\"b\";s:11:\"create-bugs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:6:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:8;i:4;i:9;i:5;i:10;}}i:61;a:4:{s:1:\"a\";i:62;s:1:\"b\";s:9:\"edit-bugs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:8;i:3;i:10;}}i:62;a:4:{s:1:\"a\";i:63;s:1:\"b\";s:11:\"delete-bugs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:63;a:4:{s:1:\"a\";i:64;s:1:\"b\";s:7:\"view-qa\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:8;i:3;i:10;}}i:64;a:4:{s:1:\"a\";i:65;s:1:\"b\";s:9:\"create-qa\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:8;i:3;i:10;}}i:65;a:4:{s:1:\"a\";i:66;s:1:\"b\";s:7:\"edit-qa\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:8;i:3;i:10;}}i:66;a:4:{s:1:\"a\";i:67;s:1:\"b\";s:9:\"delete-qa\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:67;a:4:{s:1:\"a\";i:68;s:1:\"b\";s:12:\"view-tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:9;}}i:68;a:4:{s:1:\"a\";i:69;s:1:\"b\";s:14:\"create-tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:9;}}i:69;a:4:{s:1:\"a\";i:70;s:1:\"b\";s:12:\"edit-tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:9;}}i:70;a:4:{s:1:\"a\";i:71;s:1:\"b\";s:14:\"delete-tickets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:71;a:4:{s:1:\"a\";i:72;s:1:\"b\";s:16:\"view-departments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:72;a:4:{s:1:\"a\";i:73;s:1:\"b\";s:18:\"create-departments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:73;a:4:{s:1:\"a\";i:74;s:1:\"b\";s:16:\"edit-departments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:74;a:4:{s:1:\"a\";i:75;s:1:\"b\";s:18:\"delete-departments\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:75;a:4:{s:1:\"a\";i:76;s:1:\"b\";s:14:\"export-reports\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:6;}}i:76;a:4:{s:1:\"a\";i:77;s:1:\"b\";s:14:\"view-analytics\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:6;}}i:77;a:4:{s:1:\"a\";i:78;s:1:\"b\";s:12:\"manage-roles\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:78;a:4:{s:1:\"a\";i:79;s:1:\"b\";s:13:\"view-training\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:5;i:4;i:8;}}i:79;a:4:{s:1:\"a\";i:80;s:1:\"b\";s:15:\"create-training\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:5;i:3;i:8;}}i:80;a:4:{s:1:\"a\";i:81;s:1:\"b\";s:13:\"edit-training\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:5;i:3;i:8;}}i:81;a:4:{s:1:\"a\";i:82;s:1:\"b\";s:15:\"delete-training\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:82;a:4:{s:1:\"a\";i:83;s:1:\"b\";s:13:\"view-meetings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:5:{i:0;i:1;i:1;i:2;i:2;i:4;i:3;i:5;i:4;i:8;}}i:83;a:4:{s:1:\"a\";i:84;s:1:\"b\";s:15:\"create-meetings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:5;i:3;i:8;}}i:84;a:4:{s:1:\"a\";i:85;s:1:\"b\";s:13:\"edit-meetings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:5;i:3;i:8;}}i:85;a:4:{s:1:\"a\";i:86;s:1:\"b\";s:15:\"delete-meetings\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:86;a:4:{s:1:\"a\";i:87;s:1:\"b\";s:11:\"view-assets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:5;}}i:87;a:4:{s:1:\"a\";i:88;s:1:\"b\";s:13:\"create-assets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:88;a:4:{s:1:\"a\";i:89;s:1:\"b\";s:11:\"edit-assets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:89;a:4:{s:1:\"a\";i:90;s:1:\"b\";s:13:\"delete-assets\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:90;a:4:{s:1:\"a\";i:91;s:1:\"b\";s:24:\"manage-asset-maintenance\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:91;a:4:{s:1:\"a\";i:92;s:1:\"b\";s:16:\"approve-salaries\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}}s:5:\"roles\";a:11:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"super_admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:2:\"hr\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:7:\"manager\";s:1:\"c\";s:3:\"web\";}i:4;a:3:{s:1:\"a\";i:8;s:1:\"b\";s:15:\"project_manager\";s:1:\"c\";s:3:\"web\";}i:5;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:8:\"employee\";s:1:\"c\";s:3:\"web\";}i:6;a:3:{s:1:\"a\";i:10;s:1:\"b\";s:9:\"developer\";s:1:\"c\";s:3:\"web\";}i:7;a:3:{s:1:\"a\";i:11;s:1:\"b\";s:8:\"designer\";s:1:\"c\";s:3:\"web\";}i:8;a:3:{s:1:\"a\";i:7;s:1:\"b\";s:9:\"sales_rep\";s:1:\"c\";s:3:\"web\";}i:9;a:3:{s:1:\"a\";i:9;s:1:\"b\";s:7:\"support\";s:1:\"c\";s:3:\"web\";}i:10;a:3:{s:1:\"a\";i:6;s:1:\"b\";s:10:\"accountant\";s:1:\"c\";s:3:\"web\";}}}', 1777204986),
('laravel-cache-system_setting_bank_account_number', 's:16:\"8450383000000162\";', 1767208794),
('laravel-cache-system_setting_bank_iban', 's:29:\"EG120002084508450383000000162\";', 1767208794),
('laravel-cache-system_setting_bank_name', 's:13:\"بنك مصر\";', 1767208794),
('laravel-cache-system_setting_bank_swift', 's:11:\"BMISEGCXXXX\";', 1767208794),
('laravel-cache-system_setting_company_address', 's:179:\"القاهرة المعادي شارع 9\r\nالقاهرة مدينة نصر عباس العقاد\r\nالمنصورة حي الجامعة \r\nدمياط المنطقة المركزية\";', 1767208794),
('laravel-cache-system_setting_company_email', 's:17:\"info@solvesta.com\";', 1767208794),
('laravel-cache-system_setting_company_name', 's:16:\"Solvesta Company\";', 1777296400),
('laravel-cache-system_setting_company_phone', 's:11:\"01044610510\";', 1767208794),
('laravel-cache-system_setting_default_payment_period', 's:2:\"30\";', 1767208794),
('laravel-cache-system_setting_favicon', 's:37:\"favicons/1762280500_690a44345b7af.png\";', 1777296400),
('laravel-cache-system_setting_invoice_financial_notes', 'N;', 1767211910),
('laravel-cache-system_setting_logo', 's:34:\"logos/1762280922_690a45dac8977.png\";', 1777296400),
('laravel-cache-system_setting_logo_size', 's:6:\"medium\";', 1777122186),
('laravel-cache-system_setting_payment_methods', 's:31:\"تحويل بنكي او كاش\";', 1767208794),
('laravel-cache-system_setting_sidebar_color', 's:7:\"#1f2937\";', 1777122186),
('laravel-cache-system_setting_system_description', 's:96:\"نظام شامل لإدارة العمليات التجارية والموارد البشرية\";', 1777122186),
('laravel-cache-system_setting_system_name', 's:16:\"Solvesta Company\";', 1777296400),
('laravel-cache-system_setting_theme_color', 's:7:\"#2563eb\";', 1777296400),
('laravel-cache-system_setting_whatsapp_auto_open', 's:1:\"1\";', 1775675057),
('laravel-cache-system_setting_whatsapp_default_number', 's:12:\"201044610510\";', 1775675057),
('laravel-cache-system_setting_whatsapp_enabled', 's:1:\"1\";', 1775675057),
('laravel-cache-verification:1:95cdfe3afaaaf1e406ceb35fee592c52', 'i:1;', 1762459382),
('laravel-cache-verification:1:95cdfe3afaaaf1e406ceb35fee592c52:timer', 'i:1762459382;', 1762459382);

-- --------------------------------------------------------

--
-- بنية الجدول `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `industry` varchar(255) DEFAULT NULL,
  `client_type` enum('individual','small_business','enterprise') NOT NULL DEFAULT 'individual',
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `clients`
--

INSERT INTO `clients` (`id`, `name`, `email`, `phone`, `company_name`, `industry`, `client_type`, `status`, `address`, `city`, `country`, `website`, `notes`, `assigned_to`, `created_at`, `updated_at`) VALUES
(2, 'رغد بنت فيصل المطيري', 'info@qaddaha.com', '966508680206', NULL, NULL, 'individual', 'active', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 17:55:51', '2025-11-04 17:55:51'),
(3, 'solvesta', 'info@s0lvesta.com', '01044610510', NULL, NULL, 'individual', 'active', 'دمياط الجديدة', NULL, NULL, NULL, NULL, NULL, '2025-11-04 20:45:28', '2025-11-04 20:45:28'),
(4, 'Tasneem tech bridge', 'info@tech-bridge.com', '01018435328', NULL, NULL, 'individual', 'active', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 23:05:31', '2025-11-04 23:05:31'),
(5, 'مستر طارق الداجن', 'info@eldagen.com', '+20 15 50092201', NULL, NULL, 'individual', 'active', 'new damitta\r\n100 streat', NULL, NULL, NULL, NULL, NULL, '2026-02-13 14:38:28', '2026-02-13 14:38:28');

-- --------------------------------------------------------

--
-- بنية الجدول `contracts`
--

CREATE TABLE `contracts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contract_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `contract_type` enum('employment','service','nda','partnership','vendor') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `value` decimal(15,2) DEFAULT NULL,
  `status` enum('draft','active','expired','terminated','renewed') NOT NULL DEFAULT 'draft',
  `terms_conditions` text DEFAULT NULL,
  `parties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`parties`)),
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `renewal_notice_days` int(11) NOT NULL DEFAULT 30,
  `auto_renewal` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `contracts`
--

INSERT INTO `contracts` (`id`, `contract_number`, `title`, `description`, `client_id`, `project_id`, `contract_type`, `start_date`, `end_date`, `value`, `status`, `terms_conditions`, `parties`, `attachments`, `renewal_notice_days`, `auto_renewal`, `created_by`, `approved_by`, `created_at`, `updated_at`) VALUES
(1, 'CNT-2025-0001', 'إنشاء منصة تعليمية', 'منصتنا التعلیمیة ھي منصة متخصصة ومتكاملة تھدف إلى تأھیل الطلاب\r\nوالطالبات لاجتیاز اختبار القدرات العامة السعودي (القیاس) بدرجات عالیة، من\r\nخلال محتوى تفاعلي، مبني على أحدث الأسالیب التعلیمیة والتقنیة', 2, 7, 'service', '2025-09-01', '2025-12-31', 50000.00, 'active', NULL, NULL, NULL, 30, 0, 1, 1, '2025-11-04 18:06:46', '2025-11-04 18:06:46'),
(2, 'CNT-2025-0002', 'إنشاء أكاديمية برمجة تعليمية', 'إنشاء أكاديمية برمجة تعليمية', 4, 10, 'service', '2025-11-03', '2025-11-20', 20000.00, 'renewed', NULL, NULL, NULL, 30, 0, 1, 1, '2025-11-04 23:20:19', '2025-12-26 19:25:59'),
(3, 'CNT-2025-0003', 'إنشاء أكاديمية برمجة تعليمية', 'إنشاء أكاديمية برمجة تعليمية', 4, 10, 'service', '2025-12-26', '2026-11-20', 20000.00, 'active', NULL, NULL, NULL, 30, 0, 1, 1, '2025-12-26 19:25:59', '2025-12-26 19:25:59');

-- --------------------------------------------------------

--
-- بنية الجدول `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `manager_id` bigint(20) UNSIGNED DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `color` varchar(50) NOT NULL DEFAULT '#3B82F6',
  `icon` varchar(50) NOT NULL DEFAULT 'building',
  `budget` decimal(15,2) NOT NULL DEFAULT 0.00,
  `head_of_department` bigint(20) UNSIGNED DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `departments`
--

INSERT INTO `departments` (`id`, `name`, `name_en`, `code`, `description`, `manager_id`, `location`, `phone`, `email`, `color`, `icon`, `budget`, `head_of_department`, `parent_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'الإدارة العامة', NULL, 'ADM', 'الإدارة العامة والتنفيذية للشركة', NULL, NULL, NULL, NULL, '#3B82F6', 'building', 0.00, NULL, NULL, 1, '2025-10-10 14:38:09', '2025-10-10 14:38:09'),
(2, 'المبيعات', NULL, 'SAL', 'قسم المبيعات والتسويق', NULL, NULL, NULL, NULL, '#3B82F6', 'building', 0.00, NULL, NULL, 1, '2025-10-10 14:38:09', '2025-10-10 14:38:09'),
(3, 'التطوير', NULL, 'DEV', 'قسم تطوير البرمجيات', NULL, NULL, NULL, NULL, '#3B82F6', 'building', 0.00, NULL, NULL, 1, '2025-10-10 14:38:09', '2025-10-10 14:38:09'),
(4, 'الموارد البشرية', NULL, 'HR', 'قسم الموارد البشرية', NULL, NULL, NULL, NULL, '#3B82F6', 'building', 0.00, NULL, NULL, 1, '2025-10-10 14:38:09', '2025-10-10 14:38:09'),
(5, 'المحاسبة', NULL, 'ACC', 'قسم المحاسبة والشؤون المالية', NULL, NULL, NULL, NULL, '#3B82F6', 'building', 0.00, NULL, NULL, 1, '2025-10-10 14:38:09', '2025-10-10 14:38:09'),
(6, 'دعم العملاء', NULL, 'SUP', 'قسم دعم العملاء', NULL, NULL, NULL, NULL, '#3B82F6', 'building', 0.00, NULL, NULL, 1, '2025-10-10 14:38:09', '2025-10-10 14:38:09'),
(7, 'التصميم', NULL, 'DES', 'قسم التصميم الجرافيكي', NULL, NULL, NULL, NULL, '#3B82F6', 'building', 0.00, NULL, NULL, 1, '2025-10-10 14:38:09', '2025-10-10 14:38:09'),
(8, 'ضمان الجودة', NULL, 'QA', 'قسم ضمان الجودة واختبار المنتجات', NULL, NULL, NULL, NULL, '#3B82F6', 'building', 0.00, NULL, NULL, 1, '2025-10-10 14:38:09', '2025-10-10 14:38:09');

-- --------------------------------------------------------

--
-- بنية الجدول `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `national_id` varchar(50) DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `position` varchar(255) NOT NULL,
  `hire_date` date NOT NULL,
  `salary` decimal(12,2) NOT NULL DEFAULT 0.00,
  `employment_type` enum('full_time','part_time','contract','intern') NOT NULL DEFAULT 'full_time',
  `status` enum('active','inactive','terminated') NOT NULL DEFAULT 'active',
  `address` text DEFAULT NULL,
  `emergency_contact` varchar(255) DEFAULT NULL,
  `emergency_phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `daily_hours` decimal(5,2) NOT NULL DEFAULT 8.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `employee_id`, `first_name`, `last_name`, `email`, `phone`, `national_id`, `department_id`, `position`, `hire_date`, `salary`, `employment_type`, `status`, `address`, `emergency_contact`, `emergency_phone`, `created_at`, `updated_at`, `daily_hours`) VALUES
(1, 1, 'EMP001', 'Super', 'Admin', 'admin@solvesta.com', '966501234567', NULL, 1, 'System Administrator', '2023-10-10', 50000.00, 'full_time', 'active', NULL, NULL, NULL, '2025-10-10 14:38:09', '2025-10-10 14:38:09', 8.00),
(2, 3, '2', 'mohamed', 'hany', 'codermohamedhany@gmail.com', '1203679764', NULL, 3, 'مبرمج', '2025-10-11', 30000.00, 'part_time', 'active', NULL, NULL, NULL, '2025-10-11 12:15:36', '2025-11-03 22:34:20', 8.00),
(6, 5, 'EMP000002', 'محمود', 'ايمن', 'ma8819496@gmail.com', '01092967520', NULL, 7, 'مصمم', '2025-11-04', 0.00, 'part_time', 'active', 'طنطا', 'محمود ايمن', '01092967520', '2025-11-04 21:14:53', '2025-11-04 21:14:53', 8.00);

-- --------------------------------------------------------

--
-- بنية الجدول `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expense_number` varchar(255) NOT NULL,
  `expense_category` varchar(255) NOT NULL,
  `vendor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expense_date` date NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `payment_method` varchar(255) NOT NULL,
  `receipt_number` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `expenses`
--

INSERT INTO `expenses` (`id`, `expense_number`, `expense_category`, `vendor_id`, `expense_date`, `amount`, `description`, `notes`, `payment_method`, `receipt_number`, `attachment`, `status`, `approved_by`, `approved_at`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'EXP-1762297204', 'salaries', 2, '2025-11-30', 13000.00, 'دفع 13000 جنيه تصميم ل محمود ايمن', NULL, 'cash', NULL, NULL, 'pending', NULL, NULL, 1, '2025-11-04 23:00:04', '2025-11-04 23:00:04'),
(2, 'EXP-1766314410', 'salaries', 2, '2025-12-21', 15000.00, 'تكاليف المذكرة', NULL, 'bank_transfer', NULL, NULL, 'pending', NULL, NULL, 1, '2025-12-21 10:53:30', '2025-12-21 10:53:30');

-- --------------------------------------------------------

--
-- بنية الجدول `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` text NOT NULL,
  `exception` text NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `financial_invoices`
--

CREATE TABLE `financial_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `invoice_type` varchar(50) NOT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `balance_due` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'draft',
  `payment_status` varchar(50) NOT NULL DEFAULT 'unpaid',
  `currency` varchar(255) NOT NULL DEFAULT 'SAR',
  `notes` text DEFAULT NULL,
  `terms_conditions` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `financial_invoice_items`
--

CREATE TABLE `financial_invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `fixed_assets`
--

CREATE TABLE `fixed_assets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `asset_code` varchar(255) NOT NULL,
  `asset_name` varchar(255) NOT NULL,
  `asset_category` varchar(255) NOT NULL,
  `purchase_date` date NOT NULL,
  `purchase_cost` decimal(10,2) NOT NULL,
  `salvage_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `useful_life_years` bigint(20) NOT NULL,
  `depreciation_method` varchar(255) NOT NULL,
  `depreciation_rate` decimal(5,2) NOT NULL,
  `accumulated_depreciation` decimal(10,2) NOT NULL DEFAULT 0.00,
  `book_value` decimal(10,2) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `contract_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `issue_date` date DEFAULT NULL,
  `due_date` date NOT NULL,
  `paid_date` date DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `balance_amount` decimal(15,2) NOT NULL,
  `status` enum('draft','sent','viewed','paid','overdue','cancelled') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`items`)),
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `client_id`, `project_id`, `sale_id`, `contract_id`, `invoice_date`, `issue_date`, `due_date`, `paid_date`, `subtotal`, `amount`, `tax_rate`, `tax_amount`, `discount_amount`, `total_amount`, `paid_amount`, `balance_amount`, `status`, `notes`, `items`, `payment_method`, `payment_date`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'INV-202511-0001', 2, 7, NULL, 1, '2025-11-04', '2025-11-04', '2025-12-04', NULL, 50000.00, 50000.00, 0.00, 0.00, 0.00, 50000.00, 50000.00, 0.00, 'paid', 'فاتورة مقابل عقد: إنشاء منصة تعليمية - رقم العقد: CNT-2025-0001', '[{\"description\":\"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0645\\u0646\\u0635\\u0629 \\u062a\\u0639\\u0644\\u064a\\u0645\\u064a\\u0629\",\"quantity\":1,\"unit_price\":\"50000.00\"}]', NULL, '2025-12-11 07:21:48', 1, '2025-11-04 18:06:53', '2025-12-11 07:21:48'),
(2, 'INV-202511-0002', 4, 10, NULL, 2, '2025-11-04', '2025-11-04', '2025-12-04', NULL, 20000.00, 20000.00, 0.00, 0.00, 0.00, 20000.00, 20000.00, 0.00, 'paid', 'فاتورة مقابل عقد: إنشاء أكاديمية برمجة تعليمية - رقم العقد: CNT-2025-0002', '[{\"description\":\"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0623\\u0643\\u0627\\u062f\\u064a\\u0645\\u064a\\u0629 \\u0628\\u0631\\u0645\\u062c\\u0629 \\u062a\\u0639\\u0644\\u064a\\u0645\\u064a\\u0629\",\"quantity\":1,\"unit_price\":\"20000.00\"}]', NULL, '2025-11-04 23:29:19', 1, '2025-11-04 23:20:48', '2025-11-04 23:29:19'),
(3, 'INV-202512-0001', 4, 10, NULL, 2, '2025-12-21', '2025-12-21', '2026-01-20', NULL, 20000.00, 20000.00, 0.00, 0.00, 0.00, 20000.00, 0.00, 20000.00, 'draft', 'فاتورة مقابل عقد: إنشاء أكاديمية برمجة تعليمية - رقم العقد: CNT-2025-0002', '[{\"description\":\"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0623\\u0643\\u0627\\u062f\\u064a\\u0645\\u064a\\u0629 \\u0628\\u0631\\u0645\\u062c\\u0629 \\u062a\\u0639\\u0644\\u064a\\u0645\\u064a\\u0629\",\"quantity\":1,\"unit_price\":\"20000.00\"}]', NULL, NULL, 1, '2025-12-21 22:42:43', '2025-12-21 22:42:43');

-- --------------------------------------------------------

--
-- بنية الجدول `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` text NOT NULL,
  `attempts` bigint(20) NOT NULL,
  `reserved_at` bigint(20) DEFAULT NULL,
  `available_at` bigint(20) NOT NULL,
  `created_at` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` bigint(20) NOT NULL,
  `pending_jobs` bigint(20) NOT NULL,
  `failed_jobs` bigint(20) NOT NULL,
  `failed_job_ids` text NOT NULL,
  `options` text DEFAULT NULL,
  `cancelled_at` bigint(20) DEFAULT NULL,
  `created_at` bigint(20) NOT NULL,
  `finished_at` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `reference` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `total_debit` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_credit` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(50) NOT NULL DEFAULT 'draft',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `journal_entries`
--

INSERT INTO `journal_entries` (`id`, `date`, `reference`, `description`, `total_debit`, `total_credit`, `status`, `created_by`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, '2025-11-04', 'CNT-1', 'عقد: إنشاء منصة تعليمية للعميل: رغد بنت فيصل المطيري', 0.00, 0.00, 'posted', NULL, NULL, NULL, '2025-11-04 18:06:46', '2025-11-04 18:06:46'),
(2, '2025-11-04', 'CNT-2', 'عقد: إنشاء أكاديمية برمجة تعليمية للعميل: Tasneem tech bridge', 0.00, 0.00, 'posted', NULL, NULL, NULL, '2025-11-04 23:20:19', '2025-11-04 23:20:19'),
(3, '2025-11-04', 'INV-PAY-INV-202511-0002', 'Customer payment for invoice #INV-202511-0002', 20000.00, 20000.00, 'posted', 1, NULL, NULL, '2025-11-04 23:29:19', '2025-11-04 23:29:19'),
(4, '2025-12-11', 'INV-PAY-INV-202511-0001', 'Customer payment for invoice #INV-202511-0001', 50000.00, 50000.00, 'posted', 1, NULL, NULL, '2025-12-11 07:21:48', '2025-12-11 07:21:48');

-- --------------------------------------------------------

--
-- بنية الجدول `journal_entry_lines`
--

CREATE TABLE `journal_entry_lines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `journal_entry_id` bigint(20) UNSIGNED NOT NULL,
  `account_id` bigint(20) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `debit` decimal(10,2) NOT NULL DEFAULT 0.00,
  `credit` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `journal_entry_lines`
--

INSERT INTO `journal_entry_lines` (`id`, `journal_entry_id`, `account_id`, `description`, `debit`, `credit`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 'عقد العميل: رغد بنت فيصل المطيري', 50000.00, 0.00, '2025-11-04 18:06:46', '2025-11-04 18:06:46'),
(2, 1, 15, 'إيراد من عقد: إنشاء منصة تعليمية', 0.00, 50000.00, '2025-11-04 18:06:46', '2025-11-04 18:06:46'),
(3, 2, 4, 'عقد العميل: Tasneem tech bridge', 20000.00, 0.00, '2025-11-04 23:20:19', '2025-11-04 23:20:19'),
(4, 2, 15, 'إيراد من عقد: إنشاء أكاديمية برمجة تعليمية', 0.00, 20000.00, '2025-11-04 23:20:19', '2025-11-04 23:20:19'),
(5, 3, 1, 'Cash received', 20000.00, 0.00, '2025-11-04 23:29:19', '2025-11-04 23:29:19'),
(6, 3, 2, 'Reduce AR', 0.00, 20000.00, '2025-11-04 23:29:19', '2025-11-04 23:29:19'),
(7, 4, 1, 'Cash received', 50000.00, 0.00, '2025-12-11 07:21:48', '2025-12-11 07:21:48'),
(8, 4, 2, 'Reduce AR', 0.00, 50000.00, '2025-12-11 07:21:48', '2025-12-11 07:21:48');

-- --------------------------------------------------------

--
-- بنية الجدول `leaves`
--

CREATE TABLE `leaves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `leave_type` enum('annual','sick','emergency','maternity','paternity','unpaid','compensatory') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` int(11) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `applied_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `leaves`
--

INSERT INTO `leaves` (`id`, `employee_id`, `leave_type`, `start_date`, `end_date`, `total_days`, `reason`, `status`, `approved_by`, `rejection_reason`, `applied_date`, `created_at`, `updated_at`) VALUES
(1, 1, 'paternity', '2025-08-03', '2025-08-09', 7, 'ولادة طفل', 'pending', NULL, NULL, '2025-07-17', '2025-10-14 01:10:31', '2025-10-14 01:10:31'),
(2, 1, 'unpaid', '2025-08-23', '2025-09-04', 13, 'أمور شخصية عاجلة', 'rejected', 1, 'فترة الذروة في العمل', '2025-08-11', '2025-10-14 01:10:31', '2025-10-14 01:10:31'),
(3, 1, 'sick', '2025-09-08', '2025-09-10', 3, 'حمى', 'rejected', 1, 'السبب غير مقنع', '2025-09-07', '2025-10-14 01:10:31', '2025-10-14 01:10:31'),
(4, 2, 'annual', '2025-05-05', '2025-05-18', 14, 'رحلة عائلية', 'rejected', 1, 'ننن', '2025-04-18', '2025-10-14 01:10:31', '2025-10-14 01:10:51'),
(5, 2, 'compensatory', '2025-06-05', '2025-06-10', 6, 'تعويض عن العمل في العطل', 'approved', 1, NULL, '2025-05-25', '2025-10-14 01:10:31', '2025-10-14 01:10:31'),
(6, 2, 'emergency', '2025-03-15', '2025-03-28', 14, 'مشكلة في المنزل', 'rejected', 1, 'ل', '2025-02-19', '2025-10-14 01:10:31', '2025-11-04 18:34:35'),
(7, 2, 'annual', '2025-04-01', '2025-04-03', 3, 'زيارة الأهل', 'pending', NULL, NULL, '2025-03-28', '2025-10-14 01:10:31', '2025-10-14 01:10:31');

-- --------------------------------------------------------

--
-- بنية الجدول `login_activity_logs`
--

CREATE TABLE `login_activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `activity_type` enum('login','verification_code_sent','verification_code_verified','verification_code_resend','logout') NOT NULL DEFAULT 'login',
  `verification_code` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` enum('success','failed','pending') NOT NULL DEFAULT 'success',
  `message` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `activity_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `login_activity_logs`
--

INSERT INTO `login_activity_logs` (`id`, `user_id`, `activity_type`, `verification_code`, `email`, `status`, `message`, `ip_address`, `user_agent`, `activity_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.42.17.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 22:32:43', '2025-11-03 22:32:43', '2025-11-03 22:32:43'),
(2, 1, 'verification_code_sent', '20****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.42.17.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 22:32:43', '2025-11-03 22:32:43', '2025-11-03 22:32:43'),
(3, 1, 'verification_code_verified', '20****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.42.17.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-03 22:33:04', '2025-11-03 22:33:04', '2025-11-03 22:33:04'),
(4, 3, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.42.17.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-11-03 22:34:31', '2025-11-03 22:34:31', '2025-11-03 22:34:31'),
(5, 3, 'verification_code_sent', '84****', 'cod***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.42.17.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-11-03 22:34:31', '2025-11-03 22:34:31', '2025-11-03 22:34:31'),
(6, 3, 'verification_code_verified', '84****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.42.17.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-11-03 22:34:57', '2025-11-03 22:34:57', '2025-11-03 22:34:57'),
(7, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.42.17.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 17:50:04', '2025-11-04 17:50:04', '2025-11-04 17:50:04'),
(8, 1, 'verification_code_sent', '26****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.42.17.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 17:50:04', '2025-11-04 17:50:04', '2025-11-04 17:50:04'),
(9, 1, 'verification_code_verified', '26****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.42.17.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 17:50:17', '2025-11-04 17:50:17', '2025-11-04 17:50:17'),
(10, 1, 'logout', NULL, NULL, 'success', 'تم تسجيل الخروج بنجاح', '197.42.17.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 18:29:10', '2025-11-04 18:29:10', '2025-11-04 18:29:10'),
(11, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.42.17.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 18:29:25', '2025-11-04 18:29:25', '2025-11-04 18:29:25'),
(12, 1, 'verification_code_sent', '81****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.42.17.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 18:29:25', '2025-11-04 18:29:25', '2025-11-04 18:29:25'),
(13, 1, 'verification_code_verified', '81****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.42.17.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-04 18:29:42', '2025-11-04 18:29:42', '2025-11-04 18:29:42'),
(21, 3, 'logout', NULL, NULL, 'success', 'تم تسجيل الخروج بنجاح', '197.42.17.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', '2025-11-04 20:47:18', '2025-11-04 20:47:18', '2025-11-04 20:47:18'),
(26, 5, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '196.153.29.51', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-11-04 21:03:31', '2025-11-04 21:03:31', '2025-11-04 21:03:31'),
(27, 5, 'verification_code_sent', '16****', 'ma8***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '196.153.29.51', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-11-04 21:03:31', '2025-11-04 21:03:31', '2025-11-04 21:03:31'),
(28, 5, 'verification_code_resend', '85****', 'ma8***@gmail.com', 'success', 'تم إعادة إرسال كود التحقق بنجاح', '196.153.29.51', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-11-04 21:04:28', '2025-11-04 21:04:28', '2025-11-04 21:04:28'),
(29, 5, 'verification_code_verified', '85****', NULL, 'success', 'تم التحقق من الكود بنجاح', '196.153.29.51', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', '2025-11-04 21:08:05', '2025-11-04 21:08:05', '2025-11-04 21:08:05'),
(30, 5, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '102.184.221.15', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Mobile Safari/537.36', '2025-11-04 21:14:09', '2025-11-04 21:14:09', '2025-11-04 21:14:09'),
(31, 5, 'verification_code_sent', '36****', 'ma8***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '102.184.221.15', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Mobile Safari/537.36', '2025-11-04 21:14:09', '2025-11-04 21:14:09', '2025-11-04 21:14:09'),
(32, 5, 'verification_code_resend', '20****', 'ma8***@gmail.com', 'success', 'تم إعادة إرسال كود التحقق بنجاح', '102.184.221.15', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Mobile Safari/537.36', '2025-11-04 21:15:53', '2025-11-04 21:15:53', '2025-11-04 21:15:53'),
(33, 5, 'verification_code_verified', '20****', NULL, 'success', 'تم التحقق من الكود بنجاح', '102.184.221.15', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Mobile Safari/537.36', '2025-11-04 21:16:28', '2025-11-04 21:16:28', '2025-11-04 21:16:28'),
(35, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '196.156.23.5', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/142.0.7444.77 Mobile/15E148 Safari/604.1', '2025-11-04 21:50:00', '2025-11-04 21:50:00', '2025-11-04 21:50:00'),
(36, 1, 'verification_code_sent', '44****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '196.156.23.5', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/142.0.7444.77 Mobile/15E148 Safari/604.1', '2025-11-04 21:50:00', '2025-11-04 21:50:00', '2025-11-04 21:50:00'),
(37, 1, 'verification_code_verified', '44****', NULL, 'success', 'تم التحقق من الكود بنجاح', '196.156.23.5', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/142.0.7444.77 Mobile/15E148 Safari/604.1', '2025-11-04 21:50:12', '2025-11-04 21:50:12', '2025-11-04 21:50:12'),
(38, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '196.129.161.157', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/142.0.7444.77 Mobile/15E148 Safari/604.1', '2025-11-05 06:49:58', '2025-11-05 06:49:58', '2025-11-05 06:49:58'),
(39, 1, 'verification_code_sent', '82****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '196.129.161.157', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/142.0.7444.77 Mobile/15E148 Safari/604.1', '2025-11-05 06:49:58', '2025-11-05 06:49:58', '2025-11-05 06:49:58'),
(40, 1, 'verification_code_verified', '82****', NULL, 'success', 'تم التحقق من الكود بنجاح', '196.129.161.157', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/142.0.7444.77 Mobile/15E148 Safari/604.1', '2025-11-05 06:50:17', '2025-11-05 06:50:17', '2025-11-05 06:50:17'),
(41, 1, 'logout', NULL, NULL, 'success', 'تم تسجيل الخروج بنجاح', '196.129.161.157', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/142.0.7444.77 Mobile/15E148 Safari/604.1', '2025-11-05 08:18:01', '2025-11-05 08:18:01', '2025-11-05 08:18:01'),
(42, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '196.129.161.157', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/142.0.7444.77 Mobile/15E148 Safari/604.1', '2025-11-05 08:18:20', '2025-11-05 08:18:20', '2025-11-05 08:18:20'),
(43, 1, 'verification_code_sent', '79****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '196.129.161.157', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/142.0.7444.77 Mobile/15E148 Safari/604.1', '2025-11-05 08:18:20', '2025-11-05 08:18:20', '2025-11-05 08:18:20'),
(44, 1, 'verification_code_verified', '79****', NULL, 'success', 'تم التحقق من الكود بنجاح', '196.129.161.157', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/142.0.7444.77 Mobile/15E148 Safari/604.1', '2025-11-05 08:18:39', '2025-11-05 08:18:39', '2025-11-05 08:18:39'),
(45, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '196.129.12.115', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/142.0.7444.77 Mobile/15E148 Safari/604.1', '2025-11-06 20:01:49', '2025-11-06 20:01:49', '2025-11-06 20:01:49'),
(46, 1, 'verification_code_sent', '78****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '196.129.12.115', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_0_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/142.0.7444.77 Mobile/15E148 Safari/604.1', '2025-11-06 20:01:49', '2025-11-06 20:01:49', '2025-11-06 20:01:49'),
(47, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.42.17.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-06 23:56:09', '2025-11-06 23:56:09', '2025-11-06 23:56:09'),
(48, 1, 'verification_code_sent', '26****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.42.17.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-06 23:56:09', '2025-11-06 23:56:09', '2025-11-06 23:56:09'),
(49, 1, 'verification_code_verified', '26****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.42.17.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-06 23:56:33', '2025-11-06 23:56:33', '2025-11-06 23:56:33'),
(50, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.63.190.167', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-09 14:31:50', '2025-11-09 14:31:50', '2025-11-09 14:31:50'),
(51, 1, 'verification_code_sent', '82****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.63.190.167', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-09 14:31:50', '2025-11-09 14:31:50', '2025-11-09 14:31:50'),
(52, 1, 'verification_code_verified', '82****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.63.190.167', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-09 14:32:11', '2025-11-09 14:32:11', '2025-11-09 14:32:11'),
(53, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.63.141.146', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-21 11:08:47', '2025-11-21 11:08:47', '2025-11-21 11:08:47'),
(54, 1, 'verification_code_sent', '93****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.63.141.146', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-21 11:08:47', '2025-11-21 11:08:47', '2025-11-21 11:08:47'),
(55, 1, 'verification_code_verified', '93****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.63.141.146', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-21 11:09:03', '2025-11-21 11:09:03', '2025-11-21 11:09:03'),
(56, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.63.141.146', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-22 01:12:52', '2025-11-22 01:12:52', '2025-11-22 01:12:52'),
(57, 1, 'verification_code_sent', '59****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.63.141.146', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-22 01:12:52', '2025-11-22 01:12:52', '2025-11-22 01:12:52'),
(58, 1, 'verification_code_verified', '59****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.63.141.146', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-22 01:13:03', '2025-11-22 01:13:03', '2025-11-22 01:13:03'),
(59, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.63.191.207', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-25 11:33:10', '2025-11-25 11:33:10', '2025-11-25 11:33:10'),
(60, 1, 'verification_code_sent', '08****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.63.191.207', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-25 11:33:10', '2025-11-25 11:33:10', '2025-11-25 11:33:10'),
(61, 1, 'verification_code_verified', '08****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.63.191.207', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-25 11:33:25', '2025-11-25 11:33:25', '2025-11-25 11:33:25'),
(62, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '196.156.53.255', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_1_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/142.0.7444.148 Mobile/15E148 Safari/604.1', '2025-12-02 08:16:30', '2025-12-02 08:16:30', '2025-12-02 08:16:30'),
(63, 1, 'verification_code_sent', '36****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '196.156.53.255', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_1_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/142.0.7444.148 Mobile/15E148 Safari/604.1', '2025-12-02 08:16:30', '2025-12-02 08:16:30', '2025-12-02 08:16:30'),
(64, 1, 'verification_code_verified', '36****', NULL, 'success', 'تم التحقق من الكود بنجاح', '196.156.53.255', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_1_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/142.0.7444.148 Mobile/15E148 Safari/604.1', '2025-12-02 08:16:43', '2025-12-02 08:16:43', '2025-12-02 08:16:43'),
(65, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '196.153.173.89', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_1_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/143.0.7499.92 Mobile/15E148 Safari/604.1', '2025-12-11 07:21:15', '2025-12-11 07:21:15', '2025-12-11 07:21:15'),
(66, 1, 'verification_code_sent', '81****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '196.153.173.89', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_1_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/143.0.7499.92 Mobile/15E148 Safari/604.1', '2025-12-11 07:21:15', '2025-12-11 07:21:15', '2025-12-11 07:21:15'),
(67, 1, 'verification_code_verified', '81****', NULL, 'success', 'تم التحقق من الكود بنجاح', '196.153.173.89', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_1_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/143.0.7499.92 Mobile/15E148 Safari/604.1', '2025-12-11 07:21:30', '2025-12-11 07:21:30', '2025-12-11 07:21:30'),
(68, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.42.10.85', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-12 21:08:06', '2025-12-12 21:08:06', '2025-12-12 21:08:06'),
(69, 1, 'verification_code_sent', '51****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.42.10.85', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-12 21:08:06', '2025-12-12 21:08:06', '2025-12-12 21:08:06'),
(70, 1, 'verification_code_verified', '51****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.42.10.85', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-12 21:08:34', '2025-12-12 21:08:34', '2025-12-12 21:08:34'),
(71, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.63.153.145', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-15 16:21:17', '2025-12-15 16:21:17', '2025-12-15 16:21:17'),
(72, 1, 'verification_code_sent', '67****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.63.153.145', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-15 16:21:17', '2025-12-15 16:21:17', '2025-12-15 16:21:17'),
(73, 1, 'verification_code_verified', '67****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.63.153.145', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-15 16:21:31', '2025-12-15 16:21:31', '2025-12-15 16:21:31'),
(74, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.42.6.129', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_1_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/143.0.7499.108 Mobile/15E148 Safari/604.1', '2025-12-16 16:57:54', '2025-12-16 16:57:54', '2025-12-16 16:57:54'),
(75, 1, 'verification_code_sent', '27****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.42.6.129', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_1_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/143.0.7499.108 Mobile/15E148 Safari/604.1', '2025-12-16 16:57:54', '2025-12-16 16:57:54', '2025-12-16 16:57:54'),
(76, 1, 'verification_code_verified', '27****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.42.6.129', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_1_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/143.0.7499.108 Mobile/15E148 Safari/604.1', '2025-12-16 16:58:04', '2025-12-16 16:58:04', '2025-12-16 16:58:04'),
(77, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.63.153.145', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-20 19:09:00', '2025-12-20 19:09:00', '2025-12-20 19:09:00'),
(78, 1, 'verification_code_sent', '27****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.63.153.145', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-20 19:09:00', '2025-12-20 19:09:00', '2025-12-20 19:09:00'),
(79, 1, 'verification_code_verified', '27****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.63.153.145', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-20 19:09:13', '2025-12-20 19:09:13', '2025-12-20 19:09:13'),
(80, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.42.6.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-21 10:48:51', '2025-12-21 10:48:51', '2025-12-21 10:48:51'),
(81, 1, 'verification_code_sent', '01****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.42.6.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-21 10:48:51', '2025-12-21 10:48:51', '2025-12-21 10:48:51'),
(82, 1, 'verification_code_verified', '01****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.42.6.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-21 10:49:03', '2025-12-21 10:49:03', '2025-12-21 10:49:03'),
(83, 1, 'logout', NULL, NULL, 'success', 'تم تسجيل الخروج بنجاح', '197.42.6.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-21 10:55:54', '2025-12-21 10:55:54', '2025-12-21 10:55:54'),
(84, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.42.6.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-21 11:44:01', '2025-12-21 11:44:01', '2025-12-21 11:44:01'),
(85, 1, 'verification_code_sent', '56****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.42.6.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-21 11:44:01', '2025-12-21 11:44:01', '2025-12-21 11:44:01'),
(86, 1, 'verification_code_verified', '56****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.42.6.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-21 11:44:15', '2025-12-21 11:44:15', '2025-12-21 11:44:15'),
(87, 1, 'logout', NULL, NULL, 'success', 'تم تسجيل الخروج بنجاح', '197.42.6.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-21 11:53:32', '2025-12-21 11:53:32', '2025-12-21 11:53:32'),
(88, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.63.153.145', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-21 22:38:34', '2025-12-21 22:38:34', '2025-12-21 22:38:34'),
(89, 1, 'verification_code_sent', '68****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.63.153.145', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-21 22:38:34', '2025-12-21 22:38:34', '2025-12-21 22:38:34'),
(90, 1, 'verification_code_verified', '68****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.63.153.145', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-21 22:38:54', '2025-12-21 22:38:54', '2025-12-21 22:38:54'),
(91, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.42.6.129', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 13:37:03', '2025-12-22 13:37:03', '2025-12-22 13:37:03'),
(92, 1, 'verification_code_sent', '48****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.42.6.129', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 13:37:03', '2025-12-22 13:37:03', '2025-12-22 13:37:03'),
(93, 1, 'verification_code_verified', '48****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.42.6.129', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 13:37:21', '2025-12-22 13:37:21', '2025-12-22 13:37:21'),
(94, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.42.6.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 18:12:11', '2025-12-22 18:12:11', '2025-12-22 18:12:11'),
(95, 1, 'verification_code_sent', '35****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.42.6.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-22 18:12:11', '2025-12-22 18:12:11', '2025-12-22 18:12:11'),
(96, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.42.6.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-25 20:31:19', '2025-12-25 20:31:19', '2025-12-25 20:31:19'),
(97, 1, 'verification_code_sent', '68****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.42.6.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-25 20:31:19', '2025-12-25 20:31:19', '2025-12-25 20:31:19'),
(98, 1, 'verification_code_verified', '68****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.42.6.129', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-25 20:31:30', '2025-12-25 20:31:30', '2025-12-25 20:31:30'),
(99, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.42.24.14', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 19:24:52', '2025-12-26 19:24:52', '2025-12-26 19:24:52'),
(100, 1, 'verification_code_sent', '01****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.42.24.14', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 19:24:52', '2025-12-26 19:24:52', '2025-12-26 19:24:52'),
(101, 1, 'verification_code_verified', '01****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.42.24.14', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-26 19:25:13', '2025-12-26 19:25:13', '2025-12-26 19:25:13'),
(102, 3, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.42.24.14', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-26 19:28:06', '2025-12-26 19:28:06', '2025-12-26 19:28:06'),
(103, 3, 'verification_code_sent', '95****', 'cod***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.42.24.14', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-26 19:28:06', '2025-12-26 19:28:06', '2025-12-26 19:28:06'),
(104, 3, 'verification_code_verified', '95****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.42.24.14', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-26 19:28:59', '2025-12-26 19:28:59', '2025-12-26 19:28:59'),
(105, 3, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.42.24.14', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-27 01:19:08', '2025-12-27 01:19:08', '2025-12-27 01:19:08'),
(106, 3, 'verification_code_sent', '91****', 'cod***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.42.24.14', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-27 01:19:08', '2025-12-27 01:19:08', '2025-12-27 01:19:08'),
(107, 3, 'verification_code_verified', '91****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.42.24.14', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-27 01:19:42', '2025-12-27 01:19:42', '2025-12-27 01:19:42'),
(108, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '196.134.171.56', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_2_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/143.0.7499.151 Mobile/15E148 Safari/604.1', '2025-12-28 18:32:09', '2025-12-28 18:32:09', '2025-12-28 18:32:09'),
(109, 1, 'verification_code_sent', '99****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '196.134.171.56', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_2_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/143.0.7499.151 Mobile/15E148 Safari/604.1', '2025-12-28 18:32:09', '2025-12-28 18:32:09', '2025-12-28 18:32:09'),
(110, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.42.24.14', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 18:08:34', '2025-12-31 18:08:34', '2025-12-31 18:08:34'),
(111, 1, 'verification_code_sent', '94****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.42.24.14', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 18:08:34', '2025-12-31 18:08:34', '2025-12-31 18:08:34'),
(112, 1, 'verification_code_verified', '94****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.42.24.14', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-31 18:09:06', '2025-12-31 18:09:06', '2025-12-31 18:09:06'),
(113, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.42.24.14', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 17:45:19', '2026-01-01 17:45:19', '2026-01-01 17:45:19'),
(114, 1, 'verification_code_sent', '34****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.42.24.14', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 17:45:19', '2026-01-01 17:45:19', '2026-01-01 17:45:19'),
(115, 1, 'verification_code_verified', '34****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.42.24.14', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 17:45:36', '2026-01-01 17:45:36', '2026-01-01 17:45:36'),
(116, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.63.156.36', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-14 14:09:51', '2026-01-14 14:09:51', '2026-01-14 14:09:51'),
(117, 1, 'verification_code_sent', '12****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.63.156.36', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-14 14:09:51', '2026-01-14 14:09:51', '2026-01-14 14:09:51'),
(118, 1, 'verification_code_verified', '12****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.63.156.36', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-14 14:10:03', '2026-01-14 14:10:03', '2026-01-14 14:10:03'),
(119, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.63.188.94', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-13 14:36:41', '2026-02-13 14:36:41', '2026-02-13 14:36:41'),
(120, 1, 'verification_code_sent', '85****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.63.188.94', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-13 14:36:41', '2026-02-13 14:36:41', '2026-02-13 14:36:41'),
(121, 1, 'verification_code_verified', '85****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.63.188.94', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-13 14:36:56', '2026-02-13 14:36:56', '2026-02-13 14:36:56'),
(122, 1, 'logout', NULL, NULL, 'success', 'تم تسجيل الخروج بنجاح', '197.63.188.94', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-13 14:46:30', '2026-02-13 14:46:30', '2026-02-13 14:46:30'),
(123, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '197.63.188.94', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-15 09:11:06', '2026-02-15 09:11:06', '2026-02-15 09:11:06'),
(124, 1, 'verification_code_sent', '04****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '197.63.188.94', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-15 09:11:07', '2026-02-15 09:11:07', '2026-02-15 09:11:07'),
(125, 1, 'verification_code_verified', '04****', NULL, 'success', 'تم التحقق من الكود بنجاح', '197.63.188.94', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-15 09:11:22', '2026-02-15 09:11:22', '2026-02-15 09:11:22'),
(126, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '196.156.37.179', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 18:03:04', '2026-04-08 18:03:04', '2026-04-08 18:03:04'),
(127, 1, 'verification_code_sent', '19****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '196.156.37.179', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 18:03:04', '2026-04-08 18:03:04', '2026-04-08 18:03:04'),
(128, 1, 'verification_code_verified', '19****', NULL, 'success', 'تم التحقق من الكود بنجاح', '196.156.37.179', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '2026-04-08 18:03:15', '2026-04-08 18:03:15', '2026-04-08 18:03:15'),
(129, 1, 'login', NULL, NULL, 'success', 'تم تسجيل الدخول بنجاح', '41.41.126.72', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-25 12:02:54', '2026-04-25 12:02:54', '2026-04-25 12:02:54'),
(130, 1, 'verification_code_sent', '71****', 'lor***@gmail.com', 'success', 'تم إرسال كود التحقق بنجاح', '41.41.126.72', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-25 12:02:54', '2026-04-25 12:02:54', '2026-04-25 12:02:54'),
(131, 1, 'verification_code_verified', '71****', NULL, 'success', 'تم التحقق من الكود بنجاح', '41.41.126.72', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-25 12:03:05', '2026-04-25 12:03:05', '2026-04-25 12:03:05');

-- --------------------------------------------------------

--
-- بنية الجدول `meetings`
--

CREATE TABLE `meetings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('internal','external','online','in-person','hybrid') NOT NULL,
  `status` enum('scheduled','ongoing','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `meeting_link` varchar(500) DEFAULT NULL,
  `organizer_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `agenda` text DEFAULT NULL,
  `minutes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `meeting_participants`
--

CREATE TABLE `meeting_participants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `meeting_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('invited','accepted','declined','attended','absent') NOT NULL DEFAULT 'invited',
  `response_time` timestamp NULL DEFAULT NULL,
  `attended_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` longtext NOT NULL,
  `type` enum('direct','group','announcement') NOT NULL DEFAULT 'direct',
  `priority` enum('low','normal','high','urgent') NOT NULL DEFAULT 'normal',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `is_important` tinyint(1) NOT NULL DEFAULT 0,
  `is_deleted_by_sender` tinyint(1) NOT NULL DEFAULT 0,
  `is_deleted_by_receiver` tinyint(1) NOT NULL DEFAULT 0,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `parent_message_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `subject`, `body`, `type`, `priority`, `is_read`, `read_at`, `is_important`, `is_deleted_by_sender`, `is_deleted_by_receiver`, `attachments`, `parent_message_id`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 'رسالة ترحيبية', 'أهلا بك محمود أيمن في شركة سولفيستا للبرمجيات', 'direct', 'high', 0, NULL, 1, 1, 0, NULL, NULL, '2025-11-04 21:10:39', '2025-12-20 23:13:46'),
(3, 1, 5, 'تاخير تسليم', 'you late', 'direct', 'normal', 0, NULL, 1, 0, 0, NULL, NULL, '2025-12-21 10:49:50', '2025-12-21 10:49:50');

-- --------------------------------------------------------

--
-- بنية الجدول `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_20_000001_create_verification_codes_table', 1),
(5, '2025_10_03_150846_create_permission_tables', 1),
(6, '2025_10_03_151721_create_departments_table', 1),
(7, '2025_10_03_151732_create_employees_table', 1),
(8, '2025_10_03_151741_create_clients_table', 1),
(9, '2025_10_06_005517_create_projects_table', 1),
(10, '2025_10_06_005525_create_tasks_table', 1),
(11, '2025_10_06_005533_create_attendances_table', 1),
(12, '2025_10_06_005541_create_salaries_table', 1),
(13, '2025_10_06_005549_create_leaves_table', 1),
(14, '2025_10_06_005556_create_sales_table', 1),
(15, '2025_10_06_005604_create_tickets_table', 1),
(16, '2025_10_06_005612_create_invoices_table', 1),
(17, '2025_10_06_005620_create_contracts_table', 1),
(18, '2025_10_06_005841_create_bugs_table', 1),
(19, '2025_10_06_005853_create_activity_logs_table', 1),
(20, '2025_10_31_172836_create_login_activity_logs_table', 1),
(21, '2025_10_09_215837_add_daily_hours_to_employees_table', 1),
(22, '2025_10_09_222837_create_accounting_system_tables', 1),
(23, '2025_10_10_110129_create_user_permissions_table', 1),
(24, '2025_10_10_142556_update_accounts_table_for_new_structure', 1),
(25, '2025_10_10_161052_add_department_fields_to_departments_table', 2),
(26, '2025_10_10_163628_create_system_settings_table', 3),
(27, '2025_10_10_171849_add_profile_picture_to_users_table', 4),
(28, '2025_10_11_115742_create_project_team_members_table', 5),
(29, '2025_10_11_120836_create_qa_tests_table', 6),
(30, '2025_10_14_011330_create_q_a_tests_table', 7),
(31, '2025_10_17_135834_create_notifications_table', 1),
(32, '2025_10_17_135835_create_messages_table', 1),
(33, '2025_10_17_142221_create_new_permissions_table', 1),
(34, '2025_10_17_142258_add_new_permissions_to_permissions_table', 1),
(35, '2025_10_18_034936_add_whatsapp_settings', 1),
(36, '2025_10_24_105533_update_messages_table_add_advanced_features', 1),
(37, '2025_10_25_173902_add_employee_id_settings_to_system_settings', 2),
(38, '2025_10_30_151057_create_task_updates_table', 3),
(39, '2025_10_30_154406_add_current_status_to_attendances_table', 4),
(40, '2025_11_02_082017_add_break_duration_minutes_to_attendances_table', 7),
(41, '2025_11_02_085012_add_sale_id_to_invoices_table', 8),
(42, '2025_11_02_125109_add_contract_id_to_invoices_table', 9),
(43, '2025_11_02_125304_add_issue_date_and_paid_date_to_invoices_table', 10),
(44, '2025_11_03_180334_add_department_id_to_trainings_and_meetings_tables', 11),
(45, '2025_11_03_190315_add_project_team_members_project_user_index', 11),
(46, '2025_11_03_194332_create_assets_table', 12),
(47, '2025_11_03_194334_create_asset_maintenance_table', 12);

-- --------------------------------------------------------

--
-- بنية الجدول `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(50) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(31, 'App\\Models\\User', 3),
(60, 'App\\Models\\User', 3),
(61, 'App\\Models\\User', 3);

-- --------------------------------------------------------

--
-- بنية الجدول `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(50) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(4, 'App\\Models\\User', 5),
(10, 'App\\Models\\User', 3);

-- --------------------------------------------------------

--
-- بنية الجدول `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `data`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 5, 'message', 'رسالة جديدة من Solvesta Company', 'رسالة ترحيبية', '{\"message_id\":1,\"sender_name\":\"Solvesta Company\",\"priority\":\"high\"}', 0, NULL, '2025-11-04 21:10:39', '2025-11-04 21:10:39'),
(2, 5, 'task', 'مهمة جديدة مخصصة لك', 'تم تعيين مهمة جديدة لك: تصميم المذكرة', '{\"task_id\":1,\"project_id\":\"8\",\"created_by\":\"Solvesta Company\"}', 0, NULL, '2025-11-04 21:19:42', '2025-11-04 21:19:42'),
(5, 5, 'message', 'رسالة جديدة من Solvesta Company', 'تاخير تسليم', '{\"message_id\":3,\"sender_name\":\"Solvesta Company\",\"priority\":\"normal\"}', 0, NULL, '2025-12-21 10:49:50', '2025-12-21 10:49:50');

-- --------------------------------------------------------

--
-- بنية الجدول `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_number` varchar(255) NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `bank_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'completed',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view-users', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(2, 'create-users', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(3, 'edit-users', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(4, 'delete-users', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(5, 'view-employees', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(6, 'create-employees', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(7, 'edit-employees', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(8, 'delete-employees', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(9, 'view-projects', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(10, 'create-projects', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(11, 'edit-projects', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(12, 'delete-projects', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(13, 'view-tasks', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(14, 'create-tasks', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(15, 'edit-tasks', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(16, 'delete-tasks', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(17, 'view-clients', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(18, 'create-clients', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(19, 'edit-clients', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(20, 'delete-clients', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(21, 'view-sales', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(22, 'create-sales', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(23, 'edit-sales', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(24, 'delete-sales', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(25, 'view-finance', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(26, 'create-finance', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(27, 'edit-finance', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(28, 'delete-finance', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(29, 'view-reports', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(30, 'generate-reports', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(31, 'view-dashboard', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(32, 'view-settings', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(33, 'edit-settings', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(34, 'view-all-projects', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(35, 'view-own-projects', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(36, 'view-all-tasks', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(37, 'view-own-tasks', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(38, 'approve-expenses', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(39, 'view-attendance', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(40, 'create-attendance', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(41, 'edit-attendance', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(42, 'delete-attendance', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(43, 'view-leaves', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(44, 'create-leaves', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(45, 'edit-leaves', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(46, 'delete-leaves', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(47, 'approve-leaves', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(48, 'view-salaries', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(49, 'create-salaries', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(50, 'edit-salaries', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(51, 'delete-salaries', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(52, 'view-invoices', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(53, 'create-invoices', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(54, 'edit-invoices', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(55, 'delete-invoices', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(56, 'view-contracts', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(57, 'create-contracts', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(58, 'edit-contracts', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(59, 'delete-contracts', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(60, 'view-bugs', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(61, 'create-bugs', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(62, 'edit-bugs', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(63, 'delete-bugs', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(64, 'view-qa', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(65, 'create-qa', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(66, 'edit-qa', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(67, 'delete-qa', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(68, 'view-tickets', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(69, 'create-tickets', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(70, 'edit-tickets', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(71, 'delete-tickets', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(72, 'view-departments', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(73, 'create-departments', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(74, 'edit-departments', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(75, 'delete-departments', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(76, 'export-reports', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(77, 'view-analytics', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(78, 'manage-roles', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(79, 'view-training', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(80, 'create-training', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(81, 'edit-training', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(82, 'delete-training', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(83, 'view-meetings', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(84, 'create-meetings', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(85, 'edit-meetings', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(86, 'delete-meetings', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(87, 'view-assets', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(88, 'create-assets', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(89, 'edit-assets', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(90, 'delete-assets', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(91, 'manage-asset-maintenance', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(92, 'approve-salaries', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53');

-- --------------------------------------------------------

--
-- بنية الجدول `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `project_manager_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `budget` decimal(15,2) DEFAULT NULL,
  `status` enum('planning','in_progress','on_hold','completed','cancelled') NOT NULL DEFAULT 'planning',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `progress_percentage` int(11) NOT NULL DEFAULT 0,
  `team_members` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`team_members`)),
  `technologies` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`technologies`)),
  `project_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `client_id`, `project_manager_id`, `start_date`, `end_date`, `budget`, `status`, `priority`, `progress_percentage`, `team_members`, `technologies`, `project_type`, `created_at`, `updated_at`, `department_id`) VALUES
(7, 'منصة تعليمية qaddaha', 'منصة تعليمية لاختبار القدرات السعودية', 2, 1, '2025-10-01', '2025-12-31', 50000.00, 'in_progress', 'high', 0, NULL, NULL, 'development', '2025-11-04 17:59:42', '2025-11-04 18:04:04', NULL),
(8, 'تصميم مذكرة تعليمية', 'هذه المذكرة التعليمية مربوطة بمنصة qaddaha يقوم بتنفيذها محمود ايمن', 2, 1, '2025-11-01', '2025-11-30', 25000.00, 'in_progress', 'medium', 0, NULL, NULL, 'design', '2025-11-04 18:36:00', '2025-11-04 21:05:00', NULL),
(9, 'تصميمات الشركة (solvesta)', 'في هذا المشروع الذي يتجدد شهريا لابد من اكمال جميع المهام في التوقيت الخاص بها', 3, 1, '2025-11-01', '2025-11-30', 0.00, 'in_progress', 'urgent', 0, NULL, NULL, 'design', '2025-11-04 20:47:00', '2025-11-04 20:47:00', NULL),
(10, 'إنشاء أكاديمية برمجة تعليمية', 'إنشاء أكاديمية برمجة تعليمية', 4, 1, '2025-11-21', '2025-12-21', 25000.00, 'in_progress', 'high', 0, NULL, NULL, 'development', '2025-11-04 23:19:20', '2025-12-21 10:51:36', NULL),
(11, 'تجديد منصة تعليمية مستر طارق الداجن', 'منصة الطارق\r\nفي الرياضيات \r\nمع مستر طارق الداجن', 5, 1, '2026-02-01', '2026-02-15', 20000.00, 'completed', 'low', 0, NULL, NULL, 'development', '2026-02-13 14:42:26', '2026-02-13 14:43:34', NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `project_team_members`
--

CREATE TABLE `project_team_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `project_team_members`
--

INSERT INTO `project_team_members` (`id`, `project_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 7, 1, NULL, NULL),
(2, 8, 1, NULL, NULL),
(4, 8, 5, NULL, NULL),
(5, 10, 1, NULL, NULL),
(6, 11, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `q_a_tests`
--

CREATE TABLE `q_a_tests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `test_number` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('unit','integration','functional','performance','security','usability') NOT NULL DEFAULT 'functional',
  `status` enum('pending','running','passed','failed','skipped') NOT NULL DEFAULT 'pending',
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `test_steps` text DEFAULT NULL,
  `expected_result` text DEFAULT NULL,
  `actual_result` text DEFAULT NULL,
  `preconditions` text DEFAULT NULL,
  `test_data` text DEFAULT NULL,
  `environment` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  `executed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `q_a_tests`
--

INSERT INTO `q_a_tests` (`id`, `test_number`, `name`, `description`, `project_id`, `created_by`, `assigned_to`, `type`, `status`, `priority`, `test_steps`, `expected_result`, `actual_result`, `preconditions`, `test_data`, `environment`, `notes`, `execution_time`, `executed_at`, `created_at`, `updated_at`) VALUES
(1, 'QA-2025-0001', 'اختبار أداء تحميل الصفحة', 'اختبار أداء النظام تحت ظروف مختلفة', NULL, 3, 1, 'performance', 'skipped', 'low', '1. إعداد أدوات القياس\n2. تشغيل الاختبار\n3. مراقبة الأداء\n4. تحليل النتائج', 'يجب أن يكون الأداء ضمن المعايير المطلوبة', 'تم تخطي الاختبار لسبب تقني', 'يجب أن تكون أدوات القياس جاهزة', 'بيانات كبيرة للاختبار', 'Development', 'ملاحظات اختبار الأداء', 467, '2025-09-25 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(2, 'QA-2025-0002', 'اختبار تسجيل الدخول', 'اختبار الوظائف الأساسية للتطبيق', NULL, 3, 3, 'functional', 'failed', 'medium', '1. فتح التطبيق\n2. تنفيذ العملية\n3. التحقق من النتيجة\n4. اختبار الحالات المختلفة', 'يجب أن تعمل الوظيفة كما هو متوقع', 'الاختبار فشل - النتيجة غير متوقعة', 'يجب أن يكون التطبيق قيد التشغيل', 'بيانات المستخدم والمنتجات', 'Staging', 'ملاحظات الاختبار الوظيفي - فشل الاختبار يحتاج لإصلاح', 229, '2025-10-01 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(3, 'QA-2025-0003', 'اختبار أمان الجلسات', 'اختبار أمان النظام وحماية البيانات', NULL, 3, 3, 'security', 'pending', 'low', '1. إعداد أدوات الأمان\n2. محاولة الاختراق\n3. مراقبة الحماية\n4. تقييم النتائج', 'يجب أن يكون النظام محمياً من التهديدات', NULL, 'يجب أن تكون أدوات الأمان متاحة', 'بيانات حساسة للاختبار', 'Local', 'ملاحظات اختبار الأمان', NULL, NULL, '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(4, 'QA-2025-0004', 'اختبار وحدة إدارة المنتجات', 'اختبار وحدة البرمجة للتأكد من عمل الدالة بشكل صحيح', NULL, 3, 3, 'unit', 'pending', 'medium', '1. إعداد البيانات التجريبية\n2. استدعاء الدالة\n3. التحقق من النتيجة\n4. تنظيف البيانات', 'يجب أن تعيد الدالة النتيجة المتوقعة', NULL, 'يجب أن تكون البيئة مهيأة والدوال متاحة', 'بيانات تجريبية للدالة', 'Local', 'ملاحظات اختبار الوحدة', NULL, NULL, '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(5, 'QA-2025-0005', 'اختبار وحدة حساب المستخدم', 'اختبار وحدة البرمجة للتأكد من عمل الدالة بشكل صحيح', NULL, 1, 3, 'unit', 'failed', 'critical', '1. إعداد البيانات التجريبية\n2. استدعاء الدالة\n3. التحقق من النتيجة\n4. تنظيف البيانات', 'يجب أن تعيد الدالة النتيجة المتوقعة', 'الاختبار فشل - النتيجة غير متوقعة', 'يجب أن تكون البيئة مهيأة والدوال متاحة', 'بيانات تجريبية للدالة', 'Local', 'ملاحظات اختبار الوحدة - فشل الاختبار يحتاج لإصلاح', 170, '2025-10-05 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(6, 'QA-2025-0006', 'اختبار وحدة نظام المصادقة', 'اختبار وحدة البرمجة للتأكد من عمل الدالة بشكل صحيح', NULL, 1, 3, 'unit', 'passed', 'low', '1. إعداد البيانات التجريبية\n2. استدعاء الدالة\n3. التحقق من النتيجة\n4. تنظيف البيانات', 'يجب أن تعيد الدالة النتيجة المتوقعة', 'الاختبار نجح كما هو متوقع', 'يجب أن تكون البيئة مهيأة والدوال متاحة', 'بيانات تجريبية للدالة', 'Development', 'ملاحظات اختبار الوحدة - نجح الاختبار بنجاح', 422, '2025-10-09 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(7, 'QA-2025-0007', 'اختبار تكامل إدارة المستخدمين', 'اختبار تكامل بين المكونات المختلفة للنظام', NULL, 1, 3, 'integration', 'skipped', 'medium', '1. إعداد البيئة\n2. تشغيل المكونات\n3. التحقق من التكامل\n4. اختبار التفاعل', 'يجب أن تعمل المكونات معاً بشكل صحيح', 'تم تخطي الاختبار لسبب تقني', 'يجب أن تكون جميع المكونات متاحة', 'بيانات تكامل بين المكونات', 'Production', 'ملاحظات اختبار التكامل', 280, '2025-09-26 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(8, 'QA-2025-0008', 'اختبار أداء الاستجابة', 'اختبار أداء النظام تحت ظروف مختلفة', NULL, 1, 3, 'performance', 'pending', 'medium', '1. إعداد أدوات القياس\n2. تشغيل الاختبار\n3. مراقبة الأداء\n4. تحليل النتائج', 'يجب أن يكون الأداء ضمن المعايير المطلوبة', NULL, 'يجب أن تكون أدوات القياس جاهزة', 'بيانات كبيرة للاختبار', 'Development', 'ملاحظات اختبار الأداء', NULL, NULL, '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(9, 'QA-2025-0009', 'اختبار أداء تحميل الصفحة', 'اختبار أداء النظام تحت ظروف مختلفة', NULL, 3, 3, 'performance', 'running', 'medium', '1. إعداد أدوات القياس\n2. تشغيل الاختبار\n3. مراقبة الأداء\n4. تحليل النتائج', 'يجب أن يكون الأداء ضمن المعايير المطلوبة', 'الاختبار قيد التنفيذ', 'يجب أن تكون أدوات القياس جاهزة', 'بيانات كبيرة للاختبار', 'Production', 'ملاحظات اختبار الأداء', 164, '2025-09-20 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(10, 'QA-2025-0010', 'اختبار سهولة الإعدادات', 'اختبار سهولة الاستخدام وتجربة المستخدم', NULL, 3, 3, 'usability', 'pending', 'low', '1. إعداد المهام\n2. تشغيل الاختبار\n3. مراقبة المستخدم\n4. جمع التعليقات', 'يجب أن يكون التطبيق سهلاً في الاستخدام', NULL, 'يجب أن يكون المستخدمون جاهزين للاختبار', 'بيانات المستخدم الحقيقية', 'Local', 'ملاحظات اختبار سهولة الاستخدام', NULL, NULL, '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(11, 'QA-2025-0011', 'اختبار أمان الجلسات', 'اختبار أمان النظام وحماية البيانات', NULL, 1, 1, 'security', 'failed', 'critical', '1. إعداد أدوات الأمان\n2. محاولة الاختراق\n3. مراقبة الحماية\n4. تقييم النتائج', 'يجب أن يكون النظام محمياً من التهديدات', 'الاختبار فشل - النتيجة غير متوقعة', 'يجب أن تكون أدوات الأمان متاحة', 'بيانات حساسة للاختبار', 'Local', 'ملاحظات اختبار الأمان - فشل الاختبار يحتاج لإصلاح', 85, '2025-10-01 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(12, 'QA-2025-0012', 'اختبار سهولة الشراء', 'اختبار سهولة الاستخدام وتجربة المستخدم', NULL, 1, 1, 'usability', 'passed', 'low', '1. إعداد المهام\n2. تشغيل الاختبار\n3. مراقبة المستخدم\n4. جمع التعليقات', 'يجب أن يكون التطبيق سهلاً في الاستخدام', 'الاختبار نجح كما هو متوقع', 'يجب أن يكون المستخدمون جاهزين للاختبار', 'بيانات المستخدم الحقيقية', 'Testing', 'ملاحظات اختبار سهولة الاستخدام - نجح الاختبار بنجاح', 449, '2025-09-23 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(13, 'QA-2025-0013', 'اختبار أمان الجلسات', 'اختبار أمان النظام وحماية البيانات', NULL, 3, 1, 'security', 'passed', 'high', '1. إعداد أدوات الأمان\n2. محاولة الاختراق\n3. مراقبة الحماية\n4. تقييم النتائج', 'يجب أن يكون النظام محمياً من التهديدات', 'الاختبار نجح كما هو متوقع', 'يجب أن تكون أدوات الأمان متاحة', 'بيانات حساسة للاختبار', 'Development', 'ملاحظات اختبار الأمان - نجح الاختبار بنجاح', 122, '2025-10-05 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(14, 'QA-2025-0014', 'اختبار أمان الجلسات', 'اختبار أمان النظام وحماية البيانات', NULL, 1, 3, 'security', 'running', 'critical', '1. إعداد أدوات الأمان\n2. محاولة الاختراق\n3. مراقبة الحماية\n4. تقييم النتائج', 'يجب أن يكون النظام محمياً من التهديدات', 'الاختبار قيد التنفيذ', 'يجب أن تكون أدوات الأمان متاحة', 'بيانات حساسة للاختبار', 'Testing', 'ملاحظات اختبار الأمان', 497, '2025-09-27 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(15, 'QA-2025-0015', 'اختبار سهولة الإعدادات', 'اختبار سهولة الاستخدام وتجربة المستخدم', NULL, 3, 3, 'usability', 'failed', 'critical', '1. إعداد المهام\n2. تشغيل الاختبار\n3. مراقبة المستخدم\n4. جمع التعليقات', 'يجب أن يكون التطبيق سهلاً في الاستخدام', 'الاختبار فشل - النتيجة غير متوقعة', 'يجب أن يكون المستخدمون جاهزين للاختبار', 'بيانات المستخدم الحقيقية', 'Production', 'ملاحظات اختبار سهولة الاستخدام - فشل الاختبار يحتاج لإصلاح', 66, '2025-09-29 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(16, 'QA-2025-0016', 'اختبار أمان البيانات', 'اختبار أمان النظام وحماية البيانات', NULL, 1, 3, 'security', 'passed', 'critical', '1. إعداد أدوات الأمان\n2. محاولة الاختراق\n3. مراقبة الحماية\n4. تقييم النتائج', 'يجب أن يكون النظام محمياً من التهديدات', 'الاختبار نجح كما هو متوقع', 'يجب أن تكون أدوات الأمان متاحة', 'بيانات حساسة للاختبار', 'Testing', 'ملاحظات اختبار الأمان - نجح الاختبار بنجاح', 196, '2025-10-13 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(17, 'QA-2025-0017', 'اختبار أداء تحميل الصفحة', 'اختبار أداء النظام تحت ظروف مختلفة', NULL, 3, 3, 'performance', 'pending', 'low', '1. إعداد أدوات القياس\n2. تشغيل الاختبار\n3. مراقبة الأداء\n4. تحليل النتائج', 'يجب أن يكون الأداء ضمن المعايير المطلوبة', NULL, 'يجب أن تكون أدوات القياس جاهزة', 'بيانات كبيرة للاختبار', 'Production', 'ملاحظات اختبار الأداء', NULL, NULL, '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(18, 'QA-2025-0018', 'اختبار سهولة الشراء', 'اختبار سهولة الاستخدام وتجربة المستخدم', NULL, 3, 3, 'usability', 'failed', 'high', '1. إعداد المهام\n2. تشغيل الاختبار\n3. مراقبة المستخدم\n4. جمع التعليقات', 'يجب أن يكون التطبيق سهلاً في الاستخدام', 'الاختبار فشل - النتيجة غير متوقعة', 'يجب أن يكون المستخدمون جاهزين للاختبار', 'بيانات المستخدم الحقيقية', 'Development', 'ملاحظات اختبار سهولة الاستخدام - فشل الاختبار يحتاج لإصلاح', 411, '2025-09-24 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(19, 'QA-2025-0019', 'اختبار وحدة نظام المصادقة', 'اختبار وحدة البرمجة للتأكد من عمل الدالة بشكل صحيح', NULL, 3, 1, 'unit', 'failed', 'low', '1. إعداد البيانات التجريبية\n2. استدعاء الدالة\n3. التحقق من النتيجة\n4. تنظيف البيانات', 'يجب أن تعيد الدالة النتيجة المتوقعة', 'الاختبار فشل - النتيجة غير متوقعة', 'يجب أن تكون البيئة مهيأة والدوال متاحة', 'بيانات تجريبية للدالة', 'Staging', 'ملاحظات اختبار الوحدة - فشل الاختبار يحتاج لإصلاح', 375, '2025-10-05 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(20, 'QA-2025-0020', 'اختبار أمان المصادقة', 'اختبار أمان النظام وحماية البيانات', NULL, 3, 3, 'security', 'pending', 'critical', '1. إعداد أدوات الأمان\n2. محاولة الاختراق\n3. مراقبة الحماية\n4. تقييم النتائج', 'يجب أن يكون النظام محمياً من التهديدات', NULL, 'يجب أن تكون أدوات الأمان متاحة', 'بيانات حساسة للاختبار', 'Testing', 'ملاحظات اختبار الأمان', NULL, NULL, '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(21, 'QA-2025-0021', 'اختبار تكامل نظام الإشعارات', 'اختبار تكامل بين المكونات المختلفة للنظام', NULL, 1, 3, 'integration', 'skipped', 'medium', '1. إعداد البيئة\n2. تشغيل المكونات\n3. التحقق من التكامل\n4. اختبار التفاعل', 'يجب أن تعمل المكونات معاً بشكل صحيح', 'تم تخطي الاختبار لسبب تقني', 'يجب أن تكون جميع المكونات متاحة', 'بيانات تكامل بين المكونات', 'Staging', 'ملاحظات اختبار التكامل', 360, '2025-09-18 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(22, 'QA-2025-0022', 'اختبار أمان الجلسات', 'اختبار أمان النظام وحماية البيانات', NULL, 3, 3, 'security', 'passed', 'medium', '1. إعداد أدوات الأمان\n2. محاولة الاختراق\n3. مراقبة الحماية\n4. تقييم النتائج', 'يجب أن يكون النظام محمياً من التهديدات', 'الاختبار نجح كما هو متوقع', 'يجب أن تكون أدوات الأمان متاحة', 'بيانات حساسة للاختبار', 'Local', 'ملاحظات اختبار الأمان - نجح الاختبار بنجاح', 493, '2025-09-21 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21');

-- --------------------------------------------------------

--
-- بنية الجدول `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(2, 'admin', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(3, 'manager', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(4, 'employee', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(5, 'hr', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(6, 'accountant', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(7, 'sales_rep', 'web', '2025-10-10 14:38:08', '2025-10-10 14:38:08'),
(8, 'project_manager', 'web', '2025-10-11 12:24:53', '2025-10-11 12:24:53'),
(9, 'support', 'web', '2025-10-11 12:24:54', '2025-10-11 12:24:54'),
(10, 'developer', 'web', '2025-10-11 12:24:54', '2025-10-11 12:24:54'),
(11, 'designer', 'web', '2025-10-11 12:24:54', '2025-10-11 12:24:54');

-- --------------------------------------------------------

--
-- بنية الجدول `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1),
(82, 1),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(1, 2),
(2, 2),
(3, 2),
(5, 2),
(6, 2),
(7, 2),
(10, 2),
(11, 2),
(12, 2),
(14, 2),
(15, 2),
(16, 2),
(17, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 2),
(22, 2),
(23, 2),
(24, 2),
(25, 2),
(26, 2),
(27, 2),
(29, 2),
(30, 2),
(31, 2),
(32, 2),
(34, 2),
(36, 2),
(39, 2),
(40, 2),
(41, 2),
(43, 2),
(44, 2),
(45, 2),
(47, 2),
(48, 2),
(49, 2),
(50, 2),
(52, 2),
(53, 2),
(54, 2),
(56, 2),
(57, 2),
(58, 2),
(60, 2),
(61, 2),
(62, 2),
(64, 2),
(65, 2),
(66, 2),
(68, 2),
(69, 2),
(70, 2),
(72, 2),
(73, 2),
(74, 2),
(75, 2),
(76, 2),
(77, 2),
(79, 2),
(80, 2),
(81, 2),
(82, 2),
(83, 2),
(84, 2),
(85, 2),
(86, 2),
(87, 2),
(92, 2),
(5, 3),
(9, 3),
(10, 3),
(11, 3),
(13, 3),
(14, 3),
(15, 3),
(17, 3),
(18, 3),
(19, 3),
(21, 3),
(22, 3),
(23, 3),
(29, 3),
(30, 3),
(31, 3),
(15, 4),
(17, 4),
(31, 4),
(35, 4),
(37, 4),
(60, 4),
(61, 4),
(79, 4),
(83, 4),
(1, 5),
(2, 5),
(3, 5),
(5, 5),
(6, 5),
(7, 5),
(29, 5),
(30, 5),
(31, 5),
(39, 5),
(40, 5),
(41, 5),
(43, 5),
(44, 5),
(45, 5),
(47, 5),
(48, 5),
(49, 5),
(50, 5),
(79, 5),
(80, 5),
(81, 5),
(82, 5),
(83, 5),
(84, 5),
(85, 5),
(86, 5),
(87, 5),
(25, 6),
(26, 6),
(27, 6),
(28, 6),
(29, 6),
(30, 6),
(31, 6),
(38, 6),
(48, 6),
(52, 6),
(53, 6),
(54, 6),
(56, 6),
(76, 6),
(77, 6),
(17, 7),
(18, 7),
(19, 7),
(21, 7),
(22, 7),
(23, 7),
(31, 7),
(52, 7),
(53, 7),
(56, 7),
(57, 7),
(5, 8),
(10, 8),
(11, 8),
(14, 8),
(15, 8),
(17, 8),
(19, 8),
(29, 8),
(30, 8),
(31, 8),
(34, 8),
(36, 8),
(60, 8),
(61, 8),
(62, 8),
(64, 8),
(65, 8),
(66, 8),
(79, 8),
(80, 8),
(81, 8),
(83, 8),
(84, 8),
(85, 8),
(17, 9),
(31, 9),
(60, 9),
(61, 9),
(68, 9),
(69, 9),
(70, 9),
(15, 10),
(31, 10),
(35, 10),
(37, 10),
(60, 10),
(61, 10),
(62, 10),
(64, 10),
(65, 10),
(66, 10),
(15, 11),
(17, 11),
(31, 11),
(35, 11),
(37, 11);

-- --------------------------------------------------------

--
-- بنية الجدول `salaries`
--

CREATE TABLE `salaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `base_salary` decimal(10,2) NOT NULL,
  `overtime_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bonus` decimal(10,2) NOT NULL DEFAULT 0.00,
  `allowances` decimal(10,2) NOT NULL DEFAULT 0.00,
  `deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_salary` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','paid') NOT NULL DEFAULT 'pending',
  `payment_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `salaries`
--

INSERT INTO `salaries` (`id`, `employee_id`, `month`, `year`, `base_salary`, `overtime_amount`, `bonus`, `allowances`, `deductions`, `tax`, `net_salary`, `status`, `payment_date`, `notes`, `approved_by`, `created_at`, `updated_at`) VALUES
(1, 1, 10, 2025, 50000.00, 0.00, 3500.00, 5000.00, 1000.00, 5850.00, 51650.00, 'pending', NULL, 'راتب أكتوبر 2025 - معدل الحضور: -43.1%', NULL, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(2, 2, 10, 2025, 30000.00, 0.00, 0.00, 3000.00, 0.00, 3300.00, 29700.00, 'pending', NULL, 'راتب أكتوبر 2025 - معدل الحضور: -41.6%', NULL, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(3, 1, 9, 2025, 50000.00, 0.00, 0.00, 5000.00, 1000.00, 5500.00, 48500.00, 'approved', NULL, 'راتب سبتمبر 2025 - معدل الحضور: -43.3%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(4, 2, 9, 2025, 30000.00, 0.00, 0.00, 3000.00, 0.00, 3300.00, 29700.00, 'paid', '2025-09-29', 'راتب سبتمبر 2025 - معدل الحضور: -51.4%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(5, 1, 8, 2025, 50000.00, 0.00, 2500.00, 5000.00, 0.00, 5750.00, 51750.00, 'paid', '2025-08-29', 'راتب أغسطس 2025 - معدل الحضور: 81%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(6, 2, 8, 2025, 30000.00, 0.00, 1500.00, 3000.00, 0.00, 3450.00, 31050.00, 'paid', '2025-08-26', 'راتب أغسطس 2025 - معدل الحضور: 84%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(7, 1, 7, 2025, 50000.00, 0.00, 8000.00, 5000.00, 0.00, 6300.00, 56700.00, 'paid', '2025-07-29', 'راتب يوليو 2025 - معدل الحضور: 81%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(8, 2, 7, 2025, 30000.00, 0.00, 4200.00, 3000.00, 0.00, 3720.00, 33480.00, 'paid', '2025-07-30', 'راتب يوليو 2025 - معدل الحضور: 87%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(9, 1, 6, 2025, 50000.00, 0.00, 6000.00, 5000.00, 0.00, 6100.00, 54900.00, 'paid', '2025-06-25', 'راتب يونيو 2025 - معدل الحضور: 83%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(10, 2, 6, 2025, 30000.00, 0.00, 1500.00, 3000.00, 0.00, 3450.00, 31050.00, 'paid', '2025-06-26', 'راتب يونيو 2025 - معدل الحضور: 84%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(11, 1, 5, 2025, 50000.00, 0.00, 7500.00, 5000.00, 0.00, 6250.00, 56250.00, 'paid', '2025-05-26', 'راتب مايو 2025 - معدل الحضور: 83%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(12, 2, 5, 2025, 30000.00, 0.00, 5700.00, 3000.00, 0.00, 3870.00, 34830.00, 'paid', '2025-05-28', 'راتب مايو 2025 - معدل الحضور: 80%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(13, 1, 11, 2025, 50000.00, 0.00, 0.00, 2500.00, 0.00, 5250.00, 47250.00, 'pending', NULL, 'راتب نوفمبر 2025', NULL, '2025-11-04 21:50:53', '2025-11-04 21:50:53'),
(14, 2, 11, 2025, 30000.00, 0.00, 0.00, 1500.00, 0.00, 3150.00, 28350.00, 'approved', NULL, 'راتب نوفمبر 2025', 1, '2025-11-04 21:50:53', '2025-11-25 11:38:00'),
(16, 6, 11, 2025, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', NULL, 'راتب نوفمبر 2025', NULL, '2025-11-04 21:50:53', '2025-11-04 21:50:53'),
(17, 1, 12, 2025, 50000.00, 0.00, 0.00, 2500.00, 0.00, 5250.00, 47250.00, 'pending', NULL, 'راتب ديسمبر 2025', NULL, '2025-12-02 10:18:41', '2025-12-02 10:18:41'),
(18, 2, 12, 2025, 30000.00, 0.00, 0.00, 1500.00, 0.00, 3150.00, 28350.00, 'pending', NULL, 'راتب ديسمبر 2025', NULL, '2025-12-02 10:18:41', '2025-12-02 10:18:41'),
(20, 6, 12, 2025, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 'pending', NULL, 'راتب ديسمبر 2025', NULL, '2025-12-02 10:18:41', '2025-12-02 10:18:41');

-- --------------------------------------------------------

--
-- بنية الجدول `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lead_source` varchar(255) DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_to` bigint(20) UNSIGNED NOT NULL,
  `product_service` varchar(255) NOT NULL,
  `estimated_value` decimal(15,2) NOT NULL,
  `actual_value` decimal(15,2) DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `stage` enum('lead','prospect','proposal','negotiation','closed_won','closed_lost') NOT NULL DEFAULT 'lead',
  `probability_percentage` int(11) NOT NULL DEFAULT 0,
  `expected_close_date` date DEFAULT NULL,
  `actual_close_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `competitors` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`competitors`)),
  `decision_makers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`decision_makers`)),
  `project_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('A10siAks34CkqzfVATTAD2S3Os6fxuUuVKCADNhJ', 1, '41.41.126.72', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoickdRS2k4U2V4akh5dnE5dzJidzN1OXM2NGNTNklZckRuT2JsWURPZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8vc3lzdGVtLnMwbHZlc3RhLmNvbS92ZXJpZnktY29kZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoyMDoidmVyaWZpY2F0aW9uX3BlbmRpbmciO2I6MTt9', 1777118574),
('daxFbdjoVPBmLT0tbRYPBSdh0VBog4lG10SMeKEm', NULL, '2001:4860:7:140c::fa', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVW5jaHFoT1l3dEtCWGRDUm9hamxMNkJLMUtQM3V5TzdCYVIwdjk2YyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHBzOi8vc3lzdGVtLnMwbHZlc3RhLmNvbSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1777292800),
('f0t1yjFwwlTnbSX5R7ijbttn3JxG2y1lmHFGd2ln', 1, '41.41.126.72', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRXdraW1VV0ZTYnU2Q1kyR3ZUS0x5MHNENkY1dFZHNldoN1lMUGdHVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTQ6Imh0dHBzOi8vc3lzdGVtLnMwbHZlc3RhLmNvbS9ub3RpZmljYXRpb25zL3VucmVhZC1jb3VudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czo4OiJ2ZXJpZmllZCI7YjoxO30=', 1777119495),
('Jcke5Vxx7Xb72Y44iDJ9tnsiEmXWm2ncBONrSSVC', NULL, '2a02:4780:a:c0de::2', 'Go-http-client/2.0', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiUG1oZmg2d1NPQjJRblBta09BcTgyT3ZqdnlxNVFZamtBaENyVEhkayI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1777271163),
('MXHv8MeRFn165YIbr58NqwtkrGIBf17xq7p3TOMu', NULL, '197.42.7.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieFZYZ016T0JxamJERWhZS0ZWV0J5TEpiYXFkVzFTNFBtZzZpcDNsMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHBzOi8vc3lzdGVtLnMwbHZlc3RhLmNvbSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1777232277),
('NL0j9CSoSoAvD0Zuy0BXmMY4hk3Xxd4J2ATgNrM0', NULL, '2a02:4780:a:c0de::2', 'Go-http-client/2.0', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiOGh4WEgyM1B2cXp3dHk3WmtWN2VwMWdyRGFGR3VFRmJVWDE5b205ZSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1777283508),
('xKnQURUxq5zFZG3lGnJjhdT2pTvcVQ6WiNzCzvec', NULL, '2a02:4780:a:c0de::2', 'Go-http-client/2.0', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiTDBwZ2hjVUdGMXNYQmFYT2NzSFV3N2RZT29NUUd3bkVleTBUQVdtQSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1777187857);

-- --------------------------------------------------------

--
-- بنية الجدول `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'string',
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `description` text DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `system_settings`
--

INSERT INTO `system_settings` (`id`, `key`, `value`, `type`, `group`, `description`, `is_public`, `created_at`, `updated_at`) VALUES
(1, 'system_name', 'Solvesta Company', 'string', 'general', 'اسم النظام', 1, '2025-10-10 16:53:51', '2025-11-04 18:13:16'),
(2, 'system_description', 'نظام شامل لإدارة العمليات التجارية والموارد البشرية', 'text', 'general', 'وصف النظام', 1, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(3, 'company_name', 'Solvesta Company', 'string', 'general', 'اسم الشركة', 1, '2025-10-10 16:53:51', '2025-11-04 18:13:16'),
(4, 'company_address', 'القاهرة المعادي شارع 9\r\nالقاهرة مدينة نصر عباس العقاد\r\nالمنصورة حي الجامعة \r\nدمياط المنطقة المركزية', 'text', 'general', 'عنوان الشركة', 1, '2025-10-10 16:53:51', '2025-11-04 23:22:39'),
(5, 'company_phone', '01044610510', 'string', 'general', 'هاتف الشركة', 1, '2025-10-10 16:53:51', '2025-11-04 18:09:00'),
(6, 'company_email', 'info@solvesta.com', 'string', 'general', 'البريد الإلكتروني للشركة', 1, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(7, 'logo', 'logos/1762280922_690a45dac8977.png', 'file', 'appearance', 'شعار الشركة', 1, '2025-10-10 16:53:51', '2025-11-04 18:28:42'),
(8, 'favicon', 'favicons/1762280500_690a44345b7af.png', 'file', 'appearance', 'أيقونة المتصفح', 1, '2025-10-10 16:53:51', '2025-11-04 18:21:40'),
(9, 'theme_color', '#2563eb', 'string', 'appearance', 'اللون الرئيسي للنظام', 1, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(10, 'sidebar_color', '#1f2937', 'string', 'appearance', 'لون الشريط الجانبي', 1, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(11, 'timezone', 'Asia/Riyadh', 'string', 'system', 'المنطقة الزمنية', 0, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(12, 'language', 'ar', 'string', 'system', 'لغة النظام', 0, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(13, 'date_format', 'Y-m-d', 'string', 'system', 'تنسيق التاريخ', 0, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(14, 'time_format', 'H:i', 'string', 'system', 'تنسيق الوقت', 0, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(15, 'email_notifications', '1', 'boolean', 'notifications', 'تفعيل إشعارات البريد الإلكتروني', 0, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(16, 'sms_notifications', '0', 'boolean', 'notifications', 'تفعيل إشعارات الرسائل النصية', 0, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(17, 'push_notifications', '1', 'boolean', 'notifications', 'تفعيل الإشعارات الفورية', 0, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(18, 'logo_size', 'medium', 'string', 'appearance', 'حجم اللوجو في الشريط الجانبي', 1, '2025-10-10 17:11:39', '2025-10-10 17:11:39'),
(19, 'bank_name', 'بنك مصر', 'string', 'general', 'إعداد مخصص', 1, '2025-11-03 22:54:25', '2025-11-04 21:31:38'),
(20, 'bank_account_number', '8450383000000162', 'string', 'general', 'إعداد مخصص', 1, '2025-11-03 22:54:25', '2025-11-04 21:31:38'),
(21, 'bank_iban', 'EG120002084508450383000000162', 'string', 'general', 'إعداد مخصص', 1, '2025-11-03 22:54:25', '2025-11-04 23:22:39'),
(22, 'bank_account_holder', 'Mohamed Hany Refaat', 'string', 'general', 'إعداد مخصص', 1, '2025-11-03 22:54:25', '2025-11-04 21:31:38'),
(23, 'bank_swift', 'BMISEGCXXXX', 'string', 'general', 'إعداد مخصص', 1, '2025-11-03 22:54:25', '2025-11-04 21:31:38'),
(24, 'bank_branch', 'دمياط', 'string', 'general', 'إعداد مخصص', 1, '2025-11-03 22:54:25', '2025-11-04 21:31:38'),
(25, 'payment_methods', 'تحويل بنكي او كاش', 'string', 'general', 'إعداد مخصص', 1, '2025-11-03 22:54:25', '2025-11-04 23:22:39'),
(26, 'default_payment_period', '30', 'string', 'general', 'إعداد مخصص', 1, '2025-11-03 22:54:25', '2025-11-03 22:54:25'),
(27, 'default_tax_rate', '2', 'string', 'general', 'إعداد مخصص', 1, '2025-11-03 22:54:25', '2025-11-04 23:22:39'),
(28, 'tax_number', NULL, 'string', 'general', 'إعداد مخصص', 1, '2025-11-03 22:54:25', '2025-11-03 22:54:25'),
(29, 'commercial_registration', NULL, 'string', 'general', 'إعداد مخصص', 1, '2025-11-03 22:54:25', '2025-11-03 22:54:25'),
(30, 'invoice_financial_notes', NULL, 'string', 'general', 'إعداد مخصص', 1, '2025-11-03 22:54:25', '2025-11-03 22:54:25'),
(31, 'whatsapp_default_number', '201044610510', 'string', 'whatsapp', 'رقم الواتساب الافتراضي للإرسال', 1, '2025-11-03 22:54:25', '2025-11-03 22:54:25'),
(32, 'whatsapp_enabled', '1', 'boolean', 'whatsapp', 'تفعيل إرسال رسائل الواتساب', 1, '2025-11-03 22:54:25', '2025-11-03 22:54:25'),
(33, 'whatsapp_auto_open', '1', 'boolean', 'whatsapp', 'فتح الواتساب تلقائياً عند الإرسال', 1, '2025-11-03 22:54:25', '2025-11-03 22:54:25');

-- --------------------------------------------------------

--
-- بنية الجدول `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_to` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `parent_task_id` bigint(20) UNSIGNED DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `status` enum('todo','in_progress','review','completed','cancelled') NOT NULL DEFAULT 'todo',
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `estimated_hours` int(11) DEFAULT NULL,
  `actual_hours` int(11) DEFAULT NULL,
  `progress_percentage` int(11) NOT NULL DEFAULT 0,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `project_id`, `assigned_to`, `created_by`, `parent_task_id`, `due_date`, `start_date`, `status`, `priority`, `estimated_hours`, `actual_hours`, `progress_percentage`, `tags`, `attachments`, `created_at`, `updated_at`) VALUES
(1, 'تصميم المذكرة', 'تم طلب تصميم مذكرة تتكون 220 ورقة كحد ادني للتصميم المطلوب تسليم هذه المذكرة علي نهاية هذا الشهر', 8, 5, 1, NULL, '2025-11-30', '2025-11-01', 'in_progress', 'high', 30, NULL, 0, NULL, NULL, '2025-11-04 21:19:42', '2025-11-04 21:19:42');

-- --------------------------------------------------------

--
-- بنية الجدول `task_updates`
--

CREATE TABLE `task_updates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `type` enum('comment','update') NOT NULL DEFAULT 'comment',
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `tax_records`
--

CREATE TABLE `tax_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tax_type` varchar(50) NOT NULL,
  `tax_period` varchar(255) NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `taxable_amount` decimal(12,2) NOT NULL,
  `tax_rate` decimal(5,2) NOT NULL,
  `tax_amount` decimal(12,2) NOT NULL,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `due_date` date NOT NULL,
  `payment_date` date DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'calculated',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_number` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `category` enum('technical','billing','general','bug_report','feature_request') NOT NULL,
  `status` enum('open','in_progress','pending_client','resolved','closed') NOT NULL DEFAULT 'open',
  `sla_hours` int(11) DEFAULT NULL,
  `first_response_time` timestamp NULL DEFAULT NULL,
  `resolution_time` timestamp NULL DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `resolution_notes` text DEFAULT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `trainings`
--

CREATE TABLE `trainings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` enum('internal','external','online','workshop','seminar') NOT NULL,
  `status` enum('planned','ongoing','completed','cancelled') NOT NULL DEFAULT 'planned',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `max_participants` int(11) NOT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `instructor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `training_participants`
--

CREATE TABLE `training_participants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `training_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('registered','attended','completed','cancelled') NOT NULL DEFAULT 'registered',
  `attendance_rate` decimal(5,2) DEFAULT NULL,
  `grade` decimal(5,2) DEFAULT NULL,
  `certificate_issued` tinyint(1) NOT NULL DEFAULT 0,
  `certificate_issued_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `profile_picture`) VALUES
(1, 'Solvesta Company', 'loransmogay@gmail.com', '2025-10-10 14:38:09', '$2y$12$o52oHUUS4XvybcMe.OD9z.0c0Xdyviku1tkAWqHBA15FU1HJBeJdq', 'AQj9bIbo2CJcOo9Nb7Mla28MO9504iiQUxzXDIpDU0gZOZU6po1TEEE5LLT0', '2025-10-10 14:38:09', '2026-02-15 09:11:42', 'profile-pictures/EVFFdBKaGkltqJ4HVDd45IcMF99J8gyFYoCIILUD.png'),
(3, 'mohamed hany', 'codermohamedhany@gmail.com', NULL, '$2y$12$ZG/ooZv2uB.VFTL6X/VZ2ugD/lWHq7Z73K63JuI7YvoYeN6E.6mdS', 'gFyTqloEYwP7NZVH1423MZ6oWroydr93BXMLNyCwVlpjKeiQFrqCrAxv1NkB', '2025-10-11 12:15:36', '2025-11-03 22:34:20', NULL),
(5, 'محمود ايمن', 'ma8819496@gmail.com', NULL, '$2y$12$lf6kKP8oIabbn1IhSD4gsOxIABC5IvvpMCvbGn/rZgQJjGADE8oPm', '9Js0SSYU3lQ12YhWFCheKoxRkL0ugsp6EbScHM32eAx23meHFOXmZfK8Mszh', '2025-11-04 21:01:03', '2025-11-04 21:01:03', NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `user_permissions`
--

CREATE TABLE `user_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `permission_key` varchar(255) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `custom_settings` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `user_permissions`
--

INSERT INTO `user_permissions` (`id`, `user_id`, `permission_key`, `is_enabled`, `custom_settings`, `created_at`, `updated_at`) VALUES
(191, 1, 'sidebar_dashboard', 1, '{\"description\":\"\\u0644\\u0648\\u062d\\u0629 \\u0627\\u0644\\u062a\\u062d\\u0643\\u0645\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(192, 1, 'sidebar_administration', 1, '{\"description\":\"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u0625\\u062f\\u0627\\u0631\\u0629 \\u0627\\u0644\\u0639\\u0644\\u064a\\u0627\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(193, 1, 'sidebar_hr', 1, '{\"description\":\"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u0645\\u0648\\u0627\\u0631\\u062f \\u0627\\u0644\\u0628\\u0634\\u0631\\u064a\\u0629\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(194, 1, 'sidebar_projects', 1, '{\"description\":\"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u064a\\u0639\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(195, 1, 'sidebar_operations', 1, '{\"description\":\"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u0639\\u0645\\u0644\\u064a\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(196, 1, 'sidebar_development', 1, '{\"description\":\"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u062a\\u0637\\u0648\\u064a\\u0631 \\u0648\\u0627\\u0644\\u0628\\u0631\\u0645\\u062c\\u0629\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(197, 1, 'sidebar_design', 1, '{\"description\":\"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u062a\\u0635\\u0645\\u064a\\u0645\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(198, 1, 'sidebar_business', 1, '{\"description\":\"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u0645\\u0628\\u064a\\u0639\\u0627\\u062a \\u0648\\u0627\\u0644\\u062a\\u0633\\u0648\\u064a\\u0642\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(199, 1, 'sidebar_support', 1, '{\"description\":\"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u062f\\u0639\\u0645 \\u0627\\u0644\\u0641\\u0646\\u064a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(200, 1, 'sidebar_finance', 1, '{\"description\":\"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629 \\u0648\\u0627\\u0644\\u0645\\u062d\\u0627\\u0633\\u0628\\u0629\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(201, 1, 'sidebar_legal', 1, '{\"description\":\"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u0634\\u0624\\u0648\\u0646 \\u0627\\u0644\\u0642\\u0627\\u0646\\u0648\\u0646\\u064a\\u0629\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(202, 1, 'sidebar_users', 1, '{\"description\":\"\\u0627\\u0644\\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\\u064a\\u0646\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(203, 1, 'sidebar_reports', 1, '{\"description\":\"\\u0627\\u0644\\u062a\\u0642\\u0627\\u0631\\u064a\\u0631 \\u0648\\u0627\\u0644\\u062a\\u062d\\u0644\\u064a\\u0644\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(204, 1, 'sidebar_departments', 1, '{\"description\":\"\\u0627\\u0644\\u0623\\u0642\\u0633\\u0627\\u0645\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(205, 1, 'sidebar_system_settings', 1, '{\"description\":\"\\u0625\\u0639\\u062f\\u0627\\u062f\\u0627\\u062a \\u0627\\u0644\\u0646\\u0638\\u0627\\u0645\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(206, 1, 'sidebar_employees', 1, '{\"description\":\"\\u0627\\u0644\\u0645\\u0648\\u0638\\u0641\\u064a\\u0646\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(207, 1, 'sidebar_attendances', 1, '{\"description\":\"\\u0627\\u0644\\u062d\\u0636\\u0648\\u0631 \\u0648\\u0627\\u0644\\u0627\\u0646\\u0635\\u0631\\u0627\\u0641\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(208, 1, 'sidebar_leaves', 1, '{\"description\":\"\\u0627\\u0644\\u0625\\u062c\\u0627\\u0632\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(209, 1, 'sidebar_salaries', 1, '{\"description\":\"\\u0627\\u0644\\u0631\\u0648\\u0627\\u062a\\u0628\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(210, 1, 'sidebar_projects_list', 1, '{\"description\":\"\\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u064a\\u0639\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(211, 1, 'sidebar_tasks', 1, '{\"description\":\"\\u0627\\u0644\\u0645\\u0647\\u0627\\u0645\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(212, 1, 'sidebar_bugs', 1, '{\"description\":\"\\u0627\\u0644\\u0623\\u062e\\u0637\\u0627\\u0621\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(213, 1, 'sidebar_qa', 1, '{\"description\":\"\\u0636\\u0645\\u0627\\u0646 \\u0627\\u0644\\u062c\\u0648\\u062f\\u0629\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(214, 1, 'sidebar_design_page', 1, '{\"description\":\"\\u0627\\u0644\\u062a\\u0635\\u0645\\u064a\\u0645\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(215, 1, 'sidebar_clients', 1, '{\"description\":\"\\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(216, 1, 'sidebar_sales', 1, '{\"description\":\"\\u0627\\u0644\\u0645\\u0628\\u064a\\u0639\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(217, 1, 'sidebar_marketing', 1, '{\"description\":\"\\u0627\\u0644\\u062a\\u0633\\u0648\\u064a\\u0642\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(218, 1, 'sidebar_tickets', 1, '{\"description\":\"\\u0627\\u0644\\u062a\\u0630\\u0627\\u0643\\u0631\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(219, 1, 'sidebar_invoices', 1, '{\"description\":\"\\u0627\\u0644\\u0641\\u0648\\u0627\\u062a\\u064a\\u0631\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(220, 1, 'sidebar_contracts', 1, '{\"description\":\"\\u0627\\u0644\\u0639\\u0642\\u0648\\u062f\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(221, 1, 'sidebar_accounting_dashboard', 1, '{\"description\":\"\\u0644\\u0648\\u062d\\u0629 \\u0627\\u0644\\u062a\\u062d\\u0643\\u0645 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(222, 1, 'sidebar_accounts_tree', 1, '{\"description\":\"\\u0634\\u062c\\u0631\\u0629 \\u0627\\u0644\\u062d\\u0633\\u0627\\u0628\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(223, 1, 'sidebar_journal_entries', 1, '{\"description\":\"\\u0627\\u0644\\u0642\\u064a\\u0648\\u062f \\u0627\\u0644\\u0645\\u062d\\u0627\\u0633\\u0628\\u064a\\u0629\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(224, 1, 'sidebar_financial_invoices', 1, '{\"description\":\"\\u0627\\u0644\\u0641\\u0648\\u0627\\u062a\\u064a\\u0631 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(225, 1, 'sidebar_payments', 1, '{\"description\":\"\\u0627\\u0644\\u0645\\u062f\\u0641\\u0648\\u0639\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(226, 1, 'sidebar_expenses', 1, '{\"description\":\"\\u0627\\u0644\\u0645\\u0635\\u0631\\u0648\\u0641\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(227, 1, 'sidebar_financial_reports', 1, '{\"description\":\"\\u0627\\u0644\\u062a\\u0642\\u0627\\u0631\\u064a\\u0631 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(228, 1, 'sidebar_project_invoices', 1, '{\"description\":\"\\u0641\\u0648\\u0627\\u062a\\u064a\\u0631 \\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u064a\\u0639\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(229, 1, 'dashboard_employees_count', 1, '{\"description\":\"\\u0639\\u062f\\u062f \\u0627\\u0644\\u0645\\u0648\\u0638\\u0641\\u064a\\u0646\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(230, 1, 'dashboard_clients_count', 1, '{\"description\":\"\\u0639\\u062f\\u062f \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(231, 1, 'dashboard_projects_count', 1, '{\"description\":\"\\u0639\\u062f\\u062f \\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u064a\\u0639\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(232, 1, 'dashboard_tasks_count', 1, '{\"description\":\"\\u0639\\u062f\\u062f \\u0627\\u0644\\u0645\\u0647\\u0627\\u0645\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(233, 1, 'dashboard_revenue', 1, '{\"description\":\"\\u0627\\u0644\\u0625\\u064a\\u0631\\u0627\\u062f\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(234, 1, 'dashboard_attendance_rate', 1, '{\"description\":\"\\u0645\\u0639\\u062f\\u0644 \\u0627\\u0644\\u062d\\u0636\\u0648\\u0631\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(235, 1, 'dashboard_recent_activities', 1, '{\"description\":\"\\u0627\\u0644\\u0623\\u0646\\u0634\\u0637\\u0629 \\u0627\\u0644\\u0623\\u062e\\u064a\\u0631\\u0629\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(236, 1, 'dashboard_quick_actions', 1, '{\"description\":\"\\u0627\\u0644\\u0625\\u062c\\u0631\\u0627\\u0621\\u0627\\u062a \\u0627\\u0644\\u0633\\u0631\\u064a\\u0639\\u0629\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(237, 1, 'dashboard_charts', 1, '{\"description\":\"\\u0627\\u0644\\u0631\\u0633\\u0648\\u0645 \\u0627\\u0644\\u0628\\u064a\\u0627\\u0646\\u064a\\u0629\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(238, 1, 'page_users_view', 1, '{\"description\":\"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\\u064a\\u0646\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(239, 1, 'page_users_create', 1, '{\"description\":\"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\\u064a\\u0646\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(240, 1, 'page_users_edit', 1, '{\"description\":\"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0627\\u0644\\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\\u064a\\u0646\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(241, 1, 'page_users_delete', 1, '{\"description\":\"\\u062d\\u0630\\u0641 \\u0627\\u0644\\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\\u064a\\u0646\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(242, 1, 'page_employees_view', 1, '{\"description\":\"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0645\\u0648\\u0638\\u0641\\u064a\\u0646\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(243, 1, 'page_employees_create', 1, '{\"description\":\"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0645\\u0648\\u0638\\u0641\\u064a\\u0646\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(244, 1, 'page_employees_edit', 1, '{\"description\":\"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0627\\u0644\\u0645\\u0648\\u0638\\u0641\\u064a\\u0646\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(245, 1, 'page_employees_delete', 1, '{\"description\":\"\\u062d\\u0630\\u0641 \\u0627\\u0644\\u0645\\u0648\\u0638\\u0641\\u064a\\u0646\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(246, 1, 'page_projects_view', 1, '{\"description\":\"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u064a\\u0639\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(247, 1, 'page_projects_create', 1, '{\"description\":\"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0645\\u0634\\u0627\\u0631\\u064a\\u0639\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(248, 1, 'page_projects_edit', 1, '{\"description\":\"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u064a\\u0639\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(249, 1, 'page_projects_delete', 1, '{\"description\":\"\\u062d\\u0630\\u0641 \\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u064a\\u0639\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(250, 1, 'page_tasks_view', 1, '{\"description\":\"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0645\\u0647\\u0627\\u0645\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(251, 1, 'page_tasks_create', 1, '{\"description\":\"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0645\\u0647\\u0627\\u0645\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(252, 1, 'page_tasks_edit', 1, '{\"description\":\"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0627\\u0644\\u0645\\u0647\\u0627\\u0645\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(253, 1, 'page_tasks_delete', 1, '{\"description\":\"\\u062d\\u0630\\u0641 \\u0627\\u0644\\u0645\\u0647\\u0627\\u0645\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(254, 1, 'page_clients_view', 1, '{\"description\":\"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(255, 1, 'page_clients_create', 1, '{\"description\":\"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0639\\u0645\\u0644\\u0627\\u0621\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(256, 1, 'page_clients_edit', 1, '{\"description\":\"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(257, 1, 'page_clients_delete', 1, '{\"description\":\"\\u062d\\u0630\\u0641 \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(258, 1, 'page_sales_view', 1, '{\"description\":\"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0645\\u0628\\u064a\\u0639\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(259, 1, 'page_sales_create', 1, '{\"description\":\"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0645\\u0628\\u064a\\u0639\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(260, 1, 'page_sales_edit', 1, '{\"description\":\"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0627\\u0644\\u0645\\u0628\\u064a\\u0639\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(261, 1, 'page_sales_delete', 1, '{\"description\":\"\\u062d\\u0630\\u0641 \\u0627\\u0644\\u0645\\u0628\\u064a\\u0639\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(262, 1, 'page_attendances_view', 1, '{\"description\":\"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u062d\\u0636\\u0648\\u0631\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(263, 1, 'page_attendances_create', 1, '{\"description\":\"\\u062a\\u0633\\u062c\\u064a\\u0644 \\u062d\\u0636\\u0648\\u0631\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(264, 1, 'page_attendances_edit', 1, '{\"description\":\"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u062d\\u0636\\u0648\\u0631\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(265, 1, 'page_attendances_delete', 1, '{\"description\":\"\\u062d\\u0630\\u0641 \\u062d\\u0636\\u0648\\u0631\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(266, 1, 'page_leaves_view', 1, '{\"description\":\"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0625\\u062c\\u0627\\u0632\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(267, 1, 'page_leaves_create', 1, '{\"description\":\"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0625\\u062c\\u0627\\u0632\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(268, 1, 'page_leaves_edit', 1, '{\"description\":\"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0625\\u062c\\u0627\\u0632\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(269, 1, 'page_leaves_delete', 1, '{\"description\":\"\\u062d\\u0630\\u0641 \\u0625\\u062c\\u0627\\u0632\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(270, 1, 'page_leaves_approve', 1, '{\"description\":\"\\u0627\\u0639\\u062a\\u0645\\u0627\\u062f \\u0625\\u062c\\u0627\\u0632\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(271, 1, 'page_salaries_view', 1, '{\"description\":\"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0631\\u0648\\u0627\\u062a\\u0628\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(272, 1, 'page_salaries_create', 1, '{\"description\":\"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0631\\u0648\\u0627\\u062a\\u0628\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(273, 1, 'page_salaries_edit', 1, '{\"description\":\"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0631\\u0648\\u0627\\u062a\\u0628\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(274, 1, 'page_salaries_delete', 1, '{\"description\":\"\\u062d\\u0630\\u0641 \\u0631\\u0648\\u0627\\u062a\\u0628\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(275, 1, 'page_accounting_view', 1, '{\"description\":\"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0645\\u062d\\u0627\\u0633\\u0628\\u0629\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(276, 1, 'page_accounts_create', 1, '{\"description\":\"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u062d\\u0633\\u0627\\u0628\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(277, 1, 'page_accounts_edit', 1, '{\"description\":\"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u062d\\u0633\\u0627\\u0628\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(278, 1, 'page_accounts_delete', 1, '{\"description\":\"\\u062d\\u0630\\u0641 \\u062d\\u0633\\u0627\\u0628\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(279, 1, 'page_journal_entries_create', 1, '{\"description\":\"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0642\\u064a\\u0648\\u062f \\u0645\\u062d\\u0627\\u0633\\u0628\\u064a\\u0629\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(280, 1, 'page_journal_entries_edit', 1, '{\"description\":\"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0642\\u064a\\u0648\\u062f \\u0645\\u062d\\u0627\\u0633\\u0628\\u064a\\u0629\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(281, 1, 'page_journal_entries_delete', 1, '{\"description\":\"\\u062d\\u0630\\u0641 \\u0642\\u064a\\u0648\\u062f \\u0645\\u062d\\u0627\\u0633\\u0628\\u064a\\u0629\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(282, 1, 'page_journal_entries_approve', 1, '{\"description\":\"\\u0627\\u0639\\u062a\\u0645\\u0627\\u062f \\u0642\\u064a\\u0648\\u062f \\u0645\\u062d\\u0627\\u0633\\u0628\\u064a\\u0629\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(283, 1, 'page_journal_entries_post', 1, '{\"description\":\"\\u062a\\u0631\\u062d\\u064a\\u0644 \\u0642\\u064a\\u0648\\u062f \\u0645\\u062d\\u0627\\u0633\\u0628\\u064a\\u0629\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(284, 1, 'page_financial_reports', 1, '{\"description\":\"\\u0627\\u0644\\u062a\\u0642\\u0627\\u0631\\u064a\\u0631 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(285, 1, 'page_salary_reports', 1, '{\"description\":\"\\u062a\\u0642\\u0627\\u0631\\u064a\\u0631 \\u0627\\u0644\\u0631\\u0648\\u0627\\u062a\\u0628\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(286, 1, 'page_attendance_reports', 1, '{\"description\":\"\\u062a\\u0642\\u0627\\u0631\\u064a\\u0631 \\u0627\\u0644\\u062d\\u0636\\u0648\\u0631\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(287, 1, 'page_project_reports', 1, '{\"description\":\"\\u062a\\u0642\\u0627\\u0631\\u064a\\u0631 \\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u064a\\u0639\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(288, 1, 'page_sales_reports', 1, '{\"description\":\"\\u062a\\u0642\\u0627\\u0631\\u064a\\u0631 \\u0627\\u0644\\u0645\\u0628\\u064a\\u0639\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(289, 1, 'page_tickets_view', 1, '{\"description\":\"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u062a\\u0630\\u0627\\u0643\\u0631\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(290, 1, 'page_tickets_create', 1, '{\"description\":\"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u062a\\u0630\\u0627\\u0643\\u0631\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(291, 1, 'page_tickets_edit', 1, '{\"description\":\"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u062a\\u0630\\u0627\\u0643\\u0631\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(292, 1, 'page_tickets_delete', 1, '{\"description\":\"\\u062d\\u0630\\u0641 \\u062a\\u0630\\u0627\\u0643\\u0631\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(293, 1, 'page_invoices_view', 1, '{\"description\":\"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0641\\u0648\\u0627\\u062a\\u064a\\u0631\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(294, 1, 'page_invoices_create', 1, '{\"description\":\"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0641\\u0648\\u0627\\u062a\\u064a\\u0631\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(295, 1, 'page_invoices_edit', 1, '{\"description\":\"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0641\\u0648\\u0627\\u062a\\u064a\\u0631\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(296, 1, 'page_invoices_delete', 1, '{\"description\":\"\\u062d\\u0630\\u0641 \\u0641\\u0648\\u0627\\u062a\\u064a\\u0631\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(297, 1, 'page_contracts_view', 1, '{\"description\":\"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0639\\u0642\\u0648\\u062f\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(298, 1, 'page_contracts_create', 1, '{\"description\":\"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0639\\u0642\\u0648\\u062f\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(299, 1, 'page_contracts_edit', 1, '{\"description\":\"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0639\\u0642\\u0648\\u062f\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(300, 1, 'page_contracts_delete', 1, '{\"description\":\"\\u062d\\u0630\\u0641 \\u0639\\u0642\\u0648\\u062f\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(301, 1, 'page_bugs_view', 1, '{\"description\":\"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0623\\u062e\\u0637\\u0627\\u0621\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(302, 1, 'page_bugs_create', 1, '{\"description\":\"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0623\\u062e\\u0637\\u0627\\u0621\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(303, 1, 'page_bugs_edit', 1, '{\"description\":\"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0623\\u062e\\u0637\\u0627\\u0621\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(304, 1, 'page_bugs_delete', 1, '{\"description\":\"\\u062d\\u0630\\u0641 \\u0623\\u062e\\u0637\\u0627\\u0621\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(305, 1, 'page_expenses_view', 1, '{\"description\":\"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0645\\u0635\\u0631\\u0648\\u0641\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(306, 1, 'page_expenses_create', 1, '{\"description\":\"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0645\\u0635\\u0631\\u0648\\u0641\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(307, 1, 'page_expenses_edit', 1, '{\"description\":\"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0645\\u0635\\u0631\\u0648\\u0641\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(308, 1, 'page_expenses_delete', 1, '{\"description\":\"\\u062d\\u0630\\u0641 \\u0645\\u0635\\u0631\\u0648\\u0641\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(309, 1, 'page_payments_view', 1, '{\"description\":\"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0645\\u062f\\u0641\\u0648\\u0639\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(310, 1, 'page_payments_create', 1, '{\"description\":\"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0645\\u062f\\u0641\\u0648\\u0639\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(311, 1, 'page_payments_edit', 1, '{\"description\":\"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0645\\u062f\\u0641\\u0648\\u0639\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(312, 1, 'page_payments_delete', 1, '{\"description\":\"\\u062d\\u0630\\u0641 \\u0645\\u062f\\u0641\\u0648\\u0639\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(313, 1, 'system_settings', 1, '{\"description\":\"\\u0625\\u0639\\u062f\\u0627\\u062f\\u0627\\u062a \\u0627\\u0644\\u0646\\u0638\\u0627\\u0645\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(314, 1, 'user_permissions_manage', 1, '{\"description\":\"\\u0625\\u062f\\u0627\\u0631\\u0629 \\u0635\\u0644\\u0627\\u062d\\u064a\\u0627\\u062a \\u0627\\u0644\\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\\u064a\\u0646\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(315, 1, 'roles_manage', 1, '{\"description\":\"\\u0625\\u062f\\u0627\\u0631\\u0629 \\u0627\\u0644\\u0623\\u062f\\u0648\\u0627\\u0631\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(316, 1, 'departments_manage', 1, '{\"description\":\"\\u0625\\u062f\\u0627\\u0631\\u0629 \\u0627\\u0644\\u0623\\u0642\\u0633\\u0627\\u0645\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(317, 1, 'export_data', 1, '{\"description\":\"\\u062a\\u0635\\u062f\\u064a\\u0631 \\u0627\\u0644\\u0628\\u064a\\u0627\\u0646\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(318, 1, 'import_data', 1, '{\"description\":\"\\u0627\\u0633\\u062a\\u064a\\u0631\\u0627\\u062f \\u0627\\u0644\\u0628\\u064a\\u0627\\u0646\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(319, 1, 'backup_database', 1, '{\"description\":\"\\u0646\\u0633\\u062e \\u0627\\u062d\\u062a\\u064a\\u0627\\u0637\\u064a \\u0644\\u0644\\u0628\\u064a\\u0627\\u0646\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(320, 1, 'restore_database', 1, '{\"description\":\"\\u0627\\u0633\\u062a\\u0639\\u0627\\u062f\\u0629 \\u0627\\u0644\\u0628\\u064a\\u0627\\u0646\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(321, 1, 'view_logs', 1, '{\"description\":\"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0633\\u062c\\u0644\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(322, 1, 'delete_logs', 1, '{\"description\":\"\\u062d\\u0630\\u0641 \\u0627\\u0644\\u0633\\u062c\\u0644\\u0627\\u062a\",\"granted_at\":\"2025-10-10 17:03:48\",\"granted_by\":\"System\"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(327, 3, 'create-qa', 0, NULL, '2025-11-03 22:53:40', '2025-11-03 22:53:40'),
(328, 3, 'edit-qa', 0, NULL, '2025-11-03 22:53:40', '2025-11-03 22:53:40'),
(329, 3, 'edit-tasks', 0, NULL, '2025-11-03 22:53:40', '2025-11-03 22:53:40'),
(333, 5, 'create-bugs', 0, NULL, '2025-11-04 21:03:36', '2025-11-04 21:03:36'),
(334, 5, 'edit-tasks', 0, NULL, '2025-11-04 21:03:36', '2025-11-04 21:03:36'),
(335, 5, 'view-bugs', 0, NULL, '2025-11-04 21:03:36', '2025-11-04 21:03:36'),
(336, 5, 'view-clients', 0, NULL, '2025-11-04 21:03:36', '2025-11-04 21:03:36');

-- --------------------------------------------------------

--
-- بنية الجدول `verification_codes`
--

CREATE TABLE `verification_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(6) NOT NULL,
  `expires_at` timestamp NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `verification_codes`
--

INSERT INTO `verification_codes` (`id`, `user_id`, `code`, `expires_at`, `used`, `used_at`, `created_at`, `updated_at`) VALUES
(1, 1, '201784', '2025-11-03 22:42:40', 1, '2025-11-03 22:33:04', '2025-11-03 22:32:40', '2025-11-03 22:33:04'),
(2, 3, '840043', '2025-11-03 22:44:30', 1, '2025-11-03 22:34:57', '2025-11-03 22:34:30', '2025-11-03 22:34:57'),
(3, 1, '268610', '2025-11-04 18:00:02', 1, '2025-11-04 17:50:17', '2025-11-04 17:50:02', '2025-11-04 17:50:17'),
(4, 1, '813623', '2025-11-04 18:39:24', 1, '2025-11-04 18:29:42', '2025-11-04 18:29:24', '2025-11-04 18:29:42'),
(12, 5, '160882', '2025-11-04 21:13:30', 1, NULL, '2025-11-04 21:03:30', '2025-11-04 21:04:27'),
(13, 5, '853678', '2025-11-04 21:14:27', 1, '2025-11-04 21:08:05', '2025-11-04 21:04:27', '2025-11-04 21:08:05'),
(14, 5, '361704', '2025-11-04 21:24:08', 1, NULL, '2025-11-04 21:14:08', '2025-11-04 21:15:51'),
(15, 5, '206878', '2025-11-04 21:25:51', 1, '2025-11-04 21:16:28', '2025-11-04 21:15:51', '2025-11-04 21:16:28'),
(17, 1, '445188', '2025-11-04 21:59:59', 1, '2025-11-04 21:50:12', '2025-11-04 21:49:59', '2025-11-04 21:50:12'),
(18, 1, '828383', '2025-11-05 06:59:56', 1, '2025-11-05 06:50:17', '2025-11-05 06:49:56', '2025-11-05 06:50:17'),
(19, 1, '793995', '2025-11-05 08:28:19', 1, '2025-11-05 08:18:39', '2025-11-05 08:18:19', '2025-11-05 08:18:39'),
(20, 1, '787560', '2025-11-06 20:11:48', 0, NULL, '2025-11-06 20:01:48', '2025-11-06 20:01:48'),
(21, 1, '264683', '2025-11-07 00:06:06', 1, '2025-11-06 23:56:33', '2025-11-06 23:56:06', '2025-11-06 23:56:33'),
(22, 1, '824021', '2025-11-09 14:41:48', 1, '2025-11-09 14:32:11', '2025-11-09 14:31:48', '2025-11-09 14:32:11'),
(23, 1, '937378', '2025-11-21 11:18:46', 1, '2025-11-21 11:09:03', '2025-11-21 11:08:46', '2025-11-21 11:09:03'),
(24, 1, '590723', '2025-11-22 01:22:49', 1, '2025-11-22 01:13:03', '2025-11-22 01:12:49', '2025-11-22 01:13:03'),
(25, 1, '080498', '2025-11-25 11:43:09', 1, '2025-11-25 11:33:25', '2025-11-25 11:33:09', '2025-11-25 11:33:25'),
(26, 1, '365563', '2025-12-02 08:26:29', 1, '2025-12-02 08:16:43', '2025-12-02 08:16:29', '2025-12-02 08:16:43'),
(27, 1, '815703', '2025-12-11 07:31:14', 1, '2025-12-11 07:21:30', '2025-12-11 07:21:14', '2025-12-11 07:21:30'),
(28, 1, '510767', '2025-12-12 21:18:04', 1, '2025-12-12 21:08:34', '2025-12-12 21:08:04', '2025-12-12 21:08:34'),
(29, 1, '674120', '2025-12-15 16:31:16', 1, '2025-12-15 16:21:31', '2025-12-15 16:21:16', '2025-12-15 16:21:31'),
(30, 1, '277971', '2025-12-16 17:07:52', 1, '2025-12-16 16:58:04', '2025-12-16 16:57:52', '2025-12-16 16:58:04'),
(31, 1, '271141', '2025-12-20 19:18:58', 1, '2025-12-20 19:09:13', '2025-12-20 19:08:58', '2025-12-20 19:09:13'),
(32, 1, '017665', '2025-12-21 10:58:49', 1, '2025-12-21 10:49:03', '2025-12-21 10:48:49', '2025-12-21 10:49:03'),
(33, 1, '562623', '2025-12-21 11:54:00', 1, '2025-12-21 11:44:15', '2025-12-21 11:44:00', '2025-12-21 11:44:15'),
(34, 1, '686422', '2025-12-21 22:48:31', 1, '2025-12-21 22:38:54', '2025-12-21 22:38:31', '2025-12-21 22:38:54'),
(35, 1, '484666', '2025-12-22 13:47:02', 1, '2025-12-22 13:37:21', '2025-12-22 13:37:02', '2025-12-22 13:37:21'),
(36, 1, '357262', '2025-12-22 18:22:10', 0, NULL, '2025-12-22 18:12:10', '2025-12-22 18:12:10'),
(37, 1, '689214', '2025-12-25 20:41:17', 1, '2025-12-25 20:31:30', '2025-12-25 20:31:17', '2025-12-25 20:31:30'),
(38, 1, '012343', '2025-12-26 19:34:51', 1, '2025-12-26 19:25:13', '2025-12-26 19:24:51', '2025-12-26 19:25:13'),
(39, 3, '956392', '2025-12-26 19:38:05', 1, '2025-12-26 19:28:59', '2025-12-26 19:28:05', '2025-12-26 19:28:59'),
(40, 3, '911757', '2025-12-27 01:29:06', 1, '2025-12-27 01:19:42', '2025-12-27 01:19:06', '2025-12-27 01:19:42'),
(41, 1, '999928', '2025-12-28 18:42:06', 0, NULL, '2025-12-28 18:32:06', '2025-12-28 18:32:06'),
(42, 1, '943798', '2025-12-31 18:18:33', 1, '2025-12-31 18:09:06', '2025-12-31 18:08:33', '2025-12-31 18:09:06'),
(43, 1, '348671', '2026-01-01 17:55:18', 1, '2026-01-01 17:45:36', '2026-01-01 17:45:18', '2026-01-01 17:45:36'),
(44, 1, '121127', '2026-01-14 14:19:50', 1, '2026-01-14 14:10:03', '2026-01-14 14:09:50', '2026-01-14 14:10:03'),
(45, 1, '859020', '2026-02-13 14:46:40', 1, '2026-02-13 14:36:56', '2026-02-13 14:36:40', '2026-02-13 14:36:56'),
(46, 1, '040696', '2026-02-15 09:21:05', 1, '2026-02-15 09:11:22', '2026-02-15 09:11:05', '2026-02-15 09:11:22'),
(47, 1, '792996', '2026-02-28 22:28:42', 0, NULL, '2026-02-28 22:18:42', '2026-02-28 22:18:42'),
(48, 1, '193879', '2026-04-08 18:13:02', 1, '2026-04-08 18:03:15', '2026-04-08 18:03:02', '2026-04-08 18:03:15'),
(49, 1, '712700', '2026-04-25 12:12:53', 1, '2026-04-25 12:03:05', '2026-04-25 12:02:53', '2026-04-25 12:03:05');

--
-- Indexes for dumped tables
--

--
-- فهارس للجدول `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_unique` (`code`),
  ADD KEY `accounts_parent_id_foreign` (`parent_id`);

--
-- فهارس للجدول `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`);

--
-- فهارس للجدول `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `assets_asset_tag_unique` (`asset_tag`),
  ADD KEY `assets_assigned_to_foreign` (`assigned_to`);

--
-- فهارس للجدول `asset_maintenance`
--
ALTER TABLE `asset_maintenance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asset_maintenance_asset_id_foreign` (`asset_id`),
  ADD KEY `asset_maintenance_assigned_to_foreign` (`assigned_to`);

--
-- فهارس للجدول `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id_date_unique` (`employee_id`,`date`),
  ADD KEY `attendances_employee_id_foreign` (`employee_id`);

--
-- فهارس للجدول `bank_reconciliations`
--
ALTER TABLE `bank_reconciliations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bank_reconciliations_created_by_foreign` (`created_by`),
  ADD KEY `bank_reconciliations_bank_account_id_foreign` (`bank_account_id`);

--
-- فهارس للجدول `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budgets_created_by_foreign` (`created_by`),
  ADD KEY `budgets_department_id_foreign` (`department_id`),
  ADD KEY `budgets_project_id_foreign` (`project_id`);

--
-- فهارس للجدول `budget_items`
--
ALTER TABLE `budget_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `budget_items_account_id_foreign` (`account_id`),
  ADD KEY `budget_items_budget_id_foreign` (`budget_id`);

--
-- فهارس للجدول `bugs`
--
ALTER TABLE `bugs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bug_number_unique` (`bug_number`),
  ADD KEY `bugs_assigned_to_foreign` (`assigned_to`),
  ADD KEY `bugs_reported_by_foreign` (`reported_by`),
  ADD KEY `bugs_project_id_foreign` (`project_id`);

--
-- فهارس للجدول `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- فهارس للجدول `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- فهارس للجدول `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_unique` (`email`),
  ADD KEY `clients_assigned_to_foreign` (`assigned_to`);

--
-- فهارس للجدول `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contract_number_unique` (`contract_number`),
  ADD KEY `contracts_approved_by_foreign` (`approved_by`),
  ADD KEY `contracts_created_by_foreign` (`created_by`),
  ADD KEY `contracts_project_id_foreign` (`project_id`),
  ADD KEY `contracts_client_id_foreign` (`client_id`);

--
-- فهارس للجدول `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_unique` (`code`),
  ADD KEY `departments_parent_id_foreign` (`parent_id`),
  ADD KEY `departments_head_of_department_foreign` (`head_of_department`),
  ADD KEY `departments_manager_id_foreign` (`manager_id`);

--
-- فهارس للجدول `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id_unique` (`employee_id`),
  ADD UNIQUE KEY `national_id_unique` (`national_id`),
  ADD KEY `employees_department_id_foreign` (`department_id`),
  ADD KEY `employees_user_id_foreign` (`user_id`);

--
-- فهارس للجدول `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expense_number_unique` (`expense_number`),
  ADD KEY `expenses_created_by_foreign` (`created_by`),
  ADD KEY `expenses_approved_by_foreign` (`approved_by`),
  ADD KEY `expenses_vendor_id_foreign` (`vendor_id`);

--
-- فهارس للجدول `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid_unique` (`uuid`);

--
-- فهارس للجدول `financial_invoices`
--
ALTER TABLE `financial_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number_unique` (`invoice_number`),
  ADD KEY `financial_invoices_created_by_foreign` (`created_by`),
  ADD KEY `financial_invoices_project_id_foreign` (`project_id`),
  ADD KEY `financial_invoices_client_id_foreign` (`client_id`);

--
-- فهارس للجدول `financial_invoice_items`
--
ALTER TABLE `financial_invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `financial_invoice_items_invoice_id_foreign` (`invoice_id`);

--
-- فهارس للجدول `fixed_assets`
--
ALTER TABLE `fixed_assets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `asset_code_unique` (`asset_code`),
  ADD KEY `fixed_assets_department_id_foreign` (`department_id`);

--
-- فهارس للجدول `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_created_by_foreign` (`created_by`),
  ADD KEY `invoices_project_id_foreign` (`project_id`),
  ADD KEY `invoices_client_id_foreign` (`client_id`),
  ADD KEY `invoices_sale_id_foreign` (`sale_id`),
  ADD KEY `invoices_contract_id_foreign` (`contract_id`);

--
-- فهارس للجدول `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- فهارس للجدول `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- فهارس للجدول `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_entries_approved_by_foreign` (`approved_by`),
  ADD KEY `journal_entries_created_by_foreign` (`created_by`);

--
-- فهارس للجدول `journal_entry_lines`
--
ALTER TABLE `journal_entry_lines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_entry_lines_account_id_foreign` (`account_id`),
  ADD KEY `journal_entry_lines_journal_entry_id_foreign` (`journal_entry_id`);

--
-- فهارس للجدول `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leaves_approved_by_foreign` (`approved_by`),
  ADD KEY `leaves_employee_id_foreign` (`employee_id`);

--
-- فهارس للجدول `login_activity_logs`
--
ALTER TABLE `login_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_activity_logs_user_id_activity_type_index` (`user_id`,`activity_type`),
  ADD KEY `login_activity_logs_activity_at_index` (`activity_at`),
  ADD KEY `login_activity_logs_status_index` (`status`),
  ADD KEY `login_activity_logs_user_id_foreign` (`user_id`);

--
-- فهارس للجدول `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meetings_organizer_id_foreign` (`organizer_id`),
  ADD KEY `meetings_department_id_foreign` (`department_id`);

--
-- فهارس للجدول `meeting_participants`
--
ALTER TABLE `meeting_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `meeting_participant_unique` (`meeting_id`,`user_id`),
  ADD KEY `meeting_participants_meeting_id_foreign` (`meeting_id`),
  ADD KEY `meeting_participants_user_id_foreign` (`user_id`);

--
-- فهارس للجدول `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_receiver_read_created_index` (`receiver_id`,`is_read`,`created_at`),
  ADD KEY `messages_type_created_at_index` (`type`,`created_at`),
  ADD KEY `messages_parent_message_id_index` (`parent_message_id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`);

--
-- فهارس للجدول `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- فهارس للجدول `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_type`,`model_id`);

--
-- فهارس للجدول `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_type`,`model_id`);

--
-- فهارس للجدول `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_is_read_index` (`user_id`,`is_read`),
  ADD KEY `notifications_type_created_at_index` (`type`,`created_at`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- فهارس للجدول `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- فهارس للجدول `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_number_unique` (`payment_number`),
  ADD KEY `payments_created_by_foreign` (`created_by`),
  ADD KEY `payments_bank_account_id_foreign` (`bank_account_id`),
  ADD KEY `payments_client_id_foreign` (`client_id`),
  ADD KEY `payments_employee_id_foreign` (`employee_id`),
  ADD KEY `payments_invoice_id_foreign` (`invoice_id`);

--
-- فهارس للجدول `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_guard_name_unique` (`name`,`guard_name`);

--
-- فهارس للجدول `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projects_department_id_foreign` (`department_id`),
  ADD KEY `projects_client_id_foreign` (`client_id`),
  ADD KEY `projects_project_manager_id_foreign` (`project_manager_id`);

--
-- فهارس للجدول `project_team_members`
--
ALTER TABLE `project_team_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_id_user_id_unique` (`project_id`,`user_id`),
  ADD KEY `project_team_members_project_user_index` (`project_id`,`user_id`),
  ADD KEY `project_team_members_user_id_foreign` (`user_id`),
  ADD KEY `project_team_members_project_id_foreign` (`project_id`);

--
-- فهارس للجدول `q_a_tests`
--
ALTER TABLE `q_a_tests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `test_number_unique` (`test_number`),
  ADD KEY `q_a_tests_project_id_status_index` (`project_id`,`status`),
  ADD KEY `q_a_tests_assigned_to_status_index` (`assigned_to`,`status`),
  ADD KEY `q_a_tests_type_priority_index` (`type`,`priority`),
  ADD KEY `q_a_tests_assigned_to_foreign` (`assigned_to`),
  ADD KEY `q_a_tests_created_by_foreign` (`created_by`),
  ADD KEY `q_a_tests_project_id_foreign` (`project_id`);

--
-- فهارس للجدول `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_guard_name_unique` (`name`,`guard_name`);

--
-- فهارس للجدول `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- فهارس للجدول `salaries`
--
ALTER TABLE `salaries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id_month_year_unique` (`employee_id`,`month`,`year`),
  ADD KEY `salaries_approved_by_foreign` (`approved_by`),
  ADD KEY `salaries_employee_id_foreign` (`employee_id`);

--
-- فهارس للجدول `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_assigned_to_foreign` (`assigned_to`),
  ADD KEY `sales_client_id_foreign` (`client_id`),
  ADD KEY `sales_project_id_foreign` (`project_id`);

--
-- فهارس للجدول `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- فهارس للجدول `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_unique` (`key`);

--
-- فهارس للجدول `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_parent_task_id_foreign` (`parent_task_id`),
  ADD KEY `tasks_created_by_foreign` (`created_by`),
  ADD KEY `tasks_assigned_to_foreign` (`assigned_to`),
  ADD KEY `tasks_project_id_foreign` (`project_id`);

--
-- فهارس للجدول `task_updates`
--
ALTER TABLE `task_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_updates_task_id_foreign` (`task_id`),
  ADD KEY `task_updates_user_id_foreign` (`user_id`);

--
-- فهارس للجدول `tax_records`
--
ALTER TABLE `tax_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tax_records_created_by_foreign` (`created_by`);

--
-- فهارس للجدول `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_number_unique` (`ticket_number`),
  ADD KEY `tickets_created_by_foreign` (`created_by`),
  ADD KEY `tickets_assigned_to_foreign` (`assigned_to`),
  ADD KEY `tickets_client_id_foreign` (`client_id`);

--
-- فهارس للجدول `trainings`
--
ALTER TABLE `trainings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trainings_instructor_id_foreign` (`instructor_id`),
  ADD KEY `trainings_department_id_foreign` (`department_id`);

--
-- فهارس للجدول `training_participants`
--
ALTER TABLE `training_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `training_participant_unique` (`training_id`,`user_id`),
  ADD KEY `training_participants_training_id_foreign` (`training_id`),
  ADD KEY `training_participants_user_id_foreign` (`user_id`);

--
-- فهارس للجدول `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- فهارس للجدول `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_permission_key_unique` (`user_id`,`permission_key`),
  ADD KEY `user_permissions_user_id_foreign` (`user_id`);

--
-- فهارس للجدول `verification_codes`
--
ALTER TABLE `verification_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `verification_codes_user_id_code_index` (`user_id`,`code`),
  ADD KEY `verification_codes_expires_at_index` (`expires_at`),
  ADD KEY `verification_codes_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `asset_maintenance`
--
ALTER TABLE `asset_maintenance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `bank_reconciliations`
--
ALTER TABLE `bank_reconciliations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budget_items`
--
ALTER TABLE `budget_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bugs`
--
ALTER TABLE `bugs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_invoices`
--
ALTER TABLE `financial_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_invoice_items`
--
ALTER TABLE `financial_invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fixed_assets`
--
ALTER TABLE `fixed_assets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `journal_entry_lines`
--
ALTER TABLE `journal_entry_lines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `login_activity_logs`
--
ALTER TABLE `login_activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meeting_participants`
--
ALTER TABLE `meeting_participants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `project_team_members`
--
ALTER TABLE `project_team_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `q_a_tests`
--
ALTER TABLE `q_a_tests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `salaries`
--
ALTER TABLE `salaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `task_updates`
--
ALTER TABLE `task_updates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tax_records`
--
ALTER TABLE `tax_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainings`
--
ALTER TABLE `trainings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_participants`
--
ALTER TABLE `training_participants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=349;

--
-- AUTO_INCREMENT for table `verification_codes`
--
ALTER TABLE `verification_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- القيود المفروضة على الجداول الملقاة
--

--
-- قيود الجداول `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL;

--
-- قيود الجداول `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `assets`
--
ALTER TABLE `assets`
  ADD CONSTRAINT `assets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- قيود الجداول `asset_maintenance`
--
ALTER TABLE `asset_maintenance`
  ADD CONSTRAINT `asset_maintenance_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `asset_maintenance_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- قيود الجداول `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `bank_reconciliations`
--
ALTER TABLE `bank_reconciliations`
  ADD CONSTRAINT `bank_reconciliations_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bank_reconciliations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `budgets_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `budgets_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `budget_items`
--
ALTER TABLE `budget_items`
  ADD CONSTRAINT `budget_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `budget_items_budget_id_foreign` FOREIGN KEY (`budget_id`) REFERENCES `budgets` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `bugs`
--
ALTER TABLE `bugs`
  ADD CONSTRAINT `bugs_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bugs_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bugs_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- قيود الجداول `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contracts_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contracts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contracts_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- قيود الجداول `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_head_of_department_foreign` FOREIGN KEY (`head_of_department`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `departments_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `departments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL;

--
-- قيود الجداول `financial_invoices`
--
ALTER TABLE `financial_invoices`
  ADD CONSTRAINT `financial_invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `financial_invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `financial_invoices_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- قيود الجداول `financial_invoice_items`
--
ALTER TABLE `financial_invoice_items`
  ADD CONSTRAINT `financial_invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `financial_invoices` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `fixed_assets`
--
ALTER TABLE `fixed_assets`
  ADD CONSTRAINT `fixed_assets_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL;

--
-- قيود الجداول `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoices_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL;

--
-- قيود الجداول `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD CONSTRAINT `journal_entries_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `journal_entries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- قيود الجداول `journal_entry_lines`
--
ALTER TABLE `journal_entry_lines`
  ADD CONSTRAINT `journal_entry_lines_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `journal_entry_lines_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `leaves`
--
ALTER TABLE `leaves`
  ADD CONSTRAINT `leaves_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leaves_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `login_activity_logs`
--
ALTER TABLE `login_activity_logs`
  ADD CONSTRAINT `login_activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `meetings`
--
ALTER TABLE `meetings`
  ADD CONSTRAINT `meetings_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `meetings_organizer_id_foreign` FOREIGN KEY (`organizer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `meeting_participants`
--
ALTER TABLE `meeting_participants`
  ADD CONSTRAINT `meeting_participants_meeting_id_foreign` FOREIGN KEY (`meeting_id`) REFERENCES `meetings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `meeting_participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_parent_id_foreign` FOREIGN KEY (`parent_message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `financial_invoices` (`id`) ON DELETE SET NULL;

--
-- قيود الجداول `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `projects_project_manager_id_foreign` FOREIGN KEY (`project_manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `project_team_members`
--
ALTER TABLE `project_team_members`
  ADD CONSTRAINT `project_team_members_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_team_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `q_a_tests`
--
ALTER TABLE `q_a_tests`
  ADD CONSTRAINT `q_a_tests_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `q_a_tests_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `q_a_tests_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- قيود الجداول `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `salaries`
--
ALTER TABLE `salaries`
  ADD CONSTRAINT `salaries_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `salaries_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL;

--
-- قيود الجداول `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_parent_task_id_foreign` FOREIGN KEY (`parent_task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `task_updates`
--
ALTER TABLE `task_updates`
  ADD CONSTRAINT `task_updates_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_updates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `tax_records`
--
ALTER TABLE `tax_records`
  ADD CONSTRAINT `tax_records_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tickets_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- قيود الجداول `trainings`
--
ALTER TABLE `trainings`
  ADD CONSTRAINT `trainings_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trainings_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- قيود الجداول `training_participants`
--
ALTER TABLE `training_participants`
  ADD CONSTRAINT `training_participants_training_id_foreign` FOREIGN KEY (`training_id`) REFERENCES `trainings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `training_participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD CONSTRAINT `user_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `verification_codes`
--
ALTER TABLE `verification_codes`
  ADD CONSTRAINT `verification_codes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
