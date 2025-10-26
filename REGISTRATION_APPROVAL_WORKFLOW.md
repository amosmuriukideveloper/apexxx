# 🎯 Expert/Tutor/Creator Registration & Approval Workflow

## ✅ Implementation Complete!

This document explains the comprehensive application and approval system for **Experts**, **Tutors**, and **Content Creators**.

---

## 📋 Workflow Overview

```
Expert/Tutor/Content Creator:
    ├─ 1. Initial Registration
    ├─ 2. Comprehensive Application Form
    ├─ 3. Credential Upload (CV, Certificates, ID)
    ├─ 4. Status: "Pending Verification"
    ├─ 5. Cannot Access Panel (Blocked by Middleware)
    ├─ 6. Admin Review & Vetting
    ├─ 7. Approve/Reject with Feedback
    └─ 8. Outcome:
       ├─ If Approved: Email Notification → Panel Access Granted
       └─ If Rejected: Rejection Email → Account Disabled
```

---

## 🗄️ Database Structure

### Fields Added to All Role Tables

**Tables**: `experts`, `tutors`, `content_creators`

```php
// User Connection
user_id                  // Links to users table

// Application Status
application_status       // pending | approved | rejected
rejection_reason        // Text explaining rejection
admin_notes            // Internal notes (not visible to applicant)

// Document Upload Fields
cv_document            // Path to CV/Resume file
certificates          // JSON array of certificate files
id_document           // Path to ID document

// Verification
documents_verified    // Boolean flag
approved_by          // Foreign key to users (admin who approved)
approved_at          // Timestamp of approval
```

---

## 🎨 Admin Panel Features

### 1. Expert Applications Resource

**Location**: Settings → Applications → Expert Applications

**Features**:
- ✅ List all expert applications
- ✅ Filter by status (Pending/Approved/Rejected)
- ✅ Badge showing pending count
- ✅ View applicant details
- ✅ Review uploaded documents
- ✅ Verify documents checkbox
- ✅ Approve/Reject actions
- ✅ Add rejection reason
- ✅ Add internal admin notes

**Tabs**:
- All Applications
- Pending (with count badge)
- Approved
- Rejected

**Actions**:
```php
// Quick Actions from Table
- View Details
- Edit Application
- Approve (one-click)
- Reject (with reason form)

// Actions from View Page
- Approve Application
- Reject Application
- Edit Application
- Delete Application
```

---

## 🔐 Access Control

### Middleware: CheckApplicationStatus

**Purpose**: Blocks panel access for non-approved users

**Implementation**:

```php
// Applied to Expert/Tutor/Creator panel middleware
CheckApplicationStatus::class

// Checks:
1. User has application record
2. Application status is "approved"
3. If pending → Show "Pending Review" message
4. If rejected → Show rejection reason
5. If approved → Allow access
```

**403 Error Messages**:
```
Pending: "Your expert application is pending review. 
         You will receive an email once it has been approved."

Rejected: "Your expert application was rejected. 
          Reason: [rejection_reason]"
```

---

## 📄 Document Upload System

### Supported Documents:

1. **CV/Resume**
   - Single file upload
   - Formats: PDF, DOC, DOCX
   - Downloadable by admin
   - Required for application

2. **Certificates**
   - Multiple files upload
   - Educational certificates
   - Professional certifications
   - Awards and achievements
   - Downloadable individually

3. **ID Document**
   - Single file upload
   - Government-issued ID
   - Passport or National ID
   - Required for verification

### File Storage:
```
storage/app/public/
    ├─ expert_documents/
    │   ├─ cv/
    │   ├─ certificates/
    │   └─ ids/
    ├─ tutor_documents/
    │   ├─ cv/
    │   ├─ certificates/
    │   └─ ids/
    └─ creator_documents/
        ├─ cv/
        ├─ certificates/
        └─ ids/
```

---

## 🎯 Application Statuses

### 1. Pending
**When**: Application submitted, awaiting review
**User Access**: ❌ Blocked from panel
**Admin View**: Yellow/Warning badge
**Next Action**: Admin reviews and approves/rejects

### 2. Approved
**When**: Admin approves application
**User Access**: ✅ Full panel access granted
**Admin View**: Green/Success badge
**Actions Taken**:
- User role assigned (`expert`, `tutor`, or `creator`)
- `approved_by` set to admin user ID
- `approved_at` timestamp recorded
- Status set to `active`
- Email notification sent (TODO)

