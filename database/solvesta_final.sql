-- ================================================================
-- Solvesta Management System - MySQL Database Export
-- Generated: 2025-10-14 04:53:35
-- Last Updated: 2025-11-03
-- DATA TYPE COMPATIBILITY FIXED
-- Total Tables: 56
-- All UNSIGNED compatibility issues resolved
-- Added verification_codes, login_activity_logs, notifications, messages, assets, and asset_maintenance tables
-- IMPORTANT: projects table uses 'end_date' NOT 'deadline'
-- All code uses 'end_date' - do NOT add 'deadline' column
-- All ENUM and JSON types fixed to match migrations
-- All integer types fixed (INT vs BIGINT, etc.)
-- All DECIMAL precisions standardized
-- ================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Table: `migrations`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `migration` VARCHAR(255) NOT NULL,
  `batch` BIGINT(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=48;

-- Dumping data for table `migrations`

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
-- Table: `sessions`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT(20) UNSIGNED NULL,
  `ip_address` VARCHAR(255) NULL,
  `user_agent` TEXT NULL,
  `payload` TEXT NOT NULL,
  `last_activity` BIGINT(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `sessions`

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('N6jOI3l5fAhOQnKTSvclDlw4BIMdwjyGP2b1X09R', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.6584', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOXQ0SnJMNGJaYkpRZXJZTHk1Q0Q2T09XcEkza2JaMzdyMUpta3h0aSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1760402553),
('PaS3FptaFabb7KTNTPEei8krWdywpB13BUbe6Mvf', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMmJvdkNMRkUxN0s0TWdnUjMxbFk3SWlneWZiUURGTnRVYmxXb09CdyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1760402555),
('6eimWFspoMWRArsyvBIPpRokCIuxSV9AmjB15mOx', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.6584', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM3RlMkdWVzRMb2VlQnNTRGo2SjdTVHZtd2JrMUdGbFhVOHRUYmY3aiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1760402742),
('OTiIa0293IwFvIt0ZyICbPBA2UtIW4n9CHtHLMpI', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUUlrMmNZMDEyMm9Md0xFMFJJTU5pVklZUVpXUG9JZ1R5MXhFQmN4QyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hdHRlbmRhbmNlcy9jdXJyZW50LXdvcmstdGltZSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1760405148);

-- --------------------------------------------------------
-- Table: `cache`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` TEXT NOT NULL,
  `expiration` BIGINT(20) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `cache`

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-system_setting_system_name', 's:8:"Solvesta";', 1760406170),
('laravel-cache-system_setting_company_name', 's:25:"شركة سولفيستا";', 1760406170),
('laravel-cache-system_setting_favicon', 's:18:"system/favicon.ico";', 1760406170),
('laravel-cache-system_setting_sidebar_color', 's:7:"#1f2937";', 1760406170),
('laravel-cache-system_setting_logo', 's:51:"system/AuX9VzolQXPyvh64HuBhcg5JxtQvf7w2CeAXuEHt.png";', 1760406170),
('laravel-cache-system_setting_logo_size', 's:6:"medium";', 1760406170),
('laravel-cache-system_setting_system_description', 's:96:"نظام شامل لإدارة العمليات التجارية والموارد البشرية";', 1760406170),
('laravel-cache-system_setting_theme_color', 's:7:"#2563eb";', 1760406479);

-- --------------------------------------------------------
-- Table: `cache_locks`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` BIGINT(20) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `jobs`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `queue` VARCHAR(255) NOT NULL,
  `payload` TEXT NOT NULL,
  `attempts` BIGINT(20) NOT NULL,
  `reserved_at` BIGINT(20) NULL,
  `available_at` BIGINT(20) NOT NULL,
  `created_at` BIGINT(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `job_batches`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` BIGINT(20) NOT NULL,
  `pending_jobs` BIGINT(20) NOT NULL,
  `failed_jobs` BIGINT(20) NOT NULL,
  `failed_job_ids` TEXT NOT NULL,
  `options` TEXT NULL,
  `cancelled_at` BIGINT(20) NULL,
  `created_at` BIGINT(20) NOT NULL,
  `finished_at` BIGINT(20) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `failed_jobs`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `uuid` VARCHAR(255) NOT NULL,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` TEXT NOT NULL,
  `exception` TEXT NOT NULL,
  `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `users`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` VARCHAR(255) NULL,
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `profile_picture` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=4;

-- Dumping data for table `users`

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `profile_picture`) VALUES
(1, 'Solvesta Company', 'loransmogay@gmail.com', '2025-10-10 14:38:09', '$2y$12$o52oHUUS4XvybcMe.OD9z.0c0Xdyviku1tkAWqHBA15FU1HJBeJdq', NULL, '2025-10-10 14:38:09', '2025-10-10 17:32:49', 'profile-pictures/6NRGgpLAnpWcBchGo4DGjbMooBDty4JIVSDfnH80.png'),
(3, 'mohamed hany', 'loransmogay1@gmail.com', NULL, '$2y$12$LtSxx4LwtCmj9jebn5H/JeWt2GfF2715hQLRWT7rsHlzMkr81vRUe', NULL, '2025-10-11 12:15:36', '2025-10-11 12:15:36', NULL);

-- --------------------------------------------------------
-- Table: `verification_codes`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `verification_codes`;
CREATE TABLE `verification_codes` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) UNSIGNED NOT NULL,
  `code` VARCHAR(6) NOT NULL,
  `expires_at` TIMESTAMP NOT NULL,
  `used` TINYINT(1) NOT NULL DEFAULT 0,
  `used_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `verification_codes_user_id_code_index` (`user_id`,`code`),
  KEY `verification_codes_expires_at_index` (`expires_at`),
  KEY `verification_codes_user_id_foreign` (`user_id`),
  CONSTRAINT `verification_codes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `permissions`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `guard_name` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_guard_name_unique` (`name`, `guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=93;

-- Dumping data for table `permissions`

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
-- Table: `roles`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `guard_name` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_guard_name_unique` (`name`, `guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=12;

-- Dumping data for table `roles`

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
-- Table: `model_has_permissions`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions` (
  `permission_id` BIGINT(20) UNSIGNED NOT NULL,
  `model_type` VARCHAR(50) NOT NULL,
  `model_id` BIGINT(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `model_type`, `model_id`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `model_has_permissions`

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(17, 'App\\Models\\User', 3),
(31, 'App\\Models\\User', 3),
(60, 'App\\Models\\User', 3),
(61, 'App\\Models\\User', 3),
(68, 'App\\Models\\User', 3),
(69, 'App\\Models\\User', 3),
(70, 'App\\Models\\User', 3);

-- --------------------------------------------------------
-- Table: `model_has_roles`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles` (
  `role_id` BIGINT(20) UNSIGNED NOT NULL,
  `model_type` VARCHAR(50) NOT NULL,
  `model_id` BIGINT(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `model_type`, `model_id`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `model_has_roles`

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(9, 'App\\Models\\User', 3);

-- --------------------------------------------------------
-- Table: `role_has_permissions`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions` (
  `permission_id` BIGINT(20) UNSIGNED NOT NULL,
  `role_id` BIGINT(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`),
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `role_has_permissions`

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
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
(7, 2);

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(34, 2),
(10, 2),
(11, 2),
(12, 2),
(36, 2),
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
(79, 2),
(80, 2),
(81, 2),
(82, 2),
(83, 2),
(84, 2),
(85, 2),
(86, 2),
(87, 2),
(29, 2),
(30, 2),
(76, 2),
(31, 2),
(77, 2),
(92, 2),
(32, 2),
(5, 8),
(34, 8),
(10, 8),
(11, 8),
(36, 8),
(14, 8),
(15, 8),
(17, 8),
(19, 8),
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
(29, 8),
(30, 8),
(31, 8),
(35, 4),
(37, 4),
(15, 4),
(17, 4),
(60, 4),
(61, 4),
(79, 4),
(83, 4),
(31, 4),
(1, 5),
(2, 5),
(3, 5),
(5, 5),
(6, 5),
(7, 5),
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
(29, 5),
(30, 5),
(31, 5),
(25, 6),
(26, 6),
(27, 6);

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(28, 6),
(38, 6),
(52, 6),
(53, 6),
(54, 6),
(48, 6),
(56, 6),
(29, 6),
(30, 6),
(76, 6),
(31, 6),
(77, 6),
(17, 7),
(18, 7),
(19, 7),
(21, 7),
(22, 7),
(23, 7),
(52, 7),
(53, 7),
(56, 7),
(57, 7),
(31, 7),
(17, 9),
(68, 9),
(69, 9),
(70, 9),
(60, 9),
(61, 9),
(31, 9),
(35, 10),
(37, 10),
(15, 10),
(60, 10),
(61, 10),
(62, 10),
(64, 10),
(65, 10),
(66, 10),
(31, 10),
(35, 11),
(37, 11),
(15, 11),
(17, 11),
(31, 11);

-- --------------------------------------------------------
-- Table: `password_reset_tokens`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `departments`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `name_en` VARCHAR(255) NULL,
  `code` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `manager_id` BIGINT(20) UNSIGNED NULL,
  `location` VARCHAR(255) NULL,
  `phone` VARCHAR(20) NULL,
  `email` VARCHAR(255) NULL,
  `color` VARCHAR(50) NOT NULL DEFAULT '#3B82F6',
  `icon` VARCHAR(50) NOT NULL DEFAULT 'building',
  `budget` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `head_of_department` BIGINT(20) UNSIGNED NULL,
  `parent_id` BIGINT(20) UNSIGNED NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_unique` (`code`),
  KEY `departments_parent_id_foreign` (`parent_id`),
  KEY `departments_head_of_department_foreign` (`head_of_department`),
  KEY `departments_manager_id_foreign` (`manager_id`),
  CONSTRAINT `departments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=9;

-- Dumping data for table `departments`

INSERT INTO `departments` (`id`, `name`, `name_en`, `code`, `description`, `manager_id`, `location`, `phone`, `email`, `color`, `icon`, `budget`, `head_of_department`, `parent_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'الإدارة العامة', NULL, 'ADM', 'الإدارة العامة والتنفيذية للشركة', NULL, NULL, NULL, NULL, '#3B82F6', 'building', 0, NULL, NULL, 1, '2025-10-10 14:38:09', '2025-10-10 14:38:09'),
(2, 'المبيعات', NULL, 'SAL', 'قسم المبيعات والتسويق', NULL, NULL, NULL, NULL, '#3B82F6', 'building', 0, NULL, NULL, 1, '2025-10-10 14:38:09', '2025-10-10 14:38:09'),
(3, 'التطوير', NULL, 'DEV', 'قسم تطوير البرمجيات', NULL, NULL, NULL, NULL, '#3B82F6', 'building', 0, NULL, NULL, 1, '2025-10-10 14:38:09', '2025-10-10 14:38:09'),
(4, 'الموارد البشرية', NULL, 'HR', 'قسم الموارد البشرية', NULL, NULL, NULL, NULL, '#3B82F6', 'building', 0, NULL, NULL, 1, '2025-10-10 14:38:09', '2025-10-10 14:38:09'),
(5, 'المحاسبة', NULL, 'ACC', 'قسم المحاسبة والشؤون المالية', NULL, NULL, NULL, NULL, '#3B82F6', 'building', 0, NULL, NULL, 1, '2025-10-10 14:38:09', '2025-10-10 14:38:09'),
(6, 'دعم العملاء', NULL, 'SUP', 'قسم دعم العملاء', NULL, NULL, NULL, NULL, '#3B82F6', 'building', 0, NULL, NULL, 1, '2025-10-10 14:38:09', '2025-10-10 14:38:09'),
(7, 'التصميم', NULL, 'DES', 'قسم التصميم الجرافيكي', NULL, NULL, NULL, NULL, '#3B82F6', 'building', 0, NULL, NULL, 1, '2025-10-10 14:38:09', '2025-10-10 14:38:09'),
(8, 'ضمان الجودة', NULL, 'QA', 'قسم ضمان الجودة واختبار المنتجات', NULL, NULL, NULL, NULL, '#3B82F6', 'building', 0, NULL, NULL, 1, '2025-10-10 14:38:09', '2025-10-10 14:38:09');

-- --------------------------------------------------------
-- Table: `employees`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `user_id` BIGINT(20) UNSIGNED NOT NULL,
  `employee_id` VARCHAR(50) NOT NULL,
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) NULL,
  `national_id` VARCHAR(50) NULL,
  `department_id` BIGINT(20) UNSIGNED NULL,
  `position` VARCHAR(255) NOT NULL,
  `hire_date` DATE NOT NULL,
  `salary` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `employment_type` ENUM('full_time', 'part_time', 'contract', 'intern') NOT NULL DEFAULT 'full_time',
  `status` ENUM('active', 'inactive', 'terminated') NOT NULL DEFAULT 'active',
  `address` TEXT NULL,
  `emergency_contact` VARCHAR(255) NULL,
  `emergency_phone` VARCHAR(20) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `daily_hours` DECIMAL(5,2) NOT NULL DEFAULT 8,
  PRIMARY KEY (`id`),
  UNIQUE KEY `national_id_unique` (`national_id`),
  UNIQUE KEY `employee_id_unique` (`employee_id`),
  KEY `employees_department_id_foreign` (`department_id`),
  KEY `employees_user_id_foreign` (`user_id`),
  CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=3;

-- Dumping data for table `employees`

INSERT INTO `employees` (`id`, `user_id`, `employee_id`, `first_name`, `last_name`, `email`, `phone`, `national_id`, `department_id`, `position`, `hire_date`, `salary`, `employment_type`, `status`, `address`, `emergency_contact`, `emergency_phone`, `created_at`, `updated_at`, `daily_hours`) VALUES
(1, 1, 'EMP001', 'Super', 'Admin', 'admin@solvesta.com', +966501234567, NULL, 1, 'System Administrator', '2023-10-10 14:38:09', 50000, 'full_time', 'active', NULL, NULL, NULL, '2025-10-10 14:38:09', '2025-10-10 14:38:09', 8),
(2, 3, 2, 'mohamed', 'hany', 'loransmogay@gmail.com', 01203679764, NULL, 3, 'مبرمج', '2025-10-11 00:00:00', 30000, 'part_time', 'active', NULL, NULL, NULL, '2025-10-11 12:15:36', '2025-10-11 12:15:36', 8);

-- --------------------------------------------------------
-- Table: `user_permissions`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `user_permissions`;
CREATE TABLE `user_permissions` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `user_id` BIGINT(20) UNSIGNED NOT NULL,
  `permission_key` VARCHAR(255) NOT NULL,
  `is_enabled` TINYINT(1) NOT NULL DEFAULT 1,
  `custom_settings` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_permission_key_unique` (`user_id`, `permission_key`),
  KEY `user_permissions_user_id_foreign` (`user_id`),
  CONSTRAINT `user_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=300;

-- Dumping data for table `user_permissions`

INSERT INTO `user_permissions` (`id`, `user_id`, `permission_key`, `is_enabled`, `custom_settings`, `created_at`, `updated_at`) VALUES
(191, 1, 'sidebar_dashboard', 1, '{"description":"\\u0644\\u0648\\u062d\\u0629 \\u0627\\u0644\\u062a\\u062d\\u0643\\u0645","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(192, 1, 'sidebar_administration', 1, '{"description":"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u0625\\u062f\\u0627\\u0631\\u0629 \\u0627\\u0644\\u0639\\u0644\\u064a\\u0627","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(193, 1, 'sidebar_hr', 1, '{"description":"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u0645\\u0648\\u0627\\u0631\\u062f \\u0627\\u0644\\u0628\\u0634\\u0631\\u064a\\u0629","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(194, 1, 'sidebar_projects', 1, '{"description":"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u064a\\u0639","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(195, 1, 'sidebar_operations', 1, '{"description":"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u0639\\u0645\\u0644\\u064a\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(196, 1, 'sidebar_development', 1, '{"description":"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u062a\\u0637\\u0648\\u064a\\u0631 \\u0648\\u0627\\u0644\\u0628\\u0631\\u0645\\u062c\\u0629","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(197, 1, 'sidebar_design', 1, '{"description":"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u062a\\u0635\\u0645\\u064a\\u0645","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(198, 1, 'sidebar_business', 1, '{"description":"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u0645\\u0628\\u064a\\u0639\\u0627\\u062a \\u0648\\u0627\\u0644\\u062a\\u0633\\u0648\\u064a\\u0642","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(199, 1, 'sidebar_support', 1, '{"description":"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u062f\\u0639\\u0645 \\u0627\\u0644\\u0641\\u0646\\u064a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(200, 1, 'sidebar_finance', 1, '{"description":"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629 \\u0648\\u0627\\u0644\\u0645\\u062d\\u0627\\u0633\\u0628\\u0629","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(201, 1, 'sidebar_legal', 1, '{"description":"\\u0642\\u0633\\u0645 \\u0627\\u0644\\u0634\\u0624\\u0648\\u0646 \\u0627\\u0644\\u0642\\u0627\\u0646\\u0648\\u0646\\u064a\\u0629","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(202, 1, 'sidebar_users', 1, '{"description":"\\u0627\\u0644\\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\\u064a\\u0646","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(203, 1, 'sidebar_reports', 1, '{"description":"\\u0627\\u0644\\u062a\\u0642\\u0627\\u0631\\u064a\\u0631 \\u0648\\u0627\\u0644\\u062a\\u062d\\u0644\\u064a\\u0644","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(204, 1, 'sidebar_departments', 1, '{"description":"\\u0627\\u0644\\u0623\\u0642\\u0633\\u0627\\u0645","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(205, 1, 'sidebar_system_settings', 1, '{"description":"\\u0625\\u0639\\u062f\\u0627\\u062f\\u0627\\u062a \\u0627\\u0644\\u0646\\u0638\\u0627\\u0645","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(206, 1, 'sidebar_employees', 1, '{"description":"\\u0627\\u0644\\u0645\\u0648\\u0638\\u0641\\u064a\\u0646","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(207, 1, 'sidebar_attendances', 1, '{"description":"\\u0627\\u0644\\u062d\\u0636\\u0648\\u0631 \\u0648\\u0627\\u0644\\u0627\\u0646\\u0635\\u0631\\u0627\\u0641","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(208, 1, 'sidebar_leaves', 1, '{"description":"\\u0627\\u0644\\u0625\\u062c\\u0627\\u0632\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(209, 1, 'sidebar_salaries', 1, '{"description":"\\u0627\\u0644\\u0631\\u0648\\u0627\\u062a\\u0628","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(210, 1, 'sidebar_projects_list', 1, '{"description":"\\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u064a\\u0639","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(211, 1, 'sidebar_tasks', 1, '{"description":"\\u0627\\u0644\\u0645\\u0647\\u0627\\u0645","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(212, 1, 'sidebar_bugs', 1, '{"description":"\\u0627\\u0644\\u0623\\u062e\\u0637\\u0627\\u0621","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(213, 1, 'sidebar_qa', 1, '{"description":"\\u0636\\u0645\\u0627\\u0646 \\u0627\\u0644\\u062c\\u0648\\u062f\\u0629","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(214, 1, 'sidebar_design_page', 1, '{"description":"\\u0627\\u0644\\u062a\\u0635\\u0645\\u064a\\u0645","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(215, 1, 'sidebar_clients', 1, '{"description":"\\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(216, 1, 'sidebar_sales', 1, '{"description":"\\u0627\\u0644\\u0645\\u0628\\u064a\\u0639\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(217, 1, 'sidebar_marketing', 1, '{"description":"\\u0627\\u0644\\u062a\\u0633\\u0648\\u064a\\u0642","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(218, 1, 'sidebar_tickets', 1, '{"description":"\\u0627\\u0644\\u062a\\u0630\\u0627\\u0643\\u0631","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(219, 1, 'sidebar_invoices', 1, '{"description":"\\u0627\\u0644\\u0641\\u0648\\u0627\\u062a\\u064a\\u0631","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(220, 1, 'sidebar_contracts', 1, '{"description":"\\u0627\\u0644\\u0639\\u0642\\u0648\\u062f","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(221, 1, 'sidebar_accounting_dashboard', 1, '{"description":"\\u0644\\u0648\\u062d\\u0629 \\u0627\\u0644\\u062a\\u062d\\u0643\\u0645 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(222, 1, 'sidebar_accounts_tree', 1, '{"description":"\\u0634\\u062c\\u0631\\u0629 \\u0627\\u0644\\u062d\\u0633\\u0627\\u0628\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(223, 1, 'sidebar_journal_entries', 1, '{"description":"\\u0627\\u0644\\u0642\\u064a\\u0648\\u062f \\u0627\\u0644\\u0645\\u062d\\u0627\\u0633\\u0628\\u064a\\u0629","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(224, 1, 'sidebar_financial_invoices', 1, '{"description":"\\u0627\\u0644\\u0641\\u0648\\u0627\\u062a\\u064a\\u0631 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(225, 1, 'sidebar_payments', 1, '{"description":"\\u0627\\u0644\\u0645\\u062f\\u0641\\u0648\\u0639\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(226, 1, 'sidebar_expenses', 1, '{"description":"\\u0627\\u0644\\u0645\\u0635\\u0631\\u0648\\u0641\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(227, 1, 'sidebar_financial_reports', 1, '{"description":"\\u0627\\u0644\\u062a\\u0642\\u0627\\u0631\\u064a\\u0631 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(228, 1, 'sidebar_project_invoices', 1, '{"description":"\\u0641\\u0648\\u0627\\u062a\\u064a\\u0631 \\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u064a\\u0639","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(229, 1, 'dashboard_employees_count', 1, '{"description":"\\u0639\\u062f\\u062f \\u0627\\u0644\\u0645\\u0648\\u0638\\u0641\\u064a\\u0646","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(230, 1, 'dashboard_clients_count', 1, '{"description":"\\u0639\\u062f\\u062f \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(231, 1, 'dashboard_projects_count', 1, '{"description":"\\u0639\\u062f\\u062f \\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u064a\\u0639","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(232, 1, 'dashboard_tasks_count', 1, '{"description":"\\u0639\\u062f\\u062f \\u0627\\u0644\\u0645\\u0647\\u0627\\u0645","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(233, 1, 'dashboard_revenue', 1, '{"description":"\\u0627\\u0644\\u0625\\u064a\\u0631\\u0627\\u062f\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(234, 1, 'dashboard_attendance_rate', 1, '{"description":"\\u0645\\u0639\\u062f\\u0644 \\u0627\\u0644\\u062d\\u0636\\u0648\\u0631","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(235, 1, 'dashboard_recent_activities', 1, '{"description":"\\u0627\\u0644\\u0623\\u0646\\u0634\\u0637\\u0629 \\u0627\\u0644\\u0623\\u062e\\u064a\\u0631\\u0629","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(236, 1, 'dashboard_quick_actions', 1, '{"description":"\\u0627\\u0644\\u0625\\u062c\\u0631\\u0627\\u0621\\u0627\\u062a \\u0627\\u0644\\u0633\\u0631\\u064a\\u0639\\u0629","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(237, 1, 'dashboard_charts', 1, '{"description":"\\u0627\\u0644\\u0631\\u0633\\u0648\\u0645 \\u0627\\u0644\\u0628\\u064a\\u0627\\u0646\\u064a\\u0629","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(238, 1, 'page_users_view', 1, '{"description":"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\\u064a\\u0646","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(239, 1, 'page_users_create', 1, '{"description":"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\\u064a\\u0646","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(240, 1, 'page_users_edit', 1, '{"description":"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0627\\u0644\\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\\u064a\\u0646","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(241, 1, 'page_users_delete', 1, '{"description":"\\u062d\\u0630\\u0641 \\u0627\\u0644\\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\\u064a\\u0646","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(242, 1, 'page_employees_view', 1, '{"description":"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0645\\u0648\\u0638\\u0641\\u064a\\u0646","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(243, 1, 'page_employees_create', 1, '{"description":"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0645\\u0648\\u0638\\u0641\\u064a\\u0646","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(244, 1, 'page_employees_edit', 1, '{"description":"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0627\\u0644\\u0645\\u0648\\u0638\\u0641\\u064a\\u0646","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(245, 1, 'page_employees_delete', 1, '{"description":"\\u062d\\u0630\\u0641 \\u0627\\u0644\\u0645\\u0648\\u0638\\u0641\\u064a\\u0646","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(246, 1, 'page_projects_view', 1, '{"description":"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u064a\\u0639","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(247, 1, 'page_projects_create', 1, '{"description":"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0645\\u0634\\u0627\\u0631\\u064a\\u0639","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(248, 1, 'page_projects_edit', 1, '{"description":"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u064a\\u0639","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(249, 1, 'page_projects_delete', 1, '{"description":"\\u062d\\u0630\\u0641 \\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u064a\\u0639","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(250, 1, 'page_tasks_view', 1, '{"description":"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0645\\u0647\\u0627\\u0645","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(251, 1, 'page_tasks_create', 1, '{"description":"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0645\\u0647\\u0627\\u0645","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(252, 1, 'page_tasks_edit', 1, '{"description":"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0627\\u0644\\u0645\\u0647\\u0627\\u0645","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(253, 1, 'page_tasks_delete', 1, '{"description":"\\u062d\\u0630\\u0641 \\u0627\\u0644\\u0645\\u0647\\u0627\\u0645","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(254, 1, 'page_clients_view', 1, '{"description":"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(255, 1, 'page_clients_create', 1, '{"description":"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0639\\u0645\\u0644\\u0627\\u0621","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(256, 1, 'page_clients_edit', 1, '{"description":"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(257, 1, 'page_clients_delete', 1, '{"description":"\\u062d\\u0630\\u0641 \\u0627\\u0644\\u0639\\u0645\\u0644\\u0627\\u0621","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(258, 1, 'page_sales_view', 1, '{"description":"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0645\\u0628\\u064a\\u0639\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(259, 1, 'page_sales_create', 1, '{"description":"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0645\\u0628\\u064a\\u0639\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(260, 1, 'page_sales_edit', 1, '{"description":"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0627\\u0644\\u0645\\u0628\\u064a\\u0639\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(261, 1, 'page_sales_delete', 1, '{"description":"\\u062d\\u0630\\u0641 \\u0627\\u0644\\u0645\\u0628\\u064a\\u0639\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(262, 1, 'page_attendances_view', 1, '{"description":"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u062d\\u0636\\u0648\\u0631","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(263, 1, 'page_attendances_create', 1, '{"description":"\\u062a\\u0633\\u062c\\u064a\\u0644 \\u062d\\u0636\\u0648\\u0631","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(264, 1, 'page_attendances_edit', 1, '{"description":"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u062d\\u0636\\u0648\\u0631","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(265, 1, 'page_attendances_delete', 1, '{"description":"\\u062d\\u0630\\u0641 \\u062d\\u0636\\u0648\\u0631","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(266, 1, 'page_leaves_view', 1, '{"description":"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0625\\u062c\\u0627\\u0632\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(267, 1, 'page_leaves_create', 1, '{"description":"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0625\\u062c\\u0627\\u0632\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(268, 1, 'page_leaves_edit', 1, '{"description":"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0625\\u062c\\u0627\\u0632\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(269, 1, 'page_leaves_delete', 1, '{"description":"\\u062d\\u0630\\u0641 \\u0625\\u062c\\u0627\\u0632\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(270, 1, 'page_leaves_approve', 1, '{"description":"\\u0627\\u0639\\u062a\\u0645\\u0627\\u062f \\u0625\\u062c\\u0627\\u0632\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(271, 1, 'page_salaries_view', 1, '{"description":"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0631\\u0648\\u0627\\u062a\\u0628","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(272, 1, 'page_salaries_create', 1, '{"description":"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0631\\u0648\\u0627\\u062a\\u0628","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(273, 1, 'page_salaries_edit', 1, '{"description":"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0631\\u0648\\u0627\\u062a\\u0628","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(274, 1, 'page_salaries_delete', 1, '{"description":"\\u062d\\u0630\\u0641 \\u0631\\u0648\\u0627\\u062a\\u0628","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(275, 1, 'page_accounting_view', 1, '{"description":"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0645\\u062d\\u0627\\u0633\\u0628\\u0629","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(276, 1, 'page_accounts_create', 1, '{"description":"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u062d\\u0633\\u0627\\u0628\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(277, 1, 'page_accounts_edit', 1, '{"description":"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u062d\\u0633\\u0627\\u0628\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(278, 1, 'page_accounts_delete', 1, '{"description":"\\u062d\\u0630\\u0641 \\u062d\\u0633\\u0627\\u0628\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(279, 1, 'page_journal_entries_create', 1, '{"description":"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0642\\u064a\\u0648\\u062f \\u0645\\u062d\\u0627\\u0633\\u0628\\u064a\\u0629","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(280, 1, 'page_journal_entries_edit', 1, '{"description":"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0642\\u064a\\u0648\\u062f \\u0645\\u062d\\u0627\\u0633\\u0628\\u064a\\u0629","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(281, 1, 'page_journal_entries_delete', 1, '{"description":"\\u062d\\u0630\\u0641 \\u0642\\u064a\\u0648\\u062f \\u0645\\u062d\\u0627\\u0633\\u0628\\u064a\\u0629","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(282, 1, 'page_journal_entries_approve', 1, '{"description":"\\u0627\\u0639\\u062a\\u0645\\u0627\\u062f \\u0642\\u064a\\u0648\\u062f \\u0645\\u062d\\u0627\\u0633\\u0628\\u064a\\u0629","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(283, 1, 'page_journal_entries_post', 1, '{"description":"\\u062a\\u0631\\u062d\\u064a\\u0644 \\u0642\\u064a\\u0648\\u062f \\u0645\\u062d\\u0627\\u0633\\u0628\\u064a\\u0629","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(284, 1, 'page_financial_reports', 1, '{"description":"\\u0627\\u0644\\u062a\\u0642\\u0627\\u0631\\u064a\\u0631 \\u0627\\u0644\\u0645\\u0627\\u0644\\u064a\\u0629","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(285, 1, 'page_salary_reports', 1, '{"description":"\\u062a\\u0642\\u0627\\u0631\\u064a\\u0631 \\u0627\\u0644\\u0631\\u0648\\u0627\\u062a\\u0628","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(286, 1, 'page_attendance_reports', 1, '{"description":"\\u062a\\u0642\\u0627\\u0631\\u064a\\u0631 \\u0627\\u0644\\u062d\\u0636\\u0648\\u0631","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(287, 1, 'page_project_reports', 1, '{"description":"\\u062a\\u0642\\u0627\\u0631\\u064a\\u0631 \\u0627\\u0644\\u0645\\u0634\\u0627\\u0631\\u064a\\u0639","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(288, 1, 'page_sales_reports', 1, '{"description":"\\u062a\\u0642\\u0627\\u0631\\u064a\\u0631 \\u0627\\u0644\\u0645\\u0628\\u064a\\u0639\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(289, 1, 'page_tickets_view', 1, '{"description":"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u062a\\u0630\\u0627\\u0643\\u0631","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(290, 1, 'page_tickets_create', 1, '{"description":"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u062a\\u0630\\u0627\\u0643\\u0631","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48');

INSERT INTO `user_permissions` (`id`, `user_id`, `permission_key`, `is_enabled`, `custom_settings`, `created_at`, `updated_at`) VALUES
(291, 1, 'page_tickets_edit', 1, '{"description":"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u062a\\u0630\\u0627\\u0643\\u0631","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(292, 1, 'page_tickets_delete', 1, '{"description":"\\u062d\\u0630\\u0641 \\u062a\\u0630\\u0627\\u0643\\u0631","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(293, 1, 'page_invoices_view', 1, '{"description":"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0641\\u0648\\u0627\\u062a\\u064a\\u0631","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(294, 1, 'page_invoices_create', 1, '{"description":"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0641\\u0648\\u0627\\u062a\\u064a\\u0631","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(295, 1, 'page_invoices_edit', 1, '{"description":"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0641\\u0648\\u0627\\u062a\\u064a\\u0631","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(296, 1, 'page_invoices_delete', 1, '{"description":"\\u062d\\u0630\\u0641 \\u0641\\u0648\\u0627\\u062a\\u064a\\u0631","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(297, 1, 'page_contracts_view', 1, '{"description":"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0639\\u0642\\u0648\\u062f","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(298, 1, 'page_contracts_create', 1, '{"description":"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0639\\u0642\\u0648\\u062f","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(299, 1, 'page_contracts_edit', 1, '{"description":"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0639\\u0642\\u0648\\u062f","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(300, 1, 'page_contracts_delete', 1, '{"description":"\\u062d\\u0630\\u0641 \\u0639\\u0642\\u0648\\u062f","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(301, 1, 'page_bugs_view', 1, '{"description":"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0623\\u062e\\u0637\\u0627\\u0621","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(302, 1, 'page_bugs_create', 1, '{"description":"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0623\\u062e\\u0637\\u0627\\u0621","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(303, 1, 'page_bugs_edit', 1, '{"description":"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0623\\u062e\\u0637\\u0627\\u0621","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(304, 1, 'page_bugs_delete', 1, '{"description":"\\u062d\\u0630\\u0641 \\u0623\\u062e\\u0637\\u0627\\u0621","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(305, 1, 'page_expenses_view', 1, '{"description":"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0645\\u0635\\u0631\\u0648\\u0641\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(306, 1, 'page_expenses_create', 1, '{"description":"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0645\\u0635\\u0631\\u0648\\u0641\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(307, 1, 'page_expenses_edit', 1, '{"description":"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0645\\u0635\\u0631\\u0648\\u0641\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(308, 1, 'page_expenses_delete', 1, '{"description":"\\u062d\\u0630\\u0641 \\u0645\\u0635\\u0631\\u0648\\u0641\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(309, 1, 'page_payments_view', 1, '{"description":"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0645\\u062f\\u0641\\u0648\\u0639\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(310, 1, 'page_payments_create', 1, '{"description":"\\u0625\\u0646\\u0634\\u0627\\u0621 \\u0645\\u062f\\u0641\\u0648\\u0639\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(311, 1, 'page_payments_edit', 1, '{"description":"\\u062a\\u0639\\u062f\\u064a\\u0644 \\u0645\\u062f\\u0641\\u0648\\u0639\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(312, 1, 'page_payments_delete', 1, '{"description":"\\u062d\\u0630\\u0641 \\u0645\\u062f\\u0641\\u0648\\u0639\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(313, 1, 'system_settings', 1, '{"description":"\\u0625\\u0639\\u062f\\u0627\\u062f\\u0627\\u062a \\u0627\\u0644\\u0646\\u0638\\u0627\\u0645","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(314, 1, 'user_permissions_manage', 1, '{"description":"\\u0625\\u062f\\u0627\\u0631\\u0629 \\u0635\\u0644\\u0627\\u062d\\u064a\\u0627\\u062a \\u0627\\u0644\\u0645\\u0633\\u062a\\u062e\\u062f\\u0645\\u064a\\u0646","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(315, 1, 'roles_manage', 1, '{"description":"\\u0625\\u062f\\u0627\\u0631\\u0629 \\u0627\\u0644\\u0623\\u062f\\u0648\\u0627\\u0631","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(316, 1, 'departments_manage', 1, '{"description":"\\u0625\\u062f\\u0627\\u0631\\u0629 \\u0627\\u0644\\u0623\\u0642\\u0633\\u0627\\u0645","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(317, 1, 'export_data', 1, '{"description":"\\u062a\\u0635\\u062f\\u064a\\u0631 \\u0627\\u0644\\u0628\\u064a\\u0627\\u0646\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(318, 1, 'import_data', 1, '{"description":"\\u0627\\u0633\\u062a\\u064a\\u0631\\u0627\\u062f \\u0627\\u0644\\u0628\\u064a\\u0627\\u0646\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(319, 1, 'backup_database', 1, '{"description":"\\u0646\\u0633\\u062e \\u0627\\u062d\\u062a\\u064a\\u0627\\u0637\\u064a \\u0644\\u0644\\u0628\\u064a\\u0627\\u0646\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(320, 1, 'restore_database', 1, '{"description":"\\u0627\\u0633\\u062a\\u0639\\u0627\\u062f\\u0629 \\u0627\\u0644\\u0628\\u064a\\u0627\\u0646\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(321, 1, 'view_logs', 1, '{"description":"\\u0639\\u0631\\u0636 \\u0627\\u0644\\u0633\\u062c\\u0644\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(322, 1, 'delete_logs', 1, '{"description":"\\u062d\\u0630\\u0641 \\u0627\\u0644\\u0633\\u062c\\u0644\\u0627\\u062a","granted_at":"2025-10-10 17:03:48","granted_by":"System"}', '2025-10-10 17:03:48', '2025-10-10 17:03:48'),
(323, 3, 'sidebar_dashboard', 1, NULL, '2025-10-11 12:42:15', '2025-10-11 12:42:15');

-- --------------------------------------------------------
-- Table: `clients`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NULL,
  `phone` VARCHAR(20) NULL,
  `company_name` VARCHAR(255) NULL,
  `industry` VARCHAR(255) NULL,
  `client_type` ENUM('individual', 'small_business', 'enterprise') NOT NULL DEFAULT 'individual',
  `status` ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active',
  `address` VARCHAR(255) NULL,
  `city` VARCHAR(255) NULL,
  `country` VARCHAR(255) NULL,
  `website` VARCHAR(255) NULL,
  `notes` TEXT NULL,
  `assigned_to` BIGINT(20) UNSIGNED NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_unique` (`email`),
  KEY `clients_assigned_to_foreign` (`assigned_to`),
  CONSTRAINT `clients_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=2;

-- Dumping data for table `clients`

INSERT INTO `clients` (`id`, `name`, `email`, `phone`, `company_name`, `industry`, `client_type`, `status`, `address`, `city`, `country`, `website`, `notes`, `assigned_to`, `created_at`, `updated_at`) VALUES
(1, 'mohamed hany', 'loransmogay@gmail.com', 01203679764, NULL, NULL, 'individual', 'active', 'new damitta
100 streat', NULL, NULL, NULL, NULL, NULL, '2025-10-10 17:55:12', '2025-10-10 17:55:12');

-- --------------------------------------------------------
-- Table: `system_settings`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `system_settings`;
CREATE TABLE `system_settings` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `key` VARCHAR(255) NOT NULL,
  `value` TEXT NULL,
  `type` VARCHAR(50) NOT NULL DEFAULT 'string',
  `group` VARCHAR(255) NOT NULL DEFAULT 'general',
  `description` TEXT NULL,
  `is_public` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=19;

-- Dumping data for table `system_settings`

INSERT INTO `system_settings` (`id`, `key`, `value`, `type`, `group`, `description`, `is_public`, `created_at`, `updated_at`) VALUES
(1, 'system_name', 'Solvesta', 'string', 'general', 'اسم النظام', 1, '2025-10-10 16:53:51', '2025-10-10 18:23:29'),
(2, 'system_description', 'نظام شامل لإدارة العمليات التجارية والموارد البشرية', 'text', 'general', 'وصف النظام', 1, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(3, 'company_name', 'شركة سولفيستا', 'string', 'general', 'اسم الشركة', 1, '2025-10-10 16:53:51', '2025-10-10 17:11:39'),
(4, 'company_address', 'الرياض، المملكة العربية السعودية', 'text', 'general', 'عنوان الشركة', 1, '2025-10-10 16:53:51', '2025-10-10 17:11:39'),
(5, 'company_phone', '+966 11 123 4567', 'string', 'general', 'هاتف الشركة', 1, '2025-10-10 16:53:51', '2025-10-10 17:11:39'),
(6, 'company_email', 'info@solvesta.com', 'string', 'general', 'البريد الإلكتروني للشركة', 1, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(7, 'logo', 'system/AuX9VzolQXPyvh64HuBhcg5JxtQvf7w2CeAXuEHt.png', 'file', 'appearance', 'شعار الشركة', 1, '2025-10-10 16:53:51', '2025-10-11 01:04:11'),
(8, 'favicon', 'system/favicon.ico', 'file', 'appearance', 'أيقونة المتصفح', 1, '2025-10-10 16:53:51', '2025-10-10 17:11:39'),
(9, 'theme_color', '#2563eb', 'string', 'appearance', 'اللون الرئيسي للنظام', 1, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(10, 'sidebar_color', '#1f2937', 'string', 'appearance', 'لون الشريط الجانبي', 1, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(11, 'timezone', 'Asia/Riyadh', 'string', 'system', 'المنطقة الزمنية', 0, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(12, 'language', 'ar', 'string', 'system', 'لغة النظام', 0, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(13, 'date_format', 'Y-m-d', 'string', 'system', 'تنسيق التاريخ', 0, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(14, 'time_format', 'H:i', 'string', 'system', 'تنسيق الوقت', 0, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(15, 'email_notifications', 1, 'boolean', 'notifications', 'تفعيل إشعارات البريد الإلكتروني', 0, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(16, 'sms_notifications', 0, 'boolean', 'notifications', 'تفعيل إشعارات الرسائل النصية', 0, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(17, 'push_notifications', 1, 'boolean', 'notifications', 'تفعيل الإشعارات الفورية', 0, '2025-10-10 16:53:51', '2025-10-10 16:53:51'),
(18, 'logo_size', 'medium', 'string', 'appearance', 'حجم اللوجو في الشريط الجانبي', 1, '2025-10-10 17:11:39', '2025-10-10 17:11:39');

-- --------------------------------------------------------
-- Table: `projects`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `client_id` BIGINT(20) UNSIGNED NOT NULL,
  `project_manager_id` BIGINT(20) UNSIGNED NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NULL,
  `budget` DECIMAL(15,2) NULL,
  `status` ENUM('planning', 'in_progress', 'on_hold', 'completed', 'cancelled') NOT NULL DEFAULT 'planning',
  `priority` ENUM('low', 'medium', 'high', 'urgent') NOT NULL DEFAULT 'medium',
  `progress_percentage` INT NOT NULL DEFAULT 0,
  `team_members` JSON NULL,
  `technologies` JSON NULL,
  `project_type` VARCHAR(50) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `department_id` BIGINT(20) UNSIGNED NULL,
  PRIMARY KEY (`id`),
  KEY `projects_department_id_foreign` (`department_id`),
  KEY `projects_client_id_foreign` (`client_id`),
  KEY `projects_project_manager_id_foreign` (`project_manager_id`),
  CONSTRAINT `projects_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `projects_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `projects_project_manager_id_foreign` FOREIGN KEY (`project_manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=6;

-- Dumping data for table `projects`

INSERT INTO `projects` (`id`, `name`, `description`, `client_id`, `project_manager_id`, `start_date`, `end_date`, `budget`, `status`, `priority`, `progress_percentage`, `team_members`, `technologies`, `project_type`, `created_at`, `updated_at`, `department_id`) VALUES
(1, 'نظام إدارة الموظفين', 'نظام شامل لإدارة الموظفين والحضور والانصراف', 1, 1, '2025-07-14 01:17:13', '2026-04-14 01:17:13', 500000, 'in_progress', 'high', 75, '"[1]"', '"[\\"Laravel\\",\\"MySQL\\",\\"Vue.js\\",\\"Tailwind CSS\\"]"', 'web', '2025-10-14 01:17:13', '2025-10-14 01:17:13', NULL),
(2, 'نظام المحاسبة', 'نظام محاسبي متكامل للمعاملات المالية', 1, 1, '2025-08-14 01:17:13', '2026-02-14 01:17:13', 300000, 'in_progress', 'high', 50, '"[1]"', '"[\\"Laravel\\",\\"MySQL\\",\\"React\\"]"', 'web', '2025-10-14 01:17:13', '2025-10-14 01:17:13', NULL),
(3, 'نظام إدارة العملاء', 'نظام لإدارة بيانات العملاء والعلاقات التجارية', 1, 1, '2025-09-14 01:17:13', '2026-01-14 01:17:13', 200000, 'planning', 'medium', 25, '"[1]"', '"[\\"Laravel\\",\\"MySQL\\"]"', 'web', '2025-10-14 01:17:13', '2025-10-14 01:17:13', NULL),
(4, 'نظام التقارير', 'نظام إنتاج التقارير والإحصائيات', 1, 1, '2025-09-30 01:17:13', '2025-12-14 01:17:13', 150000, 'in_progress', 'medium', 30, '"[1]"', '"[\\"Laravel\\",\\"MySQL\\",\\"Chart.js\\"]"', 'web', '2025-10-14 01:17:13', '2025-10-14 01:17:13', NULL),
(5, 'نظام الأمان', 'نظام أمان متقدم لحماية البيانات', 1, 1, '2025-04-14 01:17:13', '2025-09-14 01:17:13', 400000, 'completed', 'high', 100, '"[1]"', '"[\\"Laravel\\",\\"MySQL\\",\\"Security\\"]"', 'web', '2025-10-14 01:17:13', '2025-10-14 01:17:13', NULL);

-- --------------------------------------------------------
-- Table: `project_team_members`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `project_team_members`;
CREATE TABLE `project_team_members` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `project_id` BIGINT(20) UNSIGNED NOT NULL,
  `user_id` BIGINT(20) UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_id_user_id_unique` (`project_id`, `user_id`),
  KEY `project_team_members_project_user_index` (`project_id`,`user_id`),
  KEY `project_team_members_user_id_foreign` (`user_id`),
  KEY `project_team_members_project_id_foreign` (`project_id`),
  CONSTRAINT `project_team_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `project_team_members_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `tasks`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `project_id` BIGINT(20) UNSIGNED NOT NULL,
  `assigned_to` BIGINT(20) UNSIGNED NOT NULL,
  `created_by` BIGINT(20) UNSIGNED NOT NULL,
  `parent_task_id` BIGINT(20) UNSIGNED NULL,
  `due_date` DATE NULL,
  `start_date` DATE NULL,
  `status` ENUM('todo', 'in_progress', 'review', 'completed', 'cancelled') NOT NULL DEFAULT 'todo',
  `priority` ENUM('low', 'medium', 'high', 'urgent') NOT NULL DEFAULT 'medium',
  `estimated_hours` INT NULL,
  `actual_hours` INT NULL,
  `progress_percentage` INT NOT NULL DEFAULT 0,
  `tags` JSON NULL,
  `attachments` JSON NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `tasks_parent_task_id_foreign` (`parent_task_id`),
  KEY `tasks_created_by_foreign` (`created_by`),
  KEY `tasks_assigned_to_foreign` (`assigned_to`),
  KEY `tasks_project_id_foreign` (`project_id`),
  CONSTRAINT `tasks_parent_task_id_foreign` FOREIGN KEY (`parent_task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `tasks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `tasks_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `attendances`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `attendances`;
CREATE TABLE `attendances` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `employee_id` BIGINT(20) UNSIGNED NOT NULL,
  `date` DATE NOT NULL,
  `check_in` DATETIME NULL,
  `check_out` DATETIME NULL,
  `break_start` DATETIME NULL,
  `break_end` DATETIME NULL,
  `break_duration_minutes` INT NULL,
  `total_hours` DECIMAL(8,2) NULL,
  `overtime_hours` INT NOT NULL DEFAULT 0,
  `status` ENUM('present', 'absent', 'late', 'half_day', 'leave') NOT NULL DEFAULT 'present',
  `current_status` ENUM('working', 'on_break', 'completed') NULL,
  `notes` TEXT NULL,
  `check_in_location` VARCHAR(255) NULL,
  `check_out_location` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id_date_unique` (`employee_id`, `date`),
  KEY `attendances_employee_id_foreign` (`employee_id`),
  CONSTRAINT `attendances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=45;

-- Dumping data for table `attendances`

-- --------------------------------------------------------
-- Table: `leaves`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `leaves`;
CREATE TABLE `leaves` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `employee_id` BIGINT(20) UNSIGNED NOT NULL,
  `leave_type` ENUM('annual', 'sick', 'emergency', 'maternity', 'paternity', 'unpaid', 'compensatory') NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `total_days` INT NOT NULL,
  `reason` TEXT NOT NULL,
  `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
  `approved_by` BIGINT(20) UNSIGNED NULL,
  `rejection_reason` TEXT NULL,
  `applied_date` DATE NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `leaves_approved_by_foreign` (`approved_by`),
  KEY `leaves_employee_id_foreign` (`employee_id`),
  CONSTRAINT `leaves_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `leaves_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=8;

-- Dumping data for table `leaves`

INSERT INTO `leaves` (`id`, `employee_id`, `leave_type`, `start_date`, `end_date`, `total_days`, `reason`, `status`, `approved_by`, `rejection_reason`, `applied_date`, `created_at`, `updated_at`) VALUES
(1, 1, 'paternity', '2025-08-03 01:10:31', '2025-08-09 01:10:31', 7, 'ولادة طفل', 'pending', NULL, NULL, '2025-07-17 01:10:31', '2025-10-14 01:10:31', '2025-10-14 01:10:31'),
(2, 1, 'unpaid', '2025-08-23 01:10:31', '2025-09-04 01:10:31', 13, 'أمور شخصية عاجلة', 'rejected', 1, 'فترة الذروة في العمل', '2025-08-11 01:10:31', '2025-10-14 01:10:31', '2025-10-14 01:10:31'),
(3, 1, 'sick', '2025-09-08 01:10:31', '2025-09-10 01:10:31', 3, 'حمى', 'rejected', 1, 'السبب غير مقنع', '2025-09-07 01:10:31', '2025-10-14 01:10:31', '2025-10-14 01:10:31'),
(4, 2, 'annual', '2025-05-05 01:10:31', '2025-05-18 01:10:31', 14, 'رحلة عائلية', 'rejected', 1, 'ننن', '2025-04-18 01:10:31', '2025-10-14 01:10:31', '2025-10-14 01:10:51'),
(5, 2, 'compensatory', '2025-06-05 01:10:31', '2025-06-10 01:10:31', 6, 'تعويض عن العمل في العطل', 'approved', 1, NULL, '2025-05-25 01:10:31', '2025-10-14 01:10:31', '2025-10-14 01:10:31'),
(6, 2, 'emergency', '2025-03-15 01:10:31', '2025-03-28 01:10:31', 14, 'مشكلة في المنزل', 'pending', NULL, NULL, '2025-02-19 01:10:31', '2025-10-14 01:10:31', '2025-10-14 01:10:31'),
(7, 2, 'annual', '2025-04-01 01:10:31', '2025-04-03 01:10:31', 3, 'زيارة الأهل', 'pending', NULL, NULL, '2025-03-28 01:10:31', '2025-10-14 01:10:31', '2025-10-14 01:10:31');

-- --------------------------------------------------------
-- Table: `salaries`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `salaries`;
CREATE TABLE `salaries` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `employee_id` BIGINT(20) UNSIGNED NOT NULL,
  `month` INT NOT NULL,
  `year` INT NOT NULL,
  `base_salary` DECIMAL(10,2) NOT NULL,
  `overtime_amount` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `bonus` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `allowances` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `deductions` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `tax` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `net_salary` DECIMAL(10,2) NOT NULL,
  `status` ENUM('pending', 'approved', 'paid') NOT NULL DEFAULT 'pending',
  `payment_date` DATE NULL,
  `notes` TEXT NULL,
  `approved_by` BIGINT(20) UNSIGNED NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id_month_year_unique` (`employee_id`, `month`, `year`),
  KEY `salaries_approved_by_foreign` (`approved_by`),
  KEY `salaries_employee_id_foreign` (`employee_id`),
  CONSTRAINT `salaries_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `salaries_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=13;

-- Dumping data for table `salaries`

INSERT INTO `salaries` (`id`, `employee_id`, `month`, `year`, `base_salary`, `overtime_amount`, `bonus`, `allowances`, `deductions`, `tax`, `net_salary`, `status`, `payment_date`, `notes`, `approved_by`, `created_at`, `updated_at`) VALUES
(1, 1, 10, 2025, 50000, 0, 3500, 5000, 1000, 5850, 51650, 'pending', NULL, 'راتب أكتوبر 2025 - معدل الحضور: -43.1%', NULL, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(2, 2, 10, 2025, 30000, 0, 0, 3000, 0, 3300, 29700, 'pending', NULL, 'راتب أكتوبر 2025 - معدل الحضور: -41.6%', NULL, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(3, 1, 9, 2025, 50000, 0, 0, 5000, 1000, 5500, 48500, 'approved', NULL, 'راتب سبتمبر 2025 - معدل الحضور: -43.3%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(4, 2, 9, 2025, 30000, 0, 0, 3000, 0, 3300, 29700, 'paid', '2025-09-29 00:00:00', 'راتب سبتمبر 2025 - معدل الحضور: -51.4%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(5, 1, 8, 2025, 50000, 0, 2500, 5000, 0, 5750, 51750, 'paid', '2025-08-29 00:00:00', 'راتب أغسطس 2025 - معدل الحضور: 81%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(6, 2, 8, 2025, 30000, 0, 1500, 3000, 0, 3450, 31050, 'paid', '2025-08-26 00:00:00', 'راتب أغسطس 2025 - معدل الحضور: 84%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(7, 1, 7, 2025, 50000, 0, 8000, 5000, 0, 6300, 56700, 'paid', '2025-07-29 00:00:00', 'راتب يوليو 2025 - معدل الحضور: 81%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(8, 2, 7, 2025, 30000, 0, 4200, 3000, 0, 3720, 33480, 'paid', '2025-07-30 00:00:00', 'راتب يوليو 2025 - معدل الحضور: 87%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(9, 1, 6, 2025, 50000, 0, 6000, 5000, 0, 6100, 54900, 'paid', '2025-06-25 00:00:00', 'راتب يونيو 2025 - معدل الحضور: 83%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(10, 2, 6, 2025, 30000, 0, 1500, 3000, 0, 3450, 31050, 'paid', '2025-06-26 00:00:00', 'راتب يونيو 2025 - معدل الحضور: 84%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(11, 1, 5, 2025, 50000, 0, 7500, 5000, 0, 6250, 56250, 'paid', '2025-05-26 00:00:00', 'راتب مايو 2025 - معدل الحضور: 83%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39'),
(12, 2, 5, 2025, 30000, 0, 5700, 3000, 0, 3870, 34830, 'paid', '2025-05-28 00:00:00', 'راتب مايو 2025 - معدل الحضور: 80%', 1, '2025-10-14 01:10:39', '2025-10-14 01:10:39');

-- --------------------------------------------------------
-- Table: `activity_logs`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `user_id` BIGINT(20) UNSIGNED NOT NULL,
  `action` VARCHAR(255) NOT NULL,
  `model_type` VARCHAR(50) NOT NULL,
  `model_id` BIGINT(20) UNSIGNED NULL,
  `description` VARCHAR(255) NOT NULL,
  `old_values` TEXT NULL,
  `new_values` TEXT NULL,
  `ip_address` VARCHAR(255) NULL,
  `user_agent` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `login_activity_logs`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `login_activity_logs`;
CREATE TABLE `login_activity_logs` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) UNSIGNED NOT NULL,
  `activity_type` ENUM('login', 'verification_code_sent', 'verification_code_verified', 'verification_code_resend', 'logout') NOT NULL DEFAULT 'login',
  `verification_code` VARCHAR(255) NULL,
  `email` VARCHAR(255) NULL,
  `status` ENUM('success', 'failed', 'pending') NOT NULL DEFAULT 'success',
  `message` TEXT NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `activity_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `login_activity_logs_user_id_activity_type_index` (`user_id`,`activity_type`),
  KEY `login_activity_logs_activity_at_index` (`activity_at`),
  KEY `login_activity_logs_status_index` (`status`),
  KEY `login_activity_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `login_activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `notifications`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) UNSIGNED NOT NULL,
  `type` VARCHAR(255) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `data` JSON NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `read_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_is_read_index` (`user_id`,`is_read`),
  KEY `notifications_type_created_at_index` (`type`,`created_at`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `messages`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sender_id` BIGINT(20) UNSIGNED NOT NULL,
  `receiver_id` BIGINT(20) UNSIGNED NOT NULL,
  `subject` VARCHAR(255) NULL,
  `body` LONGTEXT NOT NULL,
  `type` ENUM('direct','group','announcement') NOT NULL DEFAULT 'direct',
  `priority` ENUM('low','normal','high','urgent') NOT NULL DEFAULT 'normal',
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `read_at` TIMESTAMP NULL DEFAULT NULL,
  `is_important` TINYINT(1) NOT NULL DEFAULT 0,
  `is_deleted_by_sender` TINYINT(1) NOT NULL DEFAULT 0,
  `is_deleted_by_receiver` TINYINT(1) NOT NULL DEFAULT 0,
  `attachments` JSON DEFAULT NULL,
  `parent_message_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_receiver_read_created_index` (`receiver_id`,`is_read`,`created_at`),
  KEY `messages_type_created_at_index` (`type`,`created_at`),
  KEY `messages_parent_message_id_index` (`parent_message_id`),
  KEY `messages_sender_id_foreign` (`sender_id`),
  KEY `messages_receiver_id_foreign` (`receiver_id`),
  CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `messages_parent_id_foreign` FOREIGN KEY (`parent_message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `assets`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `assets`;
CREATE TABLE `assets` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `category` VARCHAR(255) NOT NULL,
  `asset_tag` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `purchase_price` DECIMAL(15,2) NULL,
  `purchase_date` DATE NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'active',
  `location` VARCHAR(255) NULL,
  `assigned_to` BIGINT(20) UNSIGNED NULL,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `assets_asset_tag_unique` (`asset_tag`),
  KEY `assets_assigned_to_foreign` (`assigned_to`),
  CONSTRAINT `assets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `asset_maintenance`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `asset_maintenance`;
CREATE TABLE `asset_maintenance` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `asset_id` BIGINT(20) UNSIGNED NOT NULL,
  `type` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `scheduled_date` DATE NOT NULL,
  `completed_date` DATE NULL,
  `cost` DECIMAL(15,2) NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'scheduled',
  `assigned_to` BIGINT(20) UNSIGNED NULL,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_maintenance_asset_id_foreign` (`asset_id`),
  KEY `asset_maintenance_assigned_to_foreign` (`assigned_to`),
  CONSTRAINT `asset_maintenance_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `assets` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `asset_maintenance_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `sales`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `sales`;
CREATE TABLE `sales` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `lead_source` VARCHAR(255) NULL,
  `client_id` BIGINT(20) UNSIGNED NOT NULL,
  `assigned_to` BIGINT(20) UNSIGNED NOT NULL,
  `product_service` VARCHAR(255) NOT NULL,
  `estimated_value` DECIMAL(15,2) NOT NULL,
  `actual_value` DECIMAL(15,2) NULL,
  `amount` DECIMAL(15,2) NULL,
  `stage` ENUM('lead', 'prospect', 'proposal', 'negotiation', 'closed_won', 'closed_lost') NOT NULL DEFAULT 'lead',
  `probability_percentage` INT NOT NULL DEFAULT 0,
  `expected_close_date` DATE NULL,
  `actual_close_date` DATE NULL,
  `notes` TEXT NULL,
  `competitors` JSON NULL,
  `decision_makers` JSON NULL,
  `project_id` BIGINT(20) UNSIGNED NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `sales_assigned_to_foreign` (`assigned_to`),
  KEY `sales_client_id_foreign` (`client_id`),
  KEY `sales_project_id_foreign` (`project_id`),
  CONSTRAINT `sales_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sales_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `sales_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `tickets`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE `tickets` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `ticket_number` VARCHAR(255) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `client_id` BIGINT(20) UNSIGNED NOT NULL,
  `assigned_to` BIGINT(20) UNSIGNED NULL,
  `created_by` BIGINT(20) UNSIGNED NULL,
  `priority` ENUM('low', 'medium', 'high', 'critical') NOT NULL DEFAULT 'medium',
  `category` ENUM('technical', 'billing', 'general', 'bug_report', 'feature_request') NOT NULL,
  `status` ENUM('open', 'in_progress', 'pending_client', 'resolved', 'closed') NOT NULL DEFAULT 'open',
  `sla_hours` INT NULL,
  `first_response_time` TIMESTAMP NULL,
  `resolution_time` TIMESTAMP NULL,
  `rating` INT NULL,
  `resolution_notes` TEXT NULL,
  `attachments` JSON NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ticket_number_unique` (`ticket_number`),
  KEY `tickets_created_by_foreign` (`created_by`),
  KEY `tickets_assigned_to_foreign` (`assigned_to`),
  KEY `tickets_client_id_foreign` (`client_id`),
  CONSTRAINT `tickets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `tickets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `tickets_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `invoices`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `invoice_number` VARCHAR(255) NOT NULL,
  `client_id` BIGINT(20) UNSIGNED NOT NULL,
  `project_id` BIGINT(20) UNSIGNED NULL,
  `sale_id` BIGINT(20) UNSIGNED NULL,
  `contract_id` BIGINT(20) UNSIGNED NULL,
  `invoice_date` DATE NOT NULL,
  `issue_date` DATE NULL,
  `due_date` DATE NOT NULL,
  `paid_date` DATE NULL,
  `subtotal` DECIMAL(15,2) NOT NULL,
  `amount` DECIMAL(15,2) NULL,
  `tax_rate` DECIMAL(5,2) NOT NULL DEFAULT 0,
  `tax_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `discount_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `total_amount` DECIMAL(15,2) NOT NULL,
  `paid_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `balance_amount` DECIMAL(15,2) NOT NULL,
  `status` ENUM('draft', 'sent', 'viewed', 'paid', 'overdue', 'cancelled') NOT NULL DEFAULT 'draft',
  `notes` TEXT NULL,
  `items` JSON NULL,
  `payment_method` VARCHAR(255) NULL,
  `payment_date` TIMESTAMP NULL,
  `created_by` BIGINT(20) UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number_unique` (`invoice_number`),
  KEY `invoices_created_by_foreign` (`created_by`),
  KEY `invoices_project_id_foreign` (`project_id`),
  KEY `invoices_client_id_foreign` (`client_id`),
  KEY `invoices_sale_id_foreign` (`sale_id`),
  KEY `invoices_contract_id_foreign` (`contract_id`),
  CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `invoices_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `invoices_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `contracts`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `contracts`;
CREATE TABLE `contracts` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `contract_number` VARCHAR(255) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `client_id` BIGINT(20) UNSIGNED NOT NULL,
  `project_id` BIGINT(20) UNSIGNED NULL,
  `contract_type` ENUM('employment', 'service', 'nda', 'partnership', 'vendor') NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NULL,
  `value` DECIMAL(15,2) NULL,
  `status` ENUM('draft', 'active', 'expired', 'terminated', 'renewed') NOT NULL DEFAULT 'draft',
  `terms_conditions` TEXT NULL,
  `parties` JSON NULL,
  `attachments` JSON NULL,
  `renewal_notice_days` INT NOT NULL DEFAULT 30,
  `auto_renewal` TINYINT(1) NOT NULL DEFAULT 0,
  `created_by` BIGINT(20) UNSIGNED NOT NULL,
  `approved_by` BIGINT(20) UNSIGNED NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contract_number_unique` (`contract_number`),
  KEY `contracts_approved_by_foreign` (`approved_by`),
  KEY `contracts_created_by_foreign` (`created_by`),
  KEY `contracts_project_id_foreign` (`project_id`),
  KEY `contracts_client_id_foreign` (`client_id`),
  CONSTRAINT `contracts_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `contracts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `contracts_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `contracts_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `bugs`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `bugs`;
CREATE TABLE `bugs` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `bug_number` VARCHAR(255) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `project_id` BIGINT(20) UNSIGNED NOT NULL,
  `reported_by` BIGINT(20) UNSIGNED NOT NULL,
  `assigned_to` BIGINT(20) UNSIGNED NULL,
  `severity` ENUM('low', 'medium', 'high', 'critical') NOT NULL DEFAULT 'medium',
  `priority` ENUM('low', 'medium', 'high', 'urgent') NOT NULL DEFAULT 'medium',
  `status` ENUM('open', 'in_progress', 'testing', 'resolved', 'closed', 'duplicate') NOT NULL DEFAULT 'open',
  `environment` VARCHAR(255) NULL,
  `browser` VARCHAR(255) NULL,
  `operating_system` VARCHAR(255) NULL,
  `steps_to_reproduce` JSON NULL,
  `expected_result` TEXT NULL,
  `actual_result` TEXT NULL,
  `attachments` JSON NULL,
  `resolution_date` TIMESTAMP NULL,
  `resolution_notes` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bug_number_unique` (`bug_number`),
  KEY `bugs_assigned_to_foreign` (`assigned_to`),
  KEY `bugs_reported_by_foreign` (`reported_by`),
  KEY `bugs_project_id_foreign` (`project_id`),
  CONSTRAINT `bugs_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `bugs_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `bugs_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `q_a_tests`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `q_a_tests`;
CREATE TABLE `q_a_tests` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `test_number` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `project_id` BIGINT(20) UNSIGNED NULL,
  `created_by` BIGINT(20) UNSIGNED NULL,
  `assigned_to` BIGINT(20) UNSIGNED NULL,
  `type` ENUM('unit', 'integration', 'functional', 'performance', 'security', 'usability') NOT NULL DEFAULT 'functional',
  `status` ENUM('pending', 'running', 'passed', 'failed', 'skipped') NOT NULL DEFAULT 'pending',
  `priority` ENUM('low', 'medium', 'high', 'critical') NOT NULL DEFAULT 'medium',
  `test_steps` TEXT NULL,
  `expected_result` TEXT NULL,
  `actual_result` TEXT NULL,
  `preconditions` TEXT NULL,
  `test_data` TEXT NULL,
  `environment` VARCHAR(255) NULL,
  `notes` TEXT NULL,
  `execution_time` INT NULL,
  `executed_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `test_number_unique` (`test_number`),
  KEY `q_a_tests_project_id_status_index` (`project_id`,`status`),
  KEY `q_a_tests_assigned_to_status_index` (`assigned_to`,`status`),
  KEY `q_a_tests_type_priority_index` (`type`,`priority`),
  KEY `q_a_tests_assigned_to_foreign` (`assigned_to`),
  KEY `q_a_tests_created_by_foreign` (`created_by`),
  KEY `q_a_tests_project_id_foreign` (`project_id`),
  CONSTRAINT `q_a_tests_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `q_a_tests_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `q_a_tests_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=23;

-- Dumping data for table `q_a_tests`

INSERT INTO `q_a_tests` (`id`, `test_number`, `name`, `description`, `project_id`, `created_by`, `assigned_to`, `type`, `status`, `priority`, `test_steps`, `expected_result`, `actual_result`, `preconditions`, `test_data`, `environment`, `notes`, `execution_time`, `executed_at`, `created_at`, `updated_at`) VALUES
(1, 'QA-2025-0001', 'اختبار أداء تحميل الصفحة', 'اختبار أداء النظام تحت ظروف مختلفة', 3, 3, 1, 'performance', 'skipped', 'low', '1. إعداد أدوات القياس
2. تشغيل الاختبار
3. مراقبة الأداء
4. تحليل النتائج', 'يجب أن يكون الأداء ضمن المعايير المطلوبة', 'تم تخطي الاختبار لسبب تقني', 'يجب أن تكون أدوات القياس جاهزة', 'بيانات كبيرة للاختبار', 'Development', 'ملاحظات اختبار الأداء', 467, '2025-09-25 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(2, 'QA-2025-0002', 'اختبار تسجيل الدخول', 'اختبار الوظائف الأساسية للتطبيق', 2, 3, 3, 'functional', 'failed', 'medium', '1. فتح التطبيق
2. تنفيذ العملية
3. التحقق من النتيجة
4. اختبار الحالات المختلفة', 'يجب أن تعمل الوظيفة كما هو متوقع', 'الاختبار فشل - النتيجة غير متوقعة', 'يجب أن يكون التطبيق قيد التشغيل', 'بيانات المستخدم والمنتجات', 'Staging', 'ملاحظات الاختبار الوظيفي - فشل الاختبار يحتاج لإصلاح', 229, '2025-10-01 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(3, 'QA-2025-0003', 'اختبار أمان الجلسات', 'اختبار أمان النظام وحماية البيانات', 3, 3, 3, 'security', 'pending', 'low', '1. إعداد أدوات الأمان
2. محاولة الاختراق
3. مراقبة الحماية
4. تقييم النتائج', 'يجب أن يكون النظام محمياً من التهديدات', NULL, 'يجب أن تكون أدوات الأمان متاحة', 'بيانات حساسة للاختبار', 'Local', 'ملاحظات اختبار الأمان', NULL, NULL, '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(4, 'QA-2025-0004', 'اختبار وحدة إدارة المنتجات', 'اختبار وحدة البرمجة للتأكد من عمل الدالة بشكل صحيح', 4, 3, 3, 'unit', 'pending', 'medium', '1. إعداد البيانات التجريبية
2. استدعاء الدالة
3. التحقق من النتيجة
4. تنظيف البيانات', 'يجب أن تعيد الدالة النتيجة المتوقعة', NULL, 'يجب أن تكون البيئة مهيأة والدوال متاحة', 'بيانات تجريبية للدالة', 'Local', 'ملاحظات اختبار الوحدة', NULL, NULL, '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(5, 'QA-2025-0005', 'اختبار وحدة حساب المستخدم', 'اختبار وحدة البرمجة للتأكد من عمل الدالة بشكل صحيح', 5, 1, 3, 'unit', 'failed', 'critical', '1. إعداد البيانات التجريبية
2. استدعاء الدالة
3. التحقق من النتيجة
4. تنظيف البيانات', 'يجب أن تعيد الدالة النتيجة المتوقعة', 'الاختبار فشل - النتيجة غير متوقعة', 'يجب أن تكون البيئة مهيأة والدوال متاحة', 'بيانات تجريبية للدالة', 'Local', 'ملاحظات اختبار الوحدة - فشل الاختبار يحتاج لإصلاح', 170, '2025-10-05 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(6, 'QA-2025-0006', 'اختبار وحدة نظام المصادقة', 'اختبار وحدة البرمجة للتأكد من عمل الدالة بشكل صحيح', 4, 1, 3, 'unit', 'passed', 'low', '1. إعداد البيانات التجريبية
2. استدعاء الدالة
3. التحقق من النتيجة
4. تنظيف البيانات', 'يجب أن تعيد الدالة النتيجة المتوقعة', 'الاختبار نجح كما هو متوقع', 'يجب أن تكون البيئة مهيأة والدوال متاحة', 'بيانات تجريبية للدالة', 'Development', 'ملاحظات اختبار الوحدة - نجح الاختبار بنجاح', 422, '2025-10-09 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(7, 'QA-2025-0007', 'اختبار تكامل إدارة المستخدمين', 'اختبار تكامل بين المكونات المختلفة للنظام', 2, 1, 3, 'integration', 'skipped', 'medium', '1. إعداد البيئة
2. تشغيل المكونات
3. التحقق من التكامل
4. اختبار التفاعل', 'يجب أن تعمل المكونات معاً بشكل صحيح', 'تم تخطي الاختبار لسبب تقني', 'يجب أن تكون جميع المكونات متاحة', 'بيانات تكامل بين المكونات', 'Production', 'ملاحظات اختبار التكامل', 280, '2025-09-26 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(8, 'QA-2025-0008', 'اختبار أداء الاستجابة', 'اختبار أداء النظام تحت ظروف مختلفة', 4, 1, 3, 'performance', 'pending', 'medium', '1. إعداد أدوات القياس
2. تشغيل الاختبار
3. مراقبة الأداء
4. تحليل النتائج', 'يجب أن يكون الأداء ضمن المعايير المطلوبة', NULL, 'يجب أن تكون أدوات القياس جاهزة', 'بيانات كبيرة للاختبار', 'Development', 'ملاحظات اختبار الأداء', NULL, NULL, '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(9, 'QA-2025-0009', 'اختبار أداء تحميل الصفحة', 'اختبار أداء النظام تحت ظروف مختلفة', 4, 3, 3, 'performance', 'running', 'medium', '1. إعداد أدوات القياس
2. تشغيل الاختبار
3. مراقبة الأداء
4. تحليل النتائج', 'يجب أن يكون الأداء ضمن المعايير المطلوبة', 'الاختبار قيد التنفيذ', 'يجب أن تكون أدوات القياس جاهزة', 'بيانات كبيرة للاختبار', 'Production', 'ملاحظات اختبار الأداء', 164, '2025-09-20 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(10, 'QA-2025-0010', 'اختبار سهولة الإعدادات', 'اختبار سهولة الاستخدام وتجربة المستخدم', 1, 3, 3, 'usability', 'pending', 'low', '1. إعداد المهام
2. تشغيل الاختبار
3. مراقبة المستخدم
4. جمع التعليقات', 'يجب أن يكون التطبيق سهلاً في الاستخدام', NULL, 'يجب أن يكون المستخدمون جاهزين للاختبار', 'بيانات المستخدم الحقيقية', 'Local', 'ملاحظات اختبار سهولة الاستخدام', NULL, NULL, '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(11, 'QA-2025-0011', 'اختبار أمان الجلسات', 'اختبار أمان النظام وحماية البيانات', 2, 1, 1, 'security', 'failed', 'critical', '1. إعداد أدوات الأمان
2. محاولة الاختراق
3. مراقبة الحماية
4. تقييم النتائج', 'يجب أن يكون النظام محمياً من التهديدات', 'الاختبار فشل - النتيجة غير متوقعة', 'يجب أن تكون أدوات الأمان متاحة', 'بيانات حساسة للاختبار', 'Local', 'ملاحظات اختبار الأمان - فشل الاختبار يحتاج لإصلاح', 85, '2025-10-01 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(12, 'QA-2025-0012', 'اختبار سهولة الشراء', 'اختبار سهولة الاستخدام وتجربة المستخدم', 5, 1, 1, 'usability', 'passed', 'low', '1. إعداد المهام
2. تشغيل الاختبار
3. مراقبة المستخدم
4. جمع التعليقات', 'يجب أن يكون التطبيق سهلاً في الاستخدام', 'الاختبار نجح كما هو متوقع', 'يجب أن يكون المستخدمون جاهزين للاختبار', 'بيانات المستخدم الحقيقية', 'Testing', 'ملاحظات اختبار سهولة الاستخدام - نجح الاختبار بنجاح', 449, '2025-09-23 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(13, 'QA-2025-0013', 'اختبار أمان الجلسات', 'اختبار أمان النظام وحماية البيانات', 4, 3, 1, 'security', 'passed', 'high', '1. إعداد أدوات الأمان
2. محاولة الاختراق
3. مراقبة الحماية
4. تقييم النتائج', 'يجب أن يكون النظام محمياً من التهديدات', 'الاختبار نجح كما هو متوقع', 'يجب أن تكون أدوات الأمان متاحة', 'بيانات حساسة للاختبار', 'Development', 'ملاحظات اختبار الأمان - نجح الاختبار بنجاح', 122, '2025-10-05 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(14, 'QA-2025-0014', 'اختبار أمان الجلسات', 'اختبار أمان النظام وحماية البيانات', 1, 1, 3, 'security', 'running', 'critical', '1. إعداد أدوات الأمان
2. محاولة الاختراق
3. مراقبة الحماية
4. تقييم النتائج', 'يجب أن يكون النظام محمياً من التهديدات', 'الاختبار قيد التنفيذ', 'يجب أن تكون أدوات الأمان متاحة', 'بيانات حساسة للاختبار', 'Testing', 'ملاحظات اختبار الأمان', 497, '2025-09-27 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(15, 'QA-2025-0015', 'اختبار سهولة الإعدادات', 'اختبار سهولة الاستخدام وتجربة المستخدم', 3, 3, 3, 'usability', 'failed', 'critical', '1. إعداد المهام
2. تشغيل الاختبار
3. مراقبة المستخدم
4. جمع التعليقات', 'يجب أن يكون التطبيق سهلاً في الاستخدام', 'الاختبار فشل - النتيجة غير متوقعة', 'يجب أن يكون المستخدمون جاهزين للاختبار', 'بيانات المستخدم الحقيقية', 'Production', 'ملاحظات اختبار سهولة الاستخدام - فشل الاختبار يحتاج لإصلاح', 66, '2025-09-29 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(16, 'QA-2025-0016', 'اختبار أمان البيانات', 'اختبار أمان النظام وحماية البيانات', 4, 1, 3, 'security', 'passed', 'critical', '1. إعداد أدوات الأمان
2. محاولة الاختراق
3. مراقبة الحماية
4. تقييم النتائج', 'يجب أن يكون النظام محمياً من التهديدات', 'الاختبار نجح كما هو متوقع', 'يجب أن تكون أدوات الأمان متاحة', 'بيانات حساسة للاختبار', 'Testing', 'ملاحظات اختبار الأمان - نجح الاختبار بنجاح', 196, '2025-10-13 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(17, 'QA-2025-0017', 'اختبار أداء تحميل الصفحة', 'اختبار أداء النظام تحت ظروف مختلفة', 3, 3, 3, 'performance', 'pending', 'low', '1. إعداد أدوات القياس
2. تشغيل الاختبار
3. مراقبة الأداء
4. تحليل النتائج', 'يجب أن يكون الأداء ضمن المعايير المطلوبة', NULL, 'يجب أن تكون أدوات القياس جاهزة', 'بيانات كبيرة للاختبار', 'Production', 'ملاحظات اختبار الأداء', NULL, NULL, '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(18, 'QA-2025-0018', 'اختبار سهولة الشراء', 'اختبار سهولة الاستخدام وتجربة المستخدم', 1, 3, 3, 'usability', 'failed', 'high', '1. إعداد المهام
2. تشغيل الاختبار
3. مراقبة المستخدم
4. جمع التعليقات', 'يجب أن يكون التطبيق سهلاً في الاستخدام', 'الاختبار فشل - النتيجة غير متوقعة', 'يجب أن يكون المستخدمون جاهزين للاختبار', 'بيانات المستخدم الحقيقية', 'Development', 'ملاحظات اختبار سهولة الاستخدام - فشل الاختبار يحتاج لإصلاح', 411, '2025-09-24 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(19, 'QA-2025-0019', 'اختبار وحدة نظام المصادقة', 'اختبار وحدة البرمجة للتأكد من عمل الدالة بشكل صحيح', 4, 3, 1, 'unit', 'failed', 'low', '1. إعداد البيانات التجريبية
2. استدعاء الدالة
3. التحقق من النتيجة
4. تنظيف البيانات', 'يجب أن تعيد الدالة النتيجة المتوقعة', 'الاختبار فشل - النتيجة غير متوقعة', 'يجب أن تكون البيئة مهيأة والدوال متاحة', 'بيانات تجريبية للدالة', 'Staging', 'ملاحظات اختبار الوحدة - فشل الاختبار يحتاج لإصلاح', 375, '2025-10-05 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(20, 'QA-2025-0020', 'اختبار أمان المصادقة', 'اختبار أمان النظام وحماية البيانات', 2, 3, 3, 'security', 'pending', 'critical', '1. إعداد أدوات الأمان
2. محاولة الاختراق
3. مراقبة الحماية
4. تقييم النتائج', 'يجب أن يكون النظام محمياً من التهديدات', NULL, 'يجب أن تكون أدوات الأمان متاحة', 'بيانات حساسة للاختبار', 'Testing', 'ملاحظات اختبار الأمان', NULL, NULL, '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(21, 'QA-2025-0021', 'اختبار تكامل نظام الإشعارات', 'اختبار تكامل بين المكونات المختلفة للنظام', 3, 1, 3, 'integration', 'skipped', 'medium', '1. إعداد البيئة
2. تشغيل المكونات
3. التحقق من التكامل
4. اختبار التفاعل', 'يجب أن تعمل المكونات معاً بشكل صحيح', 'تم تخطي الاختبار لسبب تقني', 'يجب أن تكون جميع المكونات متاحة', 'بيانات تكامل بين المكونات', 'Staging', 'ملاحظات اختبار التكامل', 360, '2025-09-18 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21'),
(22, 'QA-2025-0022', 'اختبار أمان الجلسات', 'اختبار أمان النظام وحماية البيانات', 4, 3, 3, 'security', 'passed', 'medium', '1. إعداد أدوات الأمان
2. محاولة الاختراق
3. مراقبة الحماية
4. تقييم النتائج', 'يجب أن يكون النظام محمياً من التهديدات', 'الاختبار نجح كما هو متوقع', 'يجب أن تكون أدوات الأمان متاحة', 'بيانات حساسة للاختبار', 'Local', 'ملاحظات اختبار الأمان - نجح الاختبار بنجاح', 493, '2025-09-21 01:17:21', '2025-10-14 01:17:21', '2025-10-14 01:17:21');

-- --------------------------------------------------------
-- Table: `accounts`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE `accounts` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `code` VARCHAR(255) NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `parent_id` BIGINT(20) UNSIGNED NULL,
  `description` TEXT NULL,
  `balance` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_unique` (`code`),
  KEY `accounts_parent_id_foreign` (`parent_id`),
  CONSTRAINT `accounts_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=30;

-- Dumping data for table `accounts`

INSERT INTO `accounts` (`id`, `name`, `code`, `type`, `parent_id`, `description`, `balance`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'الأصول المتداولة', 1000, 'asset', NULL, 'الأصول التي يمكن تحويلها إلى نقد خلال سنة واحدة', 0, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(2, 'النقدية', 1100, 'asset', 1, 'النقدية المتاحة في الخزينة', 50000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(3, 'البنوك', 1200, 'asset', 1, 'الودائع البنكية والحسابات الجارية', 150000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(4, 'العملاء', 1300, 'asset', 1, 'المبالغ المستحقة على العملاء', 75000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(5, 'المخزون', 1400, 'asset', 1, 'البضائع والمخزون المتاح للبيع', 100000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(6, 'الأصول الثابتة', 2000, 'asset', NULL, 'الأصول طويلة الأجل', 0, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(7, 'المعدات', 2100, 'asset', 6, 'المعدات والآلات', 200000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(8, 'المباني', 2200, 'asset', 6, 'المباني والمكاتب', 500000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(9, 'المركبات', 2300, 'asset', 6, 'المركبات والسيارات', 80000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(10, 'الخصوم المتداولة', 3000, 'liability', NULL, 'الخصوم المستحقة خلال سنة واحدة', 0, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(11, 'الموردون', 3100, 'liability', 10, 'المبالغ المستحقة للموردين', 45000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(12, 'الرواتب المستحقة', 3200, 'liability', 10, 'الرواتب والاستحقاقات المستحقة للموظفين', 25000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(13, 'الضرائب المستحقة', 3300, 'liability', 10, 'الضرائب والرسوم المستحقة', 15000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(14, 'الخصوم طويلة الأجل', 4000, 'liability', NULL, 'الخصوم المستحقة لأكثر من سنة', 0, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(15, 'القروض البنكية', 4100, 'liability', 14, 'القروض البنكية طويلة الأجل', 300000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(16, 'حقوق الملكية', 5000, 'equity', NULL, 'حقوق المالكين في الشركة', 0, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(17, 'رأس المال', 5100, 'equity', 16, 'رأس المال المدفوع', 500000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(18, 'الأرباح المحتجزة', 5200, 'equity', 16, 'الأرباح المحتجزة من السنوات السابقة', 85000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(19, 'الإيرادات', 6000, 'revenue', NULL, 'إيرادات الشركة', 0, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(20, 'إيرادات المبيعات', 6100, 'revenue', 19, 'إيرادات مبيعات المنتجات والخدمات', 350000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(21, 'إيرادات الخدمات', 6200, 'revenue', 19, 'إيرادات تقديم الخدمات', 120000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(22, 'إيرادات أخرى', 6300, 'revenue', 19, 'إيرادات أخرى متنوعة', 15000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(23, 'المصروفات', 7000, 'expense', NULL, 'مصروفات التشغيل', 0, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(24, 'مصروفات الرواتب', 7100, 'expense', 23, 'رواتب وأجور الموظفين', 180000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(25, 'مصروفات الإيجار', 7200, 'expense', 23, 'إيجار المكاتب والمباني', 24000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(26, 'مصروفات الكهرباء والمياه', 7300, 'expense', 23, 'فواتير الكهرباء والمياه', 12000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(27, 'مصروفات التسويق', 7400, 'expense', 23, 'مصروفات التسويق والإعلان', 15000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:22'),
(28, 'مصروفات النقل', 7500, 'expense', 23, 'مصروفات النقل والشحن', 8000, 1, '2025-10-10 14:38:22', '2025-10-10 14:38:23'),
(29, 'مصروفات أخرى', 7600, 'expense', 23, 'مصروفات أخرى متنوعة', 6000, 1, '2025-10-10 14:38:23', '2025-10-10 14:38:23');

-- --------------------------------------------------------
-- Table: `journal_entries`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `journal_entries`;
CREATE TABLE `journal_entries` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `date` DATE NOT NULL,
  `reference` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `total_debit` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `total_credit` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `status` VARCHAR(50) NOT NULL DEFAULT 'draft',
  `created_by` BIGINT(20) UNSIGNED NULL,
  `approved_by` BIGINT(20) UNSIGNED NULL,
  `approved_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `journal_entries_approved_by_foreign` (`approved_by`),
  KEY `journal_entries_created_by_foreign` (`created_by`),
  CONSTRAINT `journal_entries_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `journal_entries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `journal_entry_lines`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `journal_entry_lines`;
CREATE TABLE `journal_entry_lines` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `journal_entry_id` BIGINT(20) UNSIGNED NOT NULL,
  `account_id` BIGINT(20) UNSIGNED NOT NULL,
  `description` TEXT NULL,
  `debit` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `credit` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `journal_entry_lines_account_id_foreign` (`account_id`),
  KEY `journal_entry_lines_journal_entry_id_foreign` (`journal_entry_id`),
  CONSTRAINT `journal_entry_lines_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `journal_entry_lines_journal_entry_id_foreign` FOREIGN KEY (`journal_entry_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `financial_invoices`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `financial_invoices`;
CREATE TABLE `financial_invoices` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `invoice_number` VARCHAR(255) NOT NULL,
  `invoice_type` VARCHAR(50) NOT NULL,
  `client_id` BIGINT(20) UNSIGNED NULL,
  `project_id` BIGINT(20) UNSIGNED NULL,
  `invoice_date` DATE NOT NULL,
  `due_date` DATE NOT NULL,
  `description` TEXT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  `tax_rate` DECIMAL(5,2) NOT NULL DEFAULT 0,
  `tax_amount` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `discount_percentage` DECIMAL(5,2) NOT NULL DEFAULT 0,
  `discount_amount` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `total_amount` DECIMAL(12,2) NOT NULL,
  `paid_amount` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `balance_due` DECIMAL(10,2) NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'draft',
  `payment_status` VARCHAR(50) NOT NULL DEFAULT 'unpaid',
  `currency` VARCHAR(255) NOT NULL DEFAULT 'SAR',
  `notes` TEXT NULL,
  `terms_conditions` TEXT NULL,
  `created_by` BIGINT(20) UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number_unique` (`invoice_number`),
  KEY `financial_invoices_created_by_foreign` (`created_by`),
  KEY `financial_invoices_project_id_foreign` (`project_id`),
  KEY `financial_invoices_client_id_foreign` (`client_id`),
  CONSTRAINT `financial_invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `financial_invoices_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `financial_invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `financial_invoice_items`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `financial_invoice_items`;
CREATE TABLE `financial_invoice_items` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `invoice_id` BIGINT(20) UNSIGNED NOT NULL,
  `item_name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `quantity` DECIMAL(10,2) NOT NULL,
  `unit_price` DECIMAL(12,2) NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `financial_invoice_items_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `financial_invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `financial_invoices` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `payments`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `payment_number` VARCHAR(255) NOT NULL,
  `payment_type` VARCHAR(50) NOT NULL,
  `invoice_id` BIGINT(20) UNSIGNED NULL,
  `employee_id` BIGINT(20) UNSIGNED NULL,
  `client_id` BIGINT(20) UNSIGNED NULL,
  `payment_date` DATE NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `payment_method` VARCHAR(255) NOT NULL,
  `reference_number` VARCHAR(255) NULL,
  `bank_account_id` BIGINT(20) UNSIGNED NULL,
  `description` TEXT NULL,
  `notes` TEXT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'completed',
  `created_by` BIGINT(20) UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_number_unique` (`payment_number`),
  KEY `payments_created_by_foreign` (`created_by`),
  KEY `payments_bank_account_id_foreign` (`bank_account_id`),
  KEY `payments_client_id_foreign` (`client_id`),
  KEY `payments_employee_id_foreign` (`employee_id`),
  KEY `payments_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `payments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `payments_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `payments_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `payments_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `financial_invoices` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `expenses`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `expenses`;
CREATE TABLE `expenses` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `expense_number` VARCHAR(255) NOT NULL,
  `expense_category` VARCHAR(255) NOT NULL,
  `vendor_id` BIGINT(20) UNSIGNED NULL,
  `expense_date` DATE NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `notes` TEXT NULL,
  `payment_method` VARCHAR(255) NOT NULL,
  `receipt_number` VARCHAR(255) NULL,
  `attachment` VARCHAR(255) NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'pending',
  `approved_by` BIGINT(20) UNSIGNED NULL,
  `approved_at` TIMESTAMP NULL,
  `created_by` BIGINT(20) UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `expense_number_unique` (`expense_number`),
  KEY `expenses_created_by_foreign` (`created_by`),
  KEY `expenses_approved_by_foreign` (`approved_by`),
  KEY `expenses_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `expenses_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `expenses_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `budgets`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `budgets`;
CREATE TABLE `budgets` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `budget_name` VARCHAR(255) NOT NULL,
  `budget_type` VARCHAR(50) NOT NULL,
  `project_id` BIGINT(20) UNSIGNED NULL,
  `department_id` BIGINT(20) UNSIGNED NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `total_budget` DECIMAL(15,2) NOT NULL,
  `allocated_amount` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `spent_amount` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `remaining_amount` DECIMAL(12,2) NOT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'draft',
  `notes` TEXT NULL,
  `created_by` BIGINT(20) UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `budgets_created_by_foreign` (`created_by`),
  KEY `budgets_department_id_foreign` (`department_id`),
  KEY `budgets_project_id_foreign` (`project_id`),
  CONSTRAINT `budgets_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `budgets_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `budgets_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `budget_items`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `budget_items`;
CREATE TABLE `budget_items` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `budget_id` BIGINT(20) UNSIGNED NOT NULL,
  `account_id` BIGINT(20) UNSIGNED NOT NULL,
  `item_name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `allocated_amount` DECIMAL(12,2) NOT NULL,
  `spent_amount` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `remaining_amount` DECIMAL(12,2) NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `budget_items_account_id_foreign` (`account_id`),
  KEY `budget_items_budget_id_foreign` (`budget_id`),
  CONSTRAINT `budget_items_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `budget_items_budget_id_foreign` FOREIGN KEY (`budget_id`) REFERENCES `budgets` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `bank_reconciliations`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `bank_reconciliations`;
CREATE TABLE `bank_reconciliations` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `bank_account_id` BIGINT(20) UNSIGNED NOT NULL,
  `reconciliation_date` DATE NOT NULL,
  `bank_statement_balance` DECIMAL(10,2) NOT NULL,
  `book_balance` DECIMAL(10,2) NOT NULL,
  `outstanding_deposits` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `outstanding_checks` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `bank_charges` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `interest_earned` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `adjusted_balance` DECIMAL(10,2) NOT NULL,
  `difference` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `status` VARCHAR(50) NOT NULL DEFAULT 'in_progress',
  `notes` TEXT NULL,
  `created_by` BIGINT(20) UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `bank_reconciliations_created_by_foreign` (`created_by`),
  KEY `bank_reconciliations_bank_account_id_foreign` (`bank_account_id`),
  CONSTRAINT `bank_reconciliations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `bank_reconciliations_bank_account_id_foreign` FOREIGN KEY (`bank_account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `fixed_assets`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `fixed_assets`;
CREATE TABLE `fixed_assets` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `asset_code` VARCHAR(255) NOT NULL,
  `asset_name` VARCHAR(255) NOT NULL,
  `asset_category` VARCHAR(255) NOT NULL,
  `purchase_date` DATE NOT NULL,
  `purchase_cost` DECIMAL(10,2) NOT NULL,
  `salvage_value` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `useful_life_years` BIGINT(20) NOT NULL,
  `depreciation_method` VARCHAR(255) NOT NULL,
  `depreciation_rate` DECIMAL(5,2) NOT NULL,
  `accumulated_depreciation` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `book_value` DECIMAL(10,2) NOT NULL,
  `location` VARCHAR(255) NULL,
  `department_id` BIGINT(20) UNSIGNED NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'active',
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `asset_code_unique` (`asset_code`),
  KEY `fixed_assets_department_id_foreign` (`department_id`),
  CONSTRAINT `fixed_assets_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `tax_records`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tax_records`;
CREATE TABLE `tax_records` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `tax_type` VARCHAR(50) NOT NULL,
  `tax_period` VARCHAR(255) NOT NULL,
  `period_start` DATE NOT NULL,
  `period_end` DATE NOT NULL,
  `taxable_amount` DECIMAL(12,2) NOT NULL,
  `tax_rate` DECIMAL(5,2) NOT NULL,
  `tax_amount` DECIMAL(12,2) NOT NULL,
  `paid_amount` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `due_date` DATE NOT NULL,
  `payment_date` DATE NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'calculated',
  `notes` TEXT NULL,
  `created_by` BIGINT(20) UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `tax_records_created_by_foreign` (`created_by`),
  CONSTRAINT `tax_records_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `trainings`
-- Purpose: Stores training programs for employees
-- --------------------------------------------------------

DROP TABLE IF EXISTS `trainings`;
CREATE TABLE `trainings` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `type` ENUM('internal','external','online','workshop','seminar') NOT NULL,
  `status` ENUM('planned','ongoing','completed','cancelled') NOT NULL DEFAULT 'planned',
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `max_participants` INT(11) NOT NULL,
  `cost` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `instructor_id` BIGINT(20) UNSIGNED NULL,
  `department_id` BIGINT(20) UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `trainings_instructor_id_foreign` (`instructor_id`),
  KEY `trainings_department_id_foreign` (`department_id`),
  CONSTRAINT `trainings_instructor_id_foreign` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `trainings_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `training_participants`
-- Purpose: Stores participants in training programs
-- --------------------------------------------------------

DROP TABLE IF EXISTS `training_participants`;
CREATE TABLE `training_participants` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `training_id` BIGINT(20) UNSIGNED NOT NULL,
  `user_id` BIGINT(20) UNSIGNED NOT NULL,
  `status` ENUM('registered','attended','completed','cancelled') NOT NULL DEFAULT 'registered',
  `attendance_rate` DECIMAL(5,2) NULL,
  `grade` DECIMAL(5,2) NULL,
  `certificate_issued` TINYINT(1) NOT NULL DEFAULT 0,
  `certificate_issued_at` TIMESTAMP NULL,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `training_participants_training_id_foreign` (`training_id`),
  KEY `training_participants_user_id_foreign` (`user_id`),
  CONSTRAINT `training_participants_training_id_foreign` FOREIGN KEY (`training_id`) REFERENCES `trainings` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `training_participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  UNIQUE KEY `training_participant_unique` (`training_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `meetings`
-- Purpose: Stores meetings and conferences
-- --------------------------------------------------------

DROP TABLE IF EXISTS `meetings`;
CREATE TABLE `meetings` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `type` ENUM('internal','external','online','in-person','hybrid') NOT NULL,
  `status` ENUM('scheduled','ongoing','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  `start_time` DATETIME NOT NULL,
  `end_time` DATETIME NOT NULL,
  `location` VARCHAR(255) NULL,
  `meeting_link` VARCHAR(500) NULL,
  `organizer_id` BIGINT(20) UNSIGNED NOT NULL,
  `department_id` BIGINT(20) UNSIGNED NOT NULL,
  `agenda` TEXT NULL,
  `minutes` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `meetings_organizer_id_foreign` (`organizer_id`),
  KEY `meetings_department_id_foreign` (`department_id`),
  CONSTRAINT `meetings_organizer_id_foreign` FOREIGN KEY (`organizer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `meetings_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `meeting_participants`
-- Purpose: Stores participants in meetings
-- --------------------------------------------------------

DROP TABLE IF EXISTS `meeting_participants`;
CREATE TABLE `meeting_participants` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `meeting_id` BIGINT(20) UNSIGNED NOT NULL,
  `user_id` BIGINT(20) UNSIGNED NOT NULL,
  `status` ENUM('invited','accepted','declined','attended','absent') NOT NULL DEFAULT 'invited',
  `response_time` TIMESTAMP NULL,
  `attended_at` TIMESTAMP NULL,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `meeting_participants_meeting_id_foreign` (`meeting_id`),
  KEY `meeting_participants_user_id_foreign` (`user_id`),
  CONSTRAINT `meeting_participants_meeting_id_foreign` FOREIGN KEY (`meeting_id`) REFERENCES `meetings` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `meeting_participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  UNIQUE KEY `meeting_participant_unique` (`meeting_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: `task_updates`
-- Purpose: Stores comments and updates on tasks
-- --------------------------------------------------------

DROP TABLE IF EXISTS `task_updates`;
CREATE TABLE `task_updates` (
  `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL,
  `task_id` BIGINT(20) UNSIGNED NOT NULL,
  `user_id` BIGINT(20) UNSIGNED NOT NULL,
  `comment` TEXT NOT NULL,
  `type` ENUM('comment','update') NOT NULL DEFAULT 'comment',
  `metadata` JSON NULL,
  `attachments` JSON NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `task_updates_task_id_foreign` (`task_id`),
  KEY `task_updates_user_id_foreign` (`user_id`),
  CONSTRAINT `task_updates_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `task_updates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- Add foreign key constraints after all tables are created
-- ================================================================

-- Add foreign keys to departments table
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  ADD CONSTRAINT `departments_head_of_department_foreign` FOREIGN KEY (`head_of_department`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;

-- Add foreign key for invoices.contract_id to contracts
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;

-- ================================================================
-- Restore settings
-- ================================================================

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
COMMIT;
