<?php

namespace App\Providers;

use App\HoldingAuth;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\DB;

class HoldingUserProvider implements UserProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function retrieveById($identifier){
        $data = DB::select("exec USP_GET_USER_BY_NIK_SAP '".$identifier."'");
        $data = $data[0]; // ambil data pertama saja
        $user = new HoldingAuth;
        foreach($data as $key=>$value){
            $user->$key = $value;
        }
        return $user;
    }

    public function retrieveByToken($identifier, $token){

    }

    public function updateRememberToken(Authenticatable $user, $token){

    }

    public function retrieveByCredentials(array $credentials){
        $data = DB::select("exec USP_GET_USER_BY_NIK_SAP '".$credentials['NIK_SAP']."'");
        $data = $data[0]; // ambil data pertama saja
        $user = new HoldingAuth;
        foreach($data as $key=>$value){
            $user->$key = $value;
        }
        return $user;
    }

    public function validateCredentials(Authenticatable $user, array $credentials){
        $data = DB::select("exec USP_VALIDATE_LOGIN_MILLENA 'MILLENA', '19045EB0-7E99-4796-882E-D77884B5BF30', '".$user->NIK_SAP."','".$credentials['USER_PASSWORD']."'");
        $data = $data[0]; // ambil data pertama saja

        // jika data ditemukan
        if($data->NIK_SAP){
            return true;
        }else{
            return false;
        }
    }
}
