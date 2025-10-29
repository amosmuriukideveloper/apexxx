# 📋 Tutoring Requests Table - Actual Schema

## ✅ Columns That EXIST in tutoring_requests

```sql
CREATE TABLE tutoring_requests (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    request_number      VARCHAR(255) UNIQUE,
    student_id          BIGINT UNSIGNED,
    tutor_id            BIGINT UNSIGNED NULLABLE,
    admin_id            BIGINT UNSIGNED NULLABLE,
    subject             VARCHAR(255),
    topic               VARCHAR(255),
    description         TEXT,
    preferred_date      DATE,
    preferred_time      TIME,
    session_duration    INTEGER DEFAULT 60,
    learning_goals      TEXT NULLABLE,
    status              ENUM('pending', 'assigned', 'scheduled', 'completed', 'cancelled'),
    assigned_at         TIMESTAMP NULLABLE,
    created_at          TIMESTAMP,
    updated_at          TIMESTAMP
);
```

### **Columns Available:**
- ✅ `id`
- ✅ `request_number`
- ✅ `student_id`
- ✅ `tutor_id`
- ✅ `admin_id`
- ✅ `subject`
- ✅ `topic`
- ✅ `description`
- ✅ `preferred_date` (DATE)
- ✅ `preferred_time` (TIME)
- ✅ `session_duration` (INTEGER, minutes)
- ✅ `learning_goals`
- ✅ `status` (ENUM)
- ✅ `assigned_at`
- ✅ `created_at`
- ✅ `updated_at`

### **Additional Columns (from Model fillable):**
- ✅ `specific_topic`
- ✅ `duration` (alias for session_duration)
- ✅ `base_price`
- ✅ `platform_fee`
- ✅ `total_price`
- ✅ `paid_at`

---

## ❌ Columns That DON'T EXIST

### **Time/Date Related:**
- ❌ `confirmed_date` - Use `preferred_date` instead
- ❌ `confirmed_time` - Use `preferred_time` instead
- ❌ `tutor_suggested_date`
- ❌ `tutor_response_date`

### **Payment/Earnings Related:**
- ❌ `payment_status` - Not in table
- ❌ `tutor_earnings` - Must calculate (70% of base_price)
- ❌ `platform_commission` - Must calculate (30% of base_price)

### **Other:**
- ❌ `tutor_notes`
- ❌ `cancelled_by`
- ❌ `cancellation_reason`
- ❌ `student_rating` - This is in tutoring_sessions table
- ❌ `student_feedback` - This is in tutoring_sessions table

---

## 📊 Status Values

### **Available Status Values:**
```php
'pending'      // Initial state
'assigned'     // Admin assigned tutor
'scheduled'    // Tutor accepted
'completed'    // Session finished
'cancelled'    // Cancelled at any point
```

### **DON'T USE:**
- ❌ `confirmed` - Use `scheduled` instead
- ❌ `pending_tutor_response` - Use `assigned` instead
- ❌ `pending_student_response`
- ❌ `in_progress`
- ❌ `under_review`

---

## 💡 How to Calculate Earnings

### **Since earnings columns don't exist:**

```php
// Get base price from completed session
$basePrice = $request->base_price;

// Calculate platform fee (30%)
$platformFee = $basePrice * 0.30;

// Calculate tutor earnings (70%)
$tutorEarnings = $basePrice * 0.70;
```

### **In Queries:**

```php
// Total earnings (70% of all base_price)
$totalFees = TutoringRequest::where('tutor_id', $tutorId)
    ->where('status', 'completed')
    ->sum('base_price') ?? 0;

$totalEarnings = $totalFees * 0.70;
```

---

## 🎯 Correct Usage Examples

### **Query Scheduled Sessions:**
```php
// ✅ CORRECT
TutoringRequest::where('tutor_id', Auth::id())
    ->where('status', 'scheduled')
    ->whereDate('preferred_date', '>=', now())
    ->get();

// ❌ WRONG
TutoringRequest::where('status', 'confirmed')
    ->where('confirmed_date', '>=', now())
    ->get();
```

### **Display Date/Time:**
```php
// ✅ CORRECT
$date = $request->preferred_date->format('M d, Y');
$time = $request->preferred_time;

// ❌ WRONG
$datetime = $request->confirmed_date;
```

### **Accept Session:**
```php
// ✅ CORRECT
$request->update([
    'status' => 'scheduled',
]);

// ❌ WRONG
$request->update([
    'status' => 'confirmed',
    'confirmed_date' => $request->preferred_date,
]);
```

---

## 🔗 Related Tables

### **tutoring_sessions Table:**
Has additional session-specific data:
- `session_fee`
- `tutor_earnings`
- `platform_commission`
- `student_rating`
- `student_feedback`
- `recording_url`
- `started_at`
- `ended_at`

**Note:** After a session is completed, a `TutoringSession` record should be created with these details.

---

## 🛠️ What Was Fixed

### **Files Updated:**
1. ✅ `EarningsResource.php` - Removed payment_status references
2. ✅ `EarningsResource/Widgets/EarningsSummaryWidget.php` - Calculate from base_price
3. ✅ `CompletedSessions.php` - Removed payment_status column
4. ✅ All queries updated to use correct columns

### **Changes Made:**
- ❌ Removed `payment_status` column references (doesn't exist)
- ❌ Removed `tutor_earnings` column references (doesn't exist)
- ❌ Removed `platform_commission` column references (doesn't exist)
- ✅ Now calculating earnings as 70% of `base_price`
- ✅ Payment tracking noted as "coming soon"

---

## 📈 Future Enhancements

### **If you want to add payment tracking:**

```php
// Add migration to tutoring_requests table:
Schema::table('tutoring_requests', function (Blueprint $table) {
    $table->enum('payment_status', ['pending', 'paid', 'refunded'])
          ->default('pending')
          ->after('status');
    
    $table->decimal('tutor_earnings', 10, 2)
          ->default(0)
          ->after('base_price');
    
    $table->decimal('platform_commission', 10, 2)
          ->default(0)
          ->after('tutor_earnings');
    
    $table->timestamp('payout_date')->nullable();
});
```

---

## ✅ Summary

### **Use These Columns:**
- ✅ `preferred_date` for session date
- ✅ `preferred_time` for session time
- ✅ `status` = 'scheduled' for confirmed sessions
- ✅ `base_price` for session fee
- ✅ Calculate 70% for tutor earnings

### **DON'T Use These:**
- ❌ `confirmed_date`
- ❌ `payment_status`
- ❌ `tutor_earnings`
- ❌ `platform_commission`
- ❌ Status: 'confirmed'

**Your Tutor panel now works with the actual database schema!** 🎉
