<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\ExportExcelController;
use App\Http\Controllers\ExportRekapUangMakanController;


// Route::get('/', function () {
//     return view('layouts.main');
// });

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'auth_login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/halamanRekap', [RekapController::class, 'index'])->name('halamanRekap');

Route::post('/exportRekap', [ExportExcelController::class, 'exportRekap']);
Route::post('/exportRekapUangMakan', [ExportRekapUangMakanController::class, 'exportRekapUangMakan']);


Route::get('/unauthorized', function () {
    return view('pages.unauthorized.index');
})->name('unauthorized');
