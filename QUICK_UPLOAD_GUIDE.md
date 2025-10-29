# 🚀 Quick Upload Guide (File Manager/FTP Only)

## ⚡ FAST TRACK: What to Do Right Now

### 1️⃣ LOCAL PREPARATION (5 minutes)

**Run this batch file:**
```
prepare-deployment.bat
```

This will:
- Clear all caches
- Build production assets
- Optimize the application
- Generate an app key

**Or run manually:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2️⃣ PREPARE PRODUCTION .ENV (2 minutes)

1. Copy `.env.production.example` to `.env.production`
2. Update these values:
   ```
   APP_KEY=base64:PASTE_THE_KEY_FROM_STEP_1
   DB_DATABASE=scholars1_apexx
   DB_USERNAME=your_cpanel_db_username
   DB_PASSWORD=your_cpanel_db_password
   ```
3. Save the file

### 3️⃣ EXPORT DATABASE (1 minute)

1. Open: http://localhost/phpmyadmin
2. Select `apexx` database
3. Click **Export** → **Go**
4. Save as: `production_database.sql`

---

## 📤 UPLOAD TO HOSTING

### Method A: Using cPanel File Manager

#### Step 1: Delete Old Files

1. Login to cPanel
2. Open **File Manager**
3. Navigate to your domain root (usually `public_html/`)
4. **Delete EVERYTHING** in the folder
5. Go up one level (parent directory)
6. Delete any old Laravel folders if they exist

#### Step 2: Upload Laravel Files

1. Go to your home directory (NOT public_html)
2. Click **Upload**
3. Upload these folders/files (drag and drop or select):
   - `app/` folder
   - `bootstrap/` folder
   - `config/` folder
   - `database/` folder
   - `resources/` folder
   - `routes/` folder
   - `storage/` folder
   - `vendor/` folder
   - `artisan` file
   - `composer.json`
   - `composer.lock`

4. Rename `.env.production` to `.env`

#### Step 3: Upload Public Files

1. Navigate to `public_html/` folder
2. Click **Upload**
3. Upload ALL contents from your local `public/` folder:
   - `index.php` ⭐
   - `.htaccess` ⭐
   - `favicon.ico`
   - `build/` folder ⭐
   - `robots.txt`
   - Any other files/folders in public/

### Method B: Using FileZilla (FTP)

#### Setup Connection

1. Open FileZilla
2. Connect using your cPanel FTP credentials:
   - Host: `ftp.scholarsquiver.com`
   - Username: Your cPanel username
   - Password: Your cPanel password
   - Port: 21

#### Upload Structure

**Left side (Local):** `c:\xampp\htdocs\apexxx\`
**Right side (Server):** `/home/username/`

**Upload to `/home/username/` (root):**
- Drag `app/` folder
- Drag `bootstrap/` folder
- Drag `config/` folder
- Drag `database/` folder
- Drag `resources/` folder
- Drag `routes/` folder
- Drag `storage/` folder
- Drag `vendor/` folder
- Drag `artisan`, `composer.json`, `composer.lock`
- Drag `.env.production` (rename to `.env` after upload)

**Upload to `/home/username/public_html/`:**
- Drag ALL files from your local `public/` folder

---

## 🗄️ DATABASE SETUP

### Using cPanel phpMyAdmin

1. Login to cPanel
2. Click **phpMyAdmin**
3. Click **Databases** (left sidebar)
4. Create new database: `scholars1_apexx`
5. Go to **Users** → Create user with strong password
6. Go to **Privileges** → Grant ALL PRIVILEGES
7. Click the database name
8. Click **Import** tab
9. Choose `production_database.sql`
10. Click **Go**
11. Wait for success message

---

## ⚙️ POST-UPLOAD CONFIGURATION

### Fix Paths in index.php

1. Open `public_html/index.php` in cPanel File Manager editor
2. Check the paths look like this:

```php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```

If your Laravel files are elsewhere, update paths accordingly.

### Set Folder Permissions

Using File Manager, right-click these folders → **Change Permissions** → Set to **755**:

```
storage/                    755
storage/framework/          755
storage/framework/cache/    755
storage/framework/sessions/ 755
storage/framework/views/    755
storage/logs/               755
bootstrap/cache/            755
```

### Run Post-Deployment Commands

1. Upload `public/deployment-helper.php` to `public_html/`
2. Visit: `https://scholarsquiver.com/deployment-helper.php?password=change_this_password`
3. Update the password in the file first!
4. Click these buttons in order:
   - **Create Storage Link**
   - **Clear Cache**
   - **Clear Config**
   - **Clear Routes**
   - **Clear Views**
   - **Cache Config**
   - **Cache Routes**
   - **Cache Views**
   - **Optimize All**
