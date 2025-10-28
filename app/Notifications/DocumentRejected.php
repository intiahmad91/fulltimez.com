<?php

namespace App\Notifications;

use App\Models\EmployerDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentRejected extends Notification
{
    use Queueable;

    public function __construct(
        public readonly EmployerDocument $document
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $documentType = $this->document->document_type_name;
        $specificMessage = match($this->document->document_type) {
            'trade_license' => 'Your Trade License document is not correct and has been rejected.',
            'office_landline' => 'Your Office Landline number is not correct and has been rejected.',
            'company_email' => 'Your Company Email address is not correct and has been rejected.',
            default => 'Your document has been rejected.'
        };

        return (new MailMessage)
            ->subject('Document Rejected - ' . $documentType)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We regret to inform you that your document verification has been rejected by our admin team.')
            ->line('**' . $specificMessage . '**')
            ->line('**Document Type:** ' . $documentType)
            ->line('**Submitted On:** ' . $this->document->created_at->format('F j, Y \a\t g:i A'))
            ->line('**Rejected On:** ' . $this->document->reviewed_at->format('F j, Y \a\t g:i A'))
            ->line('**Reason for Rejection:** ' . $this->document->admin_notes)
            ->action('View Document Status', route('employer.documents.show', $this->document))
            ->line('Please review the feedback and resubmit your document with the necessary corrections.')
            ->line('If you have any questions, please contact our support team.')
            ->line('Thank you for using FullTimez!');
    }

    public function toArray(object $notifiable): array
    {
        $specificMessage = match($this->document->document_type) {
            'trade_license' => 'Your Trade License document is not correct and has been rejected.',
            'office_landline' => 'Your Office Landline number is not correct and has been rejected.',
            'company_email' => 'Your Company Email address is not correct and has been rejected.',
            default => 'Your document has been rejected.'
        };

        return [
            'title' => 'Document Rejected - ' . $this->document->document_type_name,
            'message' => $specificMessage . ' Reason: ' . $this->document->admin_notes,
            'action_text' => 'View Document',
            'action_url' => route('employer.documents.show', $this->document),
            'document_id' => $this->document->id,
            'document_type' => $this->document->document_type,
            'document_type_name' => $this->document->document_type_name,
        ];
    }
}
