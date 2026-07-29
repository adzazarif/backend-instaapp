<?php

namespace Database\Factories;

use App\Models\Story;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoryViewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'story_id' => Story::factory(),
            'viewer_id' => User::factory(),
            'viewed_at' => fake()->dateTimeBetween('-1 days', 'now'),
        ];
    }
}
