<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function getUnidad()
    {
        return $this->hasOne(TipoUnidad::class, 'id', 'unidad_id');
    }
    public function getPerfil()
    {
        return $this->hasOne(TipoPerfil::class, 'id', 'perfil_id');
    }
    public function getGrado()
    {
        return $this->hasOne(TipoGrado::class, 'id', 'estado_id');
    }
    public function getEstado()
    {
        return $this->hasOne(EstadoUsuario::class, 'id', 'estado_id');
    }
    public function getHistorialConexion()
    {
        return $this->hasMany(AuditoriaActividad::class, 'users_id', 'id')->orderBy('id', 'desc');
    }
    public function getHistorialAuditoria()
    {
        return $this->hasMany(AuditoriaUsuario::class, 'users_id', 'id')->orderBy('id', 'desc');
    }
}
