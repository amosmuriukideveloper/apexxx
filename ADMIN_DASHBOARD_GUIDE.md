# 🎯 Admin Dashboard Access & Role Verification Guide

## 🔑 How to Access Admin Dashboard

### Method 1: Direct URL (Fastest)
```
1. Open browser
2. Visit: http://127.0.0.1:8000/platform/login
3. Email: admin@apexscholars.com
4. Password: password
5. ✅ You're in the admin dashboard!
```

### Method 2: Via Login Selector
```
1. Visit: http://127.0.0.1:8000/login
2. Click: "Admin / Staff" card
3. Email: admin@apexscholars.com
4. Password: password
5. ✅ You're in!
```

---

## 👥 How to View All Roles & Users

### Once Logged in as Admin:

1. **Look at the sidebar navigation**
2. **Find "User Management" section**
3. **Click on "Users"** 
4. ✅ You'll see all users with their assigned roles

### View Roles & Permissions:
```
1. In admin dashboard
2. Click: Shield (icon) in navigation
3. Or go to: User Management → Roles
4. ✅ See all 6 roles and their permissions
```

---

## 🧪 Test Each Role Dashboard - Complete Instructions

### 1️⃣ Test STUDENT Dashboard

**Login:**
```
URL: http://127.0.0.1:8000/student/login
Email: student@example.com
Password: password
```

**What You Should See:**
- ✅ Student Dashboard title
- ✅ Stats widget (Total/Active/Completed projects)
- ✅ Navigation menu with:
  - Projects (My Projects)
  - Learning (Courses)
  - Payments (Wallet)
  - Profile

**What Student CAN Do:**
- Create new projects
- View own projects only
- Enroll in courses
- Make payments
- Track project status

**What Student CANNOT Do:**
- See other students' projects
- Assign projects to experts
- Access admin tools
- View settings pages
- Approve courses

---

### 2️⃣ Test EXPERT Dashboard

**Login:**
```
URL: http://127.0.0.1:8000/expert/login
Email: expert@example.com
Password: password
```

**What You Should See:**
- ✅ Expert Dashboard title
- ✅ Clean dashboard (no widgets yet)
- ✅ Navigation menu with:
  - My Projects (Assigned to me only)
  - Submissions (Upload deliverables)
  - Earnings (Payouts & analytics)
  - Profile

**What Expert CAN Do:**
- View projects assigned to them
- Upload deliverables
- Track earnings
- Request payouts

**What Expert CANNOT Do:**
- See unassigned projects
- Assign projects to themselves
- See other experts' projects
- Access admin tools
- View settings

---

### 3️⃣ Test TUTOR Dashboard

**Login:**
```
URL: http://127.0.0.1:8000/tutor/login
Email: tutor@example.com
Password: password
```

**What You Should See:**
- ✅ Tutor Dashboard title
- ✅ Clean dashboard
- ✅ Navigation menu with:
  - Sessions (My tutoring sessions)
  - Students (Assigned students)
  - Schedule (Set availability)
  - Earnings
  - Profile

**What Tutor CAN Do:**
- Manage tutoring sessions
- Set availability schedule
- Upload session notes
- View assigned students
- Track earnings

**What Tutor CANNOT Do:**
- See other tutors' sessions
- Access project system
- View admin tools
- Change platform settings

---

### 4️⃣ Test CONTENT CREATOR Dashboard

**Login:**
```
URL: http://127.0.0.1:8000/creator/login
Email: creator@example.com
Password: password
```

**What You Should See:**
- ✅ Creator Studio title
- ✅ Clean dashboard
- ✅ Navigation menu with:
  - My Content (Courses created)
  - Analytics (Course performance)
  - Earnings (Revenue)
  - Profile

**What Creator CAN Do:**
- Create courses
- Upload videos & content
- Create quizzes
- Set course pricing
- View course analytics
- Track revenue

**What Creator CANNOT Do:**
- Approve own courses (admin only)
- See other creators' courses
- Access student data
- Access admin tools

---

### 5️⃣ ADMIN Dashboard (You're Here)

**Login:**
```
URL: http://127.0.0.1:8000/platform/login
Email: admin@apexscholars.com
Password: password
```

**What You Should See:**
- ✅ Admin Dashboard title
- ✅ Stats widgets (if configured)
- ✅ Navigation menu with EVERYTHING:
  - **Projects** (All projects, assign, approve)
  - **Learning** (Courses, approve, moderate)
  - **Tutoring** (Sessions, requests)
  - **Financial** (Payments, transactions, payouts)
  - **Communication** (Messages, notifications)
  - **Analytics** (Platform-wide reports)
  - **User Management** (Users, roles, permissions)
  - **Settings** ← Only admins see this!
    - General Settings
    - Payment Settings
    - Email Settings
    - Notification Settings
  - **System** (Logs, configuration)

**What Admin CAN Do:**
- View ALL projects (all students)
- Assign projects to experts
- Approve/reject courses
- Manage all users
- Process payments
- Configure platform settings
- View all analytics
- Access system logs

**What Admin CANNOT Do:**
- Nothing! Admins have full access

---

