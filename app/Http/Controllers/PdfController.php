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

        $pdf = Pdf::loadView('pdf.report', compact('data', 'charts'))->setPaper('a4', 'portrait');
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

    // Fungsi untuk membuat grafik dalam format base64
    private function generateChartBase64($chartData)
    {
        $chartOptions = [
            'chart' => ['type' => 'gauge', 'height' => 200],
            'title' => ['text' => $chartData['title']],
            'pane' => ['startAngle' => -150, 'endAngle' => 150],
            'yAxis' => [
                'min' => 0,
                'max' => $chartData['standartBlock'][2],
                'plotBands' => [
                    ['from' => 0, 'to' => $chartData['standartBlock'][0], 'color' => '#DF5353'], // Merah
                    ['from' => $chartData['standartBlock'][0], 'to' => $chartData['standartBlock'][1], 'color' => '#DDDF0D'], // Kuning
                    ['from' => $chartData['standartBlock'][1], 'to' => $chartData['standartBlock'][2], 'color' => '#55BF3B'] // Hijau
                ]
            ],
            'series' => [['data' => [$chartData['value']]]]
        ];

        $chartJson = json_encode($chartOptions);
        $chartImage = file_get_contents("https://export.highcharts.com/?options=" . urlencode($chartJson));
        
        return base64_encode($chartImage);
    }
}
