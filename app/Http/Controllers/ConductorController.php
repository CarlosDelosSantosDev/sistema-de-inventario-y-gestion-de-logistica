<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conductor; // Asegúrate de tener un modelo Conductor

class ConductorController extends Controller
{
    public function index()
    {
        $conductores = Conductor::all(); // Trae todos los conductores
        return view('conductores.index', compact('conductores'));
    }
}
