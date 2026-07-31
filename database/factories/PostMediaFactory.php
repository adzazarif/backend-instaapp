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
            'file_path' => 'https://picsum.photos/seed/' . fake()->uuid() . '/800/800',
            'media_type' => 'image',
            'order' => 0,
        ];
    }
}
