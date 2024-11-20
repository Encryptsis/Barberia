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
        'usr_points',
        'free_appointment_available', // Añadido
    ];

    protected $hidden = [
        'usr_password',
        'usr_recuperacion_token',
        'usr_recuperacion_expira',
    ];

    // Especificar el campo de contraseña personalizado
    protected $authPasswordName = 'usr_password';

    // app/Models/Usuario.php

    public function isAdmin()
    {
        return $this->role && strcasecmp($this->role->rol_nombre, 'Administrador') === 0;
    }

// Usuario.php

// app/Models/Usuario.php

public function addPoints($amount, $description)
{
    // Crear una transacción de puntos
    PtsTransaction::create([
        'pts_usr_id' => $this->usr_id,
        'pts_type' => $amount >= 0 ? 'earn' : 'redeem', // 'earn' para ganar puntos, 'redeem' para canjear
        'pts_amount' => $amount,
        'pts_description' => $description,
        'pts_created_at' => now(),
    ]);

    // Actualizar el saldo de puntos del usuario
    $this->usr_points += $amount;
    $this->save();
}


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

        // Relación con servicios
    public function servicios()
    {
        return $this->belongsToMany(
            Servicio::class,
            'usuarios_servicios',
            'usr_srv_usuario_id',    // Clave foránea en la tabla pivote para Usuario
            'usr_srv_servicio_id'    // Clave foránea en la tabla pivote para Servicio
        );
    }

}
