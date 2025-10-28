<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewJobMatchingProfile extends Notification
{
    use Queueable;

    public function __construct(
        public readonly int $jobId,
        public readonly string $jobTitle,
        public readonly string $companyName,
        public readonly string $location,
        public readonly ?string $salaryRange = null,
        public readonly ?string $employmentType = null,
        public readonly ?string $experienceLevel = null,
        public readonly array $matchingReasons = []
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('New Job Match Found - ' . $this->jobTitle)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We found a new job posting that matches your profile and interests!')
            ->line('')
            ->line('**Job Title:** ' . $this->jobTitle)
            ->line('**Company:** ' . $this->companyName)
            ->line('**Location:** ' . $this->location);

        if ($this->salaryRange) {
            $mailMessage->line('**Salary:** ' . $this->salaryRange);
        }

        if ($this->employmentType) {
            $mailMessage->line('**Employment Type:** ' . ucfirst(str_replace('_', ' ', $this->employmentType)));
        }

        if ($this->experienceLevel) {
            $mailMessage->line('**Experience Level:** ' . ucfirst($this->experienceLevel));
        }

        if (!empty($this->matchingReasons)) {
            $mailMessage->line('')
                       ->line('**Why this job matches your profile:**');
            foreach ($this->matchingReasons as $reason) {
                $mailMessage->line('• ' . $reason);
            }
        }

        $mailMessage->line('')
                   ->action('View Job Details', route('jobs.show', $this->jobId))
                   ->line('Don\'t miss this opportunity! Apply now to be considered for this position.')
                   ->line('Thank you for using FullTimez!');

        return $mailMessage;
    }

    public function toArray(object $notifiable): array
    {
        $message = "New job match found: {$this->jobTitle} at {$this->companyName}";
        if (!empty($this->matchingReasons)) {
            $message .= " (Matches: " . implode(', ', $this->matchingReasons) . ")";
        }

        return [
            'title' => 'New job match found',
            'message' => $message,
            'action_text' => 'View Job',
            'action_url' => route('jobs.show', $this->jobId),
            'job_id' => $this->jobId,
            'job_title' => $this->jobTitle,
            'company_name' => $this->companyName,
            'location' => $this->location,
            'salary_range' => $this->salaryRange,
            'employment_type' => $this->employmentType,
            'experience_level' => $this->experienceLevel,
            'matching_reasons' => $this->matchingReasons,
        ];
    }
}
