# Course Platform & Payment Models

## 1. Course Platform Models

### 1.1 Course Model

```php
// app/Models/Course.php
class Course extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'creator_id',
        'title',
        'slug',
        'short_description',
        'description',
        'category',
        'subcategory',
        'tags',
        'level',
        'language',
        'thumbnail_path',
        'promo_video_path',
        'price',
        'discount_price',
        'is_free',
        'status',
        'approval_status',
        'admin_notes',
        'approved_by',
        'approved_at',
        'total_duration_minutes',
        'total_lectures',
        'total_enrollments',
        'average_rating',
        'total_reviews',
        'is_featured',
        'published_at',
    ];
    
    protected $casts = [
        'tags' => 'array',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_free' => 'boolean',
        'is_featured' => 'boolean',
        'average_rating' => 'decimal:2',
        'approved_at' => 'datetime',
        'published_at' => 'datetime',
    ];
    
    public function creator()
    {
        return $this->belongsTo(ContentCreator::class, 'creator_id');
    }
    
    public function sections()
    {
        return $this->hasMany(CourseSection::class)->orderBy('order');
    }
    
    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }
    
    public function reviews()
    {
        return $this->hasMany(CourseReview::class);
    }
    
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
```

**Migration:**
```php
Schema::create('courses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('creator_id')->constrained('content_creators');
    $table->string('title');
    $table->string('slug')->unique();
    $table->string('short_description', 500);
    $table->text('description');
    $table->string('category');
    $table->string('subcategory')->nullable();
    $table->json('tags')->nullable();
    $table->enum('level', ['beginner', 'intermediate', 'advanced']);
    $table->string('language')->default('en');
    $table->string('thumbnail_path')->nullable();
    $table->string('promo_video_path')->nullable();
    $table->decimal('price', 10, 2)->default(0);
    $table->decimal('discount_price', 10, 2')->nullable();
    $table->boolean('is_free')->default(false);
    $table->enum('status', ['draft', 'under_review', 'published', 'unpublished'])->default('draft');
    $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->text('admin_notes')->nullable();
    $table->foreignId('approved_by')->nullable()->constrained('users');
    $table->timestamp('approved_at')->nullable();
    $table->integer('total_duration_minutes')->default(0);
    $table->integer('total_lectures')->default(0);
    $table->integer('total_enrollments')->default(0);
    $table->decimal('average_rating', 3, 2)->default(0);
    $table->integer('total_reviews')->default(0);
    $table->boolean('is_featured')->default(false);
    $table->timestamp('published_at')->nullable();
    $table->timestamps();
    $table->softDeletes();
    
    $table->index(['status', 'approval_status']);
});
```

### 1.2 CourseSection Model

```php
// app/Models/CourseSection.php
class CourseSection extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order',
        'is_published',
    ];
    
    protected $casts = [
        'is_published' => 'boolean',
    ];
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function lectures()
    {
        return $this->hasMany(CourseLecture::class, 'section_id')->orderBy('order');
    }
}
```

**Migration:**
```php
Schema::create('course_sections', function (Blueprint $table) {
    $table->id();
    $table->foreignId('course_id')->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->text('description')->nullable();
    $table->integer('order')->default(0);
    $table->boolean('is_published')->default(true);
    $table->timestamps();
});
```

### 1.3 CourseLecture Model

```php
// app/Models/CourseLecture.php
class CourseLecture extends Model
{
    protected $fillable = [
        'section_id',
        'title',
        'description',
        'lecture_type',
        'video_url',
        'video_duration_minutes',
        'article_content',
        'pdf_path',
        'order',
        'is_preview',
        'is_published',
    ];
    
    protected $casts = [
        'video_duration_minutes' => 'integer',
        'is_preview' => 'boolean',
        'is_published' => 'boolean',
    ];
    
    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }
    
    public function progress()
    {
        return $this->hasMany(LectureProgress::class, 'lecture_id');
    }
    
    public function quiz()
    {
        return $this->hasOne(CourseQuiz::class, 'lecture_id');
    }
}
```

**Migration:**
```php
Schema::create('course_lectures', function (Blueprint $table) {
    $table->id();
    $table->foreignId('section_id')->constrained('course_sections')->cascadeOnDelete();
    $table->string('title');
    $table->text('description')->nullable();
    $table->enum('lecture_type', ['video', 'article', 'pdf', 'quiz']);
    $table->string('video_url')->nullable();
    $table->integer('video_duration_minutes')->nullable();
    $table->longText('article_content')->nullable();
    $table->string('pdf_path')->nullable();
    $table->integer('order')->default(0);
    $table->boolean('is_preview')->default(false);
    $table->boolean('is_published')->default(true);
    $table->timestamps();
});
```

