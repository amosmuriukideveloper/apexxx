# Project & Tutoring Models - Migrations

## 1. Project Model Migration

```php
// database/migrations/xxxx_create_projects_table.php
Schema::create('projects', function (Blueprint $table) {
    $table->id();
    $table->string('project_number')->unique();
    $table->foreignId('student_id')->constrained('users');
    $table->foreignId('expert_id')->nullable()->constrained('experts');
    $table->foreignId('admin_id')->nullable()->constrained('users');
    $table->string('title');
    $table->text('description');
    $table->string('project_type');
    $table->enum('complexity_level', ['basic', 'intermediate', 'advanced']);
    $table->string('subject_area');
    $table->json('requirements')->nullable();
    $table->integer('word_count')->nullable();
    $table->integer('page_count')->nullable();
    $table->timestamp('deadline');
    $table->decimal('cost', 10, 2);
    $table->decimal('platform_commission', 10, 2);
    $table->decimal('expert_earnings', 10, 2);
    $table->enum('status', [
        'awaiting_assignment',
        'assigned',
        'in_progress',
        'under_review',
        'revision_required',
        'completed',
        'cancelled'
    ])->default('awaiting_assignment');
    $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
    $table->decimal('quality_score', 3, 2)->nullable();
    $table->decimal('turnitin_score', 5, 2)->nullable();
    $table->decimal('ai_detection_score', 5, 2)->nullable();
    $table->decimal('student_rating', 3, 2)->nullable();
    $table->text('student_review')->nullable();
    $table->timestamp('assigned_at')->nullable();
    $table->timestamp('started_at')->nullable();
    $table->timestamp('submitted_at')->nullable();
    $table->timestamp('completed_at')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

## 2. ProjectSubmission Model

```php
// app/Models/ProjectSubmission.php
class ProjectSubmission extends Model
{
    protected $fillable = [
        'project_id',
        'expert_id',
        'submission_type',
        'version_number',
        'description',
        'turnitin_report_path',
        'ai_detection_report_path',
        'turnitin_score',
        'ai_detection_score',
        'admin_review_status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
    ];
    
    protected $casts = [
        'turnitin_score' => 'decimal:2',
        'ai_detection_score' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
    public function expert()
    {
        return $this->belongsTo(Expert::class);
    }
    
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
    
    public function files()
    {
        return $this->hasMany(SubmissionFile::class);
    }
}
```

**Migration:**
```php
Schema::create('project_submissions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('project_id')->constrained()->cascadeOnDelete();
    $table->foreignId('expert_id')->constrained();
    $table->enum('submission_type', ['draft', 'final', 'revision']);
    $table->integer('version_number')->default(1);
    $table->text('description')->nullable();
    $table->string('turnitin_report_path')->nullable();
    $table->string('ai_detection_report_path')->nullable();
    $table->decimal('turnitin_score', 5, 2)->nullable();
    $table->decimal('ai_detection_score', 5, 2)->nullable();
    $table->enum('admin_review_status', ['pending', 'approved', 'revision_required', 'rejected'])->default('pending');
    $table->text('admin_notes')->nullable();
    $table->foreignId('reviewed_by')->nullable()->constrained('users');
    $table->timestamp('reviewed_at')->nullable();
    $table->timestamps();
});
```

## 3. ProjectMaterial Model

```php
// app/Models/ProjectMaterial.php
class ProjectMaterial extends Model
{
    protected $fillable = [
        'project_id',
        'uploaded_by',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'description',
    ];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
```

**Migration:**
```php
Schema::create('project_materials', function (Blueprint $table) {
    $table->id();
    $table->foreignId('project_id')->constrained()->cascadeOnDelete();
    $table->foreignId('uploaded_by')->constrained('users');
    $table->string('file_path');
    $table->string('file_name');
    $table->string('file_type');
    $table->unsignedBigInteger('file_size');
    $table->text('description')->nullable();
    $table->timestamps();
});
```

## 4. ProjectRevision Model

```php
// app/Models/ProjectRevision.php
class ProjectRevision extends Model
{
    protected $fillable = [
        'project_id',
        'requested_by',
        'requester_id',
        'revision_notes',
        'deadline_extension',
        'status',
        'completed_at',
    ];
    
