<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class CertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $feriapp;
    public $recipient;
    public $certificatePath;
    public $sender;
    public $pdfInvoiceData;

    /**
     * Create a new message instance.
     */
    public function __construct($invoice, $feriapp, $recipient, $certificatePath, $sender, $pdfInvoiceData)
    {
        $this->invoice = $invoice;
        $this->feriapp = $feriapp;
        $this->recipient = $recipient;
        $this->certificatePath = $certificatePath;
        $this->sender = $sender;
        $this->pdfInvoiceData = $pdfInvoiceData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Final Feri Certificate & Invoice');
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.certificatedocs',
            with: [
                'invoice' => $this->invoice,
                'feriApp' => $this->feriapp,
                'recipient' => $this->recipient,
                'certificatePath' => $this->certificatePath,
                'sender' => $this->sender,
                'pdfInvoiceData' => $this->pdfInvoiceData,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];
        if ($this->certificatePath && file_exists(storage_path('app/private/' . $this->certificatePath))) {
            $attachments[] = Attachment::fromPath(storage_path('app/private/' . $this->certificatePath))
                ->as('Feri_Certificate.pdf')
                ->withMime('application/pdf');
        }

        // Attach the invoice from raw data
        if ($this->pdfInvoiceData) {
            $attachments[] = Attachment::fromData(fn() => $this->pdfInvoiceData, 'Invoice.pdf')->withMime('application/pdf');
        }

        return $attachments;
    }
}