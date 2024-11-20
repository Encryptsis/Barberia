<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PunPenalty extends Model
{
    use HasFactory;

    protected $table = 'pun_penalties';
    protected $primaryKey = 'pun_id';

    public $timestamps = false;

    protected $fillable = [
        'pun_cta_id',
        'pun_usr_id',
        'pun_amount',
        'pun_applied_at',
    ];

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'pun_cta_id', 'cta_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'pun_usr_id', 'usr_id');
    }
}
