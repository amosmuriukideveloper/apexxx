# 🚀 Laravel Deployment Guide - ApexScholars Platform

## 🎯 Quick Fix for "Illuminate\Foundation Not Found" Error

### **THE ISSUE:**
Your hosting shows: `Class 'Illuminate\Foundation\Application' not found`

### **THE CAUSE:**
The `/vendor` directory (containing all Laravel framework files) is missing because it wasn't uploaded.

### **THE FIX:**
Run ONE command on your server:
```bash
composer install --optimize-autoloader --no-dev
```

That's it! This creates the vendor directory with all Laravel files.

---

## 📧 Quick Email Template for Support

```
Subject: Need Composer Install - Vendor Directory Missing

Hi,

My Laravel app needs the vendor directory generated. Please run:

cd /home/[USERNAME]/public_html
composer install --optimize-autoloader --no-dev
chmod -R 775 storage bootstrap/cache
php artisan optimize

This will fix the "Illuminate\Foundation not found" error.

Thanks!
```

---

## 📚 Detailed Guides Created

I've created comprehensive guides for you:

1. **URGENT_FIX_COMMANDS.md** ⭐ START HERE
   - Step-by-step fix
   - Commands to send support
   - Troubleshooting

2. **DEPLOYMENT_FIX_GUIDE.md**
   - Complete deployment process
   - Common errors & solutions
   - Verification steps

3. **HOSTING_SUPPORT_REQUEST.txt**
   - Professional email template
   - Technical details for support
   - Copy-paste ready

4. **PRODUCTION_READINESS_CHECKLIST.md**
   - Pre-deployment checklist
   - Security settings
   - Performance optimization

5. **FINAL_PRODUCTION_REPORT.md**
   - Complete platform audit
   - What's ready
   - What needs configuration

---

## ✅ Pre-Deployment Checklist

### **Files to Upload:**
- [x] ✅ composer.json (VERIFIED - exists and valid)
- [x] ✅ composer.lock (VERIFIED - exists)
- [ ] app/ directory
- [ ] config/ directory
- [ ] database/ directory
- [ ] public/ directory
- [ ] resources/ directory
- [ ] routes/ directory
- [ ] storage/ directory structure
- [ ] bootstrap/ directory
- [ ] artisan file

### **Files NOT to Upload:**
- [x] ❌ vendor/ (will be generated)
- [x] ❌ node_modules/ (not needed)
- [x] ❌ .env (create fresh on server)
- [x] ❌ .git/ (version control)

### **On Server After Upload:**
1. [ ] Create .env file
2. [ ] Run: composer install
3. [ ] Run: php artisan key:generate
4. [ ] Run: php artisan migrate
5. [ ] Run: php artisan optimize
6. [ ] Set permissions (775 on storage)

---

## 🔧 Server Requirements

### **Minimum Requirements:**
- PHP 8.2 or higher ⭐ CRITICAL
- MySQL 5.7+ or MariaDB 10.3+
- Composer installed
- Apache with mod_rewrite OR Nginx

### **Required PHP Extensions:**
```
✅ BCMath
✅ Ctype
✅ cURL
✅ DOM
✅ Fileinfo
✅ JSON
✅ Mbstring
✅ OpenSSL
✅ PCRE
✅ PDO
✅ PDO MySQL
✅ Tokenizer
✅ XML
✅ GD or Imagick
```

---

## 🌐 Web Server Configuration

### **Document Root MUST Point to /public:**

```
CORRECT: 
Domain Root → /home/username/apexxx/public

WRONG:
Domain Root → /home/username/apexxx
```

### **cPanel Setup:**
1. Go to Domains
2. Set Document Root: `/public`
3. Enable PHP 8.2+

### **.htaccess Must Exist in /public:**
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

## 🔐 Production .env Configuration

```env
# Application
APP_NAME="ApexScholars"
APP_ENV=production
APP_KEY=           # Generate with: php artisan key:generate
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error

# Database
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Session & Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database

# Security
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# Mail (IMPORTANT: Don't use 'log' in production)
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourprovider.com
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🚀 Deployment Commands (Run on Server)

```bash
# 1. Navigate to application
cd /home/username/public_html

# 2. Install dependencies (THE CRITICAL STEP!)
composer install --optimize-autoloader --no-dev

# 3. Create .env from example
cp .env.example .env
nano .env  # Edit with your settings

# 4. Generate app key
php artisan key:generate

# 5. Run migrations
php artisan migrate --force

# 6. Create storage link
php artisan storage:link

# 7. Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 8. Optimize
php artisan optimize

