<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaqueteApiController extends Controller
{
    // Método para que la WEB y el CELULAR vean la info
    public function show($id)
    {
        $paquete = DB::table('paquetes as p')
            ->leftJoin('productos as prod', 'p.id_producto', '=', 'prod.id_producto')
            ->select('p.*', 'prod.nombre_producto', 'prod.categoria')
            ->where('p.id_caja', $id)
            ->first();

        if (!$paquete) {
            return response()->json(['error' => 'No encontrado'], 404);
        }
        return response()->json($paquete);
    }

    // NUEVO: Este método "enciende" la fila en tu tabla de la página web
    public function confirmarEscaneo(Request $request) 
    {
        $id = $request->input('id_caja');
        
        DB::table('paquetes')
            ->where('id_caja', $id)
            ->update([
                'escaneado' => 1, // <--- CAMBIO AQUÍ
                'estatus_caja' => 'Escaneado por Dron'
            ]);

        return response()->json(['success' => true]);
    }

    public function toggleDrone(Request $request)
    {
        $estado = $request->input('estado', false);
        cache(['drone_estado' => $estado], 3600);
        return response()->json(['success' => true, 'estado' => $estado]);
    }

    public function getDroneEstado()
    {
        return response()->json(['activo' => cache('drone_estado', false)]);
    }
}