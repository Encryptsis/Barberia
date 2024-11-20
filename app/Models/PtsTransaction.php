<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtsTransaction extends Model
{
    use HasFactory;

    protected $table = 'pts_transactions';
    protected $primaryKey = 'pts_id';

    public $timestamps = false;

    protected $fillable = [
        'pts_usr_id',
        'pts_type',
        'pts_amount',
        'pts_description',
        'pts_created_at',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'pts_usr_id', 'usr_id');
    }
}
