<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    protected $table = 'personal_choferes';
    protected $primaryKey = 'id_conductor';
    public $incrementing = false; // varchar
    public $timestamps = false;

    protected $fillable = [
        'nombre_completo',
        'numero_licencia',
        'telefono',
        'estatus_chofer'
    ];

    // Relación con Viajes
    public function viajes() {
        return $this->hasMany(Viaje::class, 'id_conductor', 'id_conductor');
    }
}
