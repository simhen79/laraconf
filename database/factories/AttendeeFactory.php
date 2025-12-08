<?php

namespace Database\Factories;

use App\Models\Conference;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendee>
 */
class AttendeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'ticket_cost' => fake()->numberBetween(10, 1000),
            'is_paid' => fake()->boolean(),
            'created_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }

    public function forConference(Conference $conference)
    {
        return $this->state([
            'conference_id' => $conference->id,
        ]);
    }
}
