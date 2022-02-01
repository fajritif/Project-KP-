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
}
