<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntidadPersona extends Model
{
    use HasFactory;

    public function getTipoNacionalidad()
    {
        return $this->hasOne(TipoNacionalidad::class, 'id', 'nacionalidad_id');
    }
    public function getTipoDocumentoIdentidad()
    {
        return $this->hasOne(TipoDocumentoIdentidad::class, 'id', 'documento_id');
    }

}
