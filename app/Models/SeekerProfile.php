<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeekerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'date_of_birth',
        'gender',
        'nationality',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'profile_picture',
        'bio',
        'current_position',
        'experience_years',
        'expected_salary',
        'cv_file',
        'skills',
        'languages',
        'availability',
        'verification_status',
        'approval_status',
        'is_featured',
        'featured_expires_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'skills' => 'array',
        'languages' => 'array',
        'is_featured' => 'boolean',
        'featured_expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the profile is currently featured
     */
    public function isFeatured(): bool
    {
        return $this->is_featured && $this->featured_expires_at && $this->featured_expires_at->isFuture();
    }

    /**
     * Scope for approved profiles
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope for pending profiles
     */
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Scope for featured profiles
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
                    ->where('featured_expires_at', '>', now());
    }

    /**
     * Scope for non-featured profiles
     */
    public function scopeNotFeatured($query)
    {
        return $query->where(function($q) {
            $q->where('is_featured', false)
              ->orWhereNull('featured_expires_at')
              ->orWhere('featured_expires_at', '<=', now());
        });
    }
}



