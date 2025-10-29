# Production Error Fix Guide

## Critical Issues Fixed

This guide addresses all the production errors found on scholarsquiver.com.

### Issues Resolved

1. ✅ Missing `user_progress` table
2. ✅ Missing `status` column in `course_enrollments` table
3. ✅ Missing settings data in database (PaymentSettings, NotificationSettings, ProjectPricingSettings)
4. ✅ Incorrect route names in widgets
5. ✅ Non-existent tutor availability route

## Step-by-Step Deployment Process

### 1. Backup Your Production Database

**CRITICAL: Always backup before running migrations!**

```bash
# SSH into your production server
ssh your-username@scholarsquiver.com

# Navigate to your application directory
cd /path/to/your/application

# Backup the database
php artisan db:backup
# OR manually via cPanel/PHPMyAdmin export
```

### 2. Pull Latest Code Changes

```bash
# Make sure you're in the application directory
cd /path/to/your/application

# Pull the latest changes
git pull origin main
# OR upload the files via FTP if not using git
```

### 3. Clear All Caches

```bash
# Clear application cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear

# Clear compiled files
php artisan clear-compiled
```

### 4. Run New Migrations

The following migrations will be executed:

- `2024_01_01_000033_create_user_progress_table.php` - Creates user_progress table
- `2024_01_01_000034_add_status_to_course_enrollments_table.php` - Adds status column to course_enrollments

```bash
# Run migrations
php artisan migrate --force

# Expected output:
# Migrating: 2024_01_01_000033_create_user_progress_table
# Migrated:  2024_01_01_000033_create_user_progress_table (XX.XXms)
# Migrating: 2024_01_01_000034_add_status_to_course_enrollments_table
# Migrated:  2024_01_01_000034_add_status_to_course_enrollments_table (XX.XXms)
```

### 5. Seed Settings Data

This will populate missing settings in the database:

```bash
# Run the settings seeder only
php artisan db:seed --class=SettingsSeeder

# Expected output:
# ✓ General Settings seeded
# ✓ Payment Settings seeded
# ✓ Email Settings seeded
# ✓ Notification Settings seeded
# ✓ Project Pricing Settings seeded
```

### 6. Update Existing Enrollments (If Needed)

If you have existing course enrollments without the status column, update them:

```bash
# Connect to MySQL
mysql -u your_db_user -p your_db_name

# Run this SQL to set default status for existing enrollments
UPDATE course_enrollments 
SET status = 'active' 
WHERE status IS NULL AND completed_at IS NULL;

UPDATE course_enrollments 
SET status = 'completed' 
WHERE status IS NULL AND completed_at IS NOT NULL;

# Exit MySQL
exit;
```

### 7. Optimize Application

```bash
# Optimize the application
php artisan optimize

# Cache routes
php artisan route:cache

# Cache config
php artisan config:cache

# Cache views
php artisan view:cache
```

### 8. Restart Queue Workers (If Using Queues)

```bash
# Restart queue workers to pick up new code
php artisan queue:restart

# If using Supervisor, restart it
sudo supervisorctl restart all
```

### 9. Verify Fixes

Visit these pages to verify all errors are resolved:

1. **Student Dashboard**: https://scholarsquiver.com/student
   - Should now load without `user_progress` table error
   
2. **Platform Dashboard**: https://scholarsquiver.com/platform
   - CourseAnalyticsWidget should work without `status` column error
   
3. **Payment Settings**: https://scholarsquiver.com/platform/manage-payment-settings
   - Should load without missing properties error
   
4. **Notification Settings**: https://scholarsquiver.com/platform/manage-notification-settings
   - Should load without missing properties error
   
5. **Project Pricing Settings**: https://scholarsquiver.com/platform/manage-project-pricing-settings
   - Should load without missing properties error
   
6. **Project Management**: https://scholarsquiver.com/platform/project-managements
   - Widgets should load with correct routes
   
7. **Tutoring Management**: https://scholarsquiver.com/platform/tutoring-managements
   - Should load without route error

## Rollback Plan (If Something Goes Wrong)

If you encounter issues after deployment:

### 1. Rollback Migrations

```bash
# Rollback the last two migrations
php artisan migrate:rollback --step=2
```

### 2. Restore Database Backup

```bash
# If you need to restore the full database
mysql -u your_db_user -p your_db_name < backup_file.sql
```

### 3. Restore Code

```bash
# If using git
git reset --hard HEAD~1

# If using FTP, restore from your backup
```

## Code Changes Summary

### Files Modified:

1. **CourseAnalyticsWidget.php**
   - Changed `status` to `payment_status` for refund checks
   - Changed completion check from `status` to `completed_at`

2. **ProjectStatsWidget.php**
   - Fixed route from `filament.admin.*` to `filament.platform.*`

3. **ListTutoringRequests.php**
   - Commented out non-existent tutor availability route

4. **SettingsSeeder.php**
   - Added `ProjectPricingSettings` initialization

5. **DatabaseSeeder.php**
   - Added call to `SettingsSeeder`

### Files Created:

1. **2024_01_01_000033_create_user_progress_table.php**
   - Creates `user_progress` table with proper indexes

2. **2024_01_01_000034_add_status_to_course_enrollments_table.php**
   - Adds `status` enum column to `course_enrollments`

## Post-Deployment Monitoring

Monitor your application logs for any new errors:

```bash
# Tail the Laravel log
tail -f storage/logs/laravel.log

# Check for PHP errors
tail -f /path/to/php/error.log
```

## Support

If you encounter any issues during deployment:

1. Check the Laravel log: `storage/logs/laravel.log`
2. Enable debug mode temporarily: Set `APP_DEBUG=true` in `.env`
3. Clear all caches and try again
4. Rollback and contact your developer

## Expected Results

After successful deployment:

- ✅ All 7+ errors should be resolved
- ✅ Student dashboard loads correctly with learning streak
- ✅ Platform widgets display correct statistics
- ✅ Settings pages load without errors
- ✅ No missing table or column errors
- ✅ No route definition errors
- ✅ Application performance is maintained

## Database Schema Changes

### New Tables:

```sql
CREATE TABLE `user_progress` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `course_id` bigint unsigned DEFAULT NULL,
  `lesson_id` bigint unsigned DEFAULT NULL,
  `activity_type` varchar(255) DEFAULT 'lesson_completion',
  `progress_value` int DEFAULT '0',
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_progress_user_id_updated_at_index` (`user_id`,`updated_at`),
  KEY `user_progress_user_id_course_id_index` (`user_id`,`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Modified Tables:

```sql
ALTER TABLE `course_enrollments` 
ADD COLUMN `status` enum('active','completed','cancelled','refunded') 
NOT NULL DEFAULT 'active' 
AFTER `student_id`;
```

## Testing Checklist

- [ ] Backup database completed
- [ ] Code deployed to production
- [ ] Migrations run successfully
- [ ] Settings seeded successfully
- [ ] Caches cleared
- [ ] Application optimized
- [ ] All error pages now load correctly
- [ ] No new errors in logs
- [ ] Student dashboard functional
- [ ] Platform dashboard functional
- [ ] Settings pages accessible
- [ ] Widgets display correct data

---

**Last Updated**: October 29, 2025
**Author**: Development Team
**Status**: Ready for Production Deployment
