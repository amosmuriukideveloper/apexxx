-- =====================================================
-- PRODUCTION DATABASE FIXES
-- scholarsquiver.com - scholars1_apexx database
-- =====================================================
-- This SQL script can be run directly if you cannot run migrations
-- IMPORTANT: Backup your database before running this script!
-- =====================================================

-- Fix 1: Create user_progress table
-- =====================================================
CREATE TABLE IF NOT EXISTS `user_progress` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `course_id` bigint unsigned DEFAULT NULL,
  `lesson_id` bigint unsigned DEFAULT NULL,
  `activity_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lesson_completion',
  `progress_value` int NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_progress_user_id_foreign` (`user_id`),
  KEY `user_progress_course_id_foreign` (`course_id`),
  KEY `user_progress_lesson_id_foreign` (`lesson_id`),
  KEY `user_progress_user_id_updated_at_index` (`user_id`,`updated_at`),
  KEY `user_progress_user_id_course_id_index` (`user_id`,`course_id`),
  CONSTRAINT `user_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_progress_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `user_progress_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Fix 2: Add status column to course_enrollments
-- =====================================================
-- Check if column exists first
SET @dbname = DATABASE();
SET @tablename = 'course_enrollments';
SET @columnname = 'status';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN `status` enum(''active'',''completed'',''cancelled'',''refunded'') NOT NULL DEFAULT ''active'' AFTER `student_id`;')
));

PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Update existing enrollments with proper status
-- =====================================================
UPDATE `course_enrollments` 
SET `status` = 'completed' 
WHERE `completed_at` IS NOT NULL AND (`status` IS NULL OR `status` = 'active');

UPDATE `course_enrollments` 
SET `status` = 'active' 
WHERE `completed_at` IS NULL AND (`status` IS NULL OR `status` = '');

