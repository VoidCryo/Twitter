<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FollowListController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostInteractionsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\Action\FollowController;
use App\Http\Controllers\Action\PostController as PostActionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login',    [LoginController::class, 'index'])->name('login');
    Route::post('/login',   [LoginController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register',[RegisterController::class, 'store'])->name('register.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::middleware('auth')->group(function () {

    // Home feed
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');

    // Posts
    Route::get('/post/{post}', [PostController::class, 'index'])->name('post');
    Route::get('/post/{post}/interactions', [PostInteractionsController::class, 'index'])->name('post.interactions');

    // Post actions
    Route::post('/post',              [PostActionController::class, 'store'])->name('post.store');
    Route::post('/post/{post}/like',  [PostActionController::class, 'like'])->name('post.like');
    Route::post('/post/{post}/repost',[PostActionController::class, 'repost'])->name('post.repost');
    Route::post('/post/{post}/reply', [PostActionController::class, 'reply'])->name('post.reply');
    Route::delete('/post/{post}',     [PostActionController::class, 'destroy'])->name('post.destroy');

    // Profile
    Route::get('/profile/{user}',          [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/{user}/followers', [FollowListController::class, 'followers'])->name('profile.followers');
    Route::get('/profile/{user}/following', [FollowListController::class, 'following'])->name('profile.following');
    Route::get('/settings/profile',        [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/settings/profile',      [ProfileController::class, 'update'])->name('profile.update');

    // Follow
    Route::post('/follow/{user}', [FollowController::class, 'toggle'])->name('follow.toggle');
});
