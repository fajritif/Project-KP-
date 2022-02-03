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

    public function getNama2Attribute()
    {
        return Str::of(Str::title($this->NAMA))->replace('Pks','PKS')->replace('Pabrik Kelapa Sawit', 'PKS');
    }
}
