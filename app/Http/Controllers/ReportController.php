<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function generatePDF()
    {
        $data = [
            'title' => 'Riwayat Tekanan dan Jam Jalan',
            'tekanan' => [19.38, 17.22, 21.53, 19.41, 18.20, 19.00], // Data tekanan contoh
            'jam_jalan' => [6] // Data total jam jalan contoh
        ];

        $pdf = Pdf::loadView('pdf.report', $data);
        return $pdf->download('report.pdf');
    }
}