-- Fix 3: Seed Payment Settings
-- =====================================================
INSERT INTO `settings` (`group`, `name`, `payload`, `locked`, `created_at`, `updated_at`) 
VALUES 
('payment', 'mpesa_active', 'false', 0, NOW(), NOW()),
('payment', 'mpesa_environment', '"sandbox"', 0, NOW(), NOW()),
('payment', 'mpesa_consumer_key', '""', 0, NOW(), NOW()),
('payment', 'mpesa_consumer_secret', '""', 0, NOW(), NOW()),
('payment', 'mpesa_shortcode', '""', 0, NOW(), NOW()),
('payment', 'mpesa_passkey', '""', 0, NOW(), NOW()),
('payment', 'mpesa_callback_url', '""', 0, NOW(), NOW()),
('payment', 'mpesa_timeout', '30', 0, NOW(), NOW()),
('payment', 'paypal_active', 'false', 0, NOW(), NOW()),
('payment', 'paypal_environment', '"sandbox"', 0, NOW(), NOW()),
('payment', 'paypal_client_id', '""', 0, NOW(), NOW()),
('payment', 'paypal_client_secret', '""', 0, NOW(), NOW()),
('payment', 'paypal_webhook_id', '""', 0, NOW(), NOW()),
('payment', 'paypal_currencies', '["USD","EUR","GBP"]', 0, NOW(), NOW()),
('payment', 'paypal_return_url', '""', 0, NOW(), NOW()),
('payment', 'paypal_cancel_url', '""', 0, NOW(), NOW()),
('payment', 'pesapal_active', 'false', 0, NOW(), NOW()),
('payment', 'pesapal_demo_mode', 'true', 0, NOW(), NOW()),
('payment', 'pesapal_consumer_key', '""', 0, NOW(), NOW()),
('payment', 'pesapal_consumer_secret', '""', 0, NOW(), NOW()),
('payment', 'pesapal_ipn_url', '""', 0, NOW(), NOW()),
('payment', 'pesapal_card_types', '["VISA","MASTERCARD"]', 0, NOW(), NOW()),
('payment', 'commission_rate', '20.00', 0, NOW(), NOW()),
('payment', 'minimum_payout', '10.00', 0, NOW(), NOW()),
('payment', 'payout_schedule', '"weekly"', 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
  `payload` = VALUES(`payload`),
  `updated_at` = NOW();

-- Fix 4: Seed Notification Settings
-- =====================================================
INSERT INTO `settings` (`group`, `name`, `payload`, `locked`, `created_at`, `updated_at`) 
VALUES 
('notification', 'application_submitted_email', 'true', 0, NOW(), NOW()),
('notification', 'application_submitted_database', 'true', 0, NOW(), NOW()),
('notification', 'application_approved_email', 'true', 0, NOW(), NOW()),
('notification', 'application_approved_database', 'true', 0, NOW(), NOW()),
('notification', 'application_rejected_email', 'true', 0, NOW(), NOW()),
('notification', 'application_rejected_database', 'true', 0, NOW(), NOW()),
('notification', 'project_assigned_email', 'true', 0, NOW(), NOW()),
('notification', 'project_assigned_database', 'true', 0, NOW(), NOW()),
('notification', 'project_submitted_email', 'true', 0, NOW(), NOW()),
('notification', 'project_submitted_database', 'true', 0, NOW(), NOW()),
('notification', 'project_completed_email', 'true', 0, NOW(), NOW()),
('notification', 'project_completed_database', 'true', 0, NOW(), NOW()),
('notification', 'tutoring_request_email', 'true', 0, NOW(), NOW()),
('notification', 'tutoring_request_database', 'true', 0, NOW(), NOW()),
('notification', 'tutoring_accepted_email', 'true', 0, NOW(), NOW()),
('notification', 'tutoring_accepted_database', 'true', 0, NOW(), NOW()),
('notification', 'tutoring_completed_email', 'true', 0, NOW(), NOW()),
('notification', 'tutoring_completed_database', 'true', 0, NOW(), NOW()),
('notification', 'payment_received_email', 'true', 0, NOW(), NOW()),
('notification', 'payment_received_database', 'true', 0, NOW(), NOW()),
('notification', 'payout_processed_email', 'true', 0, NOW(), NOW()),
('notification', 'payout_processed_database', 'true', 0, NOW(), NOW()),
('notification', 'system_maintenance_email', 'true', 0, NOW(), NOW()),
('notification', 'system_maintenance_database', 'true', 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
  `payload` = VALUES(`payload`),
  `updated_at` = NOW();

-- Fix 5: Seed Project Pricing Settings
-- =====================================================
INSERT INTO `settings` (`group`, `name`, `payload`, `locked`, `created_at`, `updated_at`) 
VALUES 
('project_pricing', 'base_rates', '{"essay":10,"research_paper":15,"dissertation":25,"thesis":20,"case_study":12,"lab_report":11,"presentation":8,"assignment":9,"coursework":10,"article":13,"coding_project":20,"data_analysis":18}', 0, NOW(), NOW()),
('project_pricing', 'easy_multiplier', '1.0', 0, NOW(), NOW()),
('project_pricing', 'medium_multiplier', '1.3', 0, NOW(), NOW()),
('project_pricing', 'hard_multiplier', '1.6', 0, NOW(), NOW()),
('project_pricing', 'normal_urgency_multiplier', '1.0', 0, NOW(), NOW()),
('project_pricing', 'urgent_multiplier', '1.5', 0, NOW(), NOW()),
('project_pricing', 'super_urgent_multiplier', '2.0', 0, NOW(), NOW()),
('project_pricing', 'currency_code', '"USD"', 0, NOW(), NOW()),
('project_pricing', 'currency_symbol', '"$"', 0, NOW(), NOW()),
('project_pricing', 'currency_position', '"before"', 0, NOW(), NOW()),
('project_pricing', 'decimal_places', '2', 0, NOW(), NOW()),
('project_pricing', 'rounding_mode', '"nearest"', 0, NOW(), NOW()),
('project_pricing', 'platform_commission_percentage', '20.0', 0, NOW(), NOW()),
('project_pricing', 'minimum_project_cost', '10.00', 0, NOW(), NOW()),
('project_pricing', 'maximum_project_cost', '10000.00', 0, NOW(), NOW()),
('project_pricing', 'tax_enabled', 'false', 0, NOW(), NOW()),
('project_pricing', 'tax_percentage', '0.0', 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
  `payload` = VALUES(`payload`),
  `updated_at` = NOW();

-- Verification Queries
-- =====================================================
-- Run these to verify the fixes

-- Check if user_progress table exists
SELECT 'user_progress table exists' AS status, COUNT(*) AS count FROM user_progress;

-- Check if status column exists in course_enrollments
SELECT 'status column exists' AS status FROM INFORMATION_SCHEMA.COLUMNS 
WHERE table_schema = DATABASE() 
  AND table_name = 'course_enrollments' 
  AND column_name = 'status';

-- Check if payment settings exist
SELECT 'Payment settings count' AS status, COUNT(*) AS count 
FROM settings WHERE `group` = 'payment';

-- Check if notification settings exist
SELECT 'Notification settings count' AS status, COUNT(*) AS count 
FROM settings WHERE `group` = 'notification';

-- Check if project pricing settings exist
SELECT 'Project pricing settings count' AS status, COUNT(*) AS count 
FROM settings WHERE `group` = 'project_pricing';

-- =====================================================
-- END OF PRODUCTION FIXES
-- =====================================================
