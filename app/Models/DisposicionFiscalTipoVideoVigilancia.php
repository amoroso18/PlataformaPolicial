<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposicionFiscalTipoVideoVigilancia extends Model
{
    use HasFactory;
    public function geTipoVideovigilancia()
    {
        return $this->hasOne(TipoVideoVigilancia::class, 'id', 'vv_id');
    }
}
