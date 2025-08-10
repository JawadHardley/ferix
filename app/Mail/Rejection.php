<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Rejection extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $feriApp;
    public $vendor;
    public $transporter;
    public $reason;

    public function __construct($feriApp, $vendor, $transporter, $reason)
    {
        $this->feriApp = $feriApp;
        $this->vendor = $vendor;
        $this->transporter = $transporter;
        $this->reason = $reason;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Draft Certificate Rejected – Action Required'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.draftrejection',
            with: [
                'feriApp' => $this->feriApp,
                'vendor' => $this->vendor,
                'transporter' => $this->transporter,
                'reason' => $this->reason,
            ],
        );
    }


    public function attachments(): array
    {
        return [];
    }
}
