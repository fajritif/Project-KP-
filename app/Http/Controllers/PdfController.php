<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function cetakPdf(Request $request)
    {
        $selectedDevices = $request->input('selectedDevices', []);
        $data = Device::whereIn('DEVICE_ID', $selectedDevices)->get();

        $charts = [];
        foreach ($data as $item) {
            $chartData = [
                'id' => $item->DEVICE_ID,
                'value' => $item->PRESSURE ?? $item->TEMPERATURE ?? $item->PH ?? $item->ARUS ?? 0,
                'title' => $item->TITLE_PAGE,
                'unit' => $item->SATUAN,
                'standartBlock' => $this->getStandartBlock($item->DEVICE_ID),
            ];
            $chartData['image'] = $this->generateChartBase64($chartData);
            $charts[] = $chartData;
        }

        $pdf = Pdf::loadView('report.pdf', compact('data', 'charts'))->setPaper('a4', 'portrait');
        return $pdf->stream('report.pdf');
    }

    // Fungsi untuk menentukan standar blok berdasarkan ID perangkat
    private function getStandartBlock($deviceId)
    {
        if (preg_match('/(BPV|RBS)/', $deviceId)) {
            return [1, 3, 5];
        }
        if (strpos($deviceId, 'PRS') !== false) {
            return [30, 50, 70];
        }
        if (preg_match('/(CST|DIG|FED|GEN)/', $deviceId)) {
            return [80, 110, 150];
        }
        if (strpos($deviceId, 'WTP') !== false) {
            return [4, 8, 14];
        }
        if (strpos($deviceId, 'CBC') !== false) {
            return [30, 50, 70];
        }
        return [10, 17, 30];
    }

   public function generatePdf(Request $request)
{
    $date = $request->query('date');
    $pksNames = explode(',', $request->query('pks'));
    

    if (!$date || empty($pksNames)) {
        abort(400, 'Parameter tidak lengkap');
    }

    // Lakukan logika pengolahan data di sini jika diperlukan
    $data = [
        'date' => $date,
        'pksNames' => $pksNames,
    ];

    // Load PDF dengan data yang dikirim
    $pdf = PDF::loadView('pdf.cobaPdf', $data);

    return $pdf->download("cobaPdf_{$date}.pdf");
}

}
