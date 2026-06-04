<?php

return [
    'notification_emails' => array_values(array_filter(array_map(
        static fn (string $email): string => strtolower(trim($email)),
        explode(',', (string) env('LEAD_NOTIFICATION_EMAILS', ''))
    ))),
    'notify_assigned_agent' => env('LEAD_NOTIFY_ASSIGNED_AGENT', true),
];
