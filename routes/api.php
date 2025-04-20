<?php

use App\Http\Controllers\Api\Authcontroller;
use Illuminate\Support\Facades\Route;

Route::post('register', [Authcontroller::class, 'register'])->name('register');
Route::post('login', [Authcontroller::class, 'login'])->name('login');

Route::group(['middleware' => 'auth:sanctum', 'verified'], function () {
    Route::get('logout', [Authcontroller::class, 'logout'])->name('logout');
});
