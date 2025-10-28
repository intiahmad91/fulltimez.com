<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentsRejected extends Notification
{
    use Queueable;

    public function __construct(
        public readonly array $rejectedDocuments,
        public readonly string $reason
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $documentTypes = collect($this->rejectedDocuments)->pluck('document_type_name')->toArray();
        $documentList = implode(', ', $documentTypes);

        return (new MailMessage)
            ->subject('Multiple Documents Rejected - ' . $documentList)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We regret to inform you that multiple documents from your verification submission have been rejected by our admin team.')
            ->line('**Rejected Documents:** ' . $documentList)
            ->line('**Reason for Rejection:** ' . $this->reason)
            ->line('**Please review and resubmit the following documents:**')
            ->line('• ' . implode('', array_map(fn($doc) => '• ' . $doc . "\n", $documentTypes)))
            ->action('View Document Status', route('employer.documents.index'))
            ->line('Please review the feedback and resubmit your documents with the necessary corrections.')
            ->line('If you have any questions, please contact our support team.')
            ->line('Thank you for using FullTimez!');
    }

    public function toArray(object $notifiable): array
    {
        $documentTypes = collect($this->rejectedDocuments)->pluck('document_type_name')->toArray();
        $documentList = implode(', ', $documentTypes);

        return [
            'title' => 'Multiple Documents Rejected',
            'message' => 'Your documents (' . $documentList . ') have been rejected. Reason: ' . $this->reason,
            'action_text' => 'View Documents',
            'action_url' => route('employer.documents.index'),
            'rejected_documents' => $this->rejectedDocuments,
            'reason' => $this->reason,
        ];
    }
}