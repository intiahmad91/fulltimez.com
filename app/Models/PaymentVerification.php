<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'employer_id',
        'job_id',
        'package_id',
        'package_type',
        'amount',
        'currency',
        'payment_method',
        'transaction_id',
        'payment_screenshot',
        'payment_notes',
        'status',
        'admin_notes',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class, 'job_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function getPackageDisplayNameAttribute(): string
    {
        return match($this->package_type) {
            'premium' => 'Premium Package',
            'premium_15' => 'Premium 15 Days',
            'premium_30' => 'Premium 30 Days',
            default => ucfirst($this->package_type),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'verified' => 'success',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending Verification',
            'verified' => 'Payment Verified',
            'rejected' => 'Payment Rejected',
            default => ucfirst($this->status),
        };
    }
}