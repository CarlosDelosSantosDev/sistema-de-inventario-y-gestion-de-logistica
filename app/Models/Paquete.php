<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paquete extends Model
{
    protected $table = 'paquetes';
    protected $primaryKey = 'id_caja';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_caja',
        'id_producto',
        'cantidad_piezas',
        'peso_total',
        'fecha_empaque',
        'origen',
        'estatus_caja',
        'activo',
    ];
    public function detalleViaje()
    {
    return $this->hasOne(\App\Models\DetalleViaje::class, 'id_caja', 'id_caja');
    }
}