# 9. Generate permissions
php artisan shield:generate --panel=platform --all
php artisan shield:generate --panel=expert --all
php artisan shield:generate --panel=tutor --all
php artisan shield:generate --panel=creator --all
php artisan shield:generate --panel=student --all

# 10. Verify
php artisan about
```

---

## 🐛 Troubleshooting

### **Error: composer command not found**
```bash
# Try full path
/usr/local/bin/composer install --optimize-autoloader --no-dev

# Or check where it is
which composer
whereis composer

# Or use composer.phar
php composer.phar install --optimize-autoloader --no-dev
```

### **Error: PHP version too low**
```bash
# Check version
php -v

# Try specific version
php82 artisan --version
/usr/bin/php8.2 artisan --version

# Use correct version for composer
php82 /usr/local/bin/composer install --optimize-autoloader --no-dev
```

### **Error: Permission denied**
```bash
# Fix ownership and permissions
sudo chown -R $USER:$USER .
chmod -R 755 .
chmod -R 775 storage bootstrap/cache
```

### **Error: No application encryption key**
```bash
# Generate new key
php artisan key:generate
```

### **Error: Database connection failed**
```bash
# Check .env database settings
cat .env | grep DB_

# Test connection
php artisan migrate:status
```

---

## ✅ Verification Steps

After deployment, check:

```bash
# 1. Vendor exists
ls -la vendor/laravel/framework/
# Should list files

# 2. Laravel version
php artisan --version
# Should show: Laravel Framework 12.35.1

# 3. Environment
php artisan about
# Should show all green

# 4. Database
php artisan migrate:status
# Should list migrations

# 5. Cache
php artisan config:cache
php artisan route:cache
# Should complete without errors

# 6. Website
curl -I https://yourdomain.com
# Should return HTTP 200

# 7. Admin panel
curl -I https://yourdomain.com/platform
# Should return HTTP 200
```

---

## 🎯 Your Application URLs

After deployment, these will be accessible:

```
Main Site:     https://yourdomain.com
Admin Panel:   https://yourdomain.com/platform
Student:       https://yourdomain.com/student
Expert:        https://yourdomain.com/expert
Tutor:         https://yourdomain.com/tutor
Creator:       https://yourdomain.com/creator
```

---

## 📞 Need Help?

### **Option 1: Contact Your Hosting Support**
Use the template in `HOSTING_SUPPORT_REQUEST.txt`

### **Option 2: Common Hosting Providers**

**cPanel/WHM:**
```bash
# Usually has composer at:
/usr/local/bin/composer

# Or accessible through Terminal in cPanel
```

**Plesk:**
```bash
# Use Plesk's terminal
# Composer is usually pre-installed
```

**VPS/Cloud:**
```bash
# You have full SSH access
# Run commands directly
```

---

## 🎉 Success Indicators

You'll know it's working when:

✅ No more "Illuminate\Foundation" errors  
✅ Website loads without errors  
✅ Admin login page appears  
✅ `/vendor` directory exists on server  
✅ `php artisan --version` works  
✅ Database tables created  

---

## 📝 Important Notes

1. **Never upload vendor/** - Always generate it with composer
2. **Always upload composer.lock** - Ensures consistent dependencies
3. **Point domain to /public** - Security requirement
4. **Use production .env** - Not your local .env
5. **Set APP_DEBUG=false** - Never true in production
6. **Use HTTPS** - Enable SSL certificate
7. **Set proper permissions** - 775 for storage/

---

## 🆘 Emergency Contact Template

```
URGENT: Laravel Application Not Loading

Error: Class 'Illuminate\Foundation\Application' not found
Server: [Your hosting provider]
Domain: [yourdomain.com]
Account: [your username]

Required Action:
Run "composer install" in my application directory to generate missing vendor files.

Full path: /home/[username]/public_html
Command: composer install --optimize-autoloader --no-dev

This is a standard Laravel deployment step that creates the framework files.
Estimated time: 2-5 minutes

Please prioritize - site is completely down.
```

---

## ✅ Final Checklist

Before contacting support, verify:

- [ ] composer.json uploaded to server ✅ (VERIFIED)
- [ ] composer.lock uploaded to server ✅ (VERIFIED)
- [ ] .env file created on server
- [ ] Document root points to /public
- [ ] PHP version is 8.2+
- [ ] MySQL database exists
- [ ] Database credentials in .env are correct

---

**Your application is ready to deploy!**

The ONLY issue is the missing vendor directory, which is fixed with one command:
```bash
composer install --optimize-autoloader --no-dev
```

🚀 **Good luck with your deployment!**
