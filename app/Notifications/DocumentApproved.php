<?php

namespace App\Notifications;

use App\Models\EmployerDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentApproved extends Notification
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
        return (new MailMessage)
            ->subject('Document Approved - ' . $this->document->document_type_name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Great news! Your ' . strtolower($this->document->document_type_name) . ' document has been approved by our admin team.')
            ->line('**Document Type:** ' . $this->document->document_type_name)
            ->line('**Submitted On:** ' . $this->document->created_at->format('F j, Y \a\t g:i A'))
            ->line('**Approved On:** ' . $this->document->reviewed_at->format('F j, Y \a\t g:i A'))
            ->when($this->document->admin_notes, function($mailMessage) {
                return $mailMessage->line('**Admin Notes:** ' . $this->document->admin_notes);
            })
            ->action('View Document Status', route('employer.documents.show', $this->document))
            ->line('Thank you for using FullTimez!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Document Approved',
            'message' => 'Your ' . strtolower($this->document->document_type_name) . ' document has been approved.',
            'action_text' => 'View Document',
            'action_url' => route('employer.documents.show', $this->document),
            'document_id' => $this->document->id,
            'document_type' => $this->document->document_type,
            'document_type_name' => $this->document->document_type_name,
        ];
    }
}
