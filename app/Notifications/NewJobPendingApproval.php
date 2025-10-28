<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewJobPendingApproval extends Notification
{
    use Queueable;

    public function __construct(
        public readonly int $jobId,
        public readonly string $jobTitle,
        public readonly string $employerName
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New job pending approval',
            'message' => "{$this->employerName} posted a new job: '{$this->jobTitle}'",
            'action_text' => 'Review jobs',
            'action_url' => route('admin.jobs.index'),
            'job_id' => $this->jobId,
        ];
    }
}