## 📊 Quick Comparison Table

| Feature | Student | Expert | Tutor | Creator | Admin |
|---------|---------|--------|-------|---------|-------|
| Create Projects | ✅ | ❌ | ❌ | ❌ | ✅ |
| Assign Projects | ❌ | ❌ | ❌ | ❌ | ✅ |
| Upload Deliverables | ❌ | ✅ | ❌ | ❌ | ✅ |
| Create Courses | ❌ | ❌ | ❌ | ✅ | ❌ |
| Approve Courses | ❌ | ❌ | ❌ | ❌ | ✅ |
| Manage Sessions | ❌ | ❌ | ✅ | ❌ | ✅ |
| View Settings | ❌ | ❌ | ❌ | ❌ | ✅ |
| Manage Users | ❌ | ❌ | ❌ | ❌ | ✅ |
| Platform Analytics | ❌ | ❌ | ❌ | ❌ | ✅ |

---

## 🔍 How to Verify Roles in Admin Dashboard

### Step-by-Step:

1. **Login as Admin**
   ```
   http://127.0.0.1:8000/platform/login
   admin@apexscholars.com / password
   ```

2. **Navigate to User Management**
   - Look at sidebar
   - Find "User Management" section
   - Click "Users"

3. **View All Users**
   - You'll see a table with all users
   - Columns: Name, Email, Roles, Created Date
   - Each user shows their assigned role(s)

4. **Check Individual User**
   - Click on any user row
   - See full user details
   - View all their roles
   - See their permissions

5. **View Roles Page**
   - Go to: User Management → Roles
   - See all 6 roles:
     - Super Admin
     - Admin
     - Student
     - Expert
     - Tutor
     - Content Creator
   - Each role shows permission count

6. **View Permissions**
   - Click on any role
   - See all 85 permissions
   - See which are assigned to that role

---

## 🧪 Complete Testing Workflow

### Test 1: Login as Each Role (15 minutes)

```bash
# Test Student
1. Logout from admin
2. Go to /student/login
3. Login: student@example.com / password
4. Note what navigation you see
5. Try to access /platform → Should be denied

# Test Expert
1. Logout
2. Go to /expert/login
3. Login: expert@example.com / password
4. Note navigation
5. Try to create project → Should not see that option

# Test Tutor
1. Logout
2. Go to /tutor/login
3. Login: tutor@example.com / password
4. Note navigation
5. Try to access projects → Should not see

# Test Creator
1. Logout
2. Go to /creator/login
3. Login: creator@example.com / password
4. Note navigation
5. Try to approve course → Should not have permission

# Test Admin
1. Logout
2. Go to /platform/login
3. Login: admin@apexscholars.com / password
4. Note you see EVERYTHING including Settings
```

---

## 🎯 Key Differences to Verify

### Navigation Menu Differences:

**Student Sees:**
- Projects (limited to own)
- Learning
- Payments
- Profile

**Expert Sees:**
- My Projects (assigned only)
- Submissions
- Earnings
- Profile

**Tutor Sees:**
- Sessions
- Students
- Schedule
- Earnings
- Profile

**Creator Sees:**
- My Content
- Analytics
- Earnings
- Profile

**Admin Sees:**
- Projects (ALL)
- Learning (with approve)
- Tutoring (ALL)
- Financial (ALL)
- Communication
- Analytics (platform-wide)
- User Management
- **Settings** ← ONLY ADMINS
- System

---

## 🔐 Permission Verification

### As Admin, Check Permissions:

1. Go to: User Management → Roles → Student
2. See student permissions (about 15-20)
3. Go to: User Management → Roles → Admin
4. See admin permissions (all 85)

### Key Permission Differences:

**Student Has:**
- `view_projects` (own only)
- `create_projects`
- `view_courses`
- `enroll_courses`

**Student Does NOT Have:**
- `assign_projects`
- `approve_courses`
- `manage_users`
- `access_settings`

**Admin Has:**
- Everything student has PLUS:
- `assign_projects`
- `approve_courses`
- `manage_users`
- `access_settings`
- `view_all_projects`

---

## 📸 What to Look For

### In Student Dashboard:
- ✅ "Create Project" button visible
- ❌ No "Assign Project" option
- ❌ No "Settings" in navigation
- ❌ No "User Management"

### In Expert Dashboard:
- ✅ Only assigned projects visible
- ❌ Cannot see all projects
- ❌ No admin tools

### In Admin Dashboard:
- ✅ "Settings" menu item present
- ✅ "User Management" visible
- ✅ Can view ALL projects
- ✅ Can assign projects
- ✅ Can configure platform

---

## 🎊 Summary

**To Access Admin Dashboard:**
```
URL: http://127.0.0.1:8000/platform/login
Email: admin@apexscholars.com
Password: password
```

**To View Roles:**
```
Admin Dashboard → User Management → Roles
```

**To View Users:**
```
Admin Dashboard → User Management → Users
```

**To Test Other Roles:**
- Logout from admin
- Login with each role's credentials
- Note the differences in navigation
- Try to access admin features (should fail)

---

**Start here: http://127.0.0.1:8000/platform/login** 🎯