### 1.4 CourseQuiz Model

```php
// app/Models/CourseQuiz.php
class CourseQuiz extends Model
{
    protected $fillable = [
        'lecture_id',
        'title',
        'description',
        'passing_score',
        'time_limit_minutes',
        'max_attempts',
        'randomize_questions',
        'show_correct_answers',
    ];
    
    protected $casts = [
        'passing_score' => 'integer',
        'time_limit_minutes' => 'integer',
        'max_attempts' => 'integer',
        'randomize_questions' => 'boolean',
        'show_correct_answers' => 'boolean',
    ];
    
    public function lecture()
    {
        return $this->belongsTo(CourseLecture::class, 'lecture_id');
    }
    
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id');
    }
    
    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class, 'quiz_id');
    }
}
```

**Migration:**
```php
Schema::create('course_quizzes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('lecture_id')->constrained('course_lectures')->cascadeOnDelete();
    $table->string('title');
    $table->text('description')->nullable();
    $table->integer('passing_score')->default(70); // percentage
    $table->integer('time_limit_minutes')->nullable();
    $table->integer('max_attempts')->default(3);
    $table->boolean('randomize_questions')->default(false);
    $table->boolean('show_correct_answers')->default(true);
    $table->timestamps();
});
```

### 1.5 QuizQuestion Model

```php
// app/Models/QuizQuestion.php
class QuizQuestion extends Model
{
    protected $fillable = [
        'quiz_id',
        'question',
        'question_type',
        'options',
        'correct_answer',
        'explanation',
        'points',
        'order',
    ];
    
    protected $casts = [
        'options' => 'array',
        'correct_answer' => 'array',
        'points' => 'integer',
    ];
    
    public function quiz()
    {
        return $this->belongsTo(CourseQuiz::class, 'quiz_id');
    }
}
```

**Migration:**
```php
Schema::create('quiz_questions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('quiz_id')->constrained('course_quizzes')->cascadeOnDelete();
    $table->text('question');
    $table->enum('question_type', ['multiple_choice', 'true_false', 'multiple_answer']);
    $table->json('options'); // For multiple choice
    $table->json('correct_answer'); // Can be single or multiple
    $table->text('explanation')->nullable();
    $table->integer('points')->default(1);
    $table->integer('order')->default(0);
    $table->timestamps();
});
```

### 1.6 CourseEnrollment Model

```php
// app/Models/CourseEnrollment.php
class CourseEnrollment extends Model
{
    protected $fillable = [
        'course_id',
        'student_id',
        'enrollment_date',
        'completion_percentage',
        'last_accessed_at',
        'completed_at',
        'certificate_issued',
        'certificate_id',
        'payment_status',
        'amount_paid',
    ];
    
    protected $casts = [
        'enrollment_date' => 'datetime',
        'last_accessed_at' => 'datetime',
        'completed_at' => 'datetime',
        'certificate_issued' => 'boolean',
        'completion_percentage' => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    public function certificate()
    {
        return $this->hasOne(CourseCertificate::class, 'enrollment_id');
    }
    
    public function lectureProgress()
    {
        return $this->hasMany(LectureProgress::class, 'enrollment_id');
    }
}
```

**Migration:**
```php
Schema::create('course_enrollments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('course_id')->constrained()->cascadeOnDelete();
    $table->foreignId('student_id')->constrained('users');
    $table->timestamp('enrollment_date')->useCurrent();
    $table->decimal('completion_percentage', 5, 2)->default(0);
    $table->timestamp('last_accessed_at')->nullable();
    $table->timestamp('completed_at')->nullable();
    $table->boolean('certificate_issued')->default(false);
    $table->foreignId('certificate_id')->nullable()->constrained('course_certificates');
    $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
    $table->decimal('amount_paid', 10, 2)->default(0);
    $table->timestamps();
    
    $table->unique(['course_id', 'student_id']);
});
```

### 1.7 LectureProgress Model

