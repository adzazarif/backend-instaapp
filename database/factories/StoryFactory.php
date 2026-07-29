<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class StoryFactory extends Factory
{
    public function definition(): array
    {
        $createdAt = fake()->dateTimeBetween('-2 days', 'now');
        $expiresAt = Carbon::instance($createdAt)->addHours(24);

        return [
            'user_id' => User::factory(),
            'media_path' => fake()->imageUrl(1080, 1920, 'abstract', true),
            'caption' => fake()->boolean(50) ? fake()->sentence(4) : null,
            'created_at' => $createdAt,
            'expires_at' => $expiresAt,
        ];
    }
}
