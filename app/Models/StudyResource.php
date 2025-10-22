<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyResource extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'creator_id',
        'title',
        'slug',
        'description',
        'resource_type',
        'category',
        'subject',
        'level',
        'file_path',
        'file_type',
        'file_size',
        'thumbnail_path',
        'price',
        'discount_price',
        'is_free',
        'status',
        'approval_status',
        'admin_notes',
        'approved_by',
        'approved_at',
        'download_count',
        'average_rating',
        'total_reviews',
        'is_featured',
        'is_active',
        'published_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_free' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'average_rating' => 'decimal:2',
        'approved_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(ContentCreator::class, 'creator_id');
    }

    public function purchases()
    {
        return $this->morphMany(ResourcePurchase::class, 'resource');
    }

    public function reviews()
    {
        return $this->hasMany(ResourceReview::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isActive()
    {
        return $this->is_active ?? true;
    }
}
