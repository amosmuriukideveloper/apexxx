# Roles and Permissions System

This document outlines the comprehensive role-based access control (RBAC) system implemented using Spatie Laravel Permission and Filament Shield.

## Installation Steps

1. **Install Required Packages**
   ```bash
   composer install
   php artisan migrate
   php artisan db:seed
   ```

2. **Publish Configuration (if needed)**
   ```bash
   php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
   ```

## Roles Overview

### 1. Student (Basic User)
**Access Level:** Basic User
- Create and submit projects
- Track project status in real-time
- Access completed and approved projects
- Request revisions
- Book tutoring sessions
- Enroll in courses from Knowledge Hub
- Manage wallet and payment methods
- View transaction history
- Rate and review experts/tutors/courses

### 2. Expert (Specialist User)
**Access Level:** Specialist User
- View assigned projects queue
- Accept/decline project assignments
- Access project details and materials
- Update project status (In Progress, Completed)
- Upload deliverables and solutions
- Track earnings (paid/unpaid tasks)
- Manage availability calendar
- View performance analytics
- Communicate via integrated messaging

### 3. Tutor (Specialist User)
**Access Level:** Specialist User
- View assigned tutoring requests
- Schedule sessions via Google Meet integration
- Access student profiles and learning history
- Upload session notes and materials
- Track session attendance
- Manage earnings and payment status
- View subject-specific modules
- Set availability and rates

### 4. Content Creator (Creator User)
**Access Level:** Creator User
- Full course creation suite (Udemy-style)
- Video upload and management
- Curriculum builder with modules/lessons
- Quiz and assessment creator
- Track course enrollments and revenue
- Analytics dashboard (views, completion rates)
- Student feedback and Q&A management
- Course pricing and promotion tools

### 5. Admin (Management)
**Access Level:** Management
- Assign projects to experts
- Assign tutoring requests to tutors
- Review and approve/reject expert deliverables
- Initiate revision requests
- Process payments to experts/tutors
- Approve/reject courses for Knowledge Hub
- View platform analytics
- Manage user disputes
- Moderate content

### 6. Super Admin (Full System Control)
**Access Level:** Full System Control
- All admin capabilities
- User role management
- System configuration
- Payment gateway settings
- Platform-wide analytics
- Database management
- Security and access controls
- API key management

## Permissions by Category

### Project Management
- `create_projects` - Create new projects
- `view_projects` - View projects
- `edit_projects` - Edit project details
- `delete_projects` - Delete projects
- `assign_projects` - Assign projects to experts
- `submit_projects` - Submit completed projects
- `approve_projects` - Approve project deliverables
- `reject_projects` - Reject project deliverables
- `request_revisions` - Request project revisions
- `track_project_status` - Track project progress
- `upload_deliverables` - Upload project solutions
- `download_project_materials` - Access project files

### Tutoring System
- `create_tutoring_sessions` - Create tutoring sessions
- `view_tutoring_sessions` - View tutoring sessions
- `edit_tutoring_sessions` - Edit session details
- `delete_tutoring_sessions` - Delete sessions
- `book_tutoring_sessions` - Book sessions as student
- `schedule_tutoring_sessions` - Schedule sessions as tutor
- `assign_tutoring_requests` - Assign requests to tutors
- `manage_availability` - Manage tutor availability
- `upload_session_notes` - Upload session materials
- `track_session_attendance` - Track attendance
- `view_student_profiles` - Access student information

### Course Management
- `create_courses` - Create new courses
- `view_courses` - View available courses
- `edit_courses` - Edit course content
- `delete_courses` - Delete courses
- `enroll_courses` - Enroll in courses
- `approve_courses` - Approve courses for publication
- `reject_courses` - Reject course submissions
- `manage_course_content` - Full course content management
- `upload_videos` - Upload course videos
- `create_quizzes` - Create assessments
- `view_course_analytics` - View course performance
- `manage_course_pricing` - Set course prices
- `moderate_course_content` - Moderate course materials

### Financial Management
- `view_wallet` - View wallet balance
- `manage_wallet` - Manage wallet operations
- `view_transactions` - View transaction history
- `process_payments` - Process user payments
- `track_earnings` - Track earnings and payouts
- `manage_payment_methods` - Manage payment options
- `view_payment_analytics` - View payment reports
- `manage_payment_gateway` - Configure payment settings

### User Management
- `view_users` - View user profiles
- `create_users` - Create new users
- `edit_users` - Edit user information
- `delete_users` - Delete user accounts
- `assign_roles` - Assign roles to users
- `manage_user_roles` - Full role management
- `view_user_analytics` - View user statistics
- `moderate_users` - Moderate user behavior
- `manage_user_disputes` - Handle user conflicts

## Usage Examples

### 1. Protecting Routes with Middleware

```php
// Protect route by role
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});

// Protect route by permission
Route::middleware(['auth', 'permission:create_projects'])->group(function () {
    Route::post('/projects', [ProjectController::class, 'store']);
});
```

### 2. Controller Authorization

```php
class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_projects')->only(['index', 'show']);
        $this->middleware('permission:create_projects')->only(['create', 'store']);
    }
    
    public function store(Request $request)
    {
        $this->authorize('create_projects');
        // Project creation logic
    }
}
```

### 3. Blade Template Checks

```blade
@role('admin')
    <a href="{{ route('admin.dashboard') }}">Admin Panel</a>
@endrole

@can('create_projects')
    <a href="{{ route('projects.create') }}">Create Project</a>
@endcan

@hasanyrole('admin|super_admin')
    <div class="admin-section">
        <!-- Admin content -->
    </div>
@endhasanyrole
```

### 4. Using Helper Methods

```php
$user = auth()->user();

// Role checks
if ($user->isStudent()) {
    // Student-specific logic
}

if ($user->isAnyAdmin()) {
    // Admin logic
}

// Permission checks
if ($user->canManageProjects()) {
    // Project management logic
}

// Get primary role
$primaryRole = $user->getPrimaryRole();
```

### 5. Assigning Roles and Permissions

```php
// Assign role to user
$user->assignRole('student');

// Give permission directly
$user->givePermissionTo('create_projects');

// Remove role
$user->removeRole('expert');

// Sync roles (removes all other roles)
$user->syncRoles(['admin']);
```

## Database Structure

The system creates the following tables:
- `roles` - Stores role definitions
- `permissions` - Stores permission definitions
- `model_has_roles` - Links users to roles
- `model_has_permissions` - Links users to direct permissions
- `role_has_permissions` - Links roles to permissions

## Security Considerations

1. **Principle of Least Privilege**: Users are granted only the minimum permissions needed
2. **Role Hierarchy**: Super Admin > Admin > Specialists > Students
3. **Permission Inheritance**: Roles inherit permissions, users can have additional direct permissions
4. **Middleware Protection**: All sensitive routes are protected by appropriate middleware
5. **Gate Definitions**: Common permission checks are defined as Gates for reusability

## Testing

Test users are created automatically with the seeder:
- Super Admin: `admin@apexscholars.com`
- Admin: `admin@example.com`
- Student: `student@example.com`
- Expert: `expert@example.com`
- Tutor: `tutor@example.com`
- Content Creator: `creator@example.com`

## Extending the System

To add new permissions:
1. Add permission to the seeder
2. Assign to appropriate roles
3. Protect routes with middleware
4. Add authorization checks in controllers
5. Update blade templates with permission checks

## Filament Integration

When using Filament Admin Panel, install Filament Shield:
```bash
composer require bezhansalleh/filament-shield
php artisan shield:install
```

This will automatically generate policies and integrate with the permission system.
