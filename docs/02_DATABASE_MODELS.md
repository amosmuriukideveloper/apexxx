# Database Models & Schema

## 1. User Management Models

### 1.1 User Model (Polymorphic Base)
```php
// app/Models/User.php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasRoles, SoftDeletes;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_photo',
        'status',
        'user_type',
        'last_login_at',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'status' => UserStatus::class,
    ];
}
```

**Migration:**
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->string('phone')->nullable();
    $table->string('profile_photo')->nullable();
    $table->enum('status', ['active', 'pending', 'suspended', 'rejected'])->default('active');
    $table->enum('user_type', ['student', 'admin', 'super_admin'])->default('student');
    $table->timestamp('last_login_at')->nullable();
    $table->rememberToken();
    $table->timestamps();
    $table->softDeletes();
});
```

### 1.2 Expert Model
```php
// app/Models/Expert.php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Expert extends Authenticatable
{
    use HasRoles, SoftDeletes;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'specialization',
        'expertise_areas',
        'bio',
        'years_of_experience',
        'application_status',
        'rejection_reason',
        'documents_verified',
        'rating',
        'total_projects_completed',
        'total_earnings',
        'available',
        'status',
        'approved_by',
        'approved_at',
        'profile_photo',
    ];
    
    protected $hidden = ['password', 'remember_token'];
    
    protected $casts = [
        'expertise_areas' => 'array',
        'documents_verified' => 'boolean',
        'available' => 'boolean',
        'approved_at' => 'datetime',
        'rating' => 'decimal:2',
        'total_earnings' => 'decimal:2',
    ];
    
    // Relationships
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    
    public function documents()
    {
        return $this->morphMany(UserDocument::class, 'documentable');
    }
    
    public function applicationForm()
    {
        return $this->morphOne(ApplicationForm::class, 'applicant');
    }
    
    public function wallet()
    {
        return $this->morphOne(Wallet::class, 'user');
    }
    
    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'user');
    }
}
```

**Migration:**
```php
Schema::create('experts', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->string('phone')->nullable();
    $table->string('specialization');
    $table->json('expertise_areas')->nullable();
    $table->text('bio')->nullable();
    $table->integer('years_of_experience')->default(0);
    $table->enum('application_status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->text('rejection_reason')->nullable();
    $table->boolean('documents_verified')->default(false);
    $table->decimal('rating', 3, 2)->default(0);
    $table->integer('total_projects_completed')->default(0);
    $table->decimal('total_earnings', 10, 2)->default(0);
    $table->boolean('available')->default(true);
    $table->enum('status', ['active', 'suspended'])->default('active');
    $table->foreignId('approved_by')->nullable()->constrained('users');
    $table->timestamp('approved_at')->nullable();
    $table->string('profile_photo')->nullable();
    $table->rememberToken();
    $table->timestamps();
    $table->softDeletes();
});
```

### 1.3 Tutor Model
```php
// app/Models/Tutor.php
class Tutor extends Authenticatable
{
    use HasRoles, SoftDeletes;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'subjects',
        'teaching_experience_years',
        'bio',
        'application_status',
        'rejection_reason',
        'documents_verified',
        'rating',
        'total_sessions_completed',
        'total_earnings',
        'available',
        'status',
        'approved_by',
        'approved_at',
        'profile_photo',
        'hourly_rate',
    ];
    
    protected $hidden = ['password', 'remember_token'];
    
    protected $casts = [
        'subjects' => 'array',
        'documents_verified' => 'boolean',
        'available' => 'boolean',
        'approved_at' => 'datetime',
        'rating' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'total_earnings' => 'decimal:2',
    ];
    
    // Relationships
    public function tutoringRequests()
    {
        return $this->hasMany(TutoringRequest::class);
    }
    
    public function tutoringSessions()
    {
        return $this->hasMany(TutoringSession::class);
    }
    
    public function documents()
    {
        return $this->morphMany(UserDocument::class, 'documentable');
    }
    
    public function applicationForm()
    {
        return $this->morphOne(ApplicationForm::class, 'applicant');
    }
}
```

**Migration:**
```php
Schema::create('tutors', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->string('phone')->nullable();
    $table->json('subjects')->nullable();
    $table->integer('teaching_experience_years')->default(0);
    $table->text('bio')->nullable();
    $table->enum('application_status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->text('rejection_reason')->nullable();
    $table->boolean('documents_verified')->default(false);
    $table->decimal('rating', 3, 2)->default(0);
    $table->integer('total_sessions_completed')->default(0);
    $table->decimal('total_earnings', 10, 2)->default(0);
    $table->boolean('available')->default(true);
    $table->enum('status', ['active', 'suspended'])->default('active');
    $table->foreignId('approved_by')->nullable()->constrained('users');
    $table->timestamp('approved_at')->nullable();
    $table->string('profile_photo')->nullable();
    $table->decimal('hourly_rate', 8, 2)->nullable();
    $table->rememberToken();
    $table->timestamps();
    $table->softDeletes();
});
```

### 1.4 ContentCreator Model
```php
// app/Models/ContentCreator.php
class ContentCreator extends Authenticatable
{
    use HasRoles, SoftDeletes;
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'expertise_areas',
        'bio',
        'portfolio_url',
        'application_status',
        'rejection_reason',
        'documents_verified',
        'total_courses',
        'total_students',
        'total_earnings',
        'status',
        'approved_by',
        'approved_at',
        'profile_photo',
    ];
    
    protected $hidden = ['password', 'remember_token'];
    
    protected $casts = [
        'expertise_areas' => 'array',
        'documents_verified' => 'boolean',
        'approved_at' => 'datetime',
        'total_earnings' => 'decimal:2',
    ];
    
    // Relationships
    public function courses()
    {
        return $this->hasMany(Course::class, 'creator_id');
    }
    
    public function documents()
    {
        return $this->morphMany(UserDocument::class, 'documentable');
    }
    
    public function applicationForm()
    {
        return $this->morphOne(ApplicationForm::class, 'applicant');
    }
}
```

**Migration:**
```php
Schema::create('content_creators', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->string('phone')->nullable();
    $table->json('expertise_areas')->nullable();
    $table->text('bio')->nullable();
    $table->string('portfolio_url')->nullable();
    $table->enum('application_status', ['pending', 'approved', 'rejected'])->default('pending');
    $table->text('rejection_reason')->nullable();
    $table->boolean('documents_verified')->default(false);
    $table->integer('total_courses')->default(0);
    $table->integer('total_students')->default(0);
    $table->decimal('total_earnings', 10, 2)->default(0);
    $table->enum('status', ['active', 'suspended'])->default('active');
    $table->foreignId('approved_by')->nullable()->constrained('users');
    $table->timestamp('approved_at')->nullable();
    $table->string('profile_photo')->nullable();
    $table->rememberToken();
    $table->timestamps();
    $table->softDeletes();
});
```

### 1.5 ApplicationForm Model
```php
// app/Models/ApplicationForm.php
class ApplicationForm extends Model
{
    protected $fillable = [
        'applicant_type',
        'applicant_id',
        'full_name',
        'email',
        'phone',
        'education_level',
        'institution',
        'field_of_study',
        'years_of_experience',
        'expertise_areas',
        'why_join',
        'sample_work_url',
        'linkedin_profile',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
    ];
    
