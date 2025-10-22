# Quick Start: Onboarding System

## For Admins: How to Review Applications

### Option 1: Quick Review from Table
1. Navigate to **Admin Panel** → **Applications**
2. Find the application you want to review
3. Click the **action button** (three dots)
4. Choose:
   - **Mark Under Review** - Flag for detailed review
   - **Approve** - Instantly approve (shows password)
   - **Reject** - Reject with reason

### Option 2: Detailed Review Page
1. Navigate to **Admin Panel** → **Applications**
2. Click on an application row or **View** action
3. Click **Review** tab or button
4. You'll see:
   - **Application Details** (all submitted info)
   - **Review Checklist** (verify requirements)
   - **Decision Form** (approve/reject/under review)
5. Make your decision and submit

### Approval Process
When you approve:
```
✓ System creates account automatically
✓ Password is generated (12 characters)
✓ Status updated to "approved"  
✓ You see the password in notification
✓ Copy it to send to the applicant
```

**Note:** Email functionality is not yet implemented. You must manually send the credentials.

### Rejection Process
When you reject:
```
✓ Rejection reason is required
✓ Status updated to "rejected"
✓ Applicant will see reason on login attempt
```

---

## For Developers: Implementation Details

### Files Modified
```
app/Filament/Resources/ApplicationFormResource.php
app/Filament/Resources/ApplicationFormResource/Pages/ReviewApplication.php
app/Http/Middleware/CheckAccountApproval.php
resources/views/filament/resources/application-form-resource/pages/review-application.blade.php
```

### Key Features
- ✅ Database transactions for data integrity
- ✅ Automatic account creation (Expert/Tutor/Creator)
- ✅ Secure password generation
- ✅ Access control via middleware
- ✅ Status tracking
- ✅ Error handling with rollback

### Middleware Setup
Add to your middleware stack:
```php
// bootstrap/app.php or app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        // ... other middleware
        \App\Http\Middleware\CheckUserStatus::class,
        \App\Http\Middleware\CheckAccountApproval::class,
    ],
];
```

### Testing
1. **Create test application:**
   ```
   - Use ApplicationFormResource
   - Fill all required fields
   - Submit
   ```

2. **Test approval:**
   ```php
   - Admin approves application
   - Check experts/tutors/content_creators table
   - Verify account created
   - Note the password
   ```

3. **Test access control:**
   ```php
   - Try to access panel with pending status
   - Should be redirected with warning
   - Approve application
   - Try again - should work
   ```

---

## Status Meanings

| Status | What It Means | Next Action |
|--------|---------------|-------------|
| **Pending** | Just submitted | Admin should review |
| **Under Review** | Being evaluated | Admin will decide soon |
| **Approved** | Account created | Applicant can login |
| **Rejected** | Not qualified | Can reapply later |

---

## Common Issues & Solutions

### Issue: "Approval Failed" Error
**Cause:** Database constraint violation (duplicate email, missing required field)
**Solution:** 
- Check for existing account with same email
- Verify all required fields are filled
- Check application logs

### Issue: Applicant Can't Login After Approval
**Cause:** Middleware blocking access
**Solution:**
- Verify `application_status` = 'approved'
- Verify `status` = 'active'
- Check middleware is properly registered
- Clear application cache

### Issue: Password Not Showing After Approval
**Cause:** Notification dismissed too quickly
**Solution:**
- Notification is set to `persistent()`
- It stays until manually dismissed
- Check browser notifications
- Re-approve if needed (will show password again)

---

## Email Implementation (TODO)

To complete the workflow, implement:

### 1. WelcomeEmail
```php
// app/Mail/WelcomeEmail.php
use Illuminate\Mail\Mailable;

class WelcomeEmail extends Mailable
{
    public function build()
    {
        return $this->view('emails.welcome')
            ->subject('Welcome to Academic Platform')
            ->with([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
                'panel_url' => config('app.url') . '/expert',
            ]);
    }
}
```

### 2. ApplicationRejectedEmail
```php
// app/Mail/ApplicationRejectedEmail.php
class ApplicationRejectedEmail extends Mailable
{
    public function build()
    {
        return $this->view('emails.application-rejected')
            ->subject('Application Status Update')
            ->with([
                'name' => $this->name,
                'reason' => $this->reason,
            ]);
    }
}
```

### 3. Usage in Resource
```php
// In ApplicationFormResource approval action
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

Mail::to($record->email)->send(new WelcomeEmail(
    $record->full_name,
    $record->email,
    $password
));
```

---

## Security Checklist

- [x] Passwords are randomly generated (12 chars)
- [x] Database transactions prevent partial updates
- [x] Access control via middleware
- [x] Status validation on every request
- [x] Activity logging enabled
- [x] Error messages are user-friendly
- [ ] Email notifications (pending)
- [ ] Password change on first login (recommended)
- [ ] Two-factor authentication (future)

---

## Quick Reference: Code Locations

**Application Resource:**
`app/Filament/Resources/ApplicationFormResource.php`

**Review Page:**
`app/Filament/Resources/ApplicationFormResource/Pages/ReviewApplication.php`

**Middleware:**
`app/Http/Middleware/CheckAccountApproval.php`

**Models:**
- `app/Models/ApplicationForm.php`
- `app/Models/Expert.php`
- `app/Models/Tutor.php`
- `app/Models/ContentCreator.php`

**Documentation:**
- `ONBOARDING_WORKFLOW.md` - Complete detailed guide
- `ONBOARDING_IMPLEMENTATION_SUMMARY.md` - Technical summary
- `QUICK_START_ONBOARDING.md` - This file

---

## Support

For issues or questions:
1. Check `ONBOARDING_WORKFLOW.md` for detailed explanations
2. Review application logs for errors
3. Verify database schema matches models
4. Test with different applicant types
5. Check middleware is registered

---

**Status:** ✅ Core workflow complete and functional
**Pending:** Email notifications
**Tested:** Manual testing recommended before production
