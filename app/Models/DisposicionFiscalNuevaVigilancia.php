<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposicionFiscalNuevaVigilancia extends Model
{
    use HasFactory;

    public function getNuevaVigilanciaActividad()
    {
        return $this->hasMany(DisposicionFiscalNuevaVigilanciaActividad::class, 'dfnv_id', 'id');
    }
}
