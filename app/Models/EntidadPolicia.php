<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntidadPolicia extends Model
{
    use HasFactory;
    public function getUnidad()
    {
        return $this->hasOne(TipoUnidad::class, 'id', 'unidad_id');
    }
    public function getGrado()
    {
        return $this->hasOne(TipoGrado::class, 'id', 'grado_id');
    }
}
