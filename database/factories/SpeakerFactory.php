<?php

namespace Database\Factories;

use App\Models\Speaker;
use App\Models\Talk;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Testing\Fakes\Fake;

class SpeakerFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $qualificationCount = fake()->numberBetween(0, 10);
        $qualifications = fake()->randomElements(array_keys(Speaker::QUALIFICATIONS), $qualificationCount);
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'bio' => fake()->text(),
            'qualifications' => $qualifications,
            'twitter_handle' => fake()->word(),
        ];
    }

    public function withTalks(int $count = 1): self
    {
        return $this->has(Talk::factory()->count($count), 'talks');
    }
}
