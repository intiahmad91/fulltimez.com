<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountApproved extends Notification
{
    use Queueable;

    public function __construct(
        public readonly string $userType // 'employer' or 'seeker'
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $userTypeText = $this->userType === 'employer' ? 'Employer' : 'Job Seeker';
        
        return (new MailMessage)
            ->subject('Account Approved - Welcome to FullTimez!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! Your ' . $userTypeText . ' account has been approved by our admin team.')
            ->line('You can now log in to your account and access all features of FullTimez.')
            ->action('Login Now', route($this->userType === 'employer' ? 'employer.login' : 'jobseeker.login'))
            ->line('Thank you for joining FullTimez! We look forward to helping you achieve your goals.');
    }

    public function toArray(object $notifiable): array
    {
        $userTypeText = $this->userType === 'employer' ? 'Employer' : 'Job Seeker';
        
        return [
            'title' => 'Account Approved',
            'message' => 'Your ' . $userTypeText . ' account has been approved. You can now log in.',
            'action_text' => 'Login Now',
            'action_url' => route($this->userType === 'employer' ? 'employer.login' : 'jobseeker.login'),
            'user_type' => $this->userType,
        ];
    }
}

