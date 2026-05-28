<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('is_admin', true)->first() ?? User::first();
        if (! $admin) {
            return;
        }

        $agentIds = Agent::active()->pluck('id')->all();
        $agentIndex = 0;

        $properties = [
            [
                'title' => 'Ultra Luxury 4BHK Apartment in DLF Camellias',
                'description' => 'Experience the pinnacle of luxury living in Gurgaon. This sprawling 4BHK apartment offers breathtaking views of the DLF Golf Course, high-end marble flooring, and automated home systems.',
                'price' => 250000,
                'status' => 'For Rent',
                'type' => 'Apartment',
                'address' => 'DLF Phase 5, Sector 42',
                'city' => 'Gurgaon',
                'category' => 'Residential',
                'listing_type' => 'Rent',
                'bhk' => '4 BHK',
                'bathrooms' => 4,
                'balconies' => 3,
                'built_up_area' => 5500,
                'furnish_type' => 'Fully Furnished',
                'is_featured' => true,
                'amenities' => ['Gym', 'Swimming Pool', 'Power Backup', 'Club House'],
                'highlights' => ['Golf Course Facing', 'Prime Location', 'High Security'],
                'photos' => ['https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80']
            ],
            [
                'title' => 'Independent Floor in Greater Kailash 2',
                'description' => 'A spacious and well-lit independent floor in the heart of South Delhi. Close to markets, parks, and metro station. Ideal for families looking for a quiet yet connected neighborhood.',
                'price' => 125000,
                'status' => 'For Rent',
                'type' => 'Independent Floor',
                'address' => 'Block S, GK 2',
                'city' => 'Delhi',
                'category' => 'Residential',
                'listing_type' => 'Rent',
                'bhk' => '3 BHK',
                'bathrooms' => 3,
                'balconies' => 2,
                'built_up_area' => 2200,
                'furnish_type' => 'Semi Furnished',
                'amenities' => ['Power Backup', 'Parking', 'Security'],
                'highlights' => ['Market Nearby', 'Park Facing', 'Metro Connection'],
                'photos' => ['https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80']
            ],
            [
                'title' => 'Modern Penthouse in Vasant Vihar',
                'description' => 'Stunning penthouse featuring a private terrace garden and state-of-the-art kitchen. Located in one of the most prestigious diplomatic areas of New Delhi.',
                'price' => 450000,
                'status' => 'For Rent',
                'type' => 'Penthouse',
                'address' => 'Palam Marg',
                'city' => 'Delhi',
                'category' => 'Residential',
                'listing_type' => 'Rent',
                'bhk' => '5+ BHK',
                'bathrooms' => 6,
                'balconies' => 4,
                'built_up_area' => 8000,
                'furnish_type' => 'Fully Furnished',
                'amenities' => ['Private Lift', 'Terrace Garden', 'Smart Home'],
                'highlights' => ['Diplomatic Area', 'Private Pool', 'Luxury Finishing'],
                'photos' => ['https://images.unsplash.com/photo-1600607687940-47a04b629571?auto=format&fit=crop&w=1200&q=80']
            ]
        ];

        foreach ($properties as $data) {
            $data['user_id'] = $admin->id;
            $data['agent_id'] = $agentIds[$agentIndex % max(count($agentIds), 1)] ?? null;
            $agentIndex++;
            $data['image'] = $data['photos'][0];
            Property::create($data);
        }
    }
}
