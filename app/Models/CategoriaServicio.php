<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaServicio extends Model
{
    use HasFactory;

    protected $primaryKey = 'cat_id';
    protected $table = 'categorias_servicios'; // Especifica el nombre correcto de la tabla
    public $timestamps = true;

    protected $fillable = [
        'cat_nombre',
        'cat_descripcion',
    ];

    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'srv_categoria_id', 'cat_id');
    }

    public function appointment_limit()
    {
        return $this->hasOne(AppointmentLimit::class, 'cat_id', 'cat_id');
    }
}
