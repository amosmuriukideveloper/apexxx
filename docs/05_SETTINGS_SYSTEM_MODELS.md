# Settings & System Models

## 1. Settings Models

### 1.1 GeneralSetting Model

```php
// app/Models/GeneralSetting.php
class GeneralSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];
    
    protected $casts = [
        'value' => 'array',
    ];
    
    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }
        
        // If it's a simple type, return the first value
        if (in_array($setting->type, ['text', 'number', 'boolean'])) {
            return $setting->value[0] ?? $default;
        }
        
        return $setting->value ?? $default;
    }
    
    /**
     * Set a setting value
     */
    public static function set(string $key, $value, string $type = 'text', string $group = 'general')
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? $value : [$value],
                'type' => $type,
                'group' => $group,
            ]
        );
    }
}
```

**Migration:**
```php
Schema::create('general_settings', function (Blueprint $table) {
    $table->id();
    $table->string('key')->unique();
    $table->json('value');
    $table->enum('type', ['text', 'number', 'boolean', 'json'])->default('text');
    $table->enum('group', ['general', 'site', 'seo', 'social'])->default('general');
    $table->timestamps();
});
```

**Default Settings Seeder:**
```php
// database/seeders/GeneralSettingsSeeder.php
class GeneralSettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            // General
            ['key' => 'site_name', 'value' => ['Academic Platform'], 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_logo', 'value' => ['/images/logo.png'], 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_favicon', 'value' => ['/images/favicon.ico'], 'type' => 'text', 'group' => 'general'],
            ['key' => 'contact_email', 'value' => ['support@platform.com'], 'type' => 'text', 'group' => 'general'],
            ['key' => 'contact_phone', 'value' => ['+1234567890'], 'type' => 'text', 'group' => 'general'],
            ['key' => 'address', 'value' => ['123 Main St, City, Country'], 'type' => 'text', 'group' => 'general'],
            ['key' => 'timezone', 'value' => ['UTC'], 'type' => 'text', 'group' => 'general'],
            ['key' => 'currency', 'value' => ['USD'], 'type' => 'text', 'group' => 'general'],
            ['key' => 'language', 'value' => ['en'], 'type' => 'text', 'group' => 'general'],
            ['key' => 'maintenance_mode', 'value' => [false], 'type' => 'boolean', 'group' => 'general'],
            ['key' => 'registration_enabled', 'value' => [true], 'type' => 'boolean', 'group' => 'general'],
            
            // SEO
            ['key' => 'meta_title', 'value' => ['Academic Platform'], 'type' => 'text', 'group' => 'seo'],
            ['key' => 'meta_description', 'value' => ['Educational platform'], 'type' => 'text', 'group' => 'seo'],
            ['key' => 'meta_keywords', 'value' => ['education, tutoring, courses'], 'type' => 'text', 'group' => 'seo'],
        ];
        
        foreach ($settings as $setting) {
            GeneralSetting::create($setting);
        }
    }
}
```

### 1.2 PaymentSetting Model

```php
// app/Models/PaymentSetting.php
class PaymentSetting extends Model
{
    protected $fillable = [
        'provider',
        'is_active',
        'is_test_mode',
        'credentials',
        'commission_rate',
        'minimum_payout',
        'payout_schedule',
        'config',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'is_test_mode' => 'boolean',
        'credentials' => 'encrypted:array',
        'commission_rate' => 'decimal:2',
        'minimum_payout' => 'decimal:2',
        'config' => 'array',
    ];
}
```

**Migration:**
```php
Schema::create('payment_settings', function (Blueprint $table) {
    $table->id();
    $table->enum('provider', ['mpesa', 'paypal', 'pesapal', 'stripe'])->unique();
    $table->boolean('is_active')->default(false);
    $table->boolean('is_test_mode')->default(true);
    $table->text('credentials'); // Encrypted JSON
    $table->decimal('commission_rate', 5, 2)->default(10.00); // Percentage
    $table->decimal('minimum_payout', 10, 2)->default(50.00);
    $table->enum('payout_schedule', ['weekly', 'bi_weekly', 'monthly'])->default('monthly');
    $table->json('config')->nullable();
    $table->timestamps();
});
```

