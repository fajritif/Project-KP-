<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table='M_DEVICE';
    protected $primaryKey = 'KODE_DEVICE';
    protected $keyType = 'string';
    protected $appends = ['kode_prefix', 'nomor'];
    protected $guarded = [];

    public function getKodePrefixAttribute()
    {
        // <td>{{ $d->KODE_DEVICE }}</td>
        //         <td>{{ $d->COMPANY_CODE}}</td>
        //         <td>{{ $d->KODE_PKS}}</td>
        //         <td>{{ $d->KODE_STASIUN}}</td>
        return sprintf("%s-%s-%s-", $this->COMPANY_CODE, $this->KODE_PKS, $this->KODE_STASIUN );
    }

    public function getNomorAttribute()
    {
        return (int)explode('-', $this->KODE_DEVICE)[3];
    }
}
