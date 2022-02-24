<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTable;
use stdClass;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$devices = Device::all();
        //dd($devices->first()->toArray());

        return view('device.index');
    }

    public function api_index()
    {
        $devices = new Device();
        
        if(auth()->user()->ROLEID == 'ADMIN_ANPER'){
            $devices = $devices->where('COMPANY_CODE', auth()->user()->PTPN);
        }elseif(auth()->user()->ROLEID == 'ADMIN_UNIT'){
            $devices = $devices->where('COMPANY_CODE', auth()->user()->PTPN)->where('KODE_PKS', auth()->user()->PSA);
        }
        $devices = $devices->get();
        debug($devices->first());
            debug(auth()->user());
        return response()->json($devices);
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
        // debug(auth()->user());
        // debug($request->all());
        $this->authorize('create-device', [$request->company, $request->pks]);
        $response = new stdClass();

        $device = Device::find($request->kode_device);

        if($device == null){

            $device = Device::create([
                "COMPANY_CODE" => $request->company,
                "KODE_PKS" => $request->pks,
                "KODE_STASIUN" => $request->stasiun,
                "KODE_DEVICE" => $request->kode_device,
                "KETERANGAN" => $request->keterangan
            ]);
            $device->KODE_DEVICE = $request->kode_device;

            $response->status = true;
            $response->data = $device;
        }else{
            $response->status = false;
            $response->message = "Device dengan kode ".$request->kode_device." sudah ada!";
        }
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function show(Device $device)
    {
        dump($device);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function edit(Device $device)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Device $device)
    {
        $device->IS_ACTIVE = $request->IS_ACTIVE;
        $device->save();

        $response = new stdClass();

        $response->status = true;
        $response->data = $device;

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function destroy(Device $device)
    {
        //
    }

    public function current($pks) {
        $data = DB::select("exec USP_GETDATA_INDIKATOR_TODAY_BY_PKS 'MILLENA', '19045EB0-7E99-4796-882E-D77884B5BF30', $pks");
        return response()->json($data, 200, [], JSON_NUMERIC_CHECK);
    }

    public function all_boiler(){
        $data = DB::select("exec USP_ALL_INDIKATOR_BOILER");
        return response()->json($data, 200, [], JSON_NUMERIC_CHECK);

    }

    public function workhour(Request $request, $deviceId) {
        $date = $date = $request->date ?: date('Y-m-d');
        $data = DB::select("EXEC USP_GET_WORK_HOUR_BY_DEVICE '$deviceId', '$date'");
        return response()->json($data, 200, [], JSON_NUMERIC_CHECK);
    }

    public function indicator(Request $request, $deviceId) {
        $date = $date = $request->date ?: date('Y-m-d');
        $data = DB::select("EXEC USP_GET_DATA_PER_DAY_BY_DEVICE 'MILLENA', '19045EB0-7E99-4796-882E-D77884B5BF30', '$deviceId', '$date'");
        return response()->json($data, 200, [], JSON_NUMERIC_CHECK);
    }

    public function insert(Request $request) {
        if (!isset($request->device_id, $request->tekanan, $request->suhu, $request->arus, $request->ph)) {
            return response()->json([
                "errorMessage" => "Data not complete"
            ], 200, [], JSON_NUMERIC_CHECK);
        }
        $exec = DB::insert("EXEC USP_INSERT_DATA_BY_DEVICE '$request->device_id', $request->tekanan, $request->suhu, $request->arus, $request->ph");
        return response()->json([
            "message" => "Insert Success"
        ], 200, [], JSON_NUMERIC_CHECK);
    }
}
