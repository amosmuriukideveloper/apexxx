<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

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

    public function purchases()
    {
        return $this->morphMany(ResourcePurchase::class, 'resource');
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
