<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewJobApplication extends Notification
{
    use Queueable;

    public function __construct(
        public readonly int $applicationId,
        public readonly string $jobseekerName,
        public readonly string $jobTitle,
        public readonly string $companyName
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New job application received',
            'message' => "{$this->jobseekerName} has applied for the position '{$this->jobTitle}' at {$this->companyName}.",
            'action_text' => 'View applications',
            'action_url' => route('admin.applications.index'),
            'application_id' => $this->applicationId,
        ];
    }
}
