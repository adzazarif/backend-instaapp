<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:6,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    
    // Posts
    Route::get('/posts', [\App\Http\Controllers\Api\PostController::class, 'index']);
    Route::post('/posts', [\App\Http\Controllers\Api\PostController::class, 'store']);
    Route::get('/posts/{post}', [\App\Http\Controllers\Api\PostController::class, 'show']);
    Route::put('/posts/{post}', [\App\Http\Controllers\Api\PostController::class, 'update']);
    Route::delete('/posts/{post}', [\App\Http\Controllers\Api\PostController::class, 'destroy']);
    
    // Likes
    Route::post('/posts/{id}/like', [\App\Http\Controllers\Api\LikeController::class, 'toggle']);
    Route::get('/posts/{id}/likes', [\App\Http\Controllers\Api\LikeController::class, 'index']);
    
    // Comments
    Route::get('/posts/{id}/comments', [\App\Http\Controllers\Api\CommentController::class, 'index']);
    Route::post('/posts/{id}/comments', [\App\Http\Controllers\Api\CommentController::class, 'store']);
    Route::delete('/comments/{comment}', [\App\Http\Controllers\Api\CommentController::class, 'destroy']);
});
