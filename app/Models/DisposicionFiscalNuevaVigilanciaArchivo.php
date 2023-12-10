<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposicionFiscalNuevaVigilanciaArchivo extends Model
{
    use HasFactory;
    public function getTipoArchivo()
    {
        return $this->hasOne(TipoContenido::class, 'id', 'ta_id');
    }
}