### 3. Rejected
**When**: Admin rejects application
**User Access**: ❌ Blocked from panel
**Admin View**: Red/Danger badge
**Actions Taken**:
- `rejection_reason` recorded
- Status set to `suspended`
- Email notification sent (TODO)
- User cannot access panel

---

## 📧 Email Notifications (TODO)

### Approval Email

**Trigger**: When admin approves application
**Recipient**: Applicant email
**Subject**: "Your [Expert/Tutor/Creator] Application has been Approved!"

**Content**:
```
Congratulations! 

Your application has been approved. You now have access to your panel.

Login here: [Panel URL]

Welcome to Apex Scholars!
```

### Rejection Email

**Trigger**: When admin rejects application
**Recipient**: Applicant email
**Subject**: "Update on Your [Expert/Tutor/Creator] Application"

**Content**:
```
Thank you for your interest in joining Apex Scholars as an [Expert/Tutor/Creator].

After careful review, we regret to inform you that your application has not been approved at this time.

Reason: [rejection_reason]

You may reapply after addressing the issues mentioned above.

Best regards,
Apex Scholars Team
```

---

## 🔄 Complete User Journey

### Expert Registration Flow:

```
1. User visits: /expert/register
   ↓
2. Fills registration form:
   - Name, Email, Password
   - Phone number
   - Specialization
   - Expertise areas
   - Years of experience
   - Bio
   ↓
3. Uploads documents:
   - CV/Resume (required)
   - Certificates (optional but recommended)
   - ID Document (required)
   ↓
4. Submits application
   ↓
5. Record created:
   - User account created
   - Expert record created
   - application_status = 'pending'
   - documents_verified = false
   ↓
6. User tries to login to /expert panel:
   → Middleware blocks access
   → Shows: "Application pending review"
   ↓
7. Admin reviews application:
   - Views application in admin panel
   - Downloads and reviews documents
   - Checks credentials
   - Marks documents_verified = true
   - Adds admin_notes
   ↓
8a. If APPROVED:
   - Clicks "Approve" button
   - application_status → 'approved'
   - User role assigned: 'expert'
   - approved_by → admin ID
   - approved_at → current timestamp
   - status → 'active'
   - Email sent: Approval notification
   - User can now login to /expert panel
   ↓
8b. If REJECTED:
   - Clicks "Reject" button
   - Enters rejection_reason
   - application_status → 'rejected'
   - status → 'suspended'
   - Email sent: Rejection notification
   - User still blocked from panel
```

### Same Flow for Tutors and Creators

The exact same workflow applies to:
- **Tutors**: `/tutor/register` → `/tutor` panel
- **Content Creators**: `/creator/register` → `/creator` panel

---

## 🛠️ Files Created/Modified

### Database Migrations:

1. ✅ `2024_01_01_000050_add_user_id_and_documents_to_role_tables.php`
   - Adds `user_id` foreign key
   - Adds document upload fields
   - Adds `admin_notes` field

### Filament Resources:

2. ✅ `app/Filament/Resources/ExpertApplicationResource.php`
   - Full CRUD for expert applications
   - Approve/Reject actions
   - Document viewing
   - Navigation badge for pending count

3. ✅ `app/Filament/Resources/ExpertApplicationResource/Pages/`
   - `ListExpertApplications.php` - List with tabs
   - `ViewExpertApplication.php` - View details
   - `EditExpertApplication.php` - Edit application

### Middleware:

4. ✅ `app/Http/Middleware/CheckApplicationStatus.php`
   - Blocks panel access for pending/rejected users
   - Shows appropriate error messages

---

## 📊 Admin Dashboard Views

### Expert Applications Index

```
┌─────────────────────────────────────────────────────────┐
│ Expert Applications                            [🔔 5]   │
├─────────────────────────────────────────────────────────┤
│ Tabs: [All] [Pending (5)] [Approved] [Rejected]        │
├──────────┬─────────┬──────────────┬─────┬──────────────┤
│ Name     │ Email   │ Specializ... │ Docs│ Status       │
├──────────┼─────────┼──────────────┼─────┼──────────────┤
│ John Doe │ john@...│ Mathematics  │  ✓  │ 🟡 Pending   │
│ Jane S.  │ jane@...│ Physics      │  ✓  │ ✅ Approved  │
│ Bob M.   │ bob@... │ Chemistry    │  ✗  │ 🔴 Rejected  │
└──────────┴─────────┴──────────────┴─────┴──────────────┘
```

### View Application Detail

