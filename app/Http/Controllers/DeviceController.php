<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class DeviceController extends Controller
{
    public function pdfReport(Request $request)
    {
        $date   = $request->query('date');
        $device = $request->query('device');

        // Contoh data dummy, sesuaikan query DB
        $timeLabels   = ["10:00","10:10","10:20","10:30","10:40","10:50"];
        $pressureData = [10.2, 9.8, 10.5, 11.0, 9.6, 10.1];

        // Render view pdf_chart.blade.php dengan data
        $html = view('pdf_chart', [
            'date'         => $date,
            'device'       => $device,
            'timeLabels'   => $timeLabels,
            'pressureData' => $pressureData,
        ])->render();

        $pdfPath = storage_path('app/public/laporan_grafik.pdf');

        // Buat PDF dari HTML di atas (dengan JS dieksekusi)
        Browsershot::html($html)
            ->emulateMedia('screen')
            ->waitUntilNetworkIdle() // Tunggu sampai grafik Highcharts selesai
            ->paperSize(8.3, 11.7)   // A4 (inch)
            ->savePdf($pdfPath);

        return response()->download($pdfPath, 'rekap_data.pdf');
    }
}