**Payment Settings Seeder:**
```php
class PaymentSettingsSeeder extends Seeder
{
    public function run()
    {
        $providers = [
            [
                'provider' => 'mpesa',
                'is_active' => false,
                'is_test_mode' => true,
                'credentials' => [
                    'consumer_key' => '',
                    'consumer_secret' => '',
                    'shortcode' => '',
                    'passkey' => '',
                    'callback_url' => '',
                ],
                'commission_rate' => 10.00,
                'minimum_payout' => 100.00,
                'payout_schedule' => 'monthly',
            ],
            [
                'provider' => 'paypal',
                'is_active' => false,
                'is_test_mode' => true,
                'credentials' => [
                    'client_id' => '',
                    'client_secret' => '',
                    'webhook_id' => '',
                ],
                'commission_rate' => 10.00,
                'minimum_payout' => 50.00,
                'payout_schedule' => 'bi_weekly',
            ],
            [
                'provider' => 'pesapal',
                'is_active' => false,
                'is_test_mode' => true,
                'credentials' => [
                    'consumer_key' => '',
                    'consumer_secret' => '',
                    'ipn_url' => '',
                ],
                'commission_rate' => 10.00,
                'minimum_payout' => 100.00,
                'payout_schedule' => 'monthly',
            ],
        ];
        
        foreach ($providers as $provider) {
            PaymentSetting::create($provider);
        }
    }
}
```

### 1.3 EmailSetting Model

```php
// app/Models/EmailSetting.php
class EmailSetting extends Model
{
    protected $fillable = [
        'driver',
        'host',
        'port',
        'username',
        'password',
        'encryption',
        'from_address',
        'from_name',
        'is_active',
    ];
    
    protected $casts = [
        'password' => 'encrypted',
        'is_active' => 'boolean',
    ];
    
    protected $hidden = [
        'password',
    ];
}
```

**Migration:**
```php
Schema::create('email_settings', function (Blueprint $table) {
    $table->id();
    $table->enum('driver', ['smtp', 'mailgun', 'ses', 'sendmail'])->default('smtp');
    $table->string('host')->nullable();
    $table->integer('port')->nullable();
    $table->string('username')->nullable();
    $table->text('password')->nullable(); // Encrypted
    $table->enum('encryption', ['tls', 'ssl', 'none'])->nullable();
    $table->string('from_address');
    $table->string('from_name');
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 1.4 NotificationSetting Model

```php
// app/Models/NotificationSetting.php
class NotificationSetting extends Model
{
    protected $fillable = [
        'channel',
        'event_type',
        'is_enabled',
        'template',
        'recipients',
    ];
    
    protected $casts = [
        'is_enabled' => 'boolean',
        'recipients' => 'array',
    ];
}
```

**Migration:**
```php
Schema::create('notification_settings', function (Blueprint $table) {
    $table->id();
    $table->enum('channel', ['email', 'sms', 'push', 'database']);
    $table->string('event_type'); // project_assigned, payment_received, etc.
    $table->boolean('is_enabled')->default(true);
    $table->text('template')->nullable();
    $table->json('recipients')->nullable(); // Roles/users to notify
    $table->timestamps();
    
    $table->unique(['channel', 'event_type']);
});
```

**Notification Events Seeder:**
```php
class NotificationSettingsSeeder extends Seeder
{
    public function run()
    {
        $events = [
            // Project Events
            ['channel' => 'email', 'event_type' => 'project_created', 'is_enabled' => true],
            ['channel' => 'email', 'event_type' => 'project_assigned', 'is_enabled' => true],
            ['channel' => 'email', 'event_type' => 'project_submitted', 'is_enabled' => true],
            ['channel' => 'email', 'event_type' => 'project_completed', 'is_enabled' => true],
            ['channel' => 'email', 'event_type' => 'revision_requested', 'is_enabled' => true],
            
            // Application Events
            ['channel' => 'email', 'event_type' => 'application_submitted', 'is_enabled' => true],
            ['channel' => 'email', 'event_type' => 'application_approved', 'is_enabled' => true],
            ['channel' => 'email', 'event_type' => 'application_rejected', 'is_enabled' => true],
            
            // Tutoring Events
            ['channel' => 'email', 'event_type' => 'tutoring_request_created', 'is_enabled' => true],
            ['channel' => 'email', 'event_type' => 'session_scheduled', 'is_enabled' => true],
            ['channel' => 'email', 'event_type' => 'session_reminder', 'is_enabled' => true],
            ['channel' => 'email', 'event_type' => 'session_completed', 'is_enabled' => true],
            
            // Course Events
            ['channel' => 'email', 'event_type' => 'course_published', 'is_enabled' => true],
            ['channel' => 'email', 'event_type' => 'course_enrolled', 'is_enabled' => true],
            ['channel' => 'email', 'event_type' => 'certificate_issued', 'is_enabled' => true],
            
            // Payment Events
            ['channel' => 'email', 'event_type' => 'payment_received', 'is_enabled' => true],
            ['channel' => 'email', 'event_type' => 'payout_requested', 'is_enabled' => true],
            ['channel' => 'email', 'event_type' => 'payout_processed', 'is_enabled' => true],
        ];
        
        foreach ($events as $event) {
            NotificationSetting::create($event);
        }
    }
}
```

### 1.5 PlatformConfiguration Model

```php
// app/Models/PlatformConfiguration.php
class PlatformConfiguration extends Model
{
    protected $fillable = [
        'key',
        'value',
        'description',
        'type',
        'group',
    ];
    
