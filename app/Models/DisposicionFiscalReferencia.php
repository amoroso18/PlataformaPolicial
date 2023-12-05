<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposicionFiscalReferencia extends Model
{
    use HasFactory;

    public function geTipoDocumentosReferencia()
    {
        return $this->hasOne(TipoDocumentosReferencia::class, 'id', 'documentos_id');
    }
}
