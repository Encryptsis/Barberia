<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $primaryKey = 'log_id';
    public $timestamps = false;

    protected $fillable = [
        'log_usuario_id',
        'log_accion',
        'log_tipo_accion_id',
        'log_descripcion',
        'log_fecha',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'log_usuario_id', 'usr_id');
    }

    public function tipoAccion()
    {
        return $this->belongsTo(TipoAccion::class, 'log_tipo_accion_id', 'tipo_accion_id');
    }
}
