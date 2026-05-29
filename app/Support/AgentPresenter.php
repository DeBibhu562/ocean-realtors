<?php

namespace App\Support;

use App\Models\Agent;
use App\Models\Property;

class AgentPresenter
{
    /**
     * @return array<string, mixed>
     */
    public static function forProperty(?Agent $agent, ?Property $property = null): array
    {
        if ($property !== null) {
            $agent = AgentResolver::resolveForProperty($property);
        } elseif (! $agent || ! $agent->is_active) {
            $agent = AgentResolver::default();
        }

        if ($agent) {
            if (! isset($agent->properties_count)) {
                $agent->loadCount('properties');
            }

            $data = $agent->toPublicArray();
            $data['avatar'] = $agent->avatar_url ?? $agent->defaultAvatarUrl();
            $data['listed_count'] = (int) ($agent->properties_count ?? $agent->listed_count);

            return $data;
        }

        return self::fallback();
    }

    /**
     * @return array<string, mixed>
     */
    protected static function fallback(): array
    {
        $default = AgentResolver::default();

        if ($default) {
            return self::forProperty($default);
        }

        return [
            'name' => 'Ocean Realtors Team',
            'email' => 'no-reply@oceanrealtors.co.in',
            'phone' => '+919990633797',
            'whatsapp_phone' => '+919990633797',
            'avatar' => null,
            'designation' => 'Senior Property Consultant',
            'bio' => null,
            'city' => 'India',
            'rating' => 4.8,
            'reviews_count' => 127,
            'experience_years' => 10,
            'languages' => ['English', 'Hindi'],
            'listed_count' => 0,
            'slug' => null,
        ];
    }
}
