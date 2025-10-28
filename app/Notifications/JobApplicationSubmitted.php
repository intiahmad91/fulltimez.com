<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobApplicationSubmitted extends Notification
{
    use Queueable;

    public function __construct(
        public readonly int $applicationId,
        public readonly string $jobTitle,
        public readonly string $companyName,
        public readonly string $applicationDate,
        public readonly ?string $coverLetter = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('Job Application Confirmation - ' . $this->jobTitle)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for applying! Your job application has been successfully submitted.');

        // Application details
        $mailMessage->line('**Application Details:**')
                   ->line('• **Job Title:** ' . $this->jobTitle)
                   ->line('• **Company:** ' . $this->companyName)
                   ->line('• **Application Date:** ' . $this->applicationDate)
                   ->line('• **Application ID:** #' . $this->applicationId);

        // Cover letter section
        if ($this->coverLetter) {
            $mailMessage->line('')
                       ->line('**Your Cover Letter:**')
                       ->line($this->coverLetter);
        }

        // Next steps
        $mailMessage->line('')
                   ->line('**What happens next?**')
                   ->line('• The employer will review your application')
                   ->line('• You will be notified when your application status changes')
                   ->line('• Keep an eye on your email for updates')
                   ->line('• You can track your application status in your dashboard');

        // Action buttons
        $mailMessage->action('View My Applications', route('applications.index'))
                   ->action('Browse More Jobs', route('jobs.index'))
                   ->line('Good luck with your application!')
                   ->line('Thank you for using FullTimez!');

        return $mailMessage;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Job application submitted',
            'message' => "Your application for '{$this->jobTitle}' at {$this->companyName} has been submitted successfully.",
            'action_text' => 'View applications',
            'action_url' => route('applications.index'),
            'application_id' => $this->applicationId,
            'job_title' => $this->jobTitle,
            'company_name' => $this->companyName,
            'application_date' => $this->applicationDate,
            'cover_letter' => $this->coverLetter,
        ];
    }
}
