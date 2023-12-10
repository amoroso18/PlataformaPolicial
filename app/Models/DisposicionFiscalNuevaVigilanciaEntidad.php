<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposicionFiscalNuevaVigilanciaEntidad extends Model
{
    use HasFactory;
    public function getEntidad()
    {
        return $this->hasOne(EntidadPolicia::class, 'TipoEntidad', 'ta_id');
    }
    public function getNuevaVigilanciaArchivo()
    {
        return $this->hasMany(DisposicionFiscalNuevaVigilanciaArchivo::class, 'dfnve_id', 'id');
    }

}
