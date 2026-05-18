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
        $lead = $event->lead->loadMissing(['property.user']);

        $agentEmail = $lead->property?->user?->email;

        if (! $agentEmail) {
            Log::warning('Lead created but agent has no email', ['lead_id' => $lead->id]);

            return;
        }

        try {
            Mail::to($agentEmail)->send(new NewLeadMail($lead));
        } catch (\Throwable $e) {
            Log::error('Failed to send lead notification email: '.$e->getMessage(), ['lead_id' => $lead->id]);
        }
    }
}
