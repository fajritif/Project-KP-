<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        

        if(request()->ajax()){
            $data = collect(DB::select("exec USP_GET_USERS ")); 

            if(auth()->user()->ROLEID == 'ADMIN_ANPER'){
                $data = $data->filter(function($d){
                    //debug(sprintf("%s vs %s",auth()->user()->PTPN,$d->PTPN));
                    return auth()->user()->PTPN == $d->PTPN;
                });
            }
            
            if(auth()->user()->ROLEID == 'ADMIN_UNIT'){
                $data = $data->filter(function($d){
                    return auth()->user()->PTPN == $d->PTPN && auth()->user()->PSA == $d->PSA;
                });
            }
            
            return response()->json($data->values());
        }
        else{
            $roles = ['ADMIN_UNIT','ADMIN_ANPER','ADMIN_HOLDING','VIEWER_UNIT','VIEWER_ANPER','VIEWER_HOLDING'];
            return view('user.index', compact('roles'));
        }
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id=false)
    {

        if(request()->ajax()){
            debug($request->all());
            $response = new stdClass();

            $user = DB::select("exec USP_GET_USER_BY_NIK_SAP '".$request->nik_sap."'")[0];
            $this->authorize('update-role', [$request->roleid, $user]); 
            
            if($user->NIK_SAP){
                $response->status = true;
                if($user->ROLEID == NULL){
                    $user->ROLEID = $request->roleid;
                    $response->message = 'User berhasil ditambahkan';
                    $response->action = 'add_row';
                    $response->data =  $user;
                }else{
                    $user->ROLEID = $request->roleid;
                    $response->message = "User dengan NIK SAP ".$request->nik_sap." sudah ada. ROLE akan diganti menjadi ".$request->roleid;
                    $response->action = 'update_row';
                    $response->data =  $user;
                }
            }else{
                $response->status = false;
                $response->message = "User dengan NIK SAP '".$request->nik_sap."' tidak ditemukan";
                $response->action = false;
            }

            if ($request->roleid == ''){
                DB::statement(sprintf("exec USP_UPDATE_ROLE_USER '%s',NULL,NULL", $request->nik_sap)); 
                $response->action = 'remove_row';
            }else{
                DB::statement(sprintf("exec USP_UPDATE_ROLE_USER '%s','%s',NULL", $request->nik_sap, $request->roleid)); 
            }

            return response()->json($response);
        }
        else{
            return 'uhui';
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = false)
    {
        DB::statement(sprintf("exec USP_UPDATE_ROLE_USER '%s',NULL,NULL", request('nik_sap'))); 
    }
}
