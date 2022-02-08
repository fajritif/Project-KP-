<?php

namespace App\Http\Controllers;

use App\Models\Pks;
use Illuminate\Http\Request;

class PksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function by_company($company_code)
    {
        $pkss = Pks::where('COMPANY_CODE', $company_code)->get();
        $pkss->load('company');
        $pkss->append(['nama_company', 'nama_company_panjang']);
        //dump($pkss->toArray()); return '';
        return response()->json($pkss);
    }
}
