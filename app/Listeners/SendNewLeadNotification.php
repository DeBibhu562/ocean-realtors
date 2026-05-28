<?php

namespace App\Listeners;

use App\Events\LeadCreated;
use App\Mail\NewLeadMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNewLeadNotification
{
    public function handle(LeadCreated $event): void
    {
        $lead = $event->lead->loadMissing(['property.agent', 'property.user', 'agent']);

        $agentEmail = $lead->property?->agent?->email
            ?? $lead->agent?->email
            ?? $lead->property?->user?->email;

        $recipient = $agentEmail ?: config('mail.from.address');

        if (! $recipient) {
            Log::warning('Lead created but no notification email available', ['lead_id' => $lead->id]);

            return;
        }

        try {
            Mail::to($recipient)->send(new NewLeadMail($lead));
        } catch (\Throwable $e) {
            Log::error('Failed to send lead notification email: '.$e->getMessage(), ['lead_id' => $lead->id]);
        }
    }
}
