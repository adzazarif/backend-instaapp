<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        // Bisa dihapus oleh pembuat komentar ATAU pemilik postingan
        return $user->id === $comment->user_id || $user->id === $comment->post->user_id;
    }
}
