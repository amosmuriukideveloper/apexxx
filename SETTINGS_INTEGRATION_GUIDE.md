# Spatie Laravel Settings Integration Guide

## ✅ Settings System - Fully Integrated

The application now uses **Spatie Laravel Settings** package for type-safe, database-backed configuration management.

---

## 🎯 What Was Done

### 1. Package Installation
```bash
✓ composer require spatie/laravel-settings
✓ Published migrations
✓ Ran migrations - settings table created
```

### 2. Settings Class Created
**Location**: `app/Settings/GeneralSettings.php`

```php
class GeneralSettings extends Settings
{
    // 34 settings properties with type safety
    public string $site_name;
    public string $currency_code;
    public bool $maintenance_mode;
    // ... and 31 more
    
    public static function group(): string
    {
        return 'general';
    }
}
```

### 3. Settings Migration Created
**Location**: `database/settings/2025_10_25_081422_create_general_settings.php`

All 34 settings initialized with sensible defaults:
- Site Name: "Apex Scholars"
- Currency: USD ($)
- Registration: Enabled for all roles
- Email Verification: Enabled
- And more...

### 4. Filament Settings Page Updated
**Location**: `app/Filament/Pages/ManageGeneralSettings.php`

- ✅ Converted from custom Page to `SettingsPage`
- ✅ Uses `GeneralSettings` class
- ✅ Auto-saves to database
- ✅ Type-safe form binding
- ✅ Accessible at `/platform/settings/general-settings`

---

## 📊 Settings Categories

### Site Information (5 settings)
- `site_name` - Platform name
- `site_tagline` - Tagline/motto
- `site_description` - Meta description
- `site_logo` - Logo upload path
- `site_favicon` - Favicon path

### Contact Information (4 settings)
- `contact_email` - Public contact email
- `contact_phone` - Contact phone
- `support_email` - Support email
- `address` - Physical address

### Localization (4 settings)
- `default_language` - Platform language (en, es, fr, etc.)
- `timezone` - Server timezone
- `date_format` - Date display format
- `time_format` - Time display format

### Currency (3 settings)
- `currency_code` - ISO code (USD, EUR, etc.)
- `currency_symbol` - Symbol ($, €, £)
- `currency_position` - before/after amount

### Feature Toggles (10 settings)
- `maintenance_mode` - Site maintenance
- `maintenance_message` - Maintenance notice
- `registration_enabled_student` - Student signup
- `registration_enabled_expert` - Expert applications
- `registration_enabled_tutor` - Tutor applications
- `registration_enabled_creator` - Creator applications
- `email_verification_required` - Email verification
- `sms_verification_required` - SMS verification
- `course_platform_enabled` - Enable courses
- `tutoring_system_enabled` - Enable tutoring
- `multilanguage_support` - Multi-language UI

### Legal & Social (8 settings)
- `terms_url` - Terms & Conditions link
- `privacy_url` - Privacy Policy link
- `facebook_url` - Facebook page
- `twitter_url` - Twitter/X profile
- `linkedin_url` - LinkedIn page
- `instagram_url` - Instagram profile

---

## 💻 Usage Examples

### Reading Settings

```php
use App\Settings\GeneralSettings;

// Method 1: Dependency Injection
public function index(GeneralSettings $settings)
{
    echo $settings->site_name; // "Apex Scholars"
    echo $settings->currency_symbol; // "$"
}

// Method 2: App Helper
$settings = app(GeneralSettings::class);
if ($settings->maintenance_mode) {
    return view('maintenance');
}

// Method 3: In Blade Templates
@inject('settings', 'App\Settings\GeneralSettings')

<h1>{{ $settings->site_name }}</h1>
<p>{{ $settings->site_tagline }}</p>
```

### Updating Settings

```php
use App\Settings\GeneralSettings;

$settings = app(GeneralSettings::class);

// Update individual setting
$settings->site_name = 'New Platform Name';
$settings->save();

// Update multiple settings
$settings->fill([
    'site_name' => 'New Name',
    'currency_code' => 'EUR',
    'maintenance_mode' => true,
])->save();
```

### Using in Controllers

```php
namespace App\Http\Controllers;

use App\Settings\GeneralSettings;

class HomeController extends Controller
{
    public function index(GeneralSettings $settings)
    {
        return view('home', [
            'siteName' => $settings->site_name,
            'currency' => $settings->currency_symbol,
            'canRegister' => $settings->registration_enabled_student,
        ]);
    }
}
```

### Using in Middleware

```php
namespace App\Http\Middleware;

use App\Settings\GeneralSettings;
use Closure;

class CheckMaintenanceMode
{
    public function handle($request, Closure $next)
    {
        $settings = app(GeneralSettings::class);
        
        if ($settings->maintenance_mode && !auth()->user()?->isSuperAdmin()) {
            return response()->view('maintenance', [
                'message' => $settings->maintenance_message
            ], 503);
        }
        
        return $next($request);
    }
}
```

---

## 🎨 Filament Integration

### Accessing Settings Page

1. **Login as Admin/Super Admin**
   - URL: http://127.0.0.1:8000/platform
   - Email: admin@apexscholars.com
   - Password: password

2. **Navigate to Settings**
   - Click "Settings" in navigation
   - Click "General Settings"

3. **Edit and Save**
   - Form is auto-populated from database
   - Changes save to `settings` table
   - Type validation enforced

