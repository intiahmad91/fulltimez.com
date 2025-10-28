<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobOfferProposal extends Notification
{
    use Queueable;

    public function __construct(
        public readonly int $applicationId,
        public readonly string $jobTitle,
        public readonly string $companyName,
        public readonly string $employerName,
        public readonly string $employerPosition,
        public readonly string $employerEmail,
        public readonly string $employerPhone,
        public readonly ?string $department = null,
        public readonly ?string $startDate = null,
        public readonly ?string $salary = null,
        public readonly ?string $workType = null,
        public readonly ?string $benefits = null,
        public readonly ?string $confirmationDeadline = null,
        public readonly ?string $additionalNotes = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('Official Job Offer – ' . $this->jobTitle . ' at ' . $this->companyName)
            ->greeting('Dear ' . $notifiable->name . ',');

        // Main offer message
        $mailMessage->line('We are pleased to offer you the position of **' . $this->jobTitle . '** at **' . $this->companyName . '**. After reviewing your qualifications and background, we believe your skills and experience align perfectly with our company\'s goals and vision.');

        // Position details
        $mailMessage->line('**Position Details:**')
                   ->line('• **Position:** ' . $this->jobTitle);

        if ($this->department) {
            $mailMessage->line('• **Department:** ' . $this->department);
        }

        if ($this->startDate) {
            $mailMessage->line('• **Start Date:** ' . $this->startDate);
        }

        if ($this->salary) {
            $mailMessage->line('• **Salary:** ' . $this->salary);
        }

        if ($this->workType) {
            $mailMessage->line('• **Work Type:** ' . $this->workType);
        }

        // Benefits section
        if ($this->benefits) {
            $mailMessage->line('')
                       ->line('**Additional Benefits:**')
                       ->line($this->benefits);
        }

        // Additional notes
        if ($this->additionalNotes) {
            $mailMessage->line('')
                       ->line('**Additional Information:**')
                       ->line($this->additionalNotes);
        }

        // Confirmation section
        $mailMessage->line('')
                   ->line('We are confident that your contribution will play a key role in our company\'s success.');

        if ($this->confirmationDeadline) {
            $mailMessage->line('Please confirm your acceptance of this offer by replying to this email or contacting us at **' . $this->employerPhone . '** no later than **' . $this->confirmationDeadline . '**.');
        } else {
            $mailMessage->line('Please confirm your acceptance of this offer by replying to this email or contacting us at **' . $this->employerPhone . '** at your earliest convenience.');
        }

        // Action buttons
        $mailMessage->action('Accept Job Offer', route('applications.index'))
                   ->action('Contact Employer', 'mailto:' . $this->employerEmail . '?subject=Job Offer Response - ' . $this->jobTitle);

        // Closing
        $mailMessage->line('We look forward to welcoming you to our team.')
                   ->line('')
                   ->line('Warm regards,')
                   ->line('**' . $this->employerName . '**')
                   ->line($this->employerPosition)
                   ->line($this->companyName)
                   ->line('Email: ' . $this->employerEmail)
                   ->line('Phone: ' . $this->employerPhone);

        return $mailMessage;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Job Offer Received',
            'message' => "Congratulations! You have received a job offer for '{$this->jobTitle}' at {$this->companyName}.",
            'action_text' => 'View Offer Details',
            'action_url' => route('applications.index'),
            'application_id' => $this->applicationId,
            'job_title' => $this->jobTitle,
            'company_name' => $this->companyName,
            'employer_name' => $this->employerName,
            'employer_email' => $this->employerEmail,
            'employer_phone' => $this->employerPhone,
            'salary' => $this->salary,
            'start_date' => $this->startDate,
        ];
    }
}
