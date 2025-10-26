<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING_REVIEW = 'pending_review';
    const STATUS_APPROVED = 'approved';
    const STATUS_PUBLISHED = 'published';
    const STATUS_REJECTED = 'rejected';

    const DIFFICULTY_BEGINNER = 'beginner';
    const DIFFICULTY_INTERMEDIATE = 'intermediate';
    const DIFFICULTY_ADVANCED = 'advanced';

    protected $fillable = [
        'creator_id',
        'title',
        'slug',
        'short_description',
        'description',
        'category_id',
        'price',
        'sale_price',
        'thumbnail',
        'intro_video',
        'difficulty',
        'status',
        'rejection_reason',
        'published_at',
        'total_duration_minutes',
        'total_lectures',
        'total_enrollments',
        'average_rating',
        'is_featured',
        'language',
        'objectives',
        'requirements',
        'target_audience',
        'certificate_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'average_rating' => 'decimal:1',
        'is_featured' => 'boolean',
        'certificate_available' => 'boolean',
        'published_at' => 'datetime',
        'total_duration_minutes' => 'integer',
        'total_lectures' => 'integer',
        'total_enrollments' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title) . '-' . Str::random(6);
            }
            
            if ($course->status === self::STATUS_PUBLISHED && !$course->published_at) {
                $course->published_at = now();
            }
        });

        static::updating(function ($course) {
            if ($course->isDirty('status') && $course->status === self::STATUS_PUBLISHED && !$course->published_at) {
                $course->published_at = now();
            }
        });

        static::deleting(function ($course) {
            // Delete related sections and lectures
            $course->sections->each->delete();
            
            // Delete thumbnail if exists
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            
            // Delete intro video if exists
            if ($course->intro_video) {
                Storage::disk('public')->delete($course->intro_video);
            }
        });
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function category()
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }

    public function sections()
    {
        return $this->hasMany(CourseSection::class)->orderBy('sort_order');
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function reviews()
    {
        return $this->hasMany(CourseReview::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function approvedReviews()
    {
        return $this->reviews()->where('is_approved', true);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopePendingReview($query)
    {
        return $query->where('status', self::STATUS_PENDING_REVIEW);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeForCreator($query, $userId)
    {
        return $query->where('creator_id', $userId);
    }

    // Helpers
    public function getCurrentPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function isFree()
    {
        return $this->price <= 0;
    }

    public function hasDiscount()
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    public function discountPercentage()
    {
        if (!$this->hasDiscount()) {
            return 0;
        }
        
        return (int) ((($this->price - $this->sale_price) / $this->price) * 100);
    }

    public function getTotalDurationFormattedAttribute()
    {
        $hours = floor($this->total_duration_minutes / 60);
        $minutes = $this->total_duration_minutes % 60;
        
        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }
        
        return "{$minutes}m";
    }

    public function enrollUser($userId, $paymentData = null)
    {
        return $this->enrollments()->create([
            'user_id' => $userId,
            'amount_paid' => $this->current_price,
            'payment_method' => $paymentData['method'] ?? null,
            'transaction_id' => $paymentData['transaction_id'] ?? null,
        ]);
    }

    public function isEnrolledByUser($userId)
    {
        return $this->enrollments()->where('user_id', $userId)->exists();
    }

    public function getEnrollmentForUser($userId)
    {
        return $this->enrollments()->where('user_id', $userId)->first();
    }

    public function calculateAverageRating()
    {
        $this->average_rating = $this->approvedReviews()->avg('rating');
        $this->total_reviews = $this->approvedReviews()->count();
        $this->save();
        
        return $this->average_rating;
    }

    public function submitForReview()
    {
        $this->status = self::STATUS_PENDING_REVIEW;
        $this->save();
        
        // TODO: Send notification to admin
        
        return $this;
    }

    public function approve(User $approver)
    {
        $this->status = self::STATUS_APPROVED;
        $this->approved_by = $approver->id;
        $this->approved_at = now();
        $this->rejection_reason = null;
        $this->save();
        
        // TODO: Send notification to creator
        
        return $this;
    }

    public function reject($reason, User $rejector)
    {
        $this->status = self::STATUS_REJECTED;
        $this->rejection_reason = $reason;
        $this->approved_by = $rejector->id;
        $this->approved_at = now();
        $this->save();
        
        // TODO: Send notification to creator
        
        return $this;
    }

    public function publish()
    {
        if ($this->status !== self::STATUS_APPROVED) {
            throw new \Exception('Only approved courses can be published');
        }
        
        $this->status = self::STATUS_PUBLISHED;
        $this->published_at = now();
        $this->save();
        
        return $this;
    }

    public function unpublish()
    {
        $this->status = self::STATUS_APPROVED;
        $this->save();
        
        return $this;
    }

    public function calculateTotalDuration()
    {
        $totalMinutes = 0;
        
        foreach ($this->sections as $section) {
            foreach ($section->lectures as $lecture) {
                $totalMinutes += $lecture->video_duration ?? 0;
            }
        }
        
        $this->total_duration_minutes = $totalMinutes;
        $this->total_lectures = $this->sections->sum(fn($section) => $section->lectures->count());
        $this->save();
        
        return $totalMinutes;
    }

    public function getTotalLecturesCountAttribute()
    {
        if (isset($this->total_lectures)) {
            return $this->total_lectures;
        }
        
        return $this->sections->sum(fn($section) => $section->lectures->count());
    }

    public function getFirstLecture()
    {
        $firstSection = $this->sections()->orderBy('sort_order')->first();
        
        if (!$firstSection) {
            return null;
        }
        
        return $firstSection->lectures()->orderBy('sort_order')->first();
    }

    public function getNextLecture($currentLectureId)
    {
        $currentLecture = CourseLecture::findOrFail($currentLectureId);
        
        // Try to find next lecture in the same section
        $nextLecture = $currentLecture->section->lectures()
            ->where('sort_order', '>', $currentLecture->sort_order)
            ->orderBy('sort_order')
            ->first();
        
        if ($nextLecture) {
            return $nextLecture;
        }
        
        // If no more lectures in current section, find first lecture in next section
        $nextSection = $this->sections()
            ->where('sort_order', '>', $currentLecture->section->sort_order)
            ->orderBy('sort_order')
            ->first();
        
        if ($nextSection) {
            return $nextSection->lectures()->orderBy('sort_order')->first();
        }
        
        return null;
    }

    public function getPreviousLecture($currentLectureId)
    {
        $currentLecture = CourseLecture::findOrFail($currentLectureId);
        
        // Try to find previous lecture in the same section
        $prevLecture = $currentLecture->section->lectures()
            ->where('sort_order', '<', $currentLecture->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();
        
        if ($prevLecture) {
            return $prevLecture;
        }
        
        // If no previous lectures in current section, find last lecture in previous section
        $prevSection = $this->sections()
            ->where('sort_order', '<', $currentLecture->section->sort_order)
            ->orderBy('sort_order', 'desc')
            ->first();
        
        if ($prevSection) {
            return $prevSection->lectures()->orderBy('sort_order', 'desc')->first();
        }
        
        return null;
    }

    public function getCompletionPercentage($userId)
    {
        $enrollment = $this->enrollments()->where('user_id', $userId)->first();
        
        if (!$enrollment) {
            return 0;
        }
        
        $totalLectures = $this->total_lectures;
        $completedLectures = $enrollment->completedLectures()->count();
        
        if ($totalLectures === 0) {
            return 0;
        }
        
        return (int) (($completedLectures / $totalLectures) * 100);
    }

    public function hasCertificate()
    {
        return $this->certificate_available && $this->status === self::STATUS_PUBLISHED;
    }

    public function getCertificateForUser($userId)
    {
        if (!$this->hasCertificate()) {
            return null;
        }
        
        return $this->certificates()->where('user_id', $userId)->first();
    }
}
