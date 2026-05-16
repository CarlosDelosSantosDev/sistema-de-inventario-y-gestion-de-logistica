@extends('layouts.app_dark', ['title' => 'Inventario'])

@section('content')

@if(session('msg'))
  <div class="msg">{{ session('msg') }}</div>
@endif

<form method="POST" action="/paquetes/activar">
  @csrf

  <div class="panel">
    <table id="tabla-paquetes">
      <thead>
        <tr>
          <th>Sel</th>
          <th>ID Caja</th>
          <th>Producto</th>
          <th>Cantidad</th>
          <th>Peso</th>
          <th>Origen</th>
          <th>Estatus</th>
          <th>Activa</th>
          <th>Ruta</th>
        </tr>
      </thead>

      <tbody>
        @forelse($paquetes as $p)
          <tr data-id="{{ $p->id_caja }}">
            <td><input type="checkbox" name="ids[]" value="{{ $p->id_caja }}"></td>
            <td><b>{{ $p->id_caja }}</b></td>
            <td>{{ $p->id_producto ?? '-' }}</td>
            <td>{{ $p->cantidad_piezas ?? '-' }}</td>
            <td>{{ $p->peso_total ?? '-' }}</td>
            <td>{{ $p->origen ?? '-' }}</td>
            <td>{{ $p->estatus_caja }}</td>
            <td>
              @if($p->activo)
                <span class="chip" style="background: #28a745; color: white;">Sí</span>
              @else
                <span class="chip">No</span>
              @endif
            </td>
            <td>
              @if($p->estatus_caja === 'Asignada a Viaje')
                @php
                  $esChofer = auth()->user() && auth()->user()->rol === 'Chofer';
                  $puedeVer = !$esChofer || ($vehChofer && $p->vehiculo_viaje === $vehChofer);
                @endphp

                @if($puedeVer)
                  <a href="/paquetes/{{ $p->id_caja }}/ruta">Ver Ruta</a>
                  <div style="font-size:12px;color:var(--muted); margin-top:4px;">
                    Vehículo: {{ $p->vehiculo_viaje ?? '-' }}
                  </div>
                @else
                  <span style="font-size:12px;color:var(--muted);">Sin acceso</span>
                @endif
              @else
                <span style="color:var(--muted);">-</span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9">No hay cajas registradas.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div style="margin-top:12px; display:flex; gap:8px;">
    <button type="button" class="btn" id="btnDrone">Cargando dron...</button>
    <form id="form-limpiar" action="/paquetes/limpiar-vista" method="POST" style="display: none;">
      @csrf
    </form>

    <button type="button" class="btn" id="btnLimpiar" style="background-color: #dc3545;">Limpiar Tabla</button>
  </div>
</form>

<script>
/**
 * 1. LOGICA DEL BOTON DRON (Toggle)
 * Se conecta a /mobile-api/drone/toggle
 */
async function toggleDrone() {
    const btn = document.getElementById('btnDrone');
    const currentState = btn.textContent === 'Desactivar Dron';
    const newState = !currentState;
    
    try {
        const response = await fetch('/mobile-api/drone/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ estado: newState })
        });
        
        const data = await response.json();
        
        if(data.success) {
            actualizarInterfazBoton(newState);
        }
    } catch(error) {
        console.error('Error:', error);
        alert('Error al conectar con el servidor.');
    }
}

function actualizarInterfazBoton(activo) {
    const btn = document.getElementById('btnDrone');
    if(activo) {
        btn.textContent = 'Desactivar Dron';
        btn.style.backgroundColor = '#dc3545';
    } else {
        btn.textContent = 'Activar Dron';
        btn.style.backgroundColor = '';
    }
}

/**
 * 2. CARGAR ESTADO INICIAL
 */
async function cargarEstadoDrone() {
    try {
        const response = await fetch('/mobile-api/drone/estado');
        const data = await response.json();
        actualizarInterfazBoton(data.activo);
    } catch(error) {
        console.error('Error al cargar estado:', error);
        document.getElementById('btnDrone').textContent = 'Activar Dron';
    }
}

/**
 * 3. REFRESCAR TABLA AUTOMÁTICAMENTE
 * Si el dron está activo, pide la página cada 3 segundos y actualiza el tbody
 */
setInterval(async function() {
    const btnDrone = document.getElementById('btnDrone');
    
    if (btnDrone && btnDrone.textContent === 'Desactivar Dron') {
        try {
            const response = await fetch(window.location.href);
            const html = await response.text();
            
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const nuevoTbody = doc.querySelector('#tabla-paquetes tbody').innerHTML;
            
            const tbodyActual = document.querySelector('#tabla-paquetes tbody');
            
            // Solo actualizamos si el contenido cambió para evitar parpadeos innecesarios
            if (tbodyActual.innerHTML !== nuevoTbody) {
                tbodyActual.innerHTML = nuevoTbody;
                console.log("Tabla sincronizada con el Dron");
            }
        } catch (error) {
            console.error("Error en auto-update:", error);
        }
    }
}, 3000);

/**
 * 4. EVENTOS
 */
document.addEventListener('DOMContentLoaded', () => {
    cargarEstadoDrone();
    document.getElementById('btnDrone').addEventListener('click', toggleDrone);
    document.getElementById('btnLimpiar').addEventListener('click', function() {
      if(confirm('¿Deseas limpiar la lista? Las cajas volverán a aparecer conforme el dron las escanee.')) {
          document.getElementById('form-limpiar').submit();
      }
  });
});
</script>

@endsection