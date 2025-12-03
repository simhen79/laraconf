<?php

namespace Database\Factories;

use App\Enums\Region;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $startDate = now()->month(now()->month);
        $endDate = now()->addDays(2);
        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => fake()->randomElement(["draft","published","archived"]),
            'region' => fake()->randomElement(Region::class),
            'venue_id' => null,
            'is_published' => fake()->boolean(),
        ];
    }
}
