-- ================================================================
-- Fix Database Issues - Solvesta
-- Run these SQL queries to fix the database issues
-- ================================================================

-- Fix 1: Add AUTO_INCREMENT to project_team_members.id
ALTER TABLE `project_team_members` 
MODIFY COLUMN `id` BIGINT(20) UNSIGNED AUTO_INCREMENT NOT NULL;

-- Fix 2: Add amount column to sales table (if not exists)
-- If column already exists, this will just modify it to ensure correct type
-- If you get "Duplicate column" error, skip this and use the MODIFY statement below instead

-- Option 1: Try to add column (will fail if exists, that's OK)
-- ALTER TABLE `sales` 
-- ADD COLUMN `amount` DECIMAL(15,2) NULL AFTER `actual_value`;

-- Option 2: If column already exists, just ensure it has correct type
ALTER TABLE `sales` 
MODIFY COLUMN `amount` DECIMAL(15,2) NULL;

-- Optional: Populate amount with existing values (actual_value or estimated_value)
UPDATE `sales` 
SET `amount` = COALESCE(`actual_value`, `estimated_value`) 
WHERE `amount` IS NULL;

-- ================================================================
-- Verification Queries (Optional - to check if fixes are applied)
-- ================================================================

-- Check project_team_members structure
-- SHOW CREATE TABLE `project_team_members`;

-- Check sales table structure
-- SHOW CREATE TABLE `sales`;

-- Check if amount column exists
-- DESCRIBE `sales`;

