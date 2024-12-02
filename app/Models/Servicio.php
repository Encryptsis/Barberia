<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $primaryKey = 'srv_id';
    public $timestamps = false;

    protected $fillable = [
        'srv_nombre',
        'srv_descripcion',
        'srv_precio',
        'srv_duracion',
        'srv_disponible',
        'srv_imagen',
        'srv_categoria_id', // Añadido para la relación con CategoriaServicio
    ];

    /**
     * Relación con Cita.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function citas()
    {
        return $this->belongsToMany(
            Cita::class,
            'citas_servicios',
            'cta_srv_servicio_id',
            'cta_srv_cita_id'
        )
        ->withTimestamps();
    }

    /**
     * Relación con Usuario.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function usuarios()
    {
        return $this->belongsToMany(
            Usuario::class,
            'usuarios_servicios',
            'usr_srv_servicio_id',
            'usr_srv_usuario_id'
        )
        ->withPivot('usr_srv_notas');
    }

    /**
     * Relación con CategoriaServicio.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoria_servicio()
    {
        return $this->belongsTo(CategoriaServicio::class, 'srv_categoria_id', 'cat_id');
    }
}
