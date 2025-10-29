-- SQL to create user_progress table on production
-- Run this in cPanel phpMyAdmin on database: scholars1_apexx

CREATE TABLE IF NOT EXISTS `user_progress` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `course_id` BIGINT UNSIGNED NULL,
  `lecture_id` BIGINT UNSIGNED NULL,
  `activity_type` VARCHAR(255) NOT NULL DEFAULT 'lesson_completion',
  `progress_value` INT NOT NULL DEFAULT 0,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `user_progress_user_id_foreign` (`user_id`),
  INDEX `user_progress_course_id_foreign` (`course_id`),
  INDEX `user_progress_user_id_updated_at_index` (`user_id`, `updated_at`),
  INDEX `user_progress_user_id_course_id_index` (`user_id`, `course_id`),
  CONSTRAINT `user_progress_user_id_foreign` 
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_progress_course_id_foreign` 
    FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add migration record so it doesn't run again
INSERT INTO `migrations` (`migration`, `batch`) 
VALUES ('2025_10_29_085017_create_user_progress_table_fix', 2);