    protected $casts = [
        'value' => 'array',
    ];
    
    public static function get(string $key, $default = null)
    {
        $config = static::where('key', $key)->first();
        return $config ? $config->value : $default;
    }
    
    public static function set(string $key, $value)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
```

**Migration:**
```php
Schema::create('platform_configurations', function (Blueprint $table) {
    $table->id();
    $table->string('key')->unique();
    $table->json('value');
    $table->text('description')->nullable();
    $table->string('type')->default('text');
    $table->string('group')->default('general');
    $table->timestamps();
});
```

**Configuration Seeder:**
```php
class PlatformConfigurationSeeder extends Seeder
{
    public function run()
    {
        $configs = [
            // Project Settings
            [
                'key' => 'project_commission_rate',
                'value' => ['15'],
                'description' => 'Platform commission on projects (%)',
                'type' => 'number',
                'group' => 'projects',
            ],
            [
                'key' => 'project_auto_assignment',
                'value' => [false],
                'description' => 'Auto-assign projects to experts',
                'type' => 'boolean',
                'group' => 'projects',
            ],
            
            // Tutoring Settings
            [
                'key' => 'tutoring_commission_rate',
                'value' => ['20'],
                'description' => 'Platform commission on tutoring (%)',
                'type' => 'number',
                'group' => 'tutoring',
            ],
            [
                'key' => 'session_cancellation_hours',
                'value' => ['24'],
                'description' => 'Hours before session to allow cancellation',
                'type' => 'number',
                'group' => 'tutoring',
            ],
            
            // Course Settings
            [
                'key' => 'course_commission_rate',
                'value' => ['30'],
                'description' => 'Platform commission on courses (%)',
                'type' => 'number',
                'group' => 'courses',
            ],
            [
                'key' => 'course_approval_required',
                'value' => [true],
                'description' => 'Courses require admin approval',
                'type' => 'boolean',
                'group' => 'courses',
            ],
            
            // Quality Control
            [
                'key' => 'turnitin_check_required',
                'value' => [true],
                'description' => 'Require Turnitin check for projects',
                'type' => 'boolean',
                'group' => 'quality',
            ],
            [
                'key' => 'max_turnitin_score',
                'value' => ['20'],
                'description' => 'Maximum allowed Turnitin score (%)',
                'type' => 'number',
                'group' => 'quality',
            ],
            [
                'key' => 'ai_detection_required',
                'value' => [true],
                'description' => 'Require AI detection check',
                'type' => 'boolean',
                'group' => 'quality',
            ],
            [
                'key' => 'max_ai_detection_score',
                'value' => ['15'],
                'description' => 'Maximum allowed AI detection score (%)',
                'type' => 'number',
                'group' => 'quality',
            ],
        ];
        
        foreach ($configs as $config) {
            PlatformConfiguration::create($config);
        }
    }
}
```

## 2. System Models

### 2.1 Activity Log (Spatie)

The platform uses Spatie Activity Log package for comprehensive activity tracking.

**Installation:**
```bash
composer require spatie/laravel-activitylog
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan migrate
```

**Usage in Models:**
```php
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Project extends Model
{
    use LogsActivity;
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'expert_id', 'deadline', 'cost'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
    
    protected static function booted()
    {
        static::created(function ($project) {
            activity()
                ->performedOn($project)
                ->causedBy(auth()->user())
                ->log('Project created');
        });
        
        static::updated(function ($project) {
            activity()
                ->performedOn($project)
                ->causedBy(auth()->user())
                ->log('Project updated');
        });
    }
}
```

### 2.2 SystemLog Model

```php
// app/Models/SystemLog.php
class SystemLog extends Model
{
    protected $fillable = [
        'level',
        'message',
        'context',
        'user_id',
        'user_type',
        'ip_address',
        'user_agent',
    ];
    
