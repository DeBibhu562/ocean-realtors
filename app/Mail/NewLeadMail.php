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
        $this->lead->loadMissing(['property', 'agent']);
    }

    public function envelope(): Envelope
    {
        $subject = $this->lead->source === 'chatbot'
            ? 'New chatbot lead: '.$this->lead->name
            : 'New property enquiry: '.($this->lead->property?->title ?? 'General');

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-lead',
        );
    }
}
