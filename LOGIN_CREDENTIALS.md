# 🔑 Quick Login Reference - All Credentials

## 📋 All Test Accounts (Password: `password`)

### 🔴 ADMIN / SUPER ADMIN
```
URL: http://127.0.0.1:8000/platform/login
Email: admin@apexscholars.com
Password: password

What You See:
✅ Settings menu
✅ User Management
✅ All projects
✅ All analytics
✅ System configuration
```

### 🟠 ADMIN (Regular)
```
URL: http://127.0.0.1:8000/platform/login
Email: testadmin@example.com
Password: password

What You See:
✅ User Management
✅ All projects
✅ Settings (limited)
❌ System configuration
```

### 🔵 STUDENT
```
URL: http://127.0.0.1:8000/student/login
Email: student@example.com
Password: password

What You See:
✅ My Projects (create, view own)
✅ Courses (browse, enroll)
✅ Payments
❌ No admin tools
❌ No settings
```

### 🟣 EXPERT
```
URL: http://127.0.0.1:8000/expert/login
Email: expert@example.com
Password: password

What You See:
✅ My Projects (assigned only)
✅ Submissions (upload work)
✅ Earnings
❌ Can't see unassigned projects
❌ No admin tools
```

### 🟢 TUTOR
```
URL: http://127.0.0.1:8000/tutor/login
Email: tutor@example.com
Password: password

What You See:
✅ Sessions (my sessions)
✅ Schedule
✅ Students (assigned)
✅ Earnings
❌ No projects
❌ No admin tools
```

### 🟡 CONTENT CREATOR
```
URL: http://127.0.0.1:8000/creator/login
Email: creator@example.com
Password: password

What You See:
✅ My Content (courses)
✅ Analytics
✅ Earnings
❌ Can't approve own courses
❌ No admin tools
```

---

## 🎯 Quick Access URLs

| Role | Login URL | Dashboard URL |
|------|-----------|---------------|
| Admin | /platform/login | /platform |
| Student | /student/login | /student |
| Expert | /expert/login | /expert |
| Tutor | /tutor/login | /tutor |
| Creator | /creator/login | /creator |

**Login Selector**: http://127.0.0.1:8000/login  
**Register Selector**: http://127.0.0.1:8000/register

---

## 🔍 How to Switch Between Roles

1. **Logout** from current session
2. **Clear cookies** (optional but recommended)
3. **Login** with different credentials
4. **Verify** you see the correct dashboard

---

## 📊 What Each Role Can See

### Navigation Comparison:

**Admin Only:**
- Settings ⭐
- User Management ⭐
- System ⭐

**All Roles:**
- Profile
- (Their specific features)

**None Except Admin:**
- Settings pages
- User management
- System configuration

---

**Copy this file for quick reference!**
