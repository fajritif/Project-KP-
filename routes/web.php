<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\MillenaController;
use App\Http\Controllers\PksController;
use App\Http\Controllers\StasiunController;
use App\Http\Controllers\UserController;
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
    return redirect('ptpn');
});
/*
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
*/
/*
Route::prefix('test')->group(function () {
    Route::get('/login', function () {
        return view('test.login');
    });
    Route::get('/home', function () {
        return view('layouts.app');
    });
});
*/

    Route::get('/ptpn', [MillenaController::class, 'holding'])->middleware(['auth']);
    Route::get('/ptpn/device/{deviceId}', [MillenaController::class, 'history'])->middleware(['auth']);
    //Route::get('/ptpn/{ptpn}', [MillenaController::class, 'anper']);
    Route::get('/ptpn/pks/{pks}', [MillenaController::class, 'pks'])->middleware(['auth']);
    //Route::get('/ptpn/{ptpn}/{pks}/history', [MillenaController::class, 'history']);
    Route::get('/holding', [MillenaController::class, 'holding'])->middleware(['auth']);
    Route::get('/admin/device/index-ajax', [MillenaController::class, 'index_ajax'])->middleware(['auth']);
    Route::put('/admin/user', [UserController::class, 'update'])->middleware(['auth']);
    Route::delete('/admin/user', [UserController::class, 'destroy'])->middleware(['auth']);
    Route::resource('/admin/user', UserController::class)->only(['index','show', 'update'])->middleware(['auth']);
    Route::resource('/admin/device', DeviceController::class)->middleware(['auth']);


    // Link Stream
    Route::get('/ptpn/streaming/{device}/stream', [MillenaController::class, 'streaming'])->name('cctv.streaming')->middleware(['auth']);
    Route::get('/streaming/{device}/stream', [MillenaController::class, 'getStreamFile'])->name('get-stream-file')->middleware(['auth']);
    Route::get('/streaming/{device}/{file}', [MillenaController::class, 'getStreamFileTs'])->middleware(['auth']);

Route::prefix('api')->group(function () {
    Route::get('/company/{company}/pks', [CompanyController::class, 'pks']);
    Route::get('/device-per-pks/{pks}', [DeviceController::class, 'current']);
    Route::get('/latest-boiler', [DeviceController::class, 'all_boiler']);
    Route::get('/pks-by-company/{company}', [PksController::class, 'by_company']);
    Route::get('/history/work-hour/{deviceId}', [DeviceController::class, 'workhour']);
    Route::get('/history/indicator/{deviceId}', [DeviceController::class, 'indicator']);
    Route::get('/device', [DeviceController::class, 'api_index']);
    Route::post('/device/insert', [DeviceController::class, 'insert']);

    Route::resource('/company', CompanyController::class)->only(['index','show']);
    Route::resource('/pks', PksController::class)->only(['index','show']);
    Route::resource('/stasiun', StasiunController::class)->only(['index','show']);
    Route::post('/login', [AuthenticatedSessionController::class, 'api_login'] );
});

require __DIR__.'/auth.php';
