<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobPublished extends Notification
{
    use Queueable;

    public function __construct(
        public readonly int $jobId,
        public readonly string $jobTitle,
        public readonly ?string $expiresAtText = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Your job has been published - ' . $this->jobTitle)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Great news! Your job posting has been approved and published.')
            ->line('Job: ' . $this->jobTitle);

        if ($this->expiresAtText) {
            $mail->line('This job will be visible until: ' . $this->expiresAtText);
        }

        return $mail->action('View Job', route('employer.jobs.index'))
            ->line('You can now start receiving applications.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Job published',
            'message' => 'Your job "' . $this->jobTitle . '" has been published.',
            'action_text' => 'View Job',
            'action_url' => route('employer.jobs.index'),
            'job_id' => $this->jobId,
        ];
    }
}
