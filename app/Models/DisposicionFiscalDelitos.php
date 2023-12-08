<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposicionFiscalDelitos extends Model
{
    use HasFactory;

    public function geTipoDelitos()
    {
        return $this->hasOne(TipoDelitos::class, 'id', 'delitos_id');
    }

}
