<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPerfil extends Model
{
    use HasFactory;

    public function getUsuarios()
    {
        return $this->hasMany(User::class, 'perfil_id', 'id');
    }
}
