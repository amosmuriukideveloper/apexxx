# 🔐 Authentication Guards & Middleware Chain

## ✅ Implementation Complete!

This document explains the multi-guard authentication system and comprehensive middleware chain implemented for the Apex Scholars platform.

---

## 📋 Authentication Guards

### Guard Configuration

**File**: `config/auth.php`

```php
Guards:
├─ web (Students)               // Default for students and regular users
├─ expert                       // For expert professionals
├─ tutor                        // For tutors
├─ content_creator             // For content creators
├─ admin                       // For administrators
└─ super_admin                 // For super administrators
```

### Guard Details

| Guard | Provider | Model | Purpose |
|-------|----------|-------|---------|
| `web` | users | User | Students, general users |
| `expert` | experts | Expert | Expert professionals |
| `tutor` | tutors | Tutor | Tutoring professionals |
| `content_creator` | content_creators | ContentCreator | Course creators |
| `admin` | users | User | Admin users with admin role |
| `super_admin` | users | User | Super admin users |

---

## 🛡️ Middleware Chain

### Complete Middleware Stack

```php
Middleware Chain (in order):
├─ 1. Authenticate               // Ensures user is logged in
├─ 2. CheckUserStatus            // Checks if account is active/suspended/deleted
├─ 3. CheckApplicationStatus     // Verifies application approval (experts/tutors/creators)
├─ 4. CheckSubscription          // Validates active subscription (if applicable)
└─ 5. LogActivity               // Logs user actions for auditing
```

---

## 🔍 Middleware Details

### 1. Authenticate Middleware

**Purpose**: Verify user is authenticated

**Location**: Built-in Laravel middleware

**What it does**:
- Checks if user is logged in
- Redirects to login if not authenticated
- Uses the appropriate guard for the panel

**Usage**:
```php
// Applied automatically by Filament panels
->authMiddleware([
    Authenticate::class,
])
```

---

### 2. CheckUserStatus Middleware

**Purpose**: Ensure user account is active and not suspended

**File**: `app/Http/Middleware/CheckUserStatus.php`

**Checks**:
- ✅ Active accounts → Allow access
- ❌ Suspended accounts → Logout & redirect to login
- ❌ Inactive accounts → Logout & redirect to login  
- ❌ Deleted accounts → Logout & redirect to login

**Status Values**:
```php
'active'     → Account is active (allowed)
'suspended'  → Account suspended by admin (blocked)
'inactive'   → Account inactive (blocked)
'deleted'    → Account deleted (blocked)
```

**Error Messages**:
```
Suspended: "Your account has been suspended. Please contact support for assistance."
Inactive:  "Your account is inactive. Please contact support to reactivate your account."
Deleted:   "Your account has been deleted."
```

**Usage**:
```php
->middleware([
    'check.user.status',
])
```

---

### 3. CheckApplicationStatus Middleware

**Purpose**: Block panel access for pending/rejected professional applications

**File**: `app/Http/Middleware/CheckApplicationStatus.php`

**Applies to**: Experts, Tutors, Content Creators

**Checks**:
- ✅ Approved application → Allow access
- ❌ Pending application → Block with pending message
- ❌ Rejected application → Block with rejection reason
- ❌ No application → Block with no application message

**Application Statuses**:
```php
'pending'   → Application awaiting review (blocked)
'approved'  → Application approved (allowed)
'rejected'  → Application rejected (blocked)
```

**Error Messages**:
```
Pending:  "Your [expert/tutor/creator] application is pending review. 
           You will receive an email once it has been approved."

Rejected: "Your [expert/tutor/creator] application was rejected. 
           Reason: [rejection_reason]"

No App:   "No [expert/tutor/creator] application found"
```

**Usage**:
```php
// Expert panel
->middleware([
    'check.application:expert',
])

// Tutor panel
->middleware([
    'check.application:tutor',
])

// Creator panel
->middleware([
    'check.application:creator',
])
```

---

### 4. CheckSubscription Middleware

**Purpose**: Verify user has active subscription (if required)

**File**: `app/Http/Middleware/CheckSubscription.php`

**Checks**:
- ✅ Active subscription → Allow access
- ✅ Admin users → Skip check
- ❌ No subscription → Redirect to subscription required page
- ❌ Expired subscription → Redirect to renewal page
- ❌ Cancelled subscription → Block with message
- ❌ Past due subscription → Block with payment message

