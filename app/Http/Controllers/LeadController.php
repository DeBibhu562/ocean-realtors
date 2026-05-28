<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::query()
            ->with(['property:id,title,slug', 'agent:id,name'])
            ->latest()
            ->limit(200)
            ->get()
            ->map(fn (Lead $lead) => [
                'id' => $lead->id,
                'name' => $lead->name,
                'email' => $lead->email ?: '—',
                'phone' => $lead->phone,
                'property' => $lead->property?->title ?? 'General enquiry',
                'property_slug' => $lead->property?->slug,
                'agent' => $lead->agent?->name,
                'source' => $lead->source,
                'status' => ucfirst($lead->status),
                'message' => $lead->message,
                'date' => $lead->created_at?->format('M j, Y g:i A'),
            ]);

        return Inertia::render('Leads', [
            'leads' => $leads,
        ]);
    }
}
