<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(
        public readonly int $applicationId,
        public readonly string $jobTitle,
        public readonly string $companyName,
        public readonly string $oldStatus,
        public readonly string $newStatus,
        public readonly ?string $employerNotes = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('Application Status Updated - ' . $this->jobTitle)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your job application status has been updated by the employer.');

        // Add job details
        $mailMessage->line('**Job Title:** ' . $this->jobTitle)
                   ->line('**Company:** ' . $this->companyName)
                   ->line('**Previous Status:** ' . ucfirst($this->oldStatus))
                   ->line('**New Status:** ' . ucfirst($this->newStatus))
                   ->line('**Updated On:** ' . now()->format('F j, Y \a\t g:i A'));

        // Add status-specific message
        $statusMessage = $this->getStatusMessage($this->newStatus);
        if ($statusMessage) {
            $mailMessage->line($statusMessage);
        }

        // Add employer notes if provided
        if ($this->employerNotes) {
            $mailMessage->line('**Employer Notes:**')
                       ->line($this->employerNotes);
        }

        // Add action button
        $mailMessage->action('View Application Details', route('applications.index'))
                   ->line('You can view all your applications and their current status from your dashboard.')
                   ->line('Thank you for using FullTimez!');

        return $mailMessage;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Application status updated',
            'message' => "Your application for '{$this->jobTitle}' at {$this->companyName} has been updated to: " . ucfirst($this->newStatus),
            'action_text' => 'View applications',
            'action_url' => route('applications.index'),
            'application_id' => $this->applicationId,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ];
    }

    private function getStatusMessage(string $status): ?string
    {
        return match($status) {
            'reviewed' => 'Great news! The employer has reviewed your application and is considering you for the position.',
            'shortlisted' => 'Congratulations! You have been shortlisted for this position. The employer is interested in your profile.',
            'interviewed' => 'Thank you for attending the interview. The employer is now reviewing your interview performance.',
            'offered' => '🎉 Congratulations! You have been offered the job! Please check your dashboard for next steps.',
            'rejected' => 'Unfortunately, your application was not selected for this position. Don\'t worry, keep applying to other opportunities!',
            'withdrawn' => 'Your application has been withdrawn. If this was not intentional, please contact the employer.',
            default => null,
        };
    }
}
