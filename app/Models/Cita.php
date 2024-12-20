<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $primaryKey = 'cta_id';
    protected $table = 'citas';

    public $timestamps = true;
    // Definir nombres personalizados para created_at y updated_at
    const CREATED_AT = 'cta_created_at';
    const UPDATED_AT = 'cta_updated_at';

    protected $fillable = [
        'cta_cliente_id',
        'cta_profesional_id',
        'cta_fecha',
        'cta_hora',
        'cta_estado_id',
        'cta_activa',
        'cta_arrival_confirmed',
        'cta_punctuality_status',
        'cta_arrival_time',
        'cta_penalty_applied',
        'cta_penalty_amount',
        'cta_is_free',
    ];

    // En app/Models/Cita.php

    public function getRouteKeyName()
    {
        return 'cta_id';
    }


    public function cliente()
    {
        return $this->belongsTo(Usuario::class, 'cta_cliente_id', 'usr_id');
    }

    public function profesional()
    {
        return $this->belongsTo(Usuario::class, 'cta_profesional_id', 'usr_id');
    }

    public function estadoCita()
    {
        return $this->belongsTo(EstadoCita::class, 'cta_estado_id', 'estado_id');
    }

    public function servicios()
    {
        return $this->belongsToMany(
            Servicio::class,
            'citas_servicios',
            'cta_srv_cita_id',
            'cta_srv_servicio_id'
        );
    }
    

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'pago_cita_id', 'cta_id');
    }

    public function pun_penalties()
    {
        return $this->hasMany(PunPenalty::class, 'pun_cta_id', 'cta_id');
    }
}
