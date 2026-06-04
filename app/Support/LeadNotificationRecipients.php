<?php

namespace App\Support;

use App\Models\Lead;

class LeadNotificationRecipients
{
    /** @return list<string> */
    public static function for(Lead $lead): array
    {
        $emails = config('leads.notification_emails', []);

        if (config('leads.notify_assigned_agent', true)) {
            $lead->loadMissing(['property.agent', 'property.user', 'agent']);
            $agentEmail = self::normalize($lead->property?->agent?->email ?? $lead->agent?->email ?? $lead->property?->user?->email);
            if ($agentEmail !== null && ! in_array($agentEmail, $emails, true)) {
                $emails[] = $agentEmail;
            }
        }

        if ($emails === []) {
            $fallback = self::normalize((string) config('mail.from.address'));
            if ($fallback !== null) {
                $emails[] = $fallback;
            }
        }

        return array_values(array_unique($emails));
    }

    private static function normalize(?string $email): ?string
    {
        if ($email === null || $email === '') {
            return null;
        }
        $email = strtolower(trim($email));

        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }
}
