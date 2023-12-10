<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposicionFiscalNuevaVigilanciaEntidad extends Model
{
    use HasFactory;
    public function getNuevaVigilanciaArchivo()
    {
        return $this->hasMany(DisposicionFiscalNuevaVigilanciaArchivo::class, 'dfnve_id', 'id');
    }
}
