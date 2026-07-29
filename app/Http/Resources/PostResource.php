<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'caption' => $this->caption,
            'images' => $this->whenLoaded('media', function () {
                return $this->media->map(function ($item) {
                    return url('storage/' . $item->file_path);
                });
            }, []),
            'likeCount' => $this->likes_count ?? 0,
            'commentCount' => $this->comments_count ?? 0,
            'isLikedByMe' => (bool) $this->is_liked_by_me,
            'user' => new UserResource($this->whenLoaded('user')),
            'createdAt' => $this->created_at->toIso8601String(),
        ];
    }
}
