<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleViaje extends Model
{
    protected $table = 'detalle_viaje';
    protected $primaryKey = 'id_detalle';
    public $timestamps = false;

    protected $fillable = [
        'id_viaje',
        'id_caja',
        'confirmacion_entrega'
    ];
}