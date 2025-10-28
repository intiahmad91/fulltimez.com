<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AllDocumentsApproved extends Notification
{
    use Queueable;

    public function __construct(
        public readonly array $approvedDocuments
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $documentTypes = collect($this->approvedDocuments)->pluck('document_type_name')->toArray();
        $documentList = implode(', ', $documentTypes);

        return (new MailMessage)
            ->subject('Congratulations! All Documents Approved - You Can Now Post Jobs')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('🎉 **Congratulations!** All your required documents have been approved by our admin team.')
            ->line('**Approved Documents:** ' . $documentList)
            ->line('**You are now fully verified and can:**')
            ->line('✅ Post unlimited job listings')
            ->line('✅ Access all employer features')
            ->line('✅ Manage job applications')
            ->line('✅ Use premium job posting features')
            ->action('Post Your First Job', route('employer.jobs.create'))
            ->line('Welcome to FullTimez! We look forward to helping you find the best talent.')
            ->line('If you have any questions, please contact our support team.')
            ->line('Thank you for using FullTimez!');
    }

    public function toArray(object $notifiable): array
    {
        $documentTypes = collect($this->approvedDocuments)->pluck('document_type_name')->toArray();
        $documentList = implode(', ', $documentTypes);

        return [
            'title' => 'All Documents Approved!',
            'message' => 'Congratulations! All your documents (' . $documentList . ') have been approved. You can now post jobs.',
            'action_text' => 'Post Jobs',
            'action_url' => route('employer.jobs.create'),
            'approved_documents' => $this->approvedDocuments,
        ];
    }
}