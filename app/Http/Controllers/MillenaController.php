<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MillenaController extends Controller
{
    public function holding()
    {
        return view('millena.holding');
    }

    public function anper(Company $ptpn)
    {
        return redirect('ptpn/'.$ptpn->KODE.'/'.$ptpn->pks()->first()->KODE);
        //return view('millena.anper');
    }

    public function pks($ptpn,$pks)
    {
        $data = DB::select("exec USP_GETDATA_INDIKATOR_TODAY_BY_PKS 'EF01'");
        return view('millena.pks', compact('data'));
    }

    public function history($ptpn,$pks)
    {
        return view('millena.history');
    }
}
