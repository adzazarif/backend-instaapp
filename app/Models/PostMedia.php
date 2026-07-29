<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostMedia extends Model
{
    /** @use HasFactory<\Database\Factories\PostMediaFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['post_id', 'file_path', 'media_type', 'order'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
