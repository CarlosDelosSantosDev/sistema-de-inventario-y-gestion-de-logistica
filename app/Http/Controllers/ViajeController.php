<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Viaje;
use App\Models\Vehiculo;
use App\Models\Conductor;

class ViajeController extends Controller
{
    /**
     * Mostrar todos los viajes.
     */
    public function index()
    {
        $viajes = Viaje::all();
        return view('viajes.index', compact('viajes'));
    }

    /**
     * Mostrar el formulario para crear un nuevo viaje.
     */
    public function create()
    {
        $vehiculos = Vehiculo::where('estatus_vehiculo', 'Disponible')->get();
        $conductores = Conductor::where('estatus_chofer', 'Disponible')->get();

        return view('viajes.create', compact('vehiculos', 'conductores'));
    }

    /**
     * Guardar un nuevo viaje en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_viaje' => 'required|string|unique:viajes,id_viaje',
            'id_vehiculo' => 'required|string',
            'id_conductor' => 'required|string',
            'destino_final' => 'required|string',
            'fecha_salida' => 'required|date',
            'estatus_viaje' => 'required|in:Programado,En Tránsito,Completado',
        ]);

        Viaje::create($request->all());

        return redirect()->route('viajes.index')->with('success', 'Viaje creado correctamente.');
    }

    /**
     * Mostrar los detalles de un viaje específico.
     */
    public function show($id)
    {
        $viaje = Viaje::findOrFail($id);
        return view('viajes.show', compact('viaje'));
    }

    /**
     * Mostrar el formulario para editar un viaje existente.
     */
    public function edit($id)
    {
        $viaje = Viaje::findOrFail($id);

        // Traer vehículos disponibles o el asignado actualmente
        $vehiculos = Vehiculo::where('estatus_vehiculo', 'Disponible')
                        ->orWhere('id_vehiculo', $viaje->id_vehiculo)
                        ->get();

        // Traer conductores disponibles o el asignado actualmente
        $conductores = Conductor::where('estatus_chofer', 'Disponible')
                        ->orWhere('id_conductor', $viaje->id_conductor)
                        ->get();

        return view('viajes.edit', compact('viaje', 'vehiculos', 'conductores'));
    }

    /**
     * Actualizar un viaje existente en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $viaje = Viaje::findOrFail($id);

        $request->validate([
            'id_vehiculo' => 'required|string',
            'id_conductor' => 'required|string',
            'destino_final' => 'required|string',
            'fecha_salida' => 'required|date',
            'estatus_viaje' => 'required|in:Programado,En Tránsito,Completado',
        ]);

        $viaje->update($request->all());

        return redirect()->route('viajes.index')->with('success', 'Viaje actualizado correctamente.');
    }

    /**
     * Eliminar un viaje de la base de datos.
     */
    public function destroy($id)
    {
        $viaje = Viaje::findOrFail($id);
        $viaje->delete();

        return redirect()->route('viajes.index')->with('success', 'Viaje eliminado correctamente.');
    }
}
