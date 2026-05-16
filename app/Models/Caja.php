<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    protected $table = 'paquetes';
    protected $primaryKey = 'id_caja';
    public $incrementing = false; // varchar
    public $timestamps = false;

    protected $fillable = [
        'id_producto',
        'cantidad_piezas',
        'peso_total',
        'fecha_empaque',
        'origen',
        'estatus_caja',
        'activo'
    ];

    // Relación con Viajes (muchos a muchos)
    public function viajes() {
        return $this->belongsToMany(Viaje::class, 'detalle_viaje', 'id_caja', 'id_viaje');
    }

    // Relación con Movimientos
    public function movimientos() {
        return $this->hasMany(MovimientoHistorial::class, 'id_caja', 'id_caja');
    }
}
