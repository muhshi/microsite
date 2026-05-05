<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Microsite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Microsite>
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
            'category_id' => Category::factory(),
            'title' => fake()->sentence(3),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraph(),
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
            'template_key' => 'minimal-grid',
            'layout_type' => 'grid',
            'is_published' => true,
            'is_public' => true,
            'published_at' => fake()->dateTime(),
            'theme_color' => '#10b981',
            'accent_color' => '#059669',
        ];
    }
}
