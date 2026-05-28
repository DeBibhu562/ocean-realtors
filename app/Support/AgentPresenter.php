<?php

namespace App\Support;

use App\Models\Agent;

class AgentPresenter
{
    /**
     * @return array<string, mixed>
     */
    public static function forProperty(?Agent $agent): array
    {
        if (! $agent || ! $agent->is_active) {
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

        $data = $agent->toPublicArray();
        $data['avatar'] = $agent->avatar_url ?? $agent->defaultAvatarUrl();
        $data['listed_count'] = $agent->properties_count ?? $agent->listed_count;

        return $data;
    }
}
