<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispocicionFiscal extends Model
{
    use HasFactory;

    public function getFiscal()
    {
        return $this->hasOne(EntidadFiscal::class, 'id', 'fiscal_responsable_id');
    }
    public function getFiscalAdjunto()
    {
        return $this->hasOne(EntidadFiscal::class, 'id', 'fiscal_asistente_id');
    }
    public function getPlazo()
    {
        return $this->hasOne(TipoPlazo::class, 'id', 'plazo_id');
    }
    public function getEstado()
    {
        return $this->hasOne(EstadoDisposicionFiscal::class, 'id', 'estado_id');
    }
    public function getOficial()
    {
        return $this->hasOne(EntidadPolicia::class, 'id', 'oficial_acargo_id');
    }
    public function getNuevaVigilancia()
    {
        return $this->hasMany(DisposicionFiscalNuevaVigilancia::class, 'df_id', 'id');
    }
}


