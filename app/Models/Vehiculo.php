<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'vehiculos';
    protected $primaryKey = 'id_vehiculo';
    public $incrementing = false; // varchar
    public $timestamps = false;

    protected $fillable = [
        'modelo_marca',
        'capacidad_carga',
        'kilometraje_actual',
        'estatus_vehiculo',
        'id_usuario_asignado'
    ];

    // Relación con Viajes
    public function viajes() {
        return $this->hasMany(Viaje::class, 'id_vehiculo', 'id_vehiculo');
    }
}
