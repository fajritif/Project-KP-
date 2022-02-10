<?php

namespace App\Http\Controllers;

use App\HoldingAuth;
use App\HoldingAuth\HoldingAuth as HoldingAuthHoldingAuth;
use App\Models\Company;
use App\Models\Device;
use App\Models\Pks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class MillenaController extends Controller
{
    public function holding()
    {
          $data = DB::select("exec USP_ALL_INDIKATOR_BOILER");
        return view('millena.holding',compact('data'));
       // return view('millena.holding');
        if (! Gate::allows('view-all')) {
            $pks = Pks::where('COMPANY_CODE', auth()->user()->PTPN)->first();
            return redirect('ptpn/pks/'.$pks->KODE);
        }

        //   $data = DB::select("exec USP_ALL_INDIKATOR_BOILER");
        // return view('holding',compact('data'));
        return view('millena.holding');

    }

    public function anper(Company $ptpn)
    {
        return redirect('ptpn/'.$ptpn->KODE.'/'.$ptpn->pks()->first()->KODE);
        //return view('millena.anper');
    }

    public function pks(Pks $pks)
    {
        $this->authorize('view-company', $pks->COMPANY_CODE);

        $data = DB::select("exec USP_GETDATA_INDIKATOR_TODAY_BY_PKS '$pks->KODE'");
        $pks = $pks->KODE;
        return view('millena.pks', compact('data', 'pks'));
    }

    public function history(Request $request, $deviceId)
    {
        $splitDeviceId = explode('-', $deviceId);
        $kodePks = '';
        if (count($splitDeviceId) == 4) {
            $kodePks = $splitDeviceId[1];
        }
        $date = $request->date ?: date('Y-m-d');
        $data = DB::select("EXEC USP_GET_DATA_PER_DAY_BY_DEVICE '$deviceId', '$date'");
        $deviceList = DB::select("EXEC USP_GET_DEVICE_BY_PKS '$kodePks'");
        $getDetail = DB::select("EXEC USP_GET_DETAIL_DEVICE '$deviceId'");
        $totalWorkHour = DB::select("EXEC USP_GET_TOTAL_WORK_HOUR '$deviceId', '$date'");
        $detail = (object) [
            'NAMA_COMPANY' => '',
            'NAMA_PKS' => '',
            'KODE_STASIUN' => '',
            'TITLE_PAGE' => ''
        ];
        if (count($getDetail) > 0) {
            $detail = $getDetail[0];
        }
        return view('millena.history', compact('data', 'deviceId', 'deviceList', 'detail', 'totalWorkHour'));
    }
}
