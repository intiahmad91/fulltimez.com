<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'profile_picture',
        'company_name',
        'company_logo',
        'company_website',
        'industry',
        'company_size',
        'founded_year',
        'company_description',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'mobile_no',
        'email_id',
        'landline_no',
        'contact_person',
        'contact_email',
        'contact_phone',
        'trade_license',
        'verification_status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}



