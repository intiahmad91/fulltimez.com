<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserActionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly string $action,
        public readonly string $actionDescription,
        public readonly ?string $details = null,
        public readonly ?string $actionUrl = null,
        public readonly ?string $actionText = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('Action Confirmation - ' . $this->actionDescription)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('This is to confirm that you have successfully performed the following action:');

        // Action details
        $mailMessage->line('**Action:** ' . $this->actionDescription)
                   ->line('**Date & Time:** ' . now()->format('F j, Y \a\t g:i A'));

        if ($this->details) {
            $mailMessage->line('**Details:** ' . $this->details);
        }

        // Action button if provided
        if ($this->actionUrl && $this->actionText) {
            $mailMessage->action($this->actionText, $this->actionUrl);
        }

        // Action-specific messages
        $actionMessage = $this->getActionMessage($this->action);
        if ($actionMessage) {
            $mailMessage->line('')
                       ->line($actionMessage);
        }

        $mailMessage->line('Thank you for using FullTimez!');

        return $mailMessage;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Action completed',
            'message' => "You have successfully {$this->actionDescription}.",
            'action_text' => $this->actionText ?? 'View Details',
            'action_url' => $this->actionUrl ?? route('dashboard'),
            'action' => $this->action,
            'action_description' => $this->actionDescription,
            'details' => $this->details,
        ];
    }

    private function getActionMessage(string $action): ?string
    {
        return match($action) {
            'profile_updated' => 'Your profile has been updated successfully. These changes will be visible to employers when they view your profile.',
            'password_changed' => 'Your password has been changed successfully. If you did not make this change, please contact support immediately.',
            'cv_created' => 'Your CV has been created successfully. You can now use it when applying for jobs.',
            'cv_updated' => 'Your CV has been updated successfully. The changes will be reflected in your job applications.',
            'resume_downloaded' => 'Your resume has been downloaded successfully. Keep it safe and use it for your job applications.',
            'application_submitted' => 'Your job application has been submitted successfully. The employer will review your application and get back to you.',
            'profile_viewed' => 'Your profile was viewed by an employer. This is a good sign that employers are interested in your profile.',
            'account_created' => 'Welcome to FullTimez! Your account has been created successfully. Complete your profile to get started.',
            'email_verified' => 'Your email has been verified successfully. You now have full access to all features.',
            'login_successful' => 'You have logged in successfully. Welcome back to FullTimez!',
            'logout_successful' => 'You have logged out successfully. Thank you for using FullTimez!',
            default => null,
        };
    }
}
