<?php

namespace App\Support;

use App\Models\Agent;
use App\Models\Property;

class AgentResolver
{
    protected static ?Agent $defaultCache = null;

    public static function default(): ?Agent
    {
        if (static::$defaultCache !== null) {
            return static::$defaultCache;
        }

        static::$defaultCache = Agent::query()
            ->active()
            ->withCount('properties')
            ->where('slug', 'ocean-realtors-team')
            ->first()
            ?? Agent::query()
                ->active()
                ->withCount('properties')
                ->where('name', 'like', '%Ocean Realtors%')
                ->orderBy('id')
                ->first()
            ?? Agent::query()->active()->withCount('properties')->orderBy('id')->first();

        return static::$defaultCache;
    }

    public static function defaultId(): ?int
    {
        return static::default()?->id;
    }

    public static function resolveForProperty(Property $property): ?Agent
    {
        $agent = $property->relationLoaded('agent')
            ? $property->agent
            : $property->agent()->first();

        if ($agent && $agent->is_active) {
            if (! isset($agent->properties_count)) {
                $agent->loadCount('properties');
            }

            return $agent;
        }

        return static::default();
    }

    public static function backfillMissingPropertyAgents(): int
    {
        $defaultId = static::defaultId();
        if (! $defaultId) {
            return 0;
        }

        return Property::query()->whereNull('agent_id')->update(['agent_id' => $defaultId]);
    }
}