    protected $casts = [
        'context' => 'array',
    ];
    
    public static function log(string $level, string $message, array $context = [])
    {
        return static::create([
            'level' => $level,
            'message' => $message,
            'context' => $context,
            'user_id' => auth()->id(),
            'user_type' => auth()->user() ? get_class(auth()->user()) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
    
    public static function info(string $message, array $context = [])
    {
        return static::log('info', $message, $context);
    }
    
    public static function warning(string $message, array $context = [])
    {
        return static::log('warning', $message, $context);
    }
    
    public static function error(string $message, array $context = [])
    {
        return static::log('error', $message, $context);
    }
    
    public static function critical(string $message, array $context = [])
    {
        return static::log('critical', $message, $context);
    }
}
```

**Migration:**
```php
Schema::create('system_logs', function (Blueprint $table) {
    $table->id();
    $table->enum('level', ['info', 'warning', 'error', 'critical']);
    $table->text('message');
    $table->json('context')->nullable();
    $table->unsignedBigInteger('user_id')->nullable();
    $table->string('user_type')->nullable();
    $table->string('ip_address', 45)->nullable();
    $table->text('user_agent')->nullable();
    $table->timestamps();
    
    $table->index(['level', 'created_at']);
    $table->index(['user_id', 'user_type']);
});
```

### 2.3 Notification Model (Laravel Built-in)

Laravel's built-in notifications table is used for database notifications.

**Migration:**
```bash
php artisan notifications:table
php artisan migrate
```

### 2.4 AuditTrail Model

```php
// app/Models/AuditTrail.php
class AuditTrail extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'action',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'url',
        'ip_address',
        'user_agent',
    ];
    
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];
    
    public function user()
    {
        return $this->morphTo('user');
    }
    
    public function auditable()
    {
        return $this->morphTo();
    }
}
```

**Migration:**
```php
Schema::create('audit_trails', function (Blueprint $table) {
    $table->id();
    $table->string('user_type')->nullable();
    $table->unsignedBigInteger('user_id')->nullable();
    $table->string('action'); // created, updated, deleted, viewed
    $table->string('auditable_type');
    $table->unsignedBigInteger('auditable_id');
    $table->json('old_values')->nullable();
    $table->json('new_values')->nullable();
    $table->string('url')->nullable();
    $table->string('ip_address', 45)->nullable();
    $table->text('user_agent')->nullable();
    $table->timestamps();
    
    $table->index(['user_type', 'user_id']);
    $table->index(['auditable_type', 'auditable_id']);
    $table->index('action');
});
```

**Audit Trait:**
```php
// app/Traits/Auditable.php
namespace App\Traits;

use App\Models\AuditTrail;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            static::audit('created', $model);
        });
        
        static::updated(function ($model) {
            static::audit('updated', $model);
        });
        
        static::deleted(function ($model) {
            static::audit('deleted', $model);
        });
    }
    
    protected static function audit(string $action, $model)
    {
        AuditTrail::create([
            'user_type' => auth()->user() ? get_class(auth()->user()) : null,
            'user_id' => auth()->id(),
            'action' => $action,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->id,
            'old_values' => $action === 'updated' ? $model->getOriginal() : null,
            'new_values' => $action !== 'deleted' ? $model->getAttributes() : null,
            'url' => request()->fullUrl(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
```
