<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    use HasFactory;

    protected $fillable = [
        'pks_id',
        'name',
        'description'
    ];

    public function pks()
    {
        return $this->belongsTo(Pks::class, 'pks_id', 'KODE');
    }
}
