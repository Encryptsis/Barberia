<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $primaryKey = 'rol_id';
    public $timestamps = false;

    protected $fillable = [
        'rol_nombre',
        'rol_descripcion',
        'rol_nivel',
        'rol_activo',
    ];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'usr_rol_id', 'rol_id');
    }
}
