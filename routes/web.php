<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RekapController;


// Route::get('/', function () {
//     return view('layouts.main');
// });

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'auth_login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/halamanRekap', [RekapController::class, 'index'])->name('halamanRekap');

Route::get('/unauthorized', function () {
    return view('pages.unauthorized.index');
})->name('unauthorized');