```php
// app/Models/LectureProgress.php
class LectureProgress extends Model
{
    protected $fillable = [
        'enrollment_id',
        'lecture_id',
        'student_id',
        'is_completed',
        'progress_percentage',
        'time_spent_seconds',
        'last_position_seconds',
        'completed_at',
    ];
    
    protected $casts = [
        'is_completed' => 'boolean',
        'progress_percentage' => 'decimal:2',
        'completed_at' => 'datetime',
    ];
    
    public function enrollment()
    {
        return $this->belongsTo(CourseEnrollment::class, 'enrollment_id');
    }
    
    public function lecture()
    {
        return $this->belongsTo(CourseLecture::class, 'lecture_id');
    }
    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
```

**Migration:**
```php
Schema::create('lecture_progress', function (Blueprint $table) {
    $table->id();
    $table->foreignId('enrollment_id')->constrained('course_enrollments')->cascadeOnDelete();
    $table->foreignId('lecture_id')->constrained('course_lectures')->cascadeOnDelete();
    $table->foreignId('student_id')->constrained('users');
    $table->boolean('is_completed')->default(false);
    $table->decimal('progress_percentage', 5, 2)->default(0);
    $table->integer('time_spent_seconds')->default(0);
    $table->integer('last_position_seconds')->nullable();
    $table->timestamp('completed_at')->nullable();
    $table->timestamps();
    
    $table->unique(['enrollment_id', 'lecture_id']);
});
```

### 1.8 CourseCertificate Model

```php
// app/Models/CourseCertificate.php
class CourseCertificate extends Model
{
    protected $fillable = [
        'enrollment_id',
        'student_id',
        'course_id',
        'certificate_number',
        'issued_at',
        'pdf_path',
        'verification_code',
    ];
    
    protected $casts = [
        'issued_at' => 'datetime',
    ];
    
    public function enrollment()
    {
        return $this->belongsTo(CourseEnrollment::class, 'enrollment_id');
    }
    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
```

**Migration:**
```php
Schema::create('course_certificates', function (Blueprint $table) {
    $table->id();
    $table->foreignId('enrollment_id')->constrained('course_enrollments');
    $table->foreignId('student_id')->constrained('users');
    $table->foreignId('course_id')->constrained('courses');
    $table->string('certificate_number')->unique();
    $table->timestamp('issued_at')->useCurrent();
    $table->string('pdf_path')->nullable();
    $table->string('verification_code')->unique();
    $table->timestamps();
});
```

### 1.9 CourseReview Model

```php
// app/Models/CourseReview.php
class CourseReview extends Model
{
    protected $fillable = [
        'course_id',
        'student_id',
        'enrollment_id',
        'rating',
        'review',
        'is_approved',
        'approved_by',
        'approved_at',
    ];
    
    protected $casts = [
        'rating' => 'decimal:2',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
    ];
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    public function enrollment()
    {
        return $this->belongsTo(CourseEnrollment::class, 'enrollment_id');
    }
}
```

**Migration:**
```php
Schema::create('course_reviews', function (Blueprint $table) {
    $table->id();
    $table->foreignId('course_id')->constrained()->cascadeOnDelete();
    $table->foreignId('student_id')->constrained('users');
    $table->foreignId('enrollment_id')->constrained('course_enrollments');
    $table->decimal('rating', 3, 2);
    $table->text('review')->nullable();
    $table->boolean('is_approved')->default(false);
    $table->foreignId('approved_by')->nullable()->constrained('users');
    $table->timestamp('approved_at')->nullable();
    $table->timestamps();
    
    $table->unique(['course_id', 'student_id']);
});
```

## 2. Payment & Transaction Models

### 2.1 Transaction Model

```php
// app/Models/Transaction.php
class Transaction extends Model
{
    protected $fillable = [
        'transaction_number',
        'user_id',
        'user_type',
        'transaction_type',
        'service_type',
        'service_id',
        'amount',
        'currency',
        'platform_commission',
        'net_amount',
        'payment_method',
        'payment_gateway_ref',
        'status',
        'payment_phone',
        'description',
        'metadata',
        'processed_at',
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'platform_commission' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'metadata' => 'array',
        'processed_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->morphTo();
    }
    
    public function service()
    {
        return $this->morphTo('service');
    }
}
```

