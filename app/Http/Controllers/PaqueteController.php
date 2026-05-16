<?php

namespace App\Http\Controllers;

use App\Models\Paquete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaqueteController extends Controller
{
    public function index()
    {
        $paquetes = DB::table('paquetes as p')
            ->leftJoin('detalle_viaje as dv', 'dv.id_caja', '=', 'p.id_caja')
            ->leftJoin('viajes as v', 'v.id_viaje', '=', 'dv.id_viaje')
            ->select('p.*', 'v.id_viaje as viaje_id', 'v.id_vehiculo as vehiculo_viaje')
            ->where('p.escaneado', 1) // <--- CAMBIO AQUÍ
            ->orderBy('p.id_caja', 'asc')
            ->get();

        $user = request()->user();
        $vehChofer = null;

        if ($user && $user->rol === 'Chofer') {
            $vehChofer = DB::table('vehiculos')
                ->where('id_usuario_asignado', $user->id)
                ->value('id_vehiculo');
        }

        return view('paquetes.index', compact('paquetes', 'vehChofer'));
    }

    // Nueva función para "Ocultar" todas de nuevo
    public function limpiarVista()
    {
        // Solo reiniciamos la bandera de escaneo, la caja sigue existiendo y activa
        DB::table('paquetes')->update(['escaneado' => 0]);
        return redirect('/paquetes')->with('msg', 'Tabla vaciada.');
    }

    public function create()
    {
        return view('paquetes.create');
    }

    public function store(Request $request)
    {
        Paquete::create([
            'id_caja' => $request->id_caja,
            'id_producto' => $request->id_producto,
            'cantidad_piezas' => $request->cantidad_piezas,
            'peso_total' => $request->peso_total,
            'fecha_empaque' => $request->fecha_empaque,
            'origen' => $request->origen,
            'estatus_caja' => 'En Stock',
            'activo' => 0,
        ]);

        return redirect('/paquetes')->with('msg', 'Caja registrada.');
    }

    public function activarMultiple(Request $request)
    {
        $ids = $request->input('ids', []);

        if (!is_array($ids) || count($ids) === 0) {
            return redirect('/paquetes')->with('msg', 'No seleccionaste ninguna caja.');
        }

        Paquete::whereIn('id_caja', $ids)->update(['activo' => 1]);

        return redirect('/paquetes')->with('msg', 'Cajas activadas correctamente.');
    }

    public function verRuta($id)
    {
        $paquete = Paquete::with('detalleViaje')->findOrFail($id);

        if (!$paquete->detalleViaje) {
            return redirect('/paquetes')->with('msg', 'La caja no está asignada a ningún viaje.');
        }

        $idViaje = $paquete->detalleViaje->id_viaje;

        // 🔐 BLOQUEO PARA CHOFER (solo ver rutas de su vehículo)
        $user = request()->user();
        if ($user && $user->rol === 'Chofer') {

            $vehChofer = DB::table('vehiculos')
                ->where('id_usuario_asignado', $user->id)
                ->value('id_vehiculo');

            $vehDelViaje = DB::table('viajes')
                ->where('id_viaje', $idViaje)
                ->value('id_vehiculo');

            if (!$vehChofer || $vehChofer !== $vehDelViaje) {
                abort(403, 'No puedes ver rutas de otros vehículos.');
            }
        }

        $viaje = DB::table('viajes')
            ->join('vehiculos', 'viajes.id_vehiculo', '=', 'vehiculos.id_vehiculo')
            ->join('personal_choferes', 'viajes.id_conductor', '=', 'personal_choferes.id_conductor')
            ->select(
                'viajes.id_viaje',
                'viajes.destino_final',
                'viajes.fecha_salida',
                'viajes.estatus_viaje',
                'vehiculos.id_vehiculo',
                'vehiculos.modelo_marca',
                'personal_choferes.id_conductor',
                'personal_choferes.nombre_completo'
            )
            ->where('viajes.id_viaje', $idViaje)
            ->first();

        $puntos = DB::table('gps_viaje')
            ->where('id_viaje', $idViaje)
            ->orderBy('fecha_hora', 'asc')
            ->get();

        return view('paquetes.ruta', compact('paquete', 'idViaje', 'puntos', 'viaje'));
    }
}