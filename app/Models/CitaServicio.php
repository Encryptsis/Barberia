<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitaServicio extends Model
{
    use HasFactory;

    protected $table = 'citas_servicios';
    public $timestamps = false;

    protected $fillable = [
        'cta_srv_cita_id',
        'cta_srv_servicio_id',
    ];

    protected $primaryKey = ['cta_srv_cita_id', 'cta_srv_servicio_id'];
    public $incrementing = false;

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'cta_srv_cita_id', 'cta_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'cta_srv_servicio_id', 'srv_id');
    }
}
