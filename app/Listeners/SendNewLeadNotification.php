<?php

namespace App\Listeners;

use App\Events\LeadCreated;
use App\Mail\NewLeadMail;
use App\Support\LeadNotificationRecipients;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNewLeadNotification
{
    public function handle(LeadCreated $event): void
    {
        $lead = $event->lead->loadMissing(['property', 'agent']);
        $recipients = LeadNotificationRecipients::for($lead);

        if ($recipients === []) {
            Log::warning('Lead created but no notification recipients configured', ['lead_id' => $lead->id]);

            return;
        }

        try {
            Mail::to($recipients)->send(new NewLeadMail($lead));
            Log::info('Lead notification email sent', ['lead_id' => $lead->id, 'recipients' => $recipients]);
        } catch (\Throwable $e) {
            Log::error('Failed to send lead notification email: '.$e->getMessage(), [
                'lead_id' => $lead->id,
                'recipients' => $recipients,
            ]);
        }
    }
}