5. **DELETE deployment-helper.php after use!**

---

## ✅ VERIFICATION

### Test These URLs

Visit each URL and verify it works:

- ✓ https://scholarsquiver.com
- ✓ https://scholarsquiver.com/login
- ✓ https://scholarsquiver.com/register
- ✓ https://scholarsquiver.com/student/login
- ✓ https://scholarsquiver.com/platform/login

### Test Login

Use seeded credentials:
- Email: `student@example.com`
- Password: `password`

Should redirect to student dashboard.

### Check Browser Console

Press F12 → Console tab
- Should see NO errors
- Should see NO 404s
- Styles should load properly

---

## 🔒 FINAL SECURITY STEPS

### 1. Protect .env File

Add to `public_html/.htaccess` (at the top):

```apache
# Protect sensitive files
<FilesMatch "^\.env">
    Order allow,deny
    Deny from all
</FilesMatch>
```

### 2. Delete Deployment Helper

**IMPORTANT:** Delete this file immediately:
- `public_html/deployment-helper.php`

### 3. Verify Settings

Check `.env` has:
```
APP_ENV=production
APP_DEBUG=false
```

---

## 🆘 TROUBLESHOOTING

### Problem: 500 Internal Server Error

**Solution:**
1. Check `.env` exists in root directory
2. Check folder permissions (755 for directories, 644 for files)
3. Check `storage/` and `bootstrap/cache/` are writable
4. Enable error display temporarily in `.htaccess`:
   ```apache
   php_flag display_errors on
   ```

### Problem: Styles Not Loading

**Solution:**
1. Verify `public_html/build/` folder exists with compiled assets
2. Clear browser cache (Ctrl + Shift + Delete)
3. Check `APP_URL` in `.env` matches your domain

### Problem: Database Connection Failed

**Solution:**
1. Verify database credentials in `.env`
2. Check database user has ALL PRIVILEGES
3. Try `127.0.0.1` instead of `localhost` for `DB_HOST`

### Problem: Routes Not Working (404 errors)

**Solution:**
1. Check `.htaccess` exists in `public_html/`
2. Verify mod_rewrite is enabled (ask your host)
3. Clear route cache using deployment-helper.php

### Problem: File Uploads Not Working

**Solution:**
1. Run storage:link via deployment-helper.php
2. Check `storage/` folder permissions (755 or 777)
3. Verify `FILESYSTEM_DISK=public` in `.env`

---

## 📊 ESTIMATED TIME

| Task | Time |
|------|------|
| Local preparation | 5 min |
| Prepare .env | 2 min |
| Export database | 1 min |
| Delete old files | 2 min |
| Upload Laravel files | 10-15 min |
| Upload public files | 3-5 min |
| Database import | 2-3 min |
| Set permissions | 2 min |
| Run post-deployment | 3 min |
| Verification | 5 min |
| **TOTAL** | **~35-45 min** |

---

## 📝 CHECKLIST

Print this and check off as you go:

- [ ] Run `prepare-deployment.bat`
- [ ] Create production `.env` file
- [ ] Export database from localhost
- [ ] Login to cPanel
- [ ] Delete old hosting files
- [ ] Upload Laravel folders to root
- [ ] Upload public files to public_html
- [ ] Upload .env file
- [ ] Create database in cPanel
- [ ] Import database
- [ ] Fix index.php paths if needed
- [ ] Set folder permissions
- [ ] Upload deployment-helper.php
- [ ] Run storage:link
- [ ] Clear all caches
- [ ] Optimize application
- [ ] Test homepage
- [ ] Test login page
- [ ] Test student login
- [ ] Delete deployment-helper.php
- [ ] Verify .env is protected
- [ ] Check console for errors

---

## 🎉 DONE!

Your application should now be live at:
**https://scholarsquiver.com**

All features working perfectly! 🚀

If you encounter any issues, refer to the TROUBLESHOOTING section above.
