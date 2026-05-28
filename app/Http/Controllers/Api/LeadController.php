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
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:64',
            'email' => 'nullable|email|max:255',
            'message' => 'nullable|string|max:10000',
            'visit_date' => 'nullable|date|after_or_equal:today',
            'intent' => ['nullable', 'string', Rule::in(['rent', 'buy', 'commercial', 'explore'])],
            'city' => 'nullable|string|max:120',
            'source' => ['nullable', 'string', Rule::in(['web', 'mobile', 'chatbot'])],
            'contact_channel' => ['nullable', 'string', Rule::in(['call', 'whatsapp'])],
        ]);

        $property = null;
        if (! empty($validated['property_id'])) {
            $property = Property::query()->with(['agent'])->findOrFail($validated['property_id']);
        }

        $source = $validated['source'] ?? (str_contains(strtolower((string) $request->userAgent()), 'mobile') ? 'mobile' : 'web');

        if ($request->boolean('from_chatbot') || $source === 'chatbot') {
            $source = 'chatbot';
        }

        $agentId = $property?->agent_id ?? $this->defaultAgentId();

        $message = $validated['message'] ?? $this->buildMessage(
            $validated['intent'] ?? null,
            $validated['city'] ?? null,
            $property
        );

        if (! empty($validated['contact_channel'])) {
            $channelLabel = $validated['contact_channel'] === 'call' ? 'Phone call' : 'WhatsApp';
            $message = trim(($message ? $message."\n\n" : '')."Contact preference: {$channelLabel}");
        }

        $lead = $this->leadRepository->create([
            'property_id' => $property?->id,
            'agent_id' => $agentId,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? '',
            'message' => $message,
            'visit_date' => $validated['visit_date'] ?? null,
            'source' => $source,
            'status' => 'new',
            'ip_address' => $request->ip(),
        ]);

        event(new LeadCreated($lead));

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your enquiry has been received.',
        ]);
    }

    protected function defaultAgentId(): ?int
    {
        return Agent::query()
            ->active()
            ->where('slug', 'ocean-realtors-team')
            ->value('id')
            ?? Agent::query()->active()->orderBy('id')->value('id');
    }

    protected function buildMessage(?string $intent, ?string $city, ?Property $property): ?string
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

        return $parts === [] ? null : implode("\n", $parts);
    }
}