```
┌─────────────────────────────────────────────┐
│ Expert Application: John Doe                │
│ [Approve] [Reject] [Edit]                  │
├─────────────────────────────────────────────┤
│ Applicant Information                       │
│ Name: John Doe                             │
│ Email: john@example.com                    │
│ Phone: +254 123 456789                     │
├─────────────────────────────────────────────┤
│ Professional Details                        │
│ Specialization: Mathematics                 │
│ Expertise: Algebra, Calculus, Statistics   │
│ Experience: 5 years                        │
│ Bio: Experienced mathematics tutor...      │
├─────────────────────────────────────────────┤
│ Documents                                   │
│ CV: [📄 Download] [👁 View]                │
│ Certificates:                              │
│   - BSc Mathematics [📄 Download]          │
│   - Teaching Cert [📄 Download]            │
│ ID: [📄 Download] [👁 View]                │
│ ☑ Documents Verified                       │
├─────────────────────────────────────────────┤
│ Application Status                          │
│ Status: 🟡 Pending Review                  │
│ Admin Notes: Strong credentials...         │
└─────────────────────────────────────────────┘
```

---

## ⚙️ Configuration Needed

### 1. Register Middleware

**File**: `app/Http/Kernel.php` or `bootstrap/app.php`

```php
protected $middlewareAliases = [
    // ... existing middleware
    'check.application' => \App\Http\Middleware\CheckApplicationStatus::class,
];
```

### 2. Apply to Panel Providers

**Expert Panel** (`app/Providers/Filament/ExpertPanelProvider.php`):
```php
->middleware([
    // ... existing middleware
    'check.application:expert',
])
```

**Tutor Panel**:
```php
->middleware([
    // ... existing middleware
    'check.application:tutor',
])
```

**Creator Panel**:
```php
->middleware([
    // ... existing middleware
    'check.application:creator',
])
```

### 3. Storage Link

```bash
php artisan storage:link
```

### 4. File Upload Configuration

**File**: `config/filesystems.php`

Ensure `public` disk is configured:
```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

---

## 🧪 Testing the Workflow

### Test 1: Expert Application Submission

```
1. Visit: /expert/register
2. Fill application form
3. Upload CV, certificates, ID
4. Submit application
5. ✅ Record created with status: pending
6. Try to login to /expert panel
7. ✅ Access blocked with message
```

### Test 2: Admin Review & Approval

```
1. Login as admin: /platform/login
2. Go to: Applications → Expert Applications
3. ✅ See pending application with badge
4. Click on application
5. Review details and documents
6. Click "Approve"
7. ✅ Status changes to approved
8. ✅ User role assigned
9. Applicant can now login to /expert panel
```

### Test 3: Admin Review & Rejection

```
1. View pending application
2. Click "Reject"
3. Enter rejection reason
4. Submit
5. ✅ Status changes to rejected
6. ✅ Account suspended
7. User still blocked from panel
8. ✅ Shows rejection reason
```

---

## 🚀 Next Steps (TODO)

### 1. Email Notifications

Create mail classes:
```bash
php artisan make:mail ExpertApplicationApproved
php artisan make:mail ExpertApplicationRejected
php artisan make:mail TutorApplicationApproved
php artisan make:mail TutorApplicationRejected
php artisan make:mail CreatorApplicationApproved
php artisan make:mail CreatorApplicationRejected
```

### 2. Similar Resources for Tutor & Creator

Create resources for:
- `TutorApplicationResource`
- `ContentCreatorApplicationResource`

(Follow same pattern as ExpertApplicationResource)

### 3. Registration Forms

Enhance registration pages to include:
- Document upload fields
- Professional information fields
- Application terms & conditions

### 4. Dashboard Widgets

Add to admin dashboard:
- Pending applications count widget
- Recent applications widget
- Approval statistics

### 5. Notifications

- In-app notifications for admins on new applications
- Email notifications for applicants on status change

---

## 📝 Summary

**Implemented**:
- ✅ Database structure for applications
- ✅ Document upload fields
- ✅ Admin panel for reviewing applications
- ✅ Approve/Reject workflow
- ✅ Access control middleware
- ✅ Status badges and filters
- ✅ Role assignment on approval

**Pending**:
- ⏳ Email notifications
- ⏳ Tutor/Creator application resources
- ⏳ Enhanced registration forms
- ⏳ Dashboard widgets

---

**This comprehensive system ensures only vetted and approved professionals can access their respective panels! 🎉**
