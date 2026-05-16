<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function index()
{
    $usuarios = DB::table('users as u')
        ->leftJoin('vehiculos as v', 'v.id_usuario_asignado', '=', 'u.id')
        ->select(
            'u.id',
            'u.name',
            'u.email',
            'u.rol',
            'u.estatus_usuario',
            'v.id_vehiculo as vehiculo_asignado'
        )
        ->orderBy('u.id', 'asc')
        ->get();

    return view('usuarios.index', compact('usuarios'));
}
    

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password_hash' => null, // por ahora
            'rol' => $request->rol ?? 'Chofer',
            'estatus_usuario' => $request->estatus_usuario ?? 'Activo',
        ]);

        return redirect('/usuarios')->with('msg', 'Usuario creado.');
    }

    public function edit($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'rol' => $request->rol,
            'estatus_usuario' => $request->estatus_usuario,
        ]);

        return redirect('/usuarios')->with('msg', 'Usuario actualizado.');
    }

    public function asignarVehiculo(Request $request, $id)
    {
        // Desasignar cualquier vehículo que ya tenga ese usuario (1 usuario -> 1 vehículo)
        DB::table('vehiculos')
            ->where('id_usuario_asignado', $id)
            ->update(['id_usuario_asignado' => null]);

        // Asignar el nuevo vehículo
        $idVehiculo = $request->id_vehiculo;

        if ($idVehiculo) {
            DB::table('vehiculos')
                ->where('id_vehiculo', $idVehiculo)
                ->update(['id_usuario_asignado' => $id]);
        }

        return redirect('/usuarios')->with('msg', 'Vehículo asignado.');
    }
}