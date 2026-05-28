<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAgentRequest;
use App\Http\Requests\UpdateAgentRequest;
use App\Models\Agent;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
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
        $data = $request->validated();
        DB::transaction(function () use ($request, &$data) {
            $data = $this->prepareData($data, $request);
            Agent::create($data);
        });

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
        $data = $request->validated();
        DB::transaction(function () use ($request, $agent, &$data) {
            $data = $this->prepareData($data, $request, $agent);
            $agent->update($data);
        });

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
            try {
                $validated['avatar'] = $request->file('avatar')->store('agents', 'public');
            } catch (\Throwable $e) {
                Log::warning('Agent avatar upload failed.', ['error' => $e->getMessage()]);
                throw ValidationException::withMessages([
                    'avatar' => 'Avatar upload failed. Please try a different image.',
                ]);
            }
        }

        return $validated;
    }
}
