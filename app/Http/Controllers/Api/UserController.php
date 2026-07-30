<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request, $q)
    {
        $users = User::with(['posts', 'posts.media'])
            ->where('username', 'like', "{$q}%")
            ->orWhere('name', 'like', "{$q}%")
            ->get();

        return $this->success(UserResource::collection($users), 'Search results retrieved successfully');
    }
}
