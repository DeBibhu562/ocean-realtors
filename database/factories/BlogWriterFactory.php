<?php

namespace Database\Factories;

use App\Models\BlogWriter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BlogWriter>
 */
class BlogWriterFactory extends Factory
{
    protected $model = BlogWriter::class;

    public function definition(): array
    {
        $name = fake()->name();

        return [
            'name' => $name,
            'bio' => fake()->sentence(12),
            'photo' => 'https://ui-avatars.com/api/?name='.urlencode($name).'&size=400',
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }
}
