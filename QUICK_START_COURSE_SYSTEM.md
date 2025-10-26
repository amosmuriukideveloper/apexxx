# Quick Start Guide - Course Management System

## 🚀 Getting Started

This guide will help you quickly set up and start using the course management system.

---

## Prerequisites

✅ Laravel 10+ installed
✅ Filament 3+ installed
✅ Database configured
✅ Storage linked (`php artisan storage:link`)

---

## Step 1: Run Migrations

```bash
php artisan migrate
```

This will create all necessary tables including:
- courses
- course_categories
- course_sections
- course_lectures
- course_quizzes
- quiz_questions
- quiz_answers
- course_enrollments
- creator_payouts

---

## Step 2: Seed Sample Data (Optional)

Create a seeder for course categories:

```bash
php artisan make:seeder CourseCategorySeeder
```

Add sample categories:

```php
<?php

namespace Database\Seeders;

use App\Models\CourseCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Programming', 'icon' => 'heroicon-o-code-bracket'],
            ['name' => 'Business', 'icon' => 'heroicon-o-briefcase'],
            ['name' => 'Design', 'icon' => 'heroicon-o-paint-brush'],
            ['name' => 'Marketing', 'icon' => 'heroicon-o-megaphone'],
            ['name' => 'Photography', 'icon' => 'heroicon-o-camera'],
            ['name' => 'Music', 'icon' => 'heroicon-o-musical-note'],
            ['name' => 'Health & Fitness', 'icon' => 'heroicon-o-heart'],
            ['name' => 'Personal Development', 'icon' => 'heroicon-o-light-bulb'],
        ];

        foreach ($categories as $category) {
            CourseCategory::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'icon' => $category['icon'],
                'is_active' => true,
                'sort_order' => 0,
            ]);
        }
    }
}
```

Run the seeder:

```bash
php artisan db:seed --class=CourseCategorySeeder
```

---

## Step 3: Configure Permissions

### Option A: Simple Setup (Development)

In your User model, add temporary permission checks:

```php
public function can($ability, $arguments = [])
{
    // For development - admins can do everything
    if ($this->isAnyAdmin()) {
        return true;
    }
    
    // Content creators can manage their courses
    if ($this->isContentCreator()) {
        $creatorAbilities = [
            'view_courses', 'create_courses', 'edit_courses', 
            'delete_courses', 'view_quizzes', 'create_quizzes'
        ];
        return in_array($ability, $creatorAbilities);
    }
    
    return parent::can($ability, $arguments);
}
```

### Option B: Production Setup (Using Spatie Permission)

```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

Create permissions seeder:

```bash
php artisan make:seeder CoursePermissionsSeeder
```

---

## Step 4: Register Widgets

### Admin Dashboard

Edit `app/Providers/Filament/AdminPanelProvider.php`:

```php
use App\Filament\Widgets\CourseAnalyticsWidget;
use App\Filament\Widgets\TopPerformingCoursesWidget;
use App\Filament\Widgets\RevenueChartWidget;

