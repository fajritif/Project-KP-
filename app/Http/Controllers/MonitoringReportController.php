<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Device; // Pastikan model sesuai dengan database Anda

class MonitoringReportController extends Controller
{
    public function generatePdf($pks)
    {
        // Ambil data berdasarkan perangkat dan tanggal yang dipilih
        $data = Device::where('KODE_DEVICE', $pks)->get(); // Sesuaikan query dengan database Anda
        
        // Render tampilan PDF
        $pdf = Pdf::loadView('pdf\device_report', compact('data', 'pks'))
            ->setPaper('a4', 'portrait');

        // Unduh PDF
        return $pdf->download('Device_Report_' . now()->format('Y-m-d') . '.pdf');
    }
}
