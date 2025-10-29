@echo off
echo ========================================
echo   APEX SCHOLARS - DEPLOYMENT PREPARATION
echo ========================================
echo.

echo [1/6] Clearing all caches...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo ✓ Caches cleared
echo.

echo [2/6] Building production assets...
call npm run build
echo ✓ Assets built
echo.

echo [3/6] Optimizing application...
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo ✓ Application optimized
echo.

echo [4/6] Creating storage link...
php artisan storage:link
echo ✓ Storage linked
echo.

echo [5/6] Generating application key...
echo.
echo IMPORTANT: Copy this key to your production .env file:
echo.
php artisan key:generate --show
echo.

echo [6/6] Creating deployment package info...
echo ✓ Ready for deployment
echo.

echo ========================================
echo   DEPLOYMENT PREPARATION COMPLETE!
echo ========================================
echo.
echo NEXT STEPS:
echo 1. Update .env file with production database credentials
echo 2. Export your database from phpMyAdmin
echo 3. Follow the DEPLOYMENT_CHECKLIST.md guide
echo 4. Upload files to your hosting
echo.
pause
