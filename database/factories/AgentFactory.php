<?php

namespace Database\Factories;

use App\Models\Agent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Agent>
 */
class AgentFactory extends Factory
{
    protected $model = Agent::class;

    public function definition(): array
    {
        $name = fake()->name();

        return [
            'name' => $name,
            'slug' => Agent::uniqueSlug($name),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '+91'.fake()->numerify('9#########'),
            'whatsapp_phone' => '+91'.fake()->numerify('9#########'),
            'designation' => fake()->randomElement(['Senior Property Consultant', 'Sales Manager', 'Rental Specialist']),
            'bio' => fake()->paragraph(2),
            'city' => fake()->randomElement(['Delhi', 'Gurgaon', 'Noida', 'Mumbai', 'Bangalore']),
            'rating' => fake()->randomFloat(1, 4.5, 5.0),
            'reviews_count' => fake()->numberBetween(20, 200),
            'experience_years' => fake()->numberBetween(2, 15),
            'languages' => ['English', 'Hindi'],
            'is_active' => true,
        ];
    }
}
