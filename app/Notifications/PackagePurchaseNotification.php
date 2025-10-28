<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PackagePurchaseNotification extends Notification
{
    use Queueable;

    public $employer;
    public $package;
    public $amount;
    public $paymentVerification;

    /**
     * Create a new notification instance.
     */
    public function __construct($employer, $package, $amount, $paymentVerification)
    {
        $this->employer = $employer;
        $this->package = $package;
        $this->amount = $amount;
        $this->paymentVerification = $paymentVerification;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Package Purchase - ' . $this->package->name)
            ->greeting('Hello Admin!')
            ->line('A new package purchase has been made.')
            ->line('Employer: ' . $this->employer->name)
            ->line('Company: ' . ($this->employer->employerProfile->company_name ?? 'N/A'))
            ->line('Package: ' . $this->package->name)
            ->line('Amount: AED ' . $this->amount)
            ->line('Payment Method: ' . $this->paymentVerification->payment_method)
            ->action('Review Payment', url('/admin/payments/' . $this->paymentVerification->id))
            ->line('Please review the payment verification and contact the employer if needed.')
            ->line('Thank you for managing FullTimez!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
