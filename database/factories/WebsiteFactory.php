<?php

namespace Database\Factories;

use App\Models\WebsiteStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Website>
 */
class WebsiteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'slug' => $this->faker->slug,
            'address' => $this->faker->url,
            'website_status_id' => WebsiteStatus::query()->inRandomOrder()->first()->getKey(),
        ];
    }
}
