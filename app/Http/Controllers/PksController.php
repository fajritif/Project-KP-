<?php

namespace App\Http\Controllers;

use App\Models\Pks;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use PDF; // Alias PDF harus sesuai konfigurasi

class PksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pkss = Pks::all();
        return response()->json($pkss);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pks  $pks
     * @return \Illuminate\Http\Response
     */
    public function show(Pks $pks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pks  $pks
     * @return \Illuminate\Http\Response
     */
    public function edit(Pks $pks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pks  $pks
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pks $pks)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pks  $pks
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pks $pks)
    {
        //
    }

    /**
     * Get PKS data by company code.
     *
     * @param  string $company_code
     * @return \Illuminate\Http\Response
     */
    public function by_company($company_code)
    {
        $pkss = Pks::where('COMPANY_CODE', $company_code)->get();
        $pkss->load('company');
        $pkss->append(['nama_company', 'nama_company_panjang']);
        return response()->json($pkss);
    }

    /**
     * Export PDF for a specific PKS.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function exportPdf($id) {
        // Ambil data PKS dengan relasi widgets
        $pks = Pks::with('widgets')->where('KODE', $id)->firstOrFail();

        $data = [
            'title' => 'Data Widgets PKS ' . $pks->NAMA,
            'widgets' => $pks->widgets
        ];

        // Pemanggilan PDF
        $pdf = FacadePdf::loadView('pks_pdf', $data);

        // Format filename saat di-download
        return $pdf->download('data-pks-' . $pks->getKey() . '.pdf');
    }
}
