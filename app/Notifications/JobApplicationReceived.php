<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobApplicationReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly int $applicationId,
        public readonly string $jobseekerName,
        public readonly string $jobTitle,
        public readonly ?string $coverLetter = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('New Job Application Received - ' . $this->jobTitle)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have received a new job application for your posted position.')
            ->line('**Job Title:** ' . $this->jobTitle)
            ->line('**Applicant:** ' . $this->jobseekerName)
            ->line('**Application Date:** ' . now()->format('F j, Y \a\t g:i A'));

        if ($this->coverLetter) {
            $mailMessage->line('**Cover Letter:**')
                       ->line($this->coverLetter);
        }

        $mailMessage->action('View Application Details', route('employer.applications'))
                   ->line('You can view all applications and manage them from your employer dashboard.')
                   ->line('Thank you for using FullTimez!');

        return $mailMessage;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New job application received',
            'message' => "{$this->jobseekerName} has applied for your job: '{$this->jobTitle}'",
            'action_text' => 'View applications',
            'action_url' => route('employer.applications'),
            'application_id' => $this->applicationId,
        ];
    }
}