**Subscription Statuses**:
```php
'active'    → Subscription active (allowed)
'expired'   → Subscription expired (blocked)
'cancelled' → Subscription cancelled (blocked)
'past_due'  → Payment past due (blocked)
'suspended' → Subscription suspended (blocked)
```

**Error Messages**:
```
Expired:   "Your subscription has expired. Please renew to continue."
Cancelled: "Your subscription has been cancelled."
Past Due:  "Your subscription payment is past due. Please update your payment method."
Suspended: "Your subscription has been suspended. Please contact support."
```

**Usage**:
```php
->middleware([
    'check.subscription',
])
```

**Note**: Currently optional - can be enabled when subscription system is implemented.

---

### 5. LogActivity Middleware

**Purpose**: Log all user actions for auditing and security

**File**: `app/Http/Middleware\LogActivity.php`

**What it logs**:
- User ID
- Action (created/updated/deleted)
- Model type and ID
- Description
- IP address
- User agent
- HTTP method
- Request URL
- Status code
- Metadata (form data, etc.)

**Logged Actions**:
```php
POST        → 'created'
PUT/PATCH   → 'updated'
DELETE      → 'deleted'
```

**Skipped Routes**:
- Login
- Register
- Password reset
- Logout

**Storage**: `system_logs` table

**Example Log Entry**:
```json
{
    "user_id": 1,
    "action": "created",
    "model_type": "App\\Models\\Project",
    "model_id": 123,
    "description": "John Doe created Project",
    "ip_address": "192.168.1.1",
    "user_agent": "Mozilla/5.0...",
    "request_method": "POST",
    "request_url": "https://example.com/projects",
    "status_code": 200,
    "metadata": {
        "route": "projects.store",
        "form_data": {...}
    }
}
```

**Usage**:
```php
->middleware([
    'log.activity',
])
```

---

## 🎯 Panel-Specific Middleware Configuration

### Student Panel (Web Guard)

**Panel**: `/student`

**Middleware Stack**:
```php
->authGuard('web')
->middleware([
    'auth',
    'check.user.status',
    'log.activity',
])
```

**No subscription or application check needed for students.**

---

### Expert Panel (Expert Guard)

**Panel**: `/expert`

**Middleware Stack**:
```php
->authGuard('expert')
->middleware([
    'auth',
    'check.user.status',
    'check.application:expert',
    'check.subscription',  // Optional
    'log.activity',
])
```

**Flow**:
1. Must be authenticated as expert
2. Account must be active
3. Application must be approved
4. (Optional) Subscription must be active
5. All actions logged

---

### Tutor Panel (Tutor Guard)

**Panel**: `/tutor`

**Middleware Stack**:
```php
->authGuard('tutor')
->middleware([
    'auth',
    'check.user.status',
    'check.application:tutor',
    'check.subscription',  // Optional
    'log.activity',
])
```

---

### Creator Panel (Content Creator Guard)

**Panel**: `/creator`

**Middleware Stack**:
```php
->authGuard('content_creator')
->middleware([
    'auth',
    'check.user.status',
    'check.application:creator',
    'check.subscription',  // Optional
    'log.activity',
])
```

---

### Admin Panel (Admin Guard)

**Panel**: `/platform` or `/admin`

**Middleware Stack**:
```php
->authGuard('web')  // Uses web guard with role check
->middleware([
    'auth',
    'check.user.status',
    'log.activity',
])
```

**No application or subscription check for admins.**

---

## 📊 Access Control Flow

### Student Access Flow

```
1. User visits /student
   ↓
2. Authenticate middleware → Check login
   ↓
3. CheckUserStatus → Verify account is active
   ↓
4. LogActivity → Log the access
   ↓
5. ✅ Grant access to student panel
```

### Expert Access Flow

```
1. User visits /expert
   ↓
2. Authenticate → Check expert login
   ↓
3. CheckUserStatus → Verify account is active
   ↓
4. CheckApplicationStatus:expert → Verify application approved
   ↓
5. CheckSubscription → Verify active subscription (optional)
   ↓
6. LogActivity → Log the access
   ↓
7. ✅ Grant access to expert panel

If any check fails → ❌ Block access with appropriate message
```

---

## 🔐 Security Features

### Multi-Layer Protection

**Layer 1: Authentication**
- Users must be logged in
- Correct guard must be used
- Session-based authentication

