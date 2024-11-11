<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $primaryKey = 'pago_transaccion_id';
    public $timestamps = false;

    protected $fillable = [
        'pago_cita_id',
        'pago_usuario_id',
        'pago_metodo_id',
        'pago_monto',
        'pago_fecha',
        'pago_estado_pago_id',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'pago_cita_id', 'cta_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'pago_usuario_id', 'usr_id');
    }

    public function metodoPago()
    {
        return $this->belongsTo(MetodoPago::class, 'pago_metodo_id', 'pago_id');
    }

    public function estadoPago()
    {
        return $this->belongsTo(EstadoPago::class, 'pago_estado_pago_id', 'estado_pago_id');
    }
}
