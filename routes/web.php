<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\MillenaController;
use App\Http\Controllers\PksController;
use App\Http\Controllers\StasiunController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonitoringReportController; 

Route::get('/', function () {
    return redirect('ptpn');
});

// ============================
// Routes untuk Halaman Utama
// ============================
Route::middleware(['auth'])->group(function () {
    Route::get('/ptpn', [MillenaController::class, 'holding']);
    Route::get('/ptpn/device/{deviceId}', [MillenaController::class, 'history']);
    Route::get('/ptpn/pks/{pks}', [MillenaController::class, 'pks']);
    Route::get('/holding', [MillenaController::class, 'holding']);
});

// ============================
// Routes untuk Admin & User
// ============================
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/device/index-ajax', [MillenaController::class, 'index_ajax']);
    
    // Route::put dan Route::delete dihapus karena sudah di-handle oleh resource
    Route::resource('/admin/user', UserController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::resource('/admin/device', DeviceController::class);

    // ✅ Route untuk export PDF user
    Route::get('/users/print', [UserController::class, 'printPdf'])->name('users.print');
});

// ============================
// Routes untuk Link Streaming
// ============================
Route::middleware(['auth'])->group(function () {
    Route::get('/ptpn/streaming/{device}/stream', [MillenaController::class, 'streaming'])->name('cctv.streaming');
    Route::get('/streaming/{device}/stream', [MillenaController::class, 'getStreamFile'])->name('get-stream-file');
    Route::get('/streaming/{device}/{file}', [MillenaController::class, 'getStreamFileTs']);
});

// ============================
// Routes untuk API
// ============================
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
    
    // ✅ Route API Login
    Route::post('/login', [AuthenticatedSessionController::class, 'api_login']);
});

// ============================
// Routes untuk Export PDF PKS
// ============================

Route::get('/chart', function () {
    $data = \App\Models\Device::first(); // Contoh data untuk preview
    $chartData = [
        'id' => $data->DEVICE_ID,
        'value' => $data->PRESSURE ?? $data->TEMPERATURE ?? $data->PH ?? $data->ARUS ?? 0,
        'title' => $data->TITLE_PAGE,
        'unit' => $data->SATUAN,
        'standartBlock' => [10, 17, 30]
    ];
    return view('pdf.chart', compact('chartData'));
});

// Route::get('/ptpn/device/pdf/{pks}', [MonitoringReportController::class, 'generatePdf'])->name('device.pdf'); 

// Route::get('/ptpn/device/pdf', [MonitoringReportController::class, 'printPDF']);

Route::get('/cobaPdf', [PdfController::class, 'generatePdf']);


require __DIR__.'/auth.php';
