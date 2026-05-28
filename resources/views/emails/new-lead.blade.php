<x-mail::message>
# New enquiry received

**Source:** {{ ucfirst($lead->source ?? 'web') }}

**Property:** {{ $lead->property?->title ?? 'General enquiry (no specific listing)' }}

**From:** {{ $lead->name }}

@if($lead->email)
**Email:** {{ $lead->email }}
@endif

**Phone:** {{ $lead->phone }}

@if($lead->visit_date)
**Preferred visit:** {{ $lead->visit_date->format('M j, Y') }}
@endif

**Message**

{{ $lead->message ?: '—' }}

<x-mail::button :url="$lead->property ? url('/'.$lead->property->slug) : url('/properties')">
View listing
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
