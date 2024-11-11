<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoPago extends Model
{
    use HasFactory;

    protected $primaryKey = 'estado_pago_id';
    public $timestamps = false;

    protected $fillable = [
        'estado_pago_nombre',
        'estado_pago_descripcion',
    ];

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'pago_estado_pago_id', 'estado_pago_id');
    }
}
