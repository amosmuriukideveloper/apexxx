# Authentication & Onboarding Flow

## 1. System Overview

The Academic Platform implements a multi-guard authentication system with distinct user types and approval workflows.

## 2. User Types & Guards

```php
Guards Configuration:
├─ web (Students) - Direct access after email verification
├─ expert - Requires admin approval
├─ tutor - Requires admin approval  
├─ content_creator - Requires admin approval
├─ admin - Admin users
└─ super_admin - Super admin users
```

## 3. Registration Flows

### 3.1 Student Registration
```
Flow:
1. Student fills registration form
2. Email verification sent
3. Student verifies email
4. Access granted immediately
5. Can access platform and services
```

### 3.2 Expert/Tutor/Content Creator Registration
```
Flow:
1. Initial registration (basic info)
2. Comprehensive application form submission
3. Upload credentials:
   - CV/Resume
   - Certificates
   - ID/Passport
   - Portfolio (if applicable)
4. Status set to "Pending Verification"
5. Cannot access panel until approved
6. Admin reviews application:
   - Verifies documents
   - Checks credentials
   - Reviews experience
7a. If Approved:
    - Status changed to "Active"
    - Email notification sent
    - Panel access granted
7b. If Rejected:
    - Rejection email with feedback
    - Account disabled or deleted
    - Can reapply after addressing issues
```

## 4. Middleware Chain

```php
Route Middleware Order:
├─ 1. Authenticate (check if logged in)
├─ 2. CheckUserStatus (active/pending/suspended)
├─ 3. CheckAccountApproval (for experts/tutors/creators)
├─ 4. CheckSubscription (if applicable)
└─ 5. LogActivity (track user actions)
```

## 5. Implementation Files

### config/auth.php
```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'expert' => [
        'driver' => 'session',
        'provider' => 'experts',
    ],
    'tutor' => [
        'driver' => 'session',
        'provider' => 'tutors',
    ],
    'content_creator' => [
        'driver' => 'session',
        'provider' => 'content_creators',
    ],
    'admin' => [
        'driver' => 'session',
        'provider' => 'users', // User model with admin type
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
    'experts' => [
        'driver' => 'eloquent',
        'model' => App\Models\Expert::class,
    ],
    'tutors' => [
        'driver' => 'eloquent',
        'model' => App\Models\Tutor::class,
    ],
    'content_creators' => [
        'driver' => 'eloquent',
        'model' => App\Models\ContentCreator::class,
    ],
],
```

### app/Http/Middleware/CheckUserStatus.php
```php
public function handle($request, Closure $next)
{
    $user = $request->user();
    
    if (!$user) {
        return redirect()->route('login');
    }
    
    switch ($user->status) {
        case 'suspended':
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Your account has been suspended.');
                
        case 'pending':
            Auth::logout();
            return redirect()->route('login')
                ->with('warning', 'Your account is pending approval.');
                
        case 'rejected':
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Your application was rejected.');
                
        case 'active':
            return $next($request);
            
        default:
            Auth::logout();
            return redirect()->route('login');
    }
}
```

### app/Http/Middleware/CheckAccountApproval.php
```php
public function handle($request, Closure $next)
{
    $user = $request->user();
    
    // Only for experts, tutors, and content creators
    if (in_array($user->getMorphClass(), ['expert', 'tutor', 'content_creator'])) {
        if ($user->application_status !== 'approved') {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Your account is not yet approved.');
        }
    }
    
    return $next($request);
}
```

## 6. Email Notifications

### Application Submitted
```php
// app/Notifications/ApplicationSubmitted.php
class ApplicationSubmitted extends Notification
{
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }
    
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Application Submitted Successfully')
            ->line('Thank you for applying to join our platform.')
            ->line('Your application is under review.')
            ->line('We will notify you once the review is complete.')
            ->line('This typically takes 2-3 business days.');
    }
}
```

### Application Approved
```php
// app/Notifications/ApplicationApproved.php
class ApplicationApproved extends Notification
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Application Approved - Welcome!')
            ->line('Congratulations! Your application has been approved.')
            ->action('Access Your Panel', url('/panel'))
            ->line('You can now start using the platform.');
    }
}
```

### Application Rejected
```php
// app/Notifications/ApplicationRejected.php
class ApplicationRejected extends Notification
{
    protected $reason;
    
    public function __construct($reason)
    {
        $this->reason = $reason;
    }
    
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Application Status Update')
            ->line('Thank you for your interest in joining our platform.')
            ->line('Unfortunately, we cannot approve your application at this time.')
            ->line('Reason: ' . $this->reason)
            ->line('You may reapply after addressing the issues mentioned above.');
    }
}
```

## 7. Status Enums

```php
// app/Enums/ApplicationStatus.php
enum ApplicationStatus: string
{
    case PENDING = 'pending';
    case UNDER_REVIEW = 'under_review';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}

// app/Enums/UserStatus.php
enum UserStatus: string
{
    case ACTIVE = 'active';
    case PENDING = 'pending';
    case SUSPENDED = 'suspended';
    case REJECTED = 'rejected';
}
```
