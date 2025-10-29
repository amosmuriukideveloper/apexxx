# Production Deployment Checklist (File Upload Only)

## 🎯 STEP-BY-STEP DEPLOYMENT PROCESS

### PHASE 1: LOCAL PREPARATION (Do this BEFORE uploading)

#### 1.1 Update Environment Configuration
Create a production `.env` file with these settings:

```env
APP_NAME="Apex Scholars"
APP_ENV=production
APP_KEY=base64:YOUR_PRODUCTION_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://scholarsquiver.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=scholars1_apexx
DB_USERNAME=your_production_db_username
DB_PASSWORD=your_production_db_password

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
SESSION_DRIVER=database
SESSION_LIFETIME=120

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

#### 1.2 Run These Commands in Order

```bash
# 1. Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 2. Build frontend assets for production
npm run build

# 3. Optimize for production (creates cached config/routes)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Generate fresh application key (save this!)
php artisan key:generate --show
# Copy the key shown and add it to your production .env as APP_KEY

# 5. Create symbolic link for storage (we'll recreate on server)
php artisan storage:link
```

#### 1.3 Database Preparation

Export your current database:
```bash
# Using XAMPP phpMyAdmin:
# 1. Go to http://localhost/phpmyadmin
# 2. Select 'apexx' database
# 3. Click 'Export' tab
# 4. Choose 'Custom' export method
# 5. Check ALL tables
# 6. Format: SQL
# 7. Click 'Go' to download
# 8. Save as: production_database.sql
```

---

### PHASE 2: WHAT TO DELETE ON HOSTING

⚠️ **CRITICAL: Delete these folders/files from your hosting:**

```
DELETE EVERYTHING EXCEPT:
- Don't delete: public_html/.htaccess (if you have custom rules)
- Don't delete: Any domain-specific files
- Don't delete: Email accounts
- Don't delete: .well-known folder (SSL certificates)

