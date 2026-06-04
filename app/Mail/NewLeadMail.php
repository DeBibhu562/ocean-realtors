<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
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
        $propertyLabel = $this->lead->property?->title ?? 'General / SEO enquiry';
        $subject = match ($this->lead->source) {
            'chatbot' => "[Ocean Realtors] New chatbot lead — {$this->lead->name}",
            default => "[Ocean Realtors] New lead — {$this->lead->name} — {$propertyLabel}",
        };

        $envelope = new Envelope(subject: $subject);

        if ($replyTo = $this->lead->email) {
            $envelope = $envelope->replyTo([new Address($replyTo, $this->lead->name)]);
        }

        return $envelope;
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-lead',
            with: ['dashboardUrl' => url('/leads')],
        );
    }
}
