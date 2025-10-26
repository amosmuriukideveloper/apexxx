<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\HasRoleHelpers;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasRoleHelpers;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get projects created by this user (if student)
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'student_id');
    }

    /**
     * Get projects assigned to this user (if expert)
     */
    public function assignedProjects()
    {
        return $this->hasMany(Project::class, 'assigned_expert_id');
    }

    /**
     * Get courses enrolled by this user (if student)
     */
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'course_enrollments', 'student_id', 'course_id')
            ->withPivot(['enrollment_date', 'completion_percentage', 'last_accessed_at', 'completed_at', 'certificate_issued', 'payment_status', 'amount_paid'])
            ->withTimestamps();
    }

    /**
     * Get courses created by this user (if content creator)
     */
    public function createdCourses()
    {
        return $this->hasMany(Course::class, 'creator_id');
    }

    /**
     * Get tutoring sessions for this user (as student)
     */
    public function tutoringSessions()
    {
        return $this->hasMany(TutoringSession::class, 'student_id');
    }

    /**
     * Get tutoring sessions conducted by this user (as tutor)
     * Note: This requires the user to have a tutor record
     */
    public function conductedSessions()
    {
        // First get the tutor record for this user
        return $this->hasManyThrough(
            TutoringSession::class,
            Tutor::class,
            'user_id', // Foreign key on tutors table
            'tutor_id', // Foreign key on tutoring_sessions table
            'id', // Local key on users table
            'id' // Local key on tutors table
        );
    }

    /**
     * Get the tutor record for this user
     */
    public function tutor()
    {
        return $this->hasOne(Tutor::class, 'user_id');
    }

    /**
     * Get the expert record for this user
     */
    public function expert()
    {
        return $this->hasOne(Expert::class, 'user_id');
    }

    /**
     * Get the content creator record for this user
     */
    public function contentCreator()
    {
        return $this->hasOne(ContentCreator::class, 'user_id');
    }
}
