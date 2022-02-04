<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pks extends Model
{
    use HasFactory;

    protected $table='M_PKS';
    protected $primaryKey = 'KODE';
    protected $keyType = 'string';
    protected $appends = ['nama2'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'COMPANY_CODE', 'KODE');
    }

    public function getNama2Attribute()
    {
        return Str::of(Str::title($this->NAMA))->replace('Pks','PKS')->replace('Pabrik Kelapa Sawit', 'PKS');
    }

    public function getNamaCompanyAttribute()
    {
        return $this->company->NAMA;
    }

    public function getNamaCompanyPanjangAttribute()
    {
        return Str::of($this->company->NAMA)->replace('PTPN','PT Perkebunan Nusantara');
    }

}
