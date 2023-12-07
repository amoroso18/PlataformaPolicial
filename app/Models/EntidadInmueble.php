<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntidadInmueble extends Model
{
    use HasFactory;
    public function getTipoInmueble()
    {
        return $this->hasOne(TipoInmueble::class, 'id', 'inmuebles_id');
    }
}
