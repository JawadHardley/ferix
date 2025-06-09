<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChatNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $chat;
    public $feriApp;
    public $sender;
    public $recipient;

    /**
     * Create a new message instance.
     */
    public function __construct($chat, $feriApp, $sender, $recipient)
    {
        $this->chat = $chat;
        $this->feriApp = $feriApp;
        $this->sender = $sender;
        $this->recipient = $recipient;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(subject: 'New Query Received');
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.chatnotification',
            with: [
                'chat' => $this->chat,
                'feriApp' => $this->feriApp,
                'sender' => $this->sender,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}