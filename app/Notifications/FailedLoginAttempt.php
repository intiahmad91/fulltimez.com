<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FailedLoginAttempt extends Notification
{
    use Queueable;

    public $attemptCount;
    public $ipAddress;
    public $userAgent;
    public $timestamp;

    /**
     * Create a new notification instance.
     */
    public function __construct($attemptCount, $ipAddress = null, $userAgent = null)
    {
        $this->attemptCount = $attemptCount;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->timestamp = now();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Security Alert: Multiple Failed Login Attempts - FullTimez')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We detected multiple failed login attempts on your account.')
            ->line('**Attempt Details:**')
            ->line('• Number of failed attempts: ' . $this->attemptCount)
            ->line('• Time: ' . $this->timestamp->format('M j, Y \a\t g:i A'))
            ->line('• IP Address: ' . ($this->ipAddress ?? 'Unknown'))
            ->line('• User Agent: ' . ($this->userAgent ?? 'Unknown'))
            ->line('If this was you, please try logging in again. If you forgot your password, you can reset it using the "Forgot Password" link.')
            ->line('If this was not you, please:')
            ->line('1. Change your password immediately')
            ->line('2. Check your account for any unauthorized activity')
            ->line('3. Contact our support team if you notice any suspicious activity')
            ->action('Reset Password', $this->getResetPasswordUrl($notifiable))
            ->line('For your security, we recommend using a strong, unique password.')
            ->line('Thank you for using FullTimez!')
            ->salutation('Best regards, FullTimez Security Team');
    }

    protected function getResetPasswordUrl($notifiable): string
    {
        if (method_exists($notifiable, 'isEmployer') && $notifiable->isEmployer() && \Route::has('employer.forgot-password')) {
            return route('employer.forgot-password');
        }

        if (method_exists($notifiable, 'isSeeker') && $notifiable->isSeeker() && \Route::has('jobseeker.forgot-password')) {
            return route('jobseeker.forgot-password');
        }

        if (method_exists($notifiable, 'isAdmin') && $notifiable->isAdmin() && \Route::has('admin.password.request')) {
            return route('admin.password.request');
        }

        if (\Route::has('password.request')) {
            return route('password.request');
        }

        return url('/forgot-password');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'attempt_count' => $this->attemptCount,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'timestamp' => $this->timestamp,
        ];
    }
}
