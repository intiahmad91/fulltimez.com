<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPosting extends Model
{
    protected $fillable = [
        'employer_id',
        'category_id',
        'title',
        'slug',
        'description',
        'requirements',
        'responsibilities',
        'benefits',
        'employment_type',
        'experience_level',
        'experience_years',
        'education_level',
        'salary_min',
        'salary_max',
        'salary_currency',
        'salary_period',
        'salary_negotiable',
        'location_city',
        'location_state',
        'location_country',
        'remote_allowed',
        'positions_available',
        'application_deadline',
        'skills_required',
        'languages_required',
        'status',
        'priority',
        'ad_type',
        'is_premium',
        'premium_expires_at',
        'views_count',
        'applications_count',
        'published_at',
        'featured_expires_at',
        'featured_duration',
        'featured_amount',
    ];

    protected $casts = [
        'application_deadline' => 'date',
        'skills_required' => 'json',
        'languages_required' => 'json',
        'salary_negotiable' => 'boolean',
        'remote_allowed' => 'boolean',
        'is_premium' => 'boolean',
        'premium_expires_at' => 'datetime',
        'published_at' => 'datetime',
        'featured_expires_at' => 'datetime',
    ];

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }

    /**
     * Check if the job is currently featured
     */
    public function isFeatured(): bool
    {
        return $this->featured_expires_at && $this->featured_expires_at->isFuture();
    }

    /**
     * Scope for featured jobs
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured_expires_at', '>', now());
    }

    /**
     * Scope for non-featured jobs
     */
    public function scopeNotFeatured($query)
    {
        return $query->where(function($q) {
            $q->whereNull('featured_expires_at')
              ->orWhere('featured_expires_at', '<=', now());
        });
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'job_id');
    }

    public function isPremiumActive(): bool
    {
        return $this->is_premium && 
               $this->premium_expires_at && 
               $this->premium_expires_at->isFuture();
    }

    public function getPremiumStatusAttribute(): string
    {
        if (!$this->is_premium) {
            return 'standard';
        }

        if (!$this->premium_expires_at) {
            return 'premium';
        }

        if ($this->premium_expires_at->isFuture()) {
            return 'premium_active';
        }

        return 'premium_expired';
    }
}

