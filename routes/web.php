<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\ExportExcelController;
use App\Http\Controllers\ExportRekapUangMakanController;
use App\Http\Controllers\ExportRekapUangMakanZipController;
use App\Http\Controllers\PDFServiceController;

Route::post('/generate-zip', [PDFServiceController::class, 'generateZip']);

// Route::get('/', function () {
//     return view('layouts.main');
// });

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'auth_login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/halamanRekap', [RekapController::class, 'index'])->name('halamanRekap');
Route::get('/cetak', [RekapController::class, 'cetak'])->name('cetak');

Route::post('/exportRekap', [ExportExcelController::class, 'exportRekap']);
Route::post('/exportRekapUangMakan', [ExportRekapUangMakanController::class, 'exportRekapUangMakan']);
Route::post('/exportRekapUangMakanZip', [ExportRekapUangMakanZipController::class, 'exportRekapUangMakanZip']);
Route::post('/export/rekap-preview', [ExportRekapUangMakanZipController::class, 'exportRekapUangMakanPreview']);
Route::get('/tes', [ExportRekapUangMakanZipController::class, 'tes']);
//generate zip
// Route::post('/export/request', [ExportRekapUangMakanZipController::class, 'requestExport']);
// Route::get('/export/status', [ExportRekapUangMakanZipController::class, 'checkStatus']);

Route::post('/export/rekap-uang-makan', [ExportRekapUangMakanZipController::class, 'exportRekapUangMakanZip']);
Route::get('/export/rekap/progress', [ExportRekapUangMakanZipController::class, 'getProgress']);


Route::get('/unauthorized', function () {
    return view('pages.unauthorized.index');
})->name('unauthorized');
