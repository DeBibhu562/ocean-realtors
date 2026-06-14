<?php

namespace App\Http\Controllers\Api;

use App\Events\LeadCreated;
use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Property;
use App\Repositories\LeadRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LeadController extends Controller
{
    public function __construct(
        protected LeadRepository $leadRepository
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'property_id' => 'nullable|integer|exists:properties,id',
            'agent_id' => 'nullable|integer|exists:agents,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:64',
            'email' => 'nullable|email|max:255',
            'message' => 'nullable|string|max:10000',
            'visit_date' => 'nullable|date|after_or_equal:today',
            'intent' => ['nullable', 'string', Rule::in(['rent', 'buy', 'commercial', 'explore'])],
            'city' => 'nullable|string|max:120',
            'source' => ['nullable', 'string', Rule::in(['web', 'mobile', 'chatbot'])],
            'contact_channel' => ['nullable', 'string', Rule::in(['call', 'whatsapp', 'view_phone'])],
            'is_real_estate_agent' => ['nullable', 'boolean'],
        ]);

        $property = null;
        if (! empty($validated['property_id'])) {
            $property = Property::query()->with(['agent'])->findOrFail($validated['property_id']);
        }

        $agent = null;
        if (! $property && ! empty($validated['agent_id'])) {
            $agent = Agent::query()->active()->findOrFail($validated['agent_id']);
        }

        $source = $validated['source'] ?? (str_contains(strtolower((string) $request->userAgent()), 'mobile') ? 'mobile' : 'web');

        if ($request->boolean('from_chatbot') || $source === 'chatbot') {
            $source = 'chatbot';
        }

        $agentId = $property?->agent_id ?? $agent?->id ?? $this->defaultAgentId();

        $message = $validated['message'] ?? $this->buildMessage(
            $validated['intent'] ?? null,
            $validated['city'] ?? null,
            $property,
            $agent
        );

        $contactChannel = $validated['contact_channel'] ?? null;

        if ($contactChannel) {
            $channelLabel = match ($contactChannel) {
                'call' => 'Phone call',
                'view_phone' => 'View phone number',
                default => 'WhatsApp',
            };
            $message = trim(($message ? $message."\n\n" : '')."Contact preference: {$channelLabel}");
        }

        if (array_key_exists('is_real_estate_agent', $validated) && $validated['is_real_estate_agent'] !== null) {
            $agentLabel = $validated['is_real_estate_agent'] ? 'Yes' : 'No';
            $message = trim(($message ? $message."\n\n" : '')."Real estate agent: {$agentLabel}");
        }

        $lead = $this->leadRepository->create([
            'property_id' => $property?->id,
            'agent_id' => $agentId,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? '',
            'is_real_estate_agent' => $validated['is_real_estate_agent'] ?? null,
            'message' => $message,
            'visit_date' => $validated['visit_date'] ?? null,
            'source' => $source,
            'contact_channel' => $contactChannel,
            'status' => 'new',
            'ip_address' => $request->ip(),
        ]);

        event(new LeadCreated($lead));

        $response = [
            'success' => true,
            'message' => 'Thank you! Your enquiry has been received.',
        ];

        if ($agent && ! $property) {
            $response['contact'] = [
                'phone' => $agent->phone,
                'whatsapp_phone' => $agent->whatsapp_phone ?? $agent->phone,
            ];
        }

        return response()->json($response);
    }

    protected function defaultAgentId(): ?int
    {
        return Agent::query()
            ->active()
            ->where('slug', 'ocean-realtors-team')
            ->value('id')
            ?? Agent::query()->active()->orderBy('id')->value('id');
    }

    protected function buildMessage(?string $intent, ?string $city, ?Property $property, ?Agent $agent = null): ?string
    {
        $parts = [];

        if ($intent) {
            $labels = [
                'rent' => 'Looking to rent',
                'buy' => 'Looking to buy',
                'commercial' => 'Commercial property enquiry',
                'explore' => 'Exploring options',
            ];
            $parts[] = $labels[$intent] ?? $intent;
        }

        if ($city) {
            $parts[] = 'Preferred city/area: '.$city;
        }

        if ($property) {
            $parts[] = 'Interested in: '.$property->title;
        }

        if (! $property && $agent) {
            $parts[] = 'Agent profile enquiry: '.$agent->name;
        }

        return $parts === [] ? null : implode("\n", $parts);
    }
}
