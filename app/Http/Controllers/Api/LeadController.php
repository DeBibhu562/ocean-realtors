<?php

namespace App\Http\Controllers\Api;

use App\Events\LeadCreated;
use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Repositories\LeadRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __construct(
        protected LeadRepository $leadRepository
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'property_id' => 'required|integer|exists:properties,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:64',
            'email' => 'required|email|max:255',
            'message' => 'nullable|string|max:10000',
            'visit_date' => 'nullable|date|after_or_equal:today',
        ]);

        $property = Property::query()->with('user')->findOrFail($validated['property_id']);

        $source = str_contains(strtolower((string) $request->userAgent()), 'mobile') ? 'mobile' : 'web';

        $lead = $this->leadRepository->create([
            'property_id' => $property->id,
            'agent_id' => $property->user_id,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'message' => $validated['message'] ?? null,
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
}
