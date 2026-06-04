<x-mail::message>
# New enquiry — {{ config('app.name') }}

A lead was submitted on **{{ config('app.url') }}** and is saved in your dashboard.

<x-mail::button :url="$dashboardUrl">
Open leads dashboard
</x-mail::button>

---

**Name:** {{ $lead->name }}

**Phone:** {{ $lead->phone }}

@if($lead->email)
**Email:** {{ $lead->email }}
@endif

**Source:** {{ ucfirst($lead->source ?? 'web') }}

@if($lead->contact_channel)
**Contact preference:** @switch($lead->contact_channel)
@case('call') Phone call @break
@case('view_phone') View phone number @break
@case('whatsapp') WhatsApp @break
@default {{ $lead->contact_channel }}
@endswitch
@endif

**Property:** {{ $lead->property?->title ?? 'No specific listing (general / landing page)' }}

@if($lead->visit_date)
**Preferred visit:** {{ $lead->visit_date->format('l, M j, Y') }}
@endif

**Message**

{{ $lead->message ?: '—' }}

@if($lead->email)
Reply to this email to reach the enquirer at **{{ $lead->email }}**.
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