    protected $casts = [
        'completed_at' => 'datetime',
    ];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
    public function requester()
    {
        return $this->morphTo('requester');
    }
}
```

**Migration:**
```php
Schema::create('project_revisions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('project_id')->constrained()->cascadeOnDelete();
    $table->string('requested_by'); // 'admin' or 'student'
    $table->unsignedBigInteger('requester_id');
    $table->text('revision_notes');
    $table->integer('deadline_extension')->default(0); // days
    $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
    $table->timestamp('completed_at')->nullable();
    $table->timestamps();
});
```

## 5. ProjectMessage Model

```php
// app/Models/ProjectMessage.php
class ProjectMessage extends Model
{
    protected $fillable = [
        'project_id',
        'sender_type',
        'sender_id',
        'message',
        'attachments',
        'read_at',
    ];
    
    protected $casts = [
        'attachments' => 'array',
        'read_at' => 'datetime',
    ];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
    public function sender()
    {
        return $this->morphTo();
    }
}
```

**Migration:**
```php
Schema::create('project_messages', function (Blueprint $table) {
    $table->id();
    $table->foreignId('project_id')->constrained()->cascadeOnDelete();
    $table->string('sender_type'); // User, Expert
    $table->unsignedBigInteger('sender_id');
    $table->text('message');
    $table->json('attachments')->nullable();
    $table->timestamp('read_at')->nullable();
    $table->timestamps();
    
    $table->index(['sender_type', 'sender_id']);
});
```

## 6. ProjectStatusHistory Model

```php
// app/Models/ProjectStatusHistory.php
class ProjectStatusHistory extends Model
{
    protected $fillable = [
        'project_id',
        'old_status',
        'new_status',
        'changed_by_type',
        'changed_by_id',
        'notes',
    ];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
    public function changedBy()
    {
        return $this->morphTo('changedBy');
    }
}
```

**Migration:**
```php
Schema::create('project_status_histories', function (Blueprint $table) {
    $table->id();
    $table->foreignId('project_id')->constrained()->cascadeOnDelete();
    $table->string('old_status');
    $table->string('new_status');
    $table->string('changed_by_type');
    $table->unsignedBigInteger('changed_by_id');
    $table->text('notes')->nullable();
    $table->timestamps();
    
    $table->index(['changed_by_type', 'changed_by_id']);
});
```

## 7. Tutoring Models

### 7.1 TutoringRequest Model

```php
// app/Models/TutoringRequest.php
class TutoringRequest extends Model
{
    protected $fillable = [
        'request_number',
        'student_id',
        'tutor_id',
        'admin_id',
        'subject',
        'topic',
        'description',
        'preferred_date',
        'preferred_time',
        'session_duration',
        'learning_goals',
        'status',
        'assigned_at',
    ];
    
    protected $casts = [
        'preferred_date' => 'date',
        'assigned_at' => 'datetime',
    ];
    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
    
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    
    public function session()
    {
        return $this->hasOne(TutoringSession::class, 'request_id');
    }
}
```

**Migration:**
```php
Schema::create('tutoring_requests', function (Blueprint $table) {
    $table->id();
    $table->string('request_number')->unique();
    $table->foreignId('student_id')->constrained('users');
    $table->foreignId('tutor_id')->nullable()->constrained('tutors');
    $table->foreignId('admin_id')->nullable()->constrained('users');
    $table->string('subject');
    $table->string('topic');
    $table->text('description');
    $table->date('preferred_date');
    $table->time('preferred_time');
    $table->integer('session_duration')->default(60); // minutes
    $table->text('learning_goals')->nullable();
    $table->enum('status', ['pending', 'assigned', 'scheduled', 'completed', 'cancelled'])->default('pending');
    $table->timestamp('assigned_at')->nullable();
    $table->timestamps();
});
```

### 7.2 TutoringSession Model

```php
// app/Models/TutoringSession.php
class TutoringSession extends Model
{
    protected $fillable = [
        'request_id',
        'tutor_id',
        'student_id',
        'scheduled_date',
        'scheduled_time',
        'duration_minutes',
        'google_meet_link',
        'calendar_event_id',
        'status',
        'session_notes',
        'recording_url',
        'attendance_status',
        'payment_status',
        'session_fee',
        'platform_commission',
        'tutor_earnings',
        'student_rating',
        'student_feedback',
        'started_at',
        'ended_at',
    ];
    
