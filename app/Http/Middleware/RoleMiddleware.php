<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        // Usuario no autenticado
        if (!$user) {
            abort(401, 'No autenticado.');
        }

        // Usuario inactivo
        if (strtolower($user->estatus_usuario) !== 'activo') {
            abort(403, 'Usuario inactivo.');
        }

        // Roles permitidos (comparación insensible a mayúsculas)
        $rolesPermitidos = array_map('strtolower', $roles);
        $rolUsuario = strtolower($user->rol);

        if (!in_array($rolUsuario, $rolesPermitidos)) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
