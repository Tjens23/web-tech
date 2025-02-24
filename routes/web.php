<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostSaveController;
use App\Http\Controllers\UpdootController;
use App\Http\Middleware\AuthCheck;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;

Route::get('/', [PostController::class, 'index'])->name('home');

Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('/search-user', [UserController::class, 'search'])->name('search.user');
Route::get('/user/{username}', [UserController::class, 'profile'])->name('user.profile');


Route::middleware([AuthCheck::class])->group(function(){
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/{id}/follow', [UserController::class, 'follow'])->name('follow');
});



Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');;
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');;

Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');;


Route::resource('post-saves', PostSaveController::class)->middleware('auth');
Route::post('/posts/{postId}/save', [PostSaveController::class, 'store'])->name('posts.save')->middleware('auth');


Route::get("/posts/{postId}/comments", [CommentController::class, 'index'])->name('comments.index');

Route::middleware([AuthCheck::class])->group(function () {

    Route::post('/posts/{id}/like', [UpdootController::class, 'upvote'])->name('posts.like');
    Route::post('/posts/{id}/dislike', [UpdootController::class, 'downvote'])->name('posts.dislike');

    Route::post('/posts/{postId}/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::put('/comments/{commentId}', [CommentController::class, 'update'])->name('comments.update');

    Route::delete('/comments/{commentId}', [CommentController::class, 'destroy'])->name('comments.destroy');
});
