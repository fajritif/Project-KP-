<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
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
    return redirect('dashboard');
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
    Route::get('/auth1', [MillenaController::class, 'auth1']);
});

Route::get('/ptpn', [MillenaController::class, 'holding']);
Route::get('/ptpn/device/{deviceId}', [MillenaController::class, 'history']);
//Route::get('/ptpn/{ptpn}', [MillenaController::class, 'anper']);
Route::get('/ptpn/pks/{pks}', [MillenaController::class, 'pks']);
//Route::get('/ptpn/{ptpn}/{pks}/history', [MillenaController::class, 'history']);


Route::prefix('api')->group(function () {
    Route::get('/company/{company}/pks', [CompanyController::class, 'pks']);
    Route::get('/device-per-pks/{pks}', [DeviceController::class, 'current']);
    Route::get('/pks-by-company/{company}', [PksController::class, 'by_company']);
    Route::resource('/company', CompanyController::class)->only(['index','show']);
    Route::resource('/pks', PksController::class)->only(['index','show']);
    Route::resource('/stasiun', StasiunController::class)->only(['index','show']);
    Route::resource('/device', DeviceController::class)->only(['index','show']);
    Route::post('/login', [AuthenticatedSessionController::class, 'api_login'] );
});

require __DIR__.'/auth.php';
