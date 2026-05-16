<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    @php
        $rol = auth()->user()->rol ?? 'Chofer';
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Info rápida --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-700">
                    <b>Usuario:</b> {{ auth()->user()->name }} ({{ auth()->user()->email }})<br>
                    <b>Rol:</b> {{ $rol }} |
                    <b>Estatus:</b> {{ auth()->user()->estatus_usuario ?? 'Activo' }}
                </div>
            </div>

            {{-- Métricas --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <div class="text-sm text-gray-500">Cajas en stock</div>
                    <div class="text-3xl font-bold">{{ $cajas_stock }}</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <div class="text-sm text-gray-500">Cajas en tránsito</div>
                    <div class="text-3xl font-bold">{{ $cajas_transito }}</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <div class="text-sm text-gray-500">Viajes en tránsito</div>
                    <div class="text-3xl font-bold">{{ $viajes_transito }}</div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-5">
                    <div class="text-sm text-gray-500">Usuarios activos</div>
                    <div class="text-3xl font-bold">{{ $usuarios_activos }}</div>
                </div>
            </div>

            {{-- Accesos por rol --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                {{-- Inventario: todos --}}
                <a href="/paquetes" class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md transition">
                    <div class="text-lg font-bold">Inventario</div>
                    <div class="text-gray-600 text-sm">Ver cajas, activar y ver ruta cuando esté en viaje.</div>
                </a>

                {{-- Traslados: todos --}}
                <a href="/traslados" class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md transition">
                    <div class="text-lg font-bold">Traslados</div>
                    <div class="text-gray-600 text-sm">Cajas en tránsito (actualiza cada 5s) y acceso a mapa.</div>
                </a>

                {{-- Viajes: todos --}}
                <a href="{{ route('viajes.index') }}" class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md transition">
                    <div class="text-lg font-bold">Viajes</div>
                    <div class="text-gray-600 text-sm">Crear, editar o eliminar viajes, asignar vehículos y choferes.</div>
                </a>

                {{-- Reportes: Admin/Supervisor --}}
                @if($rol === 'Admin' || $rol === 'Supervisor')
                    <a href="/reportes/salidas" class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md transition">
                        <div class="text-lg font-bold">Reporte: salidas</div>
                        <div class="text-gray-600 text-sm">Cuántos camiones salieron por día.</div>
                    </a>

                    <a href="/reportes/cajas-por-viaje" class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md transition">
                        <div class="text-lg font-bold">Reporte: cajas por viaje</div>
                        <div class="text-gray-600 text-sm">Cuántas cajas lleva cada viaje/camión.</div>
                    </a>
                @endif

                {{-- Usuarios: solo Admin --}}
                @if($rol === 'Admin')
                    <a href="/usuarios" class="bg-white shadow-sm rounded-lg p-6 hover:shadow-md transition">
                        <div class="text-lg font-bold">Usuarios</div>
                        <div class="text-gray-600 text-sm">Crear usuarios, roles y asignación de vehículos.</div>
                    </a>
                @endif

                {{-- Chofer: accesos especiales --}}
                @if($rol === 'Chofer')
                    <div class="bg-white shadow-sm rounded-lg p-6">
                        <div class="text-lg font-bold">Modo Chofer</div>
                        <div class="text-gray-600 text-sm">
                            Aquí solo verás traslados y rutas de tu vehículo asignado.
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>
