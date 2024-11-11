<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoCita extends Model
{
    use HasFactory;

    protected $primaryKey = 'estado_id';
    public $timestamps = false;

    protected $fillable = [
        'estado_nombre',
        'estado_descripcion',
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'cta_estado_id', 'estado_id');
    }
}
