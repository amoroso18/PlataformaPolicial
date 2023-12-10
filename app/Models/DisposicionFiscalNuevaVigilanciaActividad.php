<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposicionFiscalNuevaVigilanciaActividad extends Model
{
    use HasFactory;
    public function getNuevaVigilanciaEntidad()
    {
        return $this->hasMany(DisposicionFiscalNuevaVigilanciaEntidad::class, 'dfnva_id', 'id');
    }
}
