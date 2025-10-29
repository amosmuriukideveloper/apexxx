# 🚨 DEPLOYMENT FIX - Missing Vendor Directory

## The Problem

Your server is missing the `/vendor` directory which contains all Laravel framework files and dependencies. This is **NORMAL** and expected because:

- The `/vendor` directory is never committed to Git (it's in `.gitignore`)
- It must be generated on the server using Composer
- It contains 100+ MB of dependencies

---

## ✅ SOLUTION: Run These Commands on Your Server

### **Step 1: Connect to Your Server**

```bash
# SSH into your hosting server
ssh your-username@your-server.com

# Navigate to your application directory
cd /path/to/your/application
# Example: cd /home/username/public_html
```

### **Step 2: Install Dependencies**

```bash
# Install all dependencies (this creates /vendor directory)
composer install --optimize-autoloader --no-dev

# This will take 2-5 minutes to complete
# You'll see it downloading Laravel and all packages
```

### **Step 3: Set Permissions**

```bash
# Make storage and bootstrap/cache writable
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# If using Apache (most shared hosting)
chown -R www-data:www-data storage bootstrap/cache

# Or if that doesn't work, try:
chown -R apache:apache storage bootstrap/cache

# Or for your user:
chown -R $USER:$USER storage bootstrap/cache
```

### **Step 4: Run Laravel Setup Commands**

```bash
# Generate application key (IMPORTANT!)
php artisan key:generate

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Clear and cache everything
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### **Step 5: Verify Installation**

```bash
# Check if vendor directory exists
ls -la vendor/

# Check if Laravel is loaded
php artisan --version
# Should show: Laravel Framework 12.35.1

# Check permissions
ls -la storage/
ls -la bootstrap/cache/
```

---

## 🔍 If Composer is Not Installed

If you get "composer: command not found", you need to install it first:

### **Option 1: Check if it's installed with different path**
```bash
# Try these:
/usr/local/bin/composer --version
/usr/bin/composer --version
php composer.phar --version
```

### **Option 2: Install Composer on Server**
```bash
# Download and install
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"

# Then use it like this:
php composer.phar install --optimize-autoloader --no-dev
```

### **Option 3: Ask Your Hosting Provider**
Most hosting providers have composer pre-installed. Contact support and ask:
- "Is Composer installed on my account?"
- "What is the path to composer?"
- "Can you run 'composer install' in my application directory?"

---

## 📋 Complete Deployment Checklist

```bash
# 1. Upload files (except vendor, node_modules, .env)
# Files to upload:
#   - app/
#   - bootstrap/ (but empty bootstrap/cache)
#   - config/
#   - database/
#   - public/
#   - resources/
#   - routes/
#   - storage/ (but empty storage/logs)
#   - composer.json
#   - composer.lock (IMPORTANT!)
#   - artisan
#   - package.json

# 2. Create .env file
cp .env.example .env
nano .env  # Edit with your production settings

# 3. Install dependencies
composer install --optimize-autoloader --no-dev

# 4. Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 5. Generate key
php artisan key:generate

# 6. Run migrations
php artisan migrate --force

# 7. Create storage link
php artisan storage:link

# 8. Cache everything
php artisan optimize

# 9. Generate permissions
php artisan shield:generate --panel=platform --all
php artisan shield:generate --panel=expert --all
php artisan shield:generate --panel=tutor --all
php artisan shield:generate --panel=creator --all
php artisan shield:generate --panel=student --all

# 10. Test
php artisan about
```

---

## 🌐 Web Server Configuration

### **If Using cPanel:**

1. **Set Document Root:**
   - Go to cPanel → Domains
   - Set document root to: `/public_html/public` (or `/home/username/apexxx/public`)

2. **.htaccess File:**
   - Make sure `public/.htaccess` exists
   - It should contain Laravel's default rules

### **If Using Direct Access:**

Make sure your domain points to the `/public` directory, not the root!

```
CORRECT: yourdomain.com → /path/to/apexxx/public
WRONG:   yourdomain.com → /path/to/apexxx
```

---

## 🐛 Common Errors & Solutions

### **Error: "composer: command not found"**
**Solution:** Composer not installed. Use full path or install it:
```bash
php composer.phar install --optimize-autoloader --no-dev
```

### **Error: "Your requirements could not be resolved"**
**Solution:** PHP version mismatch. Check:
```bash
php -v
# Should be PHP 8.2 or higher

# If wrong version, try:
php82 -v  # or php8.2 -v
# Then use: php82 composer.phar install
```

### **Error: "Class 'Illuminate\Foundation\Application' not found"**
**Solution:** Vendor directory not loaded. Run:
```bash
composer install --optimize-autoloader --no-dev
```

### **Error: "No application encryption key has been specified"**
**Solution:** Generate key:
```bash
php artisan key:generate
```

### **Error: "Permission denied" on storage**
**Solution:** Fix permissions:
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### **Error: "The stream or file could not be opened"**
**Solution:** Create directories and set permissions:
```bash
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
chmod -R 775 storage
```

---

## 📦 Files to Upload vs Generate

### **✅ UPLOAD These:**
- `app/` directory
- `bootstrap/` directory (empty cache folder)
- `config/` directory
- `database/` directory
- `public/` directory (including .htaccess)
- `resources/` directory
- `routes/` directory
- `storage/` directory structure (empty logs)
- `composer.json` ⭐ **CRITICAL**
- `composer.lock` ⭐ **CRITICAL**
- `artisan` file
- `.env` (create on server)

### **❌ DO NOT Upload:**
- `vendor/` - Generate with composer
- `node_modules/` - Not needed for production
- `.env` - Create fresh on server
- `storage/logs/*` - Will be generated
- `bootstrap/cache/*` - Will be generated

---

## 🔐 Production .env File

Create this on your server:

```env
APP_NAME="ApexScholars"
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE  # Run php artisan key:generate
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

# Database
DB_CONNECTION=mysql
DB_HOST=localhost  # Or your DB host
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Session & Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database

# Mail (Configure SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourprovider.com
MAIL_PORT=587
MAIL_USERNAME=your_email@domain.com
MAIL_PASSWORD=your_smtp_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Payment Gateways (Live credentials)
MPESA_CONSUMER_KEY=your_live_key
MPESA_CONSUMER_SECRET=your_live_secret
MPESA_SHORTCODE=your_shortcode
MPESA_PASSKEY=your_passkey
MPESA_ENV=production

PAYPAL_MODE=live
PAYPAL_CLIENT_ID=your_live_client_id
PAYPAL_CLIENT_SECRET=your_live_client_secret

# Security
SESSION_SECURE_COOKIE=true
SANCTUM_STATEFUL_DOMAINS=yourdomain.com
```

---

## 🚀 Quick Fix Script

Save this as `deploy.sh` and run on your server:

```bash
#!/bin/bash

echo "🚀 Deploying Laravel Application..."

# Install dependencies
echo "📦 Installing dependencies..."
composer install --optimize-autoloader --no-dev

# Set permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache

# Generate key if needed
if grep -q "APP_KEY=$" .env; then
    echo "🔑 Generating application key..."
    php artisan key:generate
fi

# Run migrations
echo "💾 Running migrations..."
php artisan migrate --force

# Storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# Optimize
echo "⚡ Optimizing..."
php artisan optimize

echo "✅ Deployment complete!"
echo "🌐 Visit your site to test!"
```

Run it:
```bash
chmod +x deploy.sh
./deploy.sh
```

---

## 📞 Contact Hosting Support

If you still have issues, ask your hosting support:

```
Hi Support,

I'm deploying a Laravel 12 application and need help with the following:

1. Can you run "composer install --optimize-autoloader --no-dev" in my application directory?
   Path: /path/to/my/application

2. Can you set these permissions?
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache

3. Can you confirm:
   - PHP version is 8.2 or higher?
   - Composer is installed?
   - Document root points to /public directory?

Thank you!
```

---

## ✅ Verification Steps

After deployment, verify everything works:

```bash
# 1. Check vendor exists
ls -la vendor/laravel/framework/

# 2. Check Laravel version
php artisan --version

# 3. Check database connection
php artisan migrate:status

# 4. Check cache is working
php artisan cache:clear
php artisan config:cache

# 5. Check permissions
ls -la storage/
ls -la bootstrap/cache/

# 6. Test the application
curl -I https://yourdomain.com
# Should return HTTP 200
```

---

## 🎯 Summary

**The Fix (3 Steps):**

1. **SSH to server** → Navigate to app directory
2. **Run:** `composer install --optimize-autoloader --no-dev`
3. **Run:** Setup commands (migrate, cache, etc.)

**Key Points:**
- ✅ Never upload `/vendor` directory
- ✅ Always upload `composer.json` and `composer.lock`
- ✅ Run `composer install` on the server
- ✅ Set proper permissions
- ✅ Point domain to `/public` directory

**Your issue will be fixed once Composer generates the vendor directory!** 🚀
