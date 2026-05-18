<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewLeadMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Lead $lead)
    {
        $this->lead->loadMissing(['property']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New property enquiry: '.$this->lead->property?->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-lead',
        );
    }
}