    protected $casts = [
        'scheduled_date' => 'date',
        'session_fee' => 'decimal:2',
        'platform_commission' => 'decimal:2',
        'tutor_earnings' => 'decimal:2',
        'student_rating' => 'decimal:2',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];
    
    public function request()
    {
        return $this->belongsTo(TutoringRequest::class, 'request_id');
    }
    
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    public function materials()
    {
        return $this->hasMany(SessionMaterial::class, 'session_id');
    }
}
```

**Migration:**
```php
Schema::create('tutoring_sessions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('request_id')->constrained('tutoring_requests');
    $table->foreignId('tutor_id')->constrained('tutors');
    $table->foreignId('student_id')->constrained('users');
    $table->date('scheduled_date');
    $table->time('scheduled_time');
    $table->integer('duration_minutes')->default(60);
    $table->string('google_meet_link')->nullable();
    $table->string('calendar_event_id')->nullable();
    $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled', 'no_show'])->default('scheduled');
    $table->text('session_notes')->nullable();
    $table->string('recording_url')->nullable();
    $table->enum('attendance_status', ['both_present', 'student_absent', 'tutor_absent', 'both_absent'])->nullable();
    $table->enum('payment_status', ['pending', 'paid'])->default('pending');
    $table->decimal('session_fee', 10, 2);
    $table->decimal('platform_commission', 10, 2);
    $table->decimal('tutor_earnings', 10, 2');
    $table->decimal('student_rating', 3, 2)->nullable();
    $table->text('student_feedback')->nullable();
    $table->timestamp('started_at')->nullable();
    $table->timestamp('ended_at')->nullable();
    $table->timestamps();
});
```

### 7.3 SessionMaterial Model

```php
// app/Models/SessionMaterial.php
class SessionMaterial extends Model
{
    protected $fillable = [
        'session_id',
        'tutor_id',
        'material_type',
        'file_path',
        'file_name',
        'description',
    ];
    
    public function session()
    {
        return $this->belongsTo(TutoringSession::class, 'session_id');
    }
    
    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
}
```

**Migration:**
```php
Schema::create('session_materials', function (Blueprint $table) {
    $table->id();
    $table->foreignId('session_id')->constrained('tutoring_sessions')->cascadeOnDelete();
    $table->foreignId('tutor_id')->constrained('tutors');
    $table->enum('material_type', ['notes', 'slides', 'recording', 'resource', 'other']);
    $table->string('file_path');
    $table->string('file_name');
    $table->text('description')->nullable();
    $table->timestamps();
});
```

### 7.4 SessionFeedback Model

```php
// app/Models/SessionFeedback.php
class SessionFeedback extends Model
{
    protected $fillable = [
        'session_id',
        'feedback_by',
        'feedback_by_id',
        'rating',
        'feedback',
        'strengths',
        'improvements',
    ];
    
    protected $casts = [
        'strengths' => 'array',
        'improvements' => 'array',
        'rating' => 'decimal:2',
    ];
    
    public function session()
    {
        return $this->belongsTo(TutoringSession::class, 'session_id');
    }
    
    public function feedbackBy()
    {
        return $this->morphTo('feedbackBy');
    }
}
```

**Migration:**
```php
Schema::create('session_feedbacks', function (Blueprint $table) {
    $table->id();
    $table->foreignId('session_id')->constrained('tutoring_sessions')->cascadeOnDelete();
    $table->string('feedback_by'); // 'student' or 'tutor'
    $table->unsignedBigInteger('feedback_by_id');
    $table->decimal('rating', 3, 2);
    $table->text('feedback');
    $table->json('strengths')->nullable();
    $table->json('improvements')->nullable();
    $table->timestamps();
    
    $table->index(['feedback_by', 'feedback_by_id']);
});
```
