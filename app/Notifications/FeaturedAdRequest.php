<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FeaturedAdRequest extends Notification
{
    use Queueable;

    public $jobId;
    public $jobTitle;
    public $amount;
    public $duration;

    /**
     * Create a new notification instance.
     */
    public function __construct($jobId, $jobTitle, $amount, $duration)
    {
        $this->jobId = $jobId;
        $this->jobTitle = $jobTitle;
        $this->amount = $amount;
        $this->duration = $duration;
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
            ->subject('New Featured Ad Request - ' . $this->jobTitle)
            ->greeting('Hello Admin!')
            ->line('A new featured ad request has been submitted.')
            ->line('Job Title: ' . $this->jobTitle)
            ->line('Duration: ' . $this->duration . ' days')
            ->line('Amount: AED ' . $this->amount)
            ->action('Review Featured Ad', url('/admin/jobs/' . $this->jobId))
            ->line('Please review the job posting and share the payment link with the employer.')
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
