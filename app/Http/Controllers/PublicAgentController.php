<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Repositories\PropertyRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PublicAgentController extends Controller
{
    public function __construct(
        protected PropertyRepository $propertyRepository
    ) {}

    public function index(Request $request): Response
    {
        $city = $request->string('city')->trim()->toString();

        $agents = Agent::query()
            ->active()
            ->withCount('properties')
            ->when($city, fn ($q) => $q->where('city', $city))
            ->orderByDesc('rating')
            ->orderBy('name')
            ->get()
            ->map(fn (Agent $agent) => $agent->toPublicArray());

        $cities = Agent::query()
            ->active()
            ->whereNotNull('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return Inertia::render('Agents/Index', [
            'agents' => $agents,
            'cities' => $cities,
            'filters' => ['city' => $city],
        ]);
    }

    public function show(Agent $agent): Response
    {
        abort_unless($agent->is_active, 404);

        $agent->loadCount('properties');

        $properties = $agent->properties()
            ->with('agent')
            ->latest()
            ->limit(24)
            ->get()
            ->map(fn ($property) => $this->propertyRepository->toCard($property));

        return Inertia::render('Agents/Show', [
            'agent' => $agent->toPublicArray(),
            'properties' => $properties,
        ]);
    }
}
