<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;

    protected $table='M_COMPANY';
    protected $primaryKey = 'KODE';
    protected $keyType = 'string';
    protected $appends = ['nama_panjang'];

    public function pks(){
        return $this->hasMany(Pks::class, 'COMPANY_CODE')
            ->where(function($query){
                $query->where('NAMA','LIKE','%PKS%')
                ->orWhere('NAMA','LIKE','%SAWIT%');
            });
            
    }    

    public function getNamaPanjangAttribute()
    {
        return Str::of($this->NAMA)->replace('PTPN','PT Perkebunan Nusantara');
    }
}
