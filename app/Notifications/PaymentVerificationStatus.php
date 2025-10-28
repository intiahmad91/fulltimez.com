<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentVerificationStatus extends Notification
{
    use Queueable;

    public function __construct(
        public readonly int $paymentVerificationId,
        public readonly string $status,
        public readonly string $packageType,
        public readonly ?string $adminNotes = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = match($this->status) {
            'verified' => 'Payment Verified - ' . $this->packageType . ' Package Activated',
            'rejected' => 'Payment Verification Rejected',
            default => 'Payment Verification Update',
        };

        $mailMessage = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . '!');

        // Status-specific message
        if ($this->status === 'verified') {
            $mailMessage->line('🎉 Great news! Your payment has been verified and your premium package has been activated.')
                       ->line('**Package:** ' . $this->packageType)
                       ->line('**Status:** Payment Verified')
                       ->line('')
                       ->line('Your job posting now has premium features and will be prominently displayed to jobseekers.')
                       ->action('View My Jobs', route('employer.jobs.index'));
        } elseif ($this->status === 'rejected') {
            $mailMessage->line('Unfortunately, your payment verification has been rejected.')
                       ->line('**Package:** ' . $this->packageType)
                       ->line('**Status:** Payment Rejected')
                       ->line('')
                       ->line('Please review the admin notes below and resubmit your payment verification if needed.')
                       ->action('View My Jobs', route('employer.jobs.index'));
        } else {
            $mailMessage->line('Your payment verification status has been updated.')
                       ->line('**Package:** ' . $this->packageType)
                       ->line('**Status:** ' . ucfirst($this->status));
        }

        // Admin notes
        if ($this->adminNotes) {
            $mailMessage->line('')
                       ->line('**Admin Notes:**')
                       ->line($this->adminNotes);
        }

        $mailMessage->line('')
                   ->line('Thank you for using FullTimez!');

        return $mailMessage;
    }

    public function toArray(object $notifiable): array
    {
        $message = match($this->status) {
            'verified' => "Your payment for {$this->packageType} package has been verified and activated!",
            'rejected' => "Your payment verification for {$this->packageType} package has been rejected.",
            default => "Your payment verification status for {$this->packageType} package has been updated.",
        };

        return [
            'title' => 'Payment verification update',
            'message' => $message,
            'action_text' => 'View My Jobs',
            'action_url' => route('employer.jobs.index'),
            'payment_verification_id' => $this->paymentVerificationId,
            'status' => $this->status,
            'package_type' => $this->packageType,
            'admin_notes' => $this->adminNotes,
        ];
    }
}
