<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade as PDF;

class NamaController extends Controller
{
    public function cetakPDF()
    {
        $data = [
            'title' => 'Laporan Data',
            'data' => [
                ['DEVICE_NAME' => 'Device A', 'VALUE' => 100],
                ['DEVICE_NAME' => 'Device B', 'VALUE' => 150],
                ['DEVICE_NAME' => 'Device C', 'VALUE' => 200],
            ]
        ];

        $pdf = PDF::loadView('pdf.laporan', $data);

        return $pdf->download('laporan.pdf');
    }
}
