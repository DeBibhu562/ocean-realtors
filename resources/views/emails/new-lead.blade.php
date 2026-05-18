<x-mail::message>
# New enquiry received

**Property:** {{ $lead->property->title ?? 'N/A' }}

**From:** {{ $lead->name }}

**Email:** {{ $lead->email }}

**Phone:** {{ $lead->phone }}

@if($lead->visit_date)
**Preferred visit:** {{ $lead->visit_date->format('M j, Y') }}
@endif

**Message**

{{ $lead->message ?: '—' }}

<x-mail::button :url="url('/property/'.$lead->property_id)">
View listing
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
