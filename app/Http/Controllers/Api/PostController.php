<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::with(['user', 'media'])
            ->withCount(['likes', 'comments'])
            ->withExists(['likes as is_liked_by_me' => function ($query) {
                $query->where('user_id', auth()->id());
            }])
            ->latest()
            ->paginate($request->get('perPage', 10));

        return $this->successWithPagination(PostResource::collection($posts), 'Feed retrieved successfully');
    }

    public function store(StorePostRequest $request)
    {
        $post = $request->user()->posts()->create([
            'caption' => $request->validated('caption')
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('posts', 'public');
                $post->media()->create([
                    'file_path' => $path,
                    'media_type' => 'image',
                    'order' => $index,
                ]);
            }
        }

        $post->load(['user', 'media'])->loadCount(['likes', 'comments']);
        $post->is_liked_by_me = false;

        return $this->success(new PostResource($post), 'Post created successfully', 201);
    }

    public function show(Post $post)
    {
        $post->load(['user', 'media'])
            ->loadCount(['likes', 'comments'])
            ->loadExists(['likes as is_liked_by_me' => function ($query) {
                $query->where('user_id', auth()->id());
            }]);

        return $this->success(new PostResource($post), 'Post retrieved successfully');
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        Gate::authorize('update', $post);

        $post->update([
            'caption' => $request->validated('caption')
        ]);

        $post->load(['user', 'media'])
            ->loadCount(['likes', 'comments'])
            ->loadExists(['likes as is_liked_by_me' => function ($query) {
                $query->where('user_id', auth()->id());
            }]);

        return $this->success(new PostResource($post), 'Post updated successfully');
    }

    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);

        foreach ($post->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }
        $post->delete();

        return $this->success(null, 'Post deleted successfully');
    }

    public function myPosts(Request $request)
    {
        $posts = Post::with(['user', 'media'])
            ->withCount(['likes', 'comments'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate($request->get('perPage', 10));

        return $this->successWithPagination(PostResource::collection($posts), 'My posts retrieved successfully');
    }
}
