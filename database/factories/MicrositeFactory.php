<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Microsite>
 */
class MicrositeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category' => fake()->randomElement(['training', 'sensus', 'zi']),
            'title' => fake()->sentence(3),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraph(),
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
            'template_key' => 'minimal-grid',
            'layout_type' => 'grid',
            'is_published' => true,
            'published_at' => fake()->dateTime(),
            'theme_color' => '#10b981',
            'accent_color' => '#059669',
        ];
    }
}
