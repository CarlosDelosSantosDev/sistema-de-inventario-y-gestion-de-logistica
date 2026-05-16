<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Viaje extends Model
{
    protected $table = 'viajes';
    protected $primaryKey = 'id_viaje';
    public $incrementing = false; // tu PK es varchar
    public $timestamps = false;

    protected $fillable = [
        'id_viaje',         
        'id_vehiculo',
        'id_conductor',
        'destino_final',
        'fecha_salida',
        'estatus_viaje'
    ];

    // Relación con Vehiculo
    public function vehiculo() {
        return $this->belongsTo(Vehiculo::class, 'id_vehiculo', 'id_vehiculo');
    }

    // Relación con Conductor
    public function conductor() {
        return $this->belongsTo(Conductor::class, 'id_conductor', 'id_conductor');
    }

    // Relación con Cajas (muchos a muchos)
    public function cajas() {
        return $this->belongsToMany(Caja::class, 'detalle_viaje', 'id_viaje', 'id_caja');
    }

    // Relación con GPS
    public function gps() {
        return $this->hasMany(GpsViaje::class, 'id_viaje', 'id_viaje');
    }
}
