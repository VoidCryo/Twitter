<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegisterController;
use Illuminate\Support\Facades\Route;

// register
Route::get('/Register', [RegisterController::class, 'index'])->name('register');
Route::post('/Register', [RegisterController::class, 'register'])->name('register.user');

// login
Route::get('/Login', [LoginController::class, 'index'])->name('login');
Route::post('/Login', [LoginController::class, 'login'])->name('login.user');
Route::post('/Logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return '
        <form action="/Logout" method="POST">
            ' . csrf_field() . '
            <button type="submit" style="background:none; border:none; color:blue; text-decoration:underline; cursor:pointer; padding:0;">
                logout
            </button>
        </form>
    ';
})->name('home');

