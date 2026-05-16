<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GpsViaje extends Model
{
    protected $table = 'gps_viaje';
    protected $primaryKey = 'id_gps';
    public $timestamps = false;

    protected $fillable = [
        'id_viaje',
        'lat',
        'lon',
        'velocidad',
        'fecha_hora'
    ];

    public function viaje() {
        return $this->belongsTo(Viaje::class, 'id_viaje', 'id_viaje');
    }
}
