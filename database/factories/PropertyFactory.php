<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10000, 5000000),
            'status' => 'For Rent',
            'type' => 'Apartment',
            'address' => fake()->streetAddress(),
            'city' => 'Gurgaon',
            'bedrooms' => 2,
            'bathrooms' => 2,
            'garage' => 1,
            'size' => 1200,
            'user_id' => User::factory(),
        ];
    }
}
