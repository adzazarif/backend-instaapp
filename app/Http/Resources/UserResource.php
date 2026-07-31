<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'avatar' => $this->avatar ? (str_starts_with($this->avatar, 'http') ? $this->avatar : url('storage/' . $this->avatar)) : null,
            'bio' => $this->when($request->routeIs('auth.me') || $request->is('api/auth/me'), $this->bio),
        ];
    }
}