    protected $casts = [
        'expertise_areas' => 'array',
        'reviewed_at' => 'datetime',
    ];
    
    // Polymorphic relationship
    public function applicant()
    {
        return $this->morphTo();
    }
    
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
```

**Migration:**
```php
Schema::create('application_forms', function (Blueprint $table) {
    $table->id();
    $table->string('applicant_type'); // Expert, Tutor, ContentCreator
    $table->unsignedBigInteger('applicant_id');
    $table->string('full_name');
    $table->string('email');
    $table->string('phone');
    $table->string('education_level')->nullable();
    $table->string('institution')->nullable();
    $table->string('field_of_study')->nullable();
    $table->integer('years_of_experience')->default(0);
    $table->json('expertise_areas')->nullable();
    $table->text('why_join')->nullable();
    $table->string('sample_work_url')->nullable();
    $table->string('linkedin_profile')->nullable();
    $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending');
    $table->foreignId('reviewed_by')->nullable()->constrained('users');
    $table->timestamp('reviewed_at')->nullable();
    $table->text('review_notes')->nullable();
    $table->timestamps();
    
    $table->index(['applicant_type', 'applicant_id']);
});
```

### 1.6 UserDocument Model
```php
// app/Models/UserDocument.php
class UserDocument extends Model
{
    protected $fillable = [
        'documentable_id',
        'documentable_type',
        'document_type',
        'file_path',
        'file_name',
        'file_size',
        'verified',
        'verified_by',
        'verified_at',
    ];
    
    protected $casts = [
        'verified' => 'boolean',
        'verified_at' => 'datetime',
    ];
    
    // Polymorphic relationship
    public function documentable()
    {
        return $this->morphTo();
    }
    
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
```

**Migration:**
```php
Schema::create('user_documents', function (Blueprint $table) {
    $table->id();
    $table->morphs('documentable'); // Creates documentable_id and documentable_type
    $table->enum('document_type', ['cv', 'certificate', 'id', 'portfolio', 'other']);
    $table->string('file_path');
    $table->string('file_name');
    $table->unsignedBigInteger('file_size')->nullable();
    $table->boolean('verified')->default(false);
    $table->foreignId('verified_by')->nullable()->constrained('users');
    $table->timestamp('verified_at')->nullable();
    $table->timestamps();
});
```

## 2. Project Management Models

### 2.1 Project Model
```php
// app/Models/Project.php
class Project extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'project_number',
        'student_id',
        'expert_id',
        'admin_id',
        'title',
        'description',
        'project_type',
        'complexity_level',
        'subject_area',
        'requirements',
        'word_count',
        'page_count',
        'deadline',
        'cost',
        'platform_commission',
        'expert_earnings',
        'status',
        'payment_status',
        'quality_score',
        'turnitin_score',
        'ai_detection_score',
        'student_rating',
        'student_review',
        'assigned_at',
        'started_at',
        'submitted_at',
        'completed_at',
    ];
    
    protected $casts = [
        'requirements' => 'array',
        'deadline' => 'datetime',
        'cost' => 'decimal:2',
        'platform_commission' => 'decimal:2',
        'expert_earnings' => 'decimal:2',
        'quality_score' => 'decimal:2',
        'turnitin_score' => 'decimal:2',
        'ai_detection_score' => 'decimal:2',
        'student_rating' => 'decimal:2',
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];
    
    // Relationships
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    public function expert()
    {
        return $this->belongsTo(Expert::class);
    }
    
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    
    public function materials()
    {
        return $this->hasMany(ProjectMaterial::class);
    }
    
    public function submissions()
    {
        return $this->hasMany(ProjectSubmission::class);
    }
    
    public function revisions()
    {
        return $this->hasMany(ProjectRevision::class);
    }
    
    public function messages()
    {
        return $this->hasMany(ProjectMessage::class);
    }
    
    public function statusHistory()
    {
        return $this->hasMany(ProjectStatusHistory::class);
    }
}
```

**Migration:** (Next document due to length)