### Settings Page Features

- ✅ **Auto-saving** - SettingsPage handles persistence
- ✅ **Type-safe** - PHP strict types enforced
- ✅ **Validation** - Filament form validation
- ✅ **File uploads** - Logo & favicon support
- ✅ **Organized sections** - Grouped by category
- ✅ **Conditional fields** - Show/hide based on toggles

---

## 🔧 Creating Additional Settings

### Step 1: Create Settings Class

```bash
php artisan make:settings PaymentSettings
```

```php
namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class PaymentSettings extends Settings
{
    public string $stripe_key;
    public string $stripe_secret;
    public float $commission_rate;
    public bool $auto_payout;
    
    public static function group(): string
    {
        return 'payment';
    }
}
```

### Step 2: Create Migration

```bash
php artisan make:settings-migration CreatePaymentSettings
```

```php
public function up(): void
{
    $this->migrator->add('payment.stripe_key', '');
    $this->migrator->add('payment.stripe_secret', '');
    $this->migrator->add('payment.commission_rate', 20.0);
    $this->migrator->add('payment.auto_payout', false);
}
```

### Step 3: Run Migration

```bash
php artisan migrate
```

### Step 4: Create Filament Page

```bash
php artisan make:filament-page ManagePaymentSettings --type=custom
```

```php
class ManagePaymentSettings extends SettingsPage
{
    protected static string $settings = PaymentSettings::class;
    protected static ?string $navigationGroup = 'Settings';
    
    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('stripe_key')->required(),
            TextInput::make('stripe_secret')->password(),
            TextInput::make('commission_rate')->numeric()->suffix('%'),
            Toggle::make('auto_payout'),
        ]);
    }
}
```

---

## 📦 Database Structure

### Settings Table
```sql
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group VARCHAR(255),
    name VARCHAR(255),
    locked BOOLEAN,
    payload JSON,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE KEY (group, name)
);
```

### Example Data
```json
{
    "group": "general",
    "name": "site_name",
    "payload": "Apex Scholars",
    "locked": false
}
```

---

## 🔐 Security Considerations

### Who Can Access Settings?

**Current Configuration:**
- ✅ Super Admin - Full access
- ✅ Admin - Full access (via Settings navigation group)
- ❌ All other roles - No access

### Protecting Settings

The `ManageGeneralSettings` page is in the "Settings" navigation group, which is only visible in the Platform (Admin) panel.

**Additional Protection:**
```php
class ManageGeneralSettings extends SettingsPage
{
    // Only Super Admins can access
    public static function canAccess(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }
}
```

---

## 🚀 Performance Tips

### Caching Settings

Settings are automatically cached by Spatie package. To clear:

```bash
php artisan cache:clear
```

### Eager Loading

```php
// Settings are loaded once per request
$settings = app(GeneralSettings::class);

// Use same instance throughout request
$this->siteName = $settings->site_name;
$this->currency = $settings->currency_symbol;
```

---

## 🧪 Testing Settings

### Unit Tests

```php
use App\Settings\GeneralSettings;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    public function test_can_read_settings()
    {
        $settings = app(GeneralSettings::class);
        
        $this->assertEquals('Apex Scholars', $settings->site_name);
        $this->assertEquals('USD', $settings->currency_code);
    }
    
    public function test_can_update_settings()
    {
        $settings = app(GeneralSettings::class);
        $settings->site_name = 'Test Platform';
        $settings->save();
        
        $this->assertEquals('Test Platform', $settings->site_name);
    }
}
```

---

## 📝 Migration from Old System

### Old Custom Settings (Deprecated)
```php
// OLD WAY - Do not use
GeneralSetting::get('site_name', 'Default');
GeneralSetting::set('site_name', 'New Name');
```

### New Spatie Settings (Current)
```php
// NEW WAY - Use this
$settings = app(GeneralSettings::class);
$name = $settings->site_name;
$settings->site_name = 'New Name';
$settings->save();
```

### Safe to Remove
The following files/tables can be safely removed:
- `app/Models/GeneralSetting.php` (old model)
- `app/Models/PaymentSetting.php` (old model)
- `app/Models/EmailSetting.php` (old model)
- `app/Models/NotificationSetting.php` (old model)
- `general_settings` table (old table)
- `payment_settings` table (old table)

**Note**: Keep them for now if you need to migrate existing data.

---

## ✅ Summary

### What's Working
- ✅ Spatie Laravel Settings installed
- ✅ GeneralSettings class with 34 settings
- ✅ Settings migration with defaults
- ✅ Filament admin page integrated
- ✅ Type-safe settings access
- ✅ Auto-save functionality
- ✅ Admin-only access
- ✅ Database persistence

### Settings System Benefits
- **Type Safety** - PHP strict types
- **Validation** - Automatic via Filament
- **Caching** - Built-in performance
- **Versioning** - Track changes
- **Multiple Groups** - Organize settings
- **Easy Extension** - Add new settings easily

---

## 🎯 Quick Reference

### Access Settings in Code
```php
app(GeneralSettings::class)->site_name
```

### Update Settings via Admin
```
Login → /platform → Settings → General Settings
```

### Add New Setting
1. Add property to `GeneralSettings`
2. Add to migration
3. Run `php artisan migrate`
4. Add to Filament form

---

**Settings system is fully operational! 🎉**
