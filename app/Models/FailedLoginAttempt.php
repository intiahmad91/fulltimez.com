<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedLoginAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'ip_address',
        'user_agent',
        'attempt_count',
        'last_attempt_at',
        'notification_sent',
    ];

    protected $casts = [
        'last_attempt_at' => 'datetime',
        'notification_sent' => 'boolean',
    ];

    /**
     * Get or create a failed login attempt record
     */
    public static function recordAttempt($email, $ipAddress = null, $userAgent = null)
    {
        $attempt = self::where('email', $email)->first();
        
        if ($attempt) {
            $attempt->increment('attempt_count');
            $attempt->update([
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'last_attempt_at' => now(),
            ]);
        } else {
            $attempt = self::create([
                'email' => $email,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'attempt_count' => 1,
                'last_attempt_at' => now(),
            ]);
        }
        
        return $attempt;
    }

    /**
     * Check if notification should be sent
     */
    public function shouldSendNotification()
    {
        return $this->attempt_count >= 3 && !$this->notification_sent;
    }

    /**
     * Mark notification as sent
     */
    public function markNotificationSent()
    {
        $this->update(['notification_sent' => true]);
    }

    /**
     * Reset failed attempts for an email
     */
    public static function resetAttempts($email)
    {
        self::where('email', $email)->delete();
    }
}
