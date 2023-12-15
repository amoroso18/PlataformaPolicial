<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditoriaNotificaciones extends Model
{
    use HasFactory;
    public function getOficial()
    {
        return $this->hasOne(EntidadPolicia::class, 'id', 'users_id');
    }
}
