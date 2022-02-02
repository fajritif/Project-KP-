<?php
namespace App;

use Illuminate\Contracts\Auth\Authenticatable;

class HoldingAuth implements Authenticatable
{
    public function getAuthIdentifierName(){
        return 'NIK_SAP';
    }

    public function getAuthIdentifier()
    {
        return $this->NIK_SAP;
    }
    public function getAuthPassword(){}
    public function getRememberToken(){}
    public function setRememberToken($value){}
    public function getRememberTokenName(){}

}

?>