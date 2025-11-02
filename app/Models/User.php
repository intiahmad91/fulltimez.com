<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'status',
        'is_approved',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function seekerProfile(): HasOne
    {
        return $this->hasOne(SeekerProfile::class, 'user_id');
    }

    public function employerProfile(): HasOne
    {
        return $this->hasOne(EmployerProfile::class, 'user_id');
    }

    public function jobPostings(): HasMany
    {
        return $this->hasMany(JobPosting::class, 'employer_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'seeker_id');
    }

    public function educationRecords(): HasMany
    {
        return $this->hasMany(EducationRecord::class, 'seeker_id');
    }

    public function experienceRecords(): HasMany
    {
        return $this->hasMany(ExperienceRecord::class, 'seeker_id');
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'seeker_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'seeker_id');
    }

    public function employerDocuments(): HasMany
    {
        return $this->hasMany(EmployerDocument::class, 'employer_id');
    }

    public function isAdmin(): bool
    {
        return $this->role->slug === 'admin';
    }

    public function isEmployer(): bool
    {
        return $this->role->slug === 'employer';
    }

    public function isSeeker(): bool
    {
        return $this->role->slug === 'seeker';
    }

    public function isVerifiedEmployer(): bool
    {
        return $this->isEmployer() && 
               $this->employerProfile && 
               $this->employerProfile->verification_status === 'verified';
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification());
    }
}
