<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposicionFiscalNuevaVigilanciaEntidad extends Model
{
    use HasFactory;

    protected $fillable = [
        'entidads_id',
    ];

    public function getTipoEntidad()
    {
        return $this->hasOne(TipoEntidad::class, 'id', 'entidads_id');
    }
    // public function getEntidad()
    // {
    //     return $this->hasOne(EntidadPersona::class, 'id', 'entidad_id')->first();
    //     return $this->hasOne(EntidadVehiculos::class, 'id', 'entidad_id')->first();
    //     return $this->hasOne(EntidadInmueble::class, 'id', 'entidad_id')->first();
    // }

    public function getNuevaVigilanciaArchivo()
    {
        return $this->hasMany(DisposicionFiscalNuevaVigilanciaArchivo::class, 'dfnve_id', 'id');
    }
}