**Migration:**
```php
Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->string('transaction_number')->unique();
    $table->string('user_type'); // User, Expert, Tutor, ContentCreator
    $table->unsignedBigInteger('user_id');
    $table->enum('transaction_type', ['payment', 'payout', 'refund', 'commission']);
    $table->string('service_type')->nullable(); // Project, TutoringSession, Course
    $table->unsignedBigInteger('service_id')->nullable();
    $table->decimal('amount', 10, 2);
    $table->string('currency', 3)->default('USD');
    $table->decimal('platform_commission', 10, 2)->default(0);
    $table->decimal('net_amount', 10, 2);
    $table->enum('payment_method', ['mpesa', 'paypal', 'pesapal', 'bank_transfer', 'wallet']);
    $table->string('payment_gateway_ref')->nullable();
    $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
    $table->string('payment_phone')->nullable();
    $table->text('description')->nullable();
    $table->json('metadata')->nullable();
    $table->timestamp('processed_at')->nullable();
    $table->timestamps();
    
    $table->index(['user_type', 'user_id']);
    $table->index(['service_type', 'service_id']);
});
```

### 2.2 Wallet Model

```php
// app/Models/Wallet.php
class Wallet extends Model
{
    protected $fillable = [
        'user_type',
        'user_id',
        'balance',
        'total_earned',
        'total_withdrawn',
        'total_pending',
        'currency',
    ];
    
    protected $casts = [
        'balance' => 'decimal:2',
        'total_earned' => 'decimal:2',
        'total_withdrawn' => 'decimal:2',
        'total_pending' => 'decimal:2',
    ];
    
    public function user()
    {
        return $this->morphTo();
    }
}
```

**Migration:**
```php
Schema::create('wallets', function (Blueprint $table) {
    $table->id();
    $table->string('user_type'); // Expert, Tutor, ContentCreator
    $table->unsignedBigInteger('user_id');
    $table->decimal('balance', 10, 2)->default(0);
    $table->decimal('total_earned', 10, 2)->default(0);
    $table->decimal('total_withdrawn', 10, 2)->default(0);
    $table->decimal('total_pending', 10, 2)->default(0);
    $table->string('currency', 3)->default('USD');
    $table->timestamps();
    
    $table->unique(['user_type', 'user_id']);
});
```

### 2.3 PayoutRequest Model

```php
// app/Models/PayoutRequest.php
class PayoutRequest extends Model
{
    protected $fillable = [
        'user_type',
        'user_id',
        'amount',
        'payout_method',
        'account_details',
        'status',
        'batch_id',
        'rejection_reason',
        'requested_at',
        'processed_by',
        'processed_at',
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'account_details' => 'array',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->morphTo();
    }
    
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
    
    public function batch()
    {
        return $this->belongsTo(PayoutBatch::class, 'batch_id');
    }
}
```

**Migration:**
```php
Schema::create('payout_requests', function (Blueprint $table) {
    $table->id();
    $table->string('user_type'); // Expert, Tutor, ContentCreator
    $table->unsignedBigInteger('user_id');
    $table->decimal('amount', 10, 2);
    $table->enum('payout_method', ['bank_transfer', 'mpesa', 'paypal']);
    $table->json('account_details'); // Bank details, phone, email
    $table->enum('status', ['pending', 'approved', 'processing', 'completed', 'rejected'])->default('pending');
    $table->foreignId('batch_id')->nullable()->constrained('payout_batches');
    $table->text('rejection_reason')->nullable();
    $table->timestamp('requested_at')->useCurrent();
    $table->foreignId('processed_by')->nullable()->constrained('users');
    $table->timestamp('processed_at')->nullable();
    $table->timestamps();
    
    $table->index(['user_type', 'user_id']);
});
```

### 2.4 PayoutBatch Model

```php
// app/Models/PayoutBatch.php
class PayoutBatch extends Model
{
    protected $fillable = [
        'batch_number',
        'total_amount',
        'total_payouts',
        'processed_by',
        'status',
        'payment_method',
        'processed_at',
    ];
    
    protected $casts = [
        'total_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];
    
    public function payouts()
    {
        return $this->hasMany(PayoutRequest::class, 'batch_id');
    }
    
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
```

**Migration:**
```php
Schema::create('payout_batches', function (Blueprint $table) {
    $table->id();
    $table->string('batch_number')->unique();
    $table->decimal('total_amount', 10, 2);
    $table->integer('total_payouts');
    $table->foreignId('processed_by')->constrained('users');
    $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
    $table->string('payment_method');
    $table->timestamp('processed_at')->nullable();
    $table->timestamps();
});
```
