<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewAppMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $feriApp;
    public $vendor;
    public $transporter;

    /**
     * Create a new message instance.
     */
    public function __construct($feriApp, $vendor, $transporter)
    {
        $this->feriApp = $feriApp;
        $this->vendor = $vendor;
        $this->transporter = $transporter;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Feri Application Submitted'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.newferiappmail',
            with: [
                'feriApp' => $this->feriApp,
                'vendor' => $this->vendor,
                'transporter' => $this->transporter,
            ],
        );
    }


    public function attachments(): array
    {
        return [];
    }
}
