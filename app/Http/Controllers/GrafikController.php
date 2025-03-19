<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GrafikController extends Controller
{
    public function index()
    {
        // Contoh data riwayat tekanan dan jam jalan (nanti bisa diambil dari database)
        $data = [
            'tekanan' => [
                ['waktu' => '07:00', 'nilai' => 19.38],
                ['waktu' => '08:00', 'nilai' => 21.53],
                ['waktu' => '09:00', 'nilai' => 19.41],
                ['waktu' => '10:00', 'nilai' => 18.75],
                ['waktu' => '11:00', 'nilai' => 19.88],
            ],
            'jam_jalan' => 6
        ];

        return view('grafik', compact('data'));
    }
}
