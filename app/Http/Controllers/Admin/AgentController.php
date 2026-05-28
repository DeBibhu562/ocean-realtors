<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAgentRequest;
use App\Http\Requests\UpdateAgentRequest;
use App\Models\Agent;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AgentController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->string('search')->trim()->toString();

        // Production Admin/Agents/Index expects a plain array, not a paginator object.
        $agents = Agent::query()
            ->withCount('properties')
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            }))
            ->orderBy('name')
            ->get()
            ->map(fn (Agent $agent) => [
                'id' => $agent->id,
                'name' => $agent->name,
                'slug' => $agent->slug,
                'email' => $agent->email,
                'phone' => $agent->phone,
                'city' => $agent->city,
                'designation' => $agent->designation,
                'is_active' => (bool) $agent->is_active,
                'avatar' => $agent->avatar_url,
                'listed_count' => $agent->listed_count,
            ])
            ->values()
            ->all();

        return Inertia::render('Admin/Agents/Index', [
            'agents' => $agents,
            'filters' => ['search' => $search],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Agents/Form', [
            'agent' => null,
        ]);
    }

    public function store(StoreAgentRequest $request): RedirectResponse
    {
        $data = $this->prepareData($request->validated(), $request);
        Agent::create($data);

        return redirect()->route('admin.agents.index')->with('message', 'Agent created successfully.');
    }

    public function edit(Agent $agent): Response
    {
        return Inertia::render('Admin/Agents/Form', [
            'agent' => array_merge($agent->toPublicArray(), [
                'is_active' => (bool) $agent->is_active,
            ]),
        ]);
    }

    public function update(UpdateAgentRequest $request, Agent $agent): RedirectResponse
    {
        $agent->update($this->prepareData($request->validated(), $request, $agent));

        return redirect()->route('admin.agents.index')->with('message', 'Agent updated successfully.');
    }

    public function toggle(Agent $agent): RedirectResponse
    {
        $agent->update(['is_active' => ! $agent->is_active]);

        return back()->with('message', 'Agent status updated.');
    }

    public function destroy(Agent $agent): RedirectResponse
    {
        if (Agent::query()->count() <= 1) {
            return back()->withErrors(['agent' => 'Cannot delete the last agent.']);
        }

        $fallback = Agent::query()
            ->where('id', '!=', $agent->id)
            ->where('name', 'like', '%Ocean Realtors%')
            ->orderBy('id')
            ->first()
            ?? Agent::query()->where('id', '!=', $agent->id)->orderBy('id')->first();

        if ($fallback) {
            Property::query()->where('agent_id', $agent->id)->update(['agent_id' => $fallback->id]);
        }

        $agent->delete();

        return redirect()->route('admin.agents.index')->with('message', 'Agent deleted.');
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    protected function prepareData(array $validated, Request $request, ?Agent $existing = null): array
    {
        unset($validated['avatar']);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('agents', 'public');
        }

        return $validated;
    }
}
