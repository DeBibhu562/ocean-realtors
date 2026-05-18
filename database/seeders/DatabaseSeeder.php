<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Property;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@ocean.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );

        // 2. Create Test User
        $testUser = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );

        // 3. Create Professional Sample Properties
        Property::updateOrCreate(
            ['title' => 'Home in Merrick Way'],
            [
                'description' => 'A beautiful home in the heart of the city with modern amenities and high-tech security.',
                'price' => 289.00,
                'status' => 'For Rent',
                'category' => 'Residential',
                'listing_type' => 'Rent',
                'type' => 'Independent House',
                'bhk' => '3 BHK',
                'address' => '3 Middle Winchendon Rd',
                'city' => 'Gurgaon',
                'society_name' => 'Merrick Greens',
                'image' => '/img/property/property-1.jpg',
                'bedrooms' => 3,
                'bathrooms' => 2,
                'balconies' => 2,
                'garage' => 1,
                'size' => 2283,
                'built_up_area' => 2500,
                'carpet_area' => 2100,
                'furnish_type' => 'Fully Furnished',
                'covered_parking' => '1',
                'open_parking' => '1',
                'tenant_type' => 'Family',
                'pet_friendly' => true,
                'available_from' => now()->addDays(10),
                'maintenance_charges' => 500.00,
                'maintenance_type' => 'Separate',
                'security_deposit' => '2 months',
                'facing' => 'North-East',
                'is_featured' => true,
                'highlights' => ['Gated Community', 'Power Backup', '24*7 Security', 'Natural Light'],
                'user_id' => $admin->id,
            ]
        );

        Property::updateOrCreate(
            ['title' => 'Balaji Symphony Luxury Suite'],
            [
                'description' => 'Ultra-luxury apartment with skyline views and premium POP finishing.',
                'price' => 1500.00,
                'status' => 'For Sale',
                'category' => 'Residential',
                'listing_type' => 'Sell',
                'type' => 'Apartment',
                'bhk' => '4 BHK',
                'address' => 'Sector 45, Panvel',
                'city' => 'Navi Mumbai',
                'society_name' => 'Balaji Symphony',
                'image' => '/img/property/property-2.jpg',
                'bedrooms' => 4,
                'bathrooms' => 4,
                'balconies' => 3,
                'garage' => 2,
                'size' => 3500,
                'built_up_area' => 3800,
                'carpet_area' => 3200,
                'furnish_type' => 'Semi Furnished',
                'covered_parking' => '2',
                'open_parking' => '0',
                'available_from' => now(),
                'maintenance_charges' => 1200.00,
                'maintenance_type' => 'Included',
                'brokerage_charge' => 'None',
                'brokerage_negotiable' => true,
                'facing' => 'East',
                'servant_room' => true,
                'is_featured' => true,
                'highlights' => ['Pool Facing', 'Gymnasium', 'Club House', 'Smart Home'],
                'user_id' => $testUser->id,
            ]
        );

        $this->call([
            PropertySeeder::class,
        ]);
    }
}
