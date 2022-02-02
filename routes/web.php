<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\MillenaController;
use App\Http\Controllers\PksController;
use App\Http\Controllers\StasiunController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::prefix('test')->group(function () {
    Route::get('/login', function () {
        return view('test.login');
    });
    Route::get('/home', function () {
        return view('layouts.app');
    });
});

Route::get('/ptpn', [MillenaController::class, 'holding']);
Route::get('/ptpn/{ptpn}', [MillenaController::class, 'anper']);
Route::get('/ptpn/{ptpn}/{pks}', [MillenaController::class, 'pks']);
Route::get('/ptpn/{ptpn}/{pks}/history', [MillenaController::class, 'history']);


Route::prefix('api')->group(function () {
    Route::get('/company/{company}/pks', [CompanyController::class, 'pks']);
    Route::get('/device-per-pks/{pks}', [DeviceController::class, 'current']);
    Route::resource('/company', CompanyController::class)->only(['index','show']);
    Route::resource('/pks', PksController::class)->only(['index','show']);
    Route::resource('/stasiun', StasiunController::class)->only(['index','show']);
    Route::resource('/device', DeviceController::class)->only(['index','show']);
});

require __DIR__.'/auth.php';
