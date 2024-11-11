<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoAccion extends Model
{
    use HasFactory;

    protected $primaryKey = 'tipo_accion_id';
    public $timestamps = false;

    protected $fillable = [
        'tipo_accion_descripcion',
        'tipo_accion_nombre',
    ];

    public function logs()
    {
        return $this->hasMany(Log::class, 'log_tipo_accion_id', 'tipo_accion_id');
    }
}
