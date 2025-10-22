# Unified Platform Panel Guide

This document explains the **unified panel system** where all users access the same interface at `/platform`, but see different content and functionality based on their roles and permissions.

## 🎯 **Concept Overview**

Instead of separate dashboards for each role, everyone uses the **same panel** but experiences it differently:

- **Same URL**: `/platform` for everyone
- **Same Navigation**: But items appear/disappear based on permissions
- **Same Resources**: But with different views, actions, and data
- **Role-Specific Widgets**: Different dashboard content per role
- **Permission-Based Actions**: Buttons and features show only when allowed

## 🏗️ **Panel Structure**

### **Navigation Groups**
```
📁 Projects (Students submit, Experts work, Admins manage)
📁 Learning (Students enroll, Creators build, Admins approve)
📁 Tutoring (Students book, Tutors provide, Admins assign)
📁 Financial (Wallet, earnings, payments - role-specific views)
📁 Communication (Messages, notifications)
📁 Analytics (Performance data - role-specific metrics)
📁 User Management (Admin-only)
📁 System (Super Admin-only)
```

## 👥 **Role-Based Experience**

### **🎓 Student Experience**
**Dashboard Widgets:**
- My Projects (total submitted)
- Active Projects (in progress)
- Completed Projects
- Enrolled Courses

**What They See:**
- ✅ **Projects**: Only their own projects
- ✅ **Courses**: Available courses + enrolled courses
- ✅ **Tutoring**: Book sessions, view scheduled sessions
- ✅ **Wallet**: Balance, transactions, payment methods
- ✅ **Messages**: Communication with experts/tutors
- ❌ **User Management**: Hidden
- ❌ **System Settings**: Hidden

**Actions Available:**
- Create new projects
- Enroll in courses
- Book tutoring sessions
- Request project revisions
- Rate and review services

### **⭐ Expert Experience**
**Dashboard Widgets:**
- Assigned Projects (total)
- Active Work (in progress)
- Pending Review (awaiting approval)
- Completed Projects

**What They See:**
- ✅ **Projects**: Only assigned projects
- ✅ **Analytics**: Personal performance metrics
- ✅ **Financial**: Earnings, payment status
- ✅ **Messages**: Communication with students/admins
- ❌ **Course Management**: Hidden (unless also Content Creator)
- ❌ **User Management**: Hidden

**Actions Available:**
- Update project status
- Upload deliverables
- Manage availability
- View earnings reports

### **👨‍🏫 Tutor Experience**
**Dashboard Widgets:**
- Scheduled Sessions
- Completed Sessions
- Student Feedback
- Earnings Overview

**What They See:**
- ✅ **Tutoring**: All assigned sessions
- ✅ **Students**: Profiles of assigned students
- ✅ **Analytics**: Session performance
- ✅ **Financial**: Session earnings
- ❌ **Project Management**: Hidden
- ❌ **Course Creation**: Hidden

### **🎨 Content Creator Experience**
**Dashboard Widgets:**
- My Courses (created)
- Course Enrollments
- Revenue Analytics
- Pending Approvals

**What They See:**
- ✅ **Courses**: Only their created courses
- ✅ **Analytics**: Course performance metrics
- ✅ **Financial**: Course revenue
- ✅ **Students**: Enrolled student data
- ❌ **Project Management**: Hidden
- ❌ **User Management**: Hidden

### **🛡️ Admin Experience**
**Dashboard Widgets:**
- Pending Projects (need assignment)
- Projects in Review (need approval)
- Pending Courses (need approval)
- Total Platform Revenue

**What They See:**
- ✅ **Everything**: All projects, courses, users
- ✅ **User Management**: Full user control
- ✅ **Analytics**: Platform-wide metrics
- ✅ **Financial**: All transactions, payments
- ✅ **System**: Configuration access
- ✅ **Moderation**: Content and user moderation

**Actions Available:**
- Assign projects to experts
- Approve/reject deliverables
- Approve/reject courses
- Manage user roles
- Process payments
- View all analytics

### **🔧 Super Admin Experience**
**Everything Admin has PLUS:**
- System configuration
- Database management
- API key management
- Security settings
- Full user role management

## 🔒 **Permission-Based Features**

### **Dynamic Navigation**
Navigation items appear only if user has required permissions:
```php
// Projects appear if user can view_projects
// User Management appears if user can view_users
// System appears if user can manage_system_config
```

### **Conditional Actions**
Table actions show based on permissions:
```php
// "Assign Expert" button - only if can assign_projects
// "Approve Course" button - only if can approve_courses
// "Delete User" button - only if can delete_users
```

### **Filtered Data**
Each resource shows different data per role:
```php
// Students see only their projects
// Experts see only assigned projects  
// Admins see all projects
```

### **Role-Specific Forms**
Form fields appear/disappear based on role:
```php
// Budget field - visible to Students and Admins only
// Assignment field - visible to Admins only
// Status field - disabled for Students
```

## 🚀 **Key Benefits**

### **1. Unified Experience**
- Single login URL for all users
- Consistent interface and navigation
- Shared design system and components

### **2. Role-Based Security**
- Automatic permission checking
- Data filtering by role
- Action-level access control

### **3. Scalable Architecture**
- Easy to add new roles
- Simple to modify permissions
- Centralized access control

### **4. Better UX**
- Users see only what they need
- No overwhelming admin features for basic users
- Contextual actions and information

## 📱 **Access Instructions**

### **For All Users:**
1. Visit: `http://your-domain.com/platform`
2. Login with your credentials
3. See personalized dashboard and navigation

### **Test Accounts:**
- **Student**: `student@example.com`
- **Expert**: `expert@example.com`
- **Tutor**: `tutor@example.com`
- **Content Creator**: `creator@example.com`
- **Admin**: `admin@example.com`
- **Super Admin**: `admin@apexscholars.com`

## 🛠️ **Technical Implementation**

### **Resource-Level Filtering**
```php
public static function getEloquentQuery(): Builder
{
    $user = Auth::user();
    
    if ($user->isStudent()) {
        return parent::getEloquentQuery()->where('student_id', $user->id);
    } elseif ($user->isExpert()) {
        return parent::getEloquentQuery()->where('assigned_expert_id', $user->id);
    }
    
    return parent::getEloquentQuery(); // Admins see all
}
```

### **Permission-Based Visibility**
```php
->visible(fn () => Auth::user()->can('create_projects'))
->disabled(fn () => !Auth::user()->can('edit_projects'))
```

### **Role-Specific Widgets**
```php
protected function getHeaderWidgets(): array
{
    $user = auth()->user();
    
    if ($user->isStudent()) {
        return [StudentDashboard::class];
    } elseif ($user->isExpert()) {
        return [ExpertDashboard::class];
    }
    // ... etc
}
```

This unified approach provides a seamless, secure, and scalable platform where every user gets exactly the interface and functionality they need for their role.
