<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        
        $like = $post->likes()->where('user_id', $request->user()->id)->first();
        
        if ($like) {
            $like->delete();
            $isLiked = false;
        } else {
            $post->likes()->create(['user_id' => $request->user()->id]);
            $isLiked = true;
        }
        
        return $this->success([
            'postId' => (int) $id,
            'isLiked' => $isLiked,
            'likeCount' => $post->likes()->count(),
        ], 'Like updated successfully');
    }

    public function index(Request $request, $id)
    {
        // Pastikan post ada
        Post::findOrFail($id);
        
        $users = User::whereHas('likes', function($q) use ($id) {
            $q->where('post_id', $id);
        })->paginate($request->get('perPage', 20));
        
        return $this->successWithPagination(UserResource::collection($users), 'Likers retrieved successfully');
    }
}
