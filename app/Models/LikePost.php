<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LikePost extends Model
{
    /** @use HasFactory<\Database\Factories\LikePostFactory> */
    use HasFactory;

    protected $table = 'likes';

    const UPDATED_AT = null;

    protected $fillable = ['post_id', 'user_id', 'created_at'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
