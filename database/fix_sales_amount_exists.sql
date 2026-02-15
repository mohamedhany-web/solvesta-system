-- Fix for sales table: Modify amount column (since it already exists)
-- This will update the column type if needed

ALTER TABLE `sales` 
MODIFY COLUMN `amount` DECIMAL(15,2) NULL;

-- Populate amount with existing values if it's NULL
UPDATE `sales` 
SET `amount` = COALESCE(`actual_value`, `estimated_value`) 
WHERE `amount` IS NULL;