public function panel(Panel $panel): Panel
{
    return $panel
        // ... other config
        ->widgets([
            CourseAnalyticsWidget::class,
            TopPerformingCoursesWidget::class,
            RevenueChartWidget::class,
        ]);
}
```

### Creator Dashboard

Already configured in `app/Filament/Creator/Pages/Dashboard.php`

---

## Step 5: Test the System

### As Admin:

1. **Login** to admin panel (usually `/admin`)
2. Navigate to **Courses** - should see empty list
3. Check **Dashboard** - widgets should show zero stats

### As Content Creator:

1. **Login** to creator panel (usually `/creator`)
2. Navigate to **My Courses**
3. Click **Create New Course**
4. Fill in the wizard (3 steps)
5. After creation, you'll be redirected to **Course Builder**
6. Add sections and lectures
7. Click **Submit for Review**

### As Admin (Review):

1. Go back to admin panel
2. Navigate to **Courses**
3. Filter by **Pending Review**
4. Click on the course
5. Click **Review Course** button
6. Review content using the preview widget
7. Choose to **Approve**, **Reject**, or **Request Edits**
8. After approval, click **Publish** to make it live

---

## Step 6: Configure File Storage

### Update `.env`:

```env
FILESYSTEM_DISK=public
```

### Create Storage Directories:

```bash
mkdir -p storage/app/public/course-thumbnails
mkdir -p storage/app/public/course-videos
mkdir -p storage/app/public/course-previews
mkdir -p storage/app/public/lecture-attachments
```

### Link Storage:

```bash
php artisan storage:link
```

---

## Usage Examples

### Creating a Complete Course

1. **Create Course** (Basic Info)
   - Title: "Laravel for Beginners"
   - Category: Programming
   - Difficulty: Beginner
   - Price: $49.99

2. **Add Media**
   - Upload thumbnail (1280x720)
   - Upload intro video (optional)

3. **Add Learning Info**
   - Objectives: ["Learn Laravel basics", "Build a CRUD app"]
   - Requirements: ["Basic PHP knowledge"]
   - Target Audience: ["Beginners", "PHP developers"]

4. **Build Content**
   - Section 1: "Getting Started"
     - Lecture 1: "Introduction" (Video - FREE PREVIEW)
     - Lecture 2: "Installation" (Video)
   - Section 2: "Laravel Basics"
     - Lecture 1: "Routing" (Video)
     - Lecture 2: "Controllers" (Video)
     - Lecture 3: "Quiz: Routing & Controllers" (Quiz)

5. **Create Quiz**
   - Navigate to **Quizzes** → Create
   - Link to "Quiz: Routing & Controllers" lecture
   - Set passing score: 70%
   - Add questions with answers

6. **Submit for Review**

---

## Common Issues & Solutions

### Issue: "Storage link not found"
**Solution:**
```bash
php artisan storage:link
```

### Issue: "Permission denied" errors
**Solution:** Check file permissions on storage directory:
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Issue: Videos not playing
**Solution:**
1. Check video format (MP4 recommended)
2. Ensure video is in `storage/app/public/course-videos`
3. Verify storage is linked correctly

### Issue: "Class not found" errors
**Solution:** Clear caches:
```bash
php artisan clear-compiled
php artisan config:clear
php artisan route:clear
composer dump-autoload
```

### Issue: Widgets not showing
**Solution:** Register widgets in panel provider and clear cache:
```bash
php artisan filament:cache-components
```

---

## Testing Checklist

### Admin Panel:
- [ ] View all courses
- [ ] Filter courses by status
- [ ] Review pending courses
- [ ] Approve a course
- [ ] Reject a course with reason
- [ ] Request edits
- [ ] Publish approved course
- [ ] View analytics widgets
- [ ] View top performing courses
- [ ] Check revenue chart

### Creator Panel:
- [ ] Create new course
- [ ] Add course thumbnail
- [ ] Add sections
- [ ] Add video lectures
- [ ] Add article lectures
- [ ] Create quiz
- [ ] Add quiz questions
- [ ] Submit course for review
- [ ] View revenue dashboard
- [ ] Check course performance metrics

---

## Default URLs

- **Admin Panel:** `http://your-domain.com/admin`
- **Creator Panel:** `http://your-domain.com/creator`
- **Student Panel:** `http://your-domain.com/student` (if configured)

---

## Configuration Tips

### Customize Platform Fee

Edit `app/Filament/Creator/Pages/RevenueDashboard.php`:

```php
// Change 0.30 (30%) to your desired platform fee
$platformFee = $totalEarnings * 0.30;
$netEarnings = $totalEarnings * 0.70;
```

### Video Upload Limits

Edit `php.ini`:

```ini
upload_max_filesize = 500M
post_max_size = 500M
max_execution_time = 300
```

Or in `.htaccess` (Apache):

```apache
php_value upload_max_filesize 500M
php_value post_max_size 500M
```

### Customize Status Labels

Edit course status constants in `app/Models/Course.php`:

```php
const STATUS_DRAFT = 'draft';
const STATUS_PENDING_REVIEW = 'pending_review';
const STATUS_APPROVED = 'approved';
const STATUS_PUBLISHED = 'published';
const STATUS_REJECTED = 'rejected';
```

---

## Next Features to Add

### Recommended Enhancements:

1. **Email Notifications**
   - Course approved/rejected notifications
   - New enrollment notifications
   - Quiz completion notifications

2. **Student Features**
   - Course player interface
   - Progress tracking
   - Certificate generation
   - Course reviews and ratings

3. **Advanced Features**
   - Course bundles
   - Coupons and discounts
   - Affiliate system
   - Live classes integration
   - Course discussions/forum

4. **Payment Integration**
   - Stripe/PayPal integration
   - Subscription plans
   - Refund processing

5. **Analytics**
   - Student engagement metrics
   - Drop-off analysis
   - Quiz performance analytics
   - Revenue forecasting

---

## Support Resources

- **Filament Documentation:** https://filamentphp.com/docs
- **Laravel Documentation:** https://laravel.com/docs
- **Course Management System Docs:** See `COURSE_MANAGEMENT_SYSTEM.md`

---

## Quick Commands Reference

```bash
# Clear all caches
php artisan optimize:clear

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Link storage
php artisan storage:link

# Generate IDE helpers
php artisan ide-helper:generate

# Queue work (for async jobs)
php artisan queue:work

# Schedule work (for cron)
php artisan schedule:work
```

---

## Production Checklist

Before deploying to production:

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Configure proper database credentials
- [ ] Set up SSL certificate
- [ ] Configure CDN for videos
- [ ] Set up queue workers
- [ ] Configure email service
- [ ] Set up backup system
- [ ] Configure caching (Redis/Memcached)
- [ ] Set up monitoring (Sentry, etc.)
- [ ] Configure file storage (S3, DigitalOcean Spaces)
- [ ] Test payment processing
- [ ] Set up proper permissions/roles

---

**Ready to go! 🎉**

Start creating courses and building your learning platform!
