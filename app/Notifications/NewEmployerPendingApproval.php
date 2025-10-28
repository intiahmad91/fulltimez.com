<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewEmployerPendingApproval extends Notification
{
    use Queueable;

    public function __construct(
        public readonly int $employerUserId,
        public readonly string $companyName,
        public readonly string $contactPerson,
        public readonly string $contactEmail,
        public readonly ?string $industry = null,
        public readonly ?string $companySize = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('New Employer Registration - ' . $this->companyName)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new employer has registered and requires your approval.');

        // Add company details
        $mailMessage->line('**Company Name:** ' . $this->companyName)
                   ->line('**Contact Person:** ' . $this->contactPerson)
                   ->line('**Contact Email:** ' . $this->contactEmail)
                   ->line('**Registration Date:** ' . now()->format('F j, Y \a\t g:i A'));

        if ($this->industry) {
            $mailMessage->line('**Industry:** ' . $this->industry);
        }

        if ($this->companySize) {
            $mailMessage->line('**Company Size:** ' . $this->companySize);
        }

        $mailMessage->line('Please review the company details and trade license before approving.')
                   ->action('Review & Approve Employer', route('admin.users.index'))
                   ->line('You can view all pending employer registrations from the admin dashboard.')
                   ->line('Thank you for maintaining the quality of our platform!');

        return $mailMessage;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New employer pending approval',
            'message' => "{$this->companyName} ({$this->contactPerson}) has registered and requires approval.",
            'action_text' => 'Review & Approve',
            'action_url' => route('admin.users.index'),
            'employer_user_id' => $this->employerUserId,
            'company_name' => $this->companyName,
            'contact_person' => $this->contactPerson,
            'contact_email' => $this->contactEmail,
            'industry' => $this->industry,
            'company_size' => $this->companySize,
        ];
    }
}


