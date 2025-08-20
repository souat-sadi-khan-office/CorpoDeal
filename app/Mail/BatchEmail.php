<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Container\Attributes\Log;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BatchEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $subjectText;
    public $messageText;
    public $attachments;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $subject, $message, $attachments = [])
    {
        $this->user = $user;
        $this->subjectText = $subject;
        $this->messageText = $this->sanitizeMessage(html_entity_decode($message));
        $this->attachments = is_array($attachments) ? $attachments : []; // Ensure it's an array
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectText,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.batch_email',  // markdown Blade view
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        $attachments = [];
        foreach ($this->attachments as $file) {
            Log::info('Attachment file:', ['file' => $file]);
            if ($file && file_exists(storage_path('app/public/' . $file))) {
                $attachments[] = Attachment::fromPath(storage_path('app/public/' . $file));
            }
        }
        return $attachments;
    }

    /**
     * Sanitize and clean up the message text.
     */
    private function sanitizeMessage($message)
    {
        // Attempt to close any unclosed tags if necessary
        return preg_replace('/(<br>)+/', '<br>', strip_tags($message, '<p><br><h1><h2><table><tr><td>'));
    }
}
