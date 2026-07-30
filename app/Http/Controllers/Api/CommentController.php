<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function index(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        
        $comments = $post->comments()->with('user')
            ->latest()
            ->paginate($request->get('perPage', 15));
            
        return $this->successWithPagination(CommentResource::collection($comments), 'Comments retrieved successfully');
    }

    public function store(StoreCommentRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        
        $comment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $request->validated('content'),
        ]);
        
        $comment->load('user');
        
        return $this->success(new CommentResource($comment), 'Comment added successfully', 201);
    }

    public function destroy(Comment $comment)
    {
        // Memerlukan CommentPolicy yang sudah kita buat
        Gate::authorize('delete', $comment);
        
        $comment->delete();
        
        return $this->success(null, 'Comment deleted successfully');
    }
}