DELETE:
✓ All old Laravel files
✓ All old folders (app, bootstrap, config, database, etc.)
✓ vendor folder
✓ node_modules folder
✓ storage folder (will upload fresh)
✓ Old .env file
```

---

### PHASE 3: WHAT TO UPLOAD

#### 3.1 Files/Folders to Upload

Upload EVERYTHING from your local project EXCEPT these:

❌ **DO NOT UPLOAD:**
- `node_modules/` (too large, not needed)
- `.git/` (version control, not needed)
- `.env` (upload production version separately)
- `storage/framework/cache/` (will be regenerated)
- `storage/framework/sessions/` (will be regenerated)
- `storage/framework/views/` (will be regenerated)
- `storage/logs/*.log` (old logs)
- `tests/` (not needed in production)
- `.gitignore`, `.editorconfig`, `phpunit.xml` (development files)

✅ **MUST UPLOAD:**
- `app/` folder
- `bootstrap/` folder
- `config/` folder
- `database/` folder (migrations and seeders)
- `public/` folder ⭐ **IMPORTANT**
- `resources/` folder
- `routes/` folder
- `storage/` folder (empty structure)
- `vendor/` folder ⭐ **IMPORTANT**
- `artisan` file
- `composer.json`
- `composer.lock`
- `package.json`

#### 3.2 Production .env File

Upload your production `.env` file with correct database credentials

#### 3.3 Public Folder Setup

⚠️ **CRITICAL:** Your hosting document root should point to `public/` folder

If your hosting uses `public_html`:
- Upload contents of `public/` folder to `public_html/`
- Upload all other folders OUTSIDE of `public_html/` (one level up)

**Folder Structure on Server:**
```
/home/username/
├── app/
├── bootstrap/
├── config/
├── database/
├── resources/
├── routes/
├── storage/
├── vendor/
├── artisan
├── composer.json
├── .env
└── public_html/ (or public/)
    ├── index.php ⭐ MUST BE HERE
    ├── .htaccess
    ├── favicon.ico
    └── build/ (built assets)
```

---

### PHASE 4: UPDATE index.php (CRITICAL!)

After uploading, you MUST update `public_html/index.php`:

**Find this line:**
```php
require __DIR__.'/../vendor/autoload.php';
```

**Change to:**
```php
require __DIR__.'/../vendor/autoload.php';
// or if vendor is in a different location:
// require '/home/username/vendor/autoload.php';
```

**Find this line:**
```php
$app = require_once __DIR__.'/../bootstrap/app.php';
```

**Change to:**
```php
$app = require_once __DIR__.'/../bootstrap/app.php';
// or if bootstrap is in a different location:
// $app = require_once '/home/username/bootstrap/app.php';
```

---

### PHASE 5: SET FOLDER PERMISSIONS

Via your hosting control panel (cPanel/Plesk):

Set these folder permissions to **777** or **755** (depending on your host):

```
storage/                    → 777
storage/framework/          → 777
storage/framework/cache/    → 777
storage/framework/sessions/ → 777
storage/framework/views/    → 777
storage/logs/               → 777
bootstrap/cache/            → 777
public/storage/             → 777
```

---

### PHASE 6: DATABASE SETUP

#### 6.1 Create Production Database

Using cPanel/phpMyAdmin:
1. Create database: `scholars1_apexx`
2. Create database user
3. Grant ALL PRIVILEGES to the user
4. Note username and password

#### 6.2 Import Database

Using phpMyAdmin:
1. Select `scholars1_apexx` database
2. Click 'Import' tab
3. Choose your `production_database.sql` file
4. Click 'Go'
5. Wait for import to complete

**OR** Run migrations manually:

If your host provides PHP command line through cPanel:
```bash
php artisan migrate:fresh --seed --force
```

---

### PHASE 7: POST-UPLOAD CONFIGURATION

#### 7.1 Storage Link (via web route)

Create a temporary route to create storage link:

1. Access: `https://scholarsquiver.com/storage-link`

**Add this to `routes/web.php` temporarily:**
```php
Route::get('/storage-link', function () {
    if (app()->environment('production')) {
        Artisan::call('storage:link');
        return 'Storage link created!';
    }
    return 'Not in production mode';
});
```

2. Visit the URL once
3. Remove the route after executing

#### 7.2 Clear Caches (via web route)

Create another temporary route:

```php
Route::get('/clear-all-caches', function () {
    if (app()->environment('production')) {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        return 'All caches cleared!';
    }
    return 'Not in production mode';
});
```

Visit: `https://scholarsquiver.com/clear-all-caches`

#### 7.3 Optimize for Production (via web route)

```php
Route::get('/optimize-production', function () {
    if (app()->environment('production')) {
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        Artisan::call('optimize');
        return 'Application optimized!';
    }
    return 'Not in production mode';
});
```

Visit: `https://scholarsquiver.com/optimize-production`

**⚠️ IMPORTANT: Delete these routes after use!**

---

### PHASE 8: VERIFICATION

#### 8.1 Check These URLs

Visit these to verify everything works:

```
✓ https://scholarsquiver.com (home page)
✓ https://scholarsquiver.com/login (enhanced login)
✓ https://scholarsquiver.com/register (enhanced register)
✓ https://scholarsquiver.com/student (student panel)
✓ https://scholarsquiver.com/platform (admin panel)
```

#### 8.2 Test Login

Use your seeded accounts:
- student@example.com / password
- admin@apexscholars.com / password

#### 8.3 Check for Errors

Look for:
- ✓ No 404 errors
- ✓ No 500 errors
- ✓ Styles loading properly
- ✓ Images displaying
- ✓ Database connections working
- ✓ File uploads working

---

### PHASE 9: SECURITY CHECKS

#### 9.1 Verify .env is Protected

Try accessing: `https://scholarsquiver.com/.env`

Should show **403 Forbidden** or **404 Not Found**

If accessible, add to `.htaccess`:
```apache
<Files .env>
    Order allow,deny
    Deny from all
</Files>
```

#### 9.2 Remove Temporary Routes

Delete all the temporary routes we created:
- `/storage-link`
- `/clear-all-caches`
- `/optimize-production`

#### 9.3 Disable Debug Mode

Ensure in `.env`:
```
APP_DEBUG=false
```

---

## 🚨 COMMON ISSUES & FIXES

### Issue: 500 Internal Server Error

**Fix:**
1. Check folder permissions (step 5)
2. Check `.env` file exists and has correct database credentials
3. Check `storage/` and `bootstrap/cache/` are writable

### Issue: Styles Not Loading

**Fix:**
1. Verify `public/build/` folder exists
2. Check `APP_URL` in `.env` matches your domain
3. Clear browser cache

### Issue: Database Connection Error

**Fix:**
1. Verify database credentials in `.env`
2. Check database user has ALL PRIVILEGES
3. Confirm database exists

### Issue: 404 on All Routes Except Home

**Fix:**
1. Check `.htaccess` exists in `public/` folder
2. Verify mod_rewrite is enabled on server
3. Check document root points to `public/`

### Issue: Storage/Uploads Not Working

**Fix:**
1. Run `/storage-link` route
2. Check `storage/` permissions (777)
3. Verify `FILESYSTEM_DISK=public` in `.env`

---

## ✅ DEPLOYMENT CHECKLIST

Before going live:

- [ ] Local preparation completed
- [ ] Production `.env` created with correct credentials
- [ ] `npm run build` executed successfully
- [ ] Database exported from local
- [ ] Old hosting files deleted
- [ ] All files uploaded (except excluded ones)
- [ ] `index.php` paths updated
- [ ] Folder permissions set correctly
- [ ] Database imported successfully
- [ ] Storage link created
- [ ] Caches cleared and optimized
- [ ] All URLs tested and working
- [ ] Test accounts can log in
- [ ] Styles and assets loading
- [ ] Temporary routes removed
- [ ] `.env` file protected
- [ ] Debug mode disabled

---

## 📞 FINAL NOTES

### Maintenance Mode

If you need to show a maintenance page during updates:

Create `public/.maintenance.html`:
```html
<!DOCTYPE html>
<html>
<head>
    <title>Under Maintenance</title>
</head>
<body>
    <h1>We'll be back soon!</h1>
    <p>Our site is currently undergoing maintenance.</p>
</body>
</html>
```

Then in `.htaccess`:
```apache
# Maintenance Mode
RewriteCond %{REQUEST_URI} !^/\.maintenance\.html$
RewriteRule ^(.*)$ /.maintenance.html [L]
```

Remove when done.

---

## 🎉 YOU'RE DONE!

Your application should now be live and running smoothly at:
**https://scholarsquiver.com**

All features working:
✅ Enhanced login/register pages
✅ Role-based dashboards
✅ Course management
✅ Session management
✅ Proper logout flow
✅ All navigation seamless

**Enjoy your production-ready application! 🚀**
