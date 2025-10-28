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
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'skills' => 'array',
        'languages' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}



