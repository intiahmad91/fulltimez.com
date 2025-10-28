<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentVerificationRequest extends Notification
{
    use Queueable;

    public function __construct(
        public readonly int $paymentVerificationId,
        public readonly string $employerName,
        public readonly string $companyName,
        public readonly string $packageType,
        public readonly float $amount,
        public readonly string $currency,
        public readonly ?string $jobTitle = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('Payment Verification Request - ' . $this->companyName)
            ->greeting('Hello Admin!')
            ->line('A new payment verification request has been submitted.');

        // Payment details
        $mailMessage->line('**Payment Details:**')
                   ->line('• **Employer:** ' . $this->employerName . ' (' . $this->companyName . ')')
                   ->line('• **Package:** ' . $this->packageType)
                   ->line('• **Amount:** ' . $this->currency . ' ' . number_format($this->amount, 2))
                   ->line('• **Date:** ' . now()->format('F j, Y \a\t g:i A'));

        if ($this->jobTitle) {
            $mailMessage->line('• **Job:** ' . $this->jobTitle);
        }

        $mailMessage->line('')
                   ->line('**Action Required:**')
                   ->line('Please review the payment screenshot and verify the payment to activate the premium package for this employer.');

        // Action buttons
        $mailMessage->action('Review Payment', route('admin.payments.show', $this->paymentVerificationId))
                   ->action('View All Payments', route('admin.payments.index'))
                   ->line('Thank you for managing FullTimez!');

        return $mailMessage;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Payment verification request',
            'message' => "{$this->employerName} ({$this->companyName}) has submitted a payment verification request for {$this->packageType} package.",
            'action_text' => 'Review Payment',
            'action_url' => route('admin.payments.show', $this->paymentVerificationId),
            'payment_verification_id' => $this->paymentVerificationId,
            'employer_name' => $this->employerName,
            'company_name' => $this->companyName,
            'package_type' => $this->packageType,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'job_title' => $this->jobTitle,
        ];
    }
}
