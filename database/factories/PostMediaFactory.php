<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostMediaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'post_id' => Post::factory(),
            'file_path' => fake()->imageUrl(800, 800, 'nature', true),
            'media_type' => 'image',
            'order' => 0,
        ];
    }
}