**Layer 2: Account Status**
- Active accounts only
- Suspended/inactive accounts blocked
- Automatic logout on status change

**Layer 3: Application Approval**
- Professionals must be approved
- Pending applications blocked
- Rejected applications blocked with reason

**Layer 4: Subscription (Optional)**
- Active subscription required
- Expired subscriptions blocked
- Payment issues handled

**Layer 5: Activity Logging**
- All actions logged
- Full audit trail
- IP and user agent tracked

---

## 🛠️ Implementation in Panel Providers

### Example: Expert Panel Provider

**File**: `app/Providers/Filament/ExpertPanelProvider.php`

```php
use Filament\Http\Middleware\Authenticate;

public function panel(Panel $panel): Panel
{
    return $panel
        ->id('expert')
        ->path('expert')
        ->authGuard('expert')  // ← Use expert guard
        ->middleware([
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,
            'check.user.status',          // ← Check account status
            'check.application:expert',   // ← Check application approved
            // 'check.subscription',      // ← Optional: Check subscription
            'log.activity',               // ← Log all actions
        ])
        ->authMiddleware([
            Authenticate::class,
        ]);
}
```

---

## 📋 Middleware Registration

**File**: `bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware): void {
    // Register middleware aliases
    $middleware->alias([
        'check.user.status' => \App\Http\Middleware\CheckUserStatus::class,
        'check.application' => \App\Http\Middleware\CheckApplicationStatus::class,
        'check.subscription' => \App\Http\Middleware\CheckSubscription::class,
        'log.activity' => \App\Http\Middleware\LogActivity::class,
    ]);
})
```

---

## 🧪 Testing the Guards & Middleware

### Test 1: Student Access (Basic)

```
1. Login as student
2. Visit /student
3. ✅ Should access panel
4. Check system_logs table
5. ✅ Should see logged activity
```

### Test 2: Expert with Pending Application

```
1. Register as expert
2. Application status: pending
3. Try to visit /expert
4. ❌ Should be blocked
5. ✅ Should see: "Application pending review"
```

### Test 3: Expert with Approved Application

```
1. Admin approves expert application
2. Expert logs in
3. Visits /expert
4. ✅ Should access panel
5. ✅ Activity logged
```

### Test 4: Suspended Account

```
1. Admin suspends user account
2. User tries to access panel
3. ❌ Should be logged out
4. ✅ Should see: "Account suspended"
```

### Test 5: Expired Subscription

```
1. User subscription expires
2. User tries to access panel
3. ❌ Should be blocked
4. ✅ Should redirect to renewal page
```

---

## 📊 Database Tables

### System Logs Table

**Table**: `system_logs`

**Columns**:
```sql
id
user_id             // Who performed the action
action              // created/updated/deleted
model_type          // App\Models\Project
model_id            // ID of the model
description         // Human-readable description
ip_address          // User's IP
user_agent          // Browser/device info
request_method      // POST/PUT/DELETE
request_url         // Full URL
status_code         // HTTP response code
metadata            // JSON with additional data
created_at
updated_at
```

---

## 🎯 Summary

**Guards Implemented**:
- ✅ 6 authentication guards
- ✅ Separate models for experts, tutors, creators
- ✅ Flexible guard-based authentication

**Middleware Implemented**:
- ✅ CheckUserStatus - Account status verification
- ✅ CheckApplicationStatus - Application approval check
- ✅ CheckSubscription - Subscription validation
- ✅ LogActivity - Comprehensive activity logging

**Security Benefits**:
- ✅ Multi-layer access control
- ✅ Automatic status enforcement
- ✅ Complete audit trail
- ✅ Role-based access
- ✅ Application approval workflow
- ✅ Subscription management (optional)

---

## 🚀 Next Steps

1. **Apply to All Panels**:
   - Update all panel providers with appropriate middleware
   - Configure guards for each panel type

2. **Test Each Flow**:
   - Test student access
   - Test expert application workflow
   - Test tutor application workflow
   - Test creator application workflow
   - Test admin access
   - Test suspended accounts
   - Test subscription validation

3. **Monitor Logs**:
   - Review system_logs table
   - Check for suspicious activity
   - Audit user actions

4. **Optional Enhancements**:
   - Add rate limiting
   - Add IP whitelisting
   - Add two-factor authentication
   - Add session management

---

**Complete multi-guard authentication system with comprehensive security middleware is ready! 🔐**
