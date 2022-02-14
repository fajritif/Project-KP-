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
            return response()->json($data);
        }
        else{
            return view('user.index');
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

            $response = new stdClass();

            $user = DB::select("exec USP_GET_USER_BY_NIK_SAP '".$request->nik_sap."'")[0];
            
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
