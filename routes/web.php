<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\page\HomeController;
use App\Http\Controllers\page\SearchController;
use App\Http\Controllers\page\PostController;
use App\Http\Controllers\service\ServPostController;
use App\Http\Controllers\service\ServFollowController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // register
    Route::get('/Register', [RegisterController::class, 'index'])->name('register');
    Route::post('/Register', [RegisterController::class, 'register'])->name('register.user');

    // login
    Route::get('/Login', [LoginController::class, 'index'])->name('login');
    Route::post('/Login', [LoginController::class, 'login'])->name('login.user');
});

Route::middleware('auth')->group(function () {
    // home
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/Home/Load-more', [HomeController::class, 'loadMore'])->name('home.load-more');

    // logout
    Route::post('/Logout', [LoginController::class, 'logout'])->name('logout');

    // follow
    Route::post('/User/{user}/Follow', [ServFollowController::class, 'toggle'])->name('user.follow');

    // post
    Route::get('/Post/{post}', [PostController::class, 'index'])->name('post');
    Route::get('/Post/{post}/Replies', [PostController::class, 'loadMoreReplies'])->name('post.load-more-replies');

    Route::prefix('/Post')->group(function () {
        Route::post('/', [ServPostController::class, 'store'])->name('post.store');
        Route::post('/{post}/Like', [ServPostController::class, 'like'])->name('post.like');
        Route::post('/{post}/Repost', [ServPostController::class, 'repost'])->name('post.repost');
        Route::post('/{post}/Reply', [ServPostController::class, 'reply'])->name('post.reply');
        Route::delete('/{post}/Destroy', [ServPostController::class, 'destroy'])->name('post.destroy');
    });
});

