<?php

namespace App\Http\Controllers;

use App\HoldingAuth;
use App\HoldingAuth\HoldingAuth as HoldingAuthHoldingAuth;
use App\Models\Company;
use App\Models\Device;
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

    public function pks($pks)
    {
        $data = DB::select("exec USP_GETDATA_INDIKATOR_TODAY_BY_PKS '$pks'");
        return view('millena.pks', compact('data'));
    }

    public function history(Request $request, $deviceId)
    {
        $date = $request->date ?: date('Y-m-d');
        $data = DB::select("EXEC USP_GET_DATA_PER_DAY_BY_DEVICE '$deviceId', '$date'");
        return view('millena.history', compact('data', 'deviceId'));
    }

    public function auth1()
    {
        $data = DB::select("exec USP_GET_USER_BY_NIK_SAP '5013097'"); // ambil data pertama saja
        if(count($data) >0){
            return true;
        }else{
            return false;
        }

        $data = DB::select("exec USP_GET_USER_BY_NIK_SAP '5013097'")[0]; // ambil data pertama saja
        $user = new HoldingAuth;
        foreach($data as $key=>$value){
            $user->$key = $value;
        }
        dump($user);
        dump($user->getAuthIdentifier());
        dd($user->getAuthIdentifierName());
    }
}
