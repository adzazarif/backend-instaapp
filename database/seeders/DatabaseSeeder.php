<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\LikePost;
use App\Models\Comment;
use App\Models\Story;
use App\Models\StoryView;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Create 10 dummy users
        $users = User::factory(10)->create();

        // Create main test user
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // password is 'password' by default but we make it explicit
        ]);

        $users->push($testUser);

        // For each user, create some posts and stories
        foreach ($users as $user) {
            // Posts
            Post::factory(rand(2, 5))->create(['user_id' => $user->id])->each(function ($post) use ($users) {
                // Post Media (1 to 3 images per post)
                PostMedia::factory(rand(1, 3))->create([
                    'post_id' => $post->id,
                ]);

                // Random likes
                $likers = $users->random(rand(0, 5));
                foreach ($likers as $liker) {
                    LikePost::factory()->create([
                        'post_id' => $post->id,
                        'user_id' => $liker->id,
                    ]);
                }

                // Random comments
                $commenters = $users->random(rand(0, 5));
                foreach ($commenters as $commenter) {
                    Comment::factory()->create([
                        'post_id' => $post->id,
                        'user_id' => $commenter->id,
                    ]);
                }
            });

            // Stories
            Story::factory(rand(1, 3))->create(['user_id' => $user->id])->each(function ($story) use ($users) {
                // Random views
                $viewers = $users->random(rand(0, 5));
                foreach ($viewers as $viewer) {
                    if ($viewer->id !== $story->user_id) { 
                        StoryView::factory()->create([
                            'story_id' => $story->id,
                            'viewer_id' => $viewer->id,
                        ]);
                    }
                }
            });
        }
    }
}
