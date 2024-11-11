<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodoPago extends Model
{
    use HasFactory;

    protected $primaryKey = 'pago_id';
    public $timestamps = false;

    protected $fillable = [
        'pago_nombre',
        'pago_descripcion',
        'pago_activo',
    ];

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'pago_metodo_id', 'pago_id');
    }
}
