<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewJobseekerRegistered extends Notification
{
    use Queueable;

    public function __construct(
        public readonly int $jobseekerUserId,
        public readonly string $jobseekerName
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Jobseeker Registered')
            ->greeting('Hello Admin,')
            ->line('A new jobseeker has registered on FullTimez:')
            ->line('Name: ' . $this->jobseekerName)
            ->action('View Jobseeker', route('admin.users.show', $this->jobseekerUserId))
            ->line('You can review their profile and activity from the admin panel.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New jobseeker registered',
            'message' => $this->jobseekerName . ' has registered as a jobseeker.',
            'action_text' => 'View Jobseeker',
            'action_url' => route('admin.users.show', $this->jobseekerUserId),
            'jobseeker_user_id' => $this->jobseekerUserId,
            'jobseeker_name' => $this->jobseekerName,
        ];
    }
}
