<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployerDocument extends Model
{
    protected $fillable = [
        'employer_id',
        'document_type',
        'document_path',
        'document_number',
        'landline_number',
        'company_email',
        'company_website',
        'contact_person_name',
        'contact_person_mobile',
        'contact_person_position',
        'contact_person_email',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function employer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function getDocumentTypeNameAttribute(): string
    {
        return match($this->document_type) {
            'trade_license' => 'Trade License',
            'office_landline' => 'Office Landline',
            'company_email' => 'Company Email',
            'company_info' => 'Company Information',
            default => ucfirst(str_replace('_', ' ', $this->document_type))
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending' => 'badge-warning',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
            default => 'badge-secondary'
        };
    }
}
