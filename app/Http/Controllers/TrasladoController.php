<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class TrasladoController extends Controller
{
    public function index()
    {
        return view('traslados.index');
    }
    public function cajasPorViaje()
{
    return view('reportes.cajas_por_viaje');
}

    public function cajasPorViajeData()
    {
    $fecha = request('fecha'); // opcional

    $q = DB::table('viajes as v')
        ->join('vehiculos as ve', 've.id_vehiculo', '=', 'v.id_vehiculo')
        ->join('personal_choferes as ch', 'ch.id_conductor', '=', 'v.id_conductor')
        ->leftJoin('detalle_viaje as dv', 'dv.id_viaje', '=', 'v.id_viaje')
        ->selectRaw("
            v.id_viaje,
            v.fecha_salida,
            v.destino_final,
            v.estatus_viaje,
            ve.id_vehiculo,
            ve.modelo_marca,
            ch.id_conductor,
            ch.nombre_completo,
            COUNT(dv.id_caja) as total_cajas
        ")
        ->groupBy(
            'v.id_viaje', 'v.fecha_salida', 'v.destino_final', 'v.estatus_viaje',
            've.id_vehiculo', 've.modelo_marca',
            'ch.id_conductor', 'ch.nombre_completo'
        )
        ->orderByDesc('v.fecha_salida');

    if ($fecha) {
        $q->whereDate('v.fecha_salida', $fecha);
    }

    $rows = $q->get();

    return response()->json($rows);
}
    public function salidas()
    {
        return view('reportes.salidas');
    }

    public function salidasData()
    {
    $fecha = request('fecha'); // formato YYYY-MM-DD

    if (!$fecha) {
        return response()->json([
            'total' => 0,
            'viajes' => []
        ]);
    }

    $viajes = DB::table('viajes as v')
        ->join('vehiculos as ve', 've.id_vehiculo', '=', 'v.id_vehiculo')
        ->join('personal_choferes as ch', 'ch.id_conductor', '=', 'v.id_conductor')
        ->whereDate('v.fecha_salida', $fecha)
        ->select([
            'v.id_viaje',
            'v.fecha_salida',
            'v.destino_final',
            'v.estatus_viaje',
            've.id_vehiculo',
            've.modelo_marca',
            'ch.id_conductor',
            'ch.nombre_completo',
        ])
        ->orderBy('v.fecha_salida', 'asc')
        ->get();

    // “camiones” = viajes (cada viaje usa un vehículo)
    return response()->json([
        'total' => $viajes->count(),
        'viajes' => $viajes
    ]);
}
    // Endpoint “tiempo real” (regresa JSON para refrescar tabla)
    public function data()
    {
    $user = request()->user();

    // Si es Chofer, solo puede ver el vehículo asignado a su usuario
    $idVehiculoChofer = null;

    if ($user && $user->rol === 'Chofer') {
        $veh = DB::table('vehiculos')
            ->where('id_usuario_asignado', $user->id)
            ->select('id_vehiculo')
            ->first();

        // Si no tiene vehículo asignado, no ve nada
        if (!$veh) {
            return response()->json([]);
        }

        $idVehiculoChofer = $veh->id_vehiculo;
    }

    $rows = DB::table('paquetes as p')
        ->join('detalle_viaje as dv', 'dv.id_caja', '=', 'p.id_caja')
        ->join('viajes as v', 'v.id_viaje', '=', 'dv.id_viaje')
        ->join('vehiculos as ve', 've.id_vehiculo', '=', 'v.id_vehiculo')
        ->join('personal_choferes as ch', 'ch.id_conductor', '=', 'v.id_conductor')
        ->leftJoin('gps_viaje as g', function ($join) {
            $join->on('g.id_viaje', '=', 'v.id_viaje')
                ->whereRaw('g.fecha_hora = (SELECT MAX(g2.fecha_hora) FROM gps_viaje g2 WHERE g2.id_viaje = v.id_viaje)');
        })
        ->where('p.estatus_caja', 'Asignada a Viaje')
        ->where('v.estatus_viaje', 'En Tránsito')

        // ✅ Filtro solo para Chofer
        ->when($idVehiculoChofer, function ($q) use ($idVehiculoChofer) {
            $q->where('v.id_vehiculo', $idVehiculoChofer);
        })

        ->select([
            'p.id_caja',
            'p.id_producto',
            'p.origen',
            'v.id_viaje',
            'v.destino_final',
            'v.fecha_salida',
            've.id_vehiculo',
            've.modelo_marca',
            'ch.id_conductor',
            'ch.nombre_completo',
            'g.lat',
            'g.lon',
            'g.velocidad',
            'g.fecha_hora',
        ])
        ->orderByDesc('v.fecha_salida')
        ->get();

    return response()->json($rows);
}
}