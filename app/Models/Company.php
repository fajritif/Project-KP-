<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table='M_COMPANY';
    protected $primaryKey = 'KODE';
    protected $keyType = 'string';

    public function pks(){
        return $this->hasMany(Pks::class, 'COMPANY_CODE')->where('NAMA','LIKE','%PKS%')
            ->orWhere('NAMA','LIKE','%SAWIT%');
    }
}
