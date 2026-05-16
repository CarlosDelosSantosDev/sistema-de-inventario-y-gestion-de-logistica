<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // métricas básicas
        $cajas_stock = DB::table('paquetes')->where('estatus_caja', 'En Stock')->count();
        $cajas_transito = DB::table('paquetes')->where('estatus_caja', 'Asignada a Viaje')->count();
        $cajas_entregadas = DB::table('paquetes')->where('estatus_caja', 'Entregada')->count();

        $viajes_programados = DB::table('viajes')->where('estatus_viaje', 'Programado')->count();
        $viajes_transito = DB::table('viajes')->where('estatus_viaje', 'En Tránsito')->count();
        $viajes_completados = DB::table('viajes')->where('estatus_viaje', 'Completado')->count();

        $usuarios_activos = DB::table('users')->where('estatus_usuario', 'Activo')->count();
        $usuarios_inactivos = DB::table('users')->where('estatus_usuario', 'Inactivo')->count();

        return view('dashboard', compact(
            'cajas_stock',
            'cajas_transito',
            'cajas_entregadas',
            'viajes_programados',
            'viajes_transito',
            'viajes_completados',
            'usuarios_activos',
            'usuarios_inactivos'
        ));
    }
}