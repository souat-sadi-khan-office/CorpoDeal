<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class RequestApprovalNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $balanceREQ;
    public $description;
    public $installments;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $balanceREQ,$description, $installments)
    {
        $this->user = $user;
        $this->balanceREQ = $balanceREQ;
        $this->description=$description;
        $this->installments = $installments;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Request Approved and Installments Scheduled',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.request_approval',
        );
    }
}
