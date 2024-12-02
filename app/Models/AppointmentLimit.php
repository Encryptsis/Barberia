<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentLimit extends Model
{
    use HasFactory;

    protected $primaryKey = 'limit_id';

    protected $fillable = [
        'cat_id',
        'limite_diario',
    ];

    public function categoria_servicio()
    {
        return $this->belongsTo(CategoriaServicio::class, 'cat_id', 'cat_id');
    }
}
