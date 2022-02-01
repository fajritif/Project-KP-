<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

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
        return view('millena.pks');
    }

    public function history($ptpn,$pks)
    {
        return view('millena.history');
    }
}
