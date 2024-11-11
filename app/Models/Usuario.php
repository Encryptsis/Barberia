<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Si usas autenticación
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'usr_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    

    protected $fillable = [
        'usr_username',
        'usr_password',
        'usr_nombre_completo',
        'usr_correo_electronico',
        'usr_telefono',
        'usr_foto_perfil',
        'usr_activo',
        'usr_rol_id',
        'usr_recuperacion_token',
        'usr_recuperacion_expira',
        'usr_ultimo_acceso',
    ];

    protected $hidden = [
        'usr_password',
        'usr_recuperacion_token',
        'usr_recuperacion_expira',
    ];

    // Especificar el campo de contraseña personalizado
    protected $authPasswordName = 'usr_password';

    public function getAuthPassword()
    {
        return $this->usr_password;
    }
    
    public function role()
    {
        return $this->belongsTo(Role::class, 'usr_rol_id', 'rol_id');
    }

    public function citasCliente()
    {
        return $this->hasMany(Cita::class, 'cta_cliente_id', 'usr_id');
    }

    public function citasProfesional()
    {
        return $this->hasMany(Cita::class, 'cta_profesional_id', 'usr_id');
    }

    public function logs()
    {
        return $this->hasMany(Log::class, 'log_usuario_id', 'usr_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'pago_usuario_id', 'usr_id');
    }

    public function usuariosServicios()
    {
        return $this->belongsToMany(Servicio::class, 'usuarios_servicios', 'usr_srv_usuario_id', 'usr_srv_servicio_id')
                    ->withPivot('usr_srv_notas')
                    ->withTimestamps();
    }
}
