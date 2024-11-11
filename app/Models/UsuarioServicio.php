<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioServicio extends Model
{
    use HasFactory;

    protected $table = 'usuarios_servicios';
    public $timestamps = false;

    protected $fillable = [
        'usr_srv_usuario_id',
        'usr_srv_servicio_id',
        'usr_srv_notas',
    ];

    protected $primaryKey = ['usr_srv_usuario_id', 'usr_srv_servicio_id'];
    public $incrementing = false;

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usr_srv_usuario_id', 'usr_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'usr_srv_servicio_id', 'srv_id');
    }
}
