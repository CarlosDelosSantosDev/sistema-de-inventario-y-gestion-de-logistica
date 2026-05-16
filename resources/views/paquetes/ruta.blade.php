@extends('layouts.app_dark', ['title' => 'Ruta del viaje'])

@section('content')

<div class="panel">

  {{-- Encabezado --}}
  <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; align-items:flex-start;">
    <div>
      <div style="color:var(--muted); font-size:12px;">Monitoreo</div>
      <div style="font-size:18px; font-weight:900;">
        Ruta del viaje: {{ $idViaje }}
      </div>

      <div style="margin-top:10px; display:flex; gap:10px; flex-wrap:wrap;">
        <span class="chip"><b>Caja:</b> {{ $paquete->id_caja }}</span>
        <span class="chip"><b>Estatus caja:</b> {{ $paquete->estatus_caja }}</span>
      </div>
    </div>

    <a href="/paquetes" class="btn">⬅ Volver al inventario</a>
  </div>

  {{-- Card de info del viaje --}}
  @if(isset($viaje) && $viaje)
    <div class="card" style="margin-top:14px;">
      <div style="display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:12px;">
        <div>
          <div class="k">Viaje</div>
          <div style="font-weight:800;">{{ $viaje->id_viaje }}</div>
          <div style="color:var(--muted); font-size:12px;">{{ $viaje->estatus_viaje }}</div>
        </div>

        <div>
          <div class="k">Destino</div>
          <div style="font-weight:800;">{{ $viaje->destino_final }}</div>
          <div style="color:var(--muted); font-size:12px;">Salida: {{ $viaje->fecha_salida }}</div>
        </div>

        <div>
          <div class="k">Vehículo / Chofer</div>
          <div style="font-weight:800;">
            {{ $viaje->id_vehiculo }} ({{ $viaje->modelo_marca ?? '-' }})
          </div>
          <div style="color:var(--muted); font-size:12px;">
            {{ $viaje->id_conductor }} - {{ $viaje->nombre_completo }}
          </div>
        </div>
      </div>
    </div>
  @endif

  {{-- Contenido --}}
  @if($puntos->count() === 0)
    <div class="msg" style="margin-top:14px;">
      No hay puntos GPS registrados para este viaje.
    </div>
  @else

    {{-- Mapa --}}
    <div class="card" style="margin-top:14px; padding:14px;">
      <div style="display:flex; justify-content:space-between; gap:12px; align-items:center; flex-wrap:wrap;">
        <div>
          <div class="k">Mapa</div>
          <div style="font-weight:900;">Trayectoria + posición</div>
        </div>
        <div class="chip" id="lastPoint">Cargando última posición…</div>
      </div>

      <div id="map" style="height:440px; border-radius:16px; border:1px solid var(--border); margin-top:12px;"></div>
    </div>

    {{-- Tabla --}}
    <div class="card" style="margin-top:14px;">
      <div style="display:flex; justify-content:space-between; gap:12px; align-items:center; flex-wrap:wrap;">
        <div>
          <div class="k">GPS</div>
          <div style="font-weight:900;">Coordenadas registradas</div>
        </div>
        <div class="chip">Total puntos: {{ $puntos->count() }}</div>
      </div>

      <div style="margin-top:12px; overflow:auto;">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Lat</th>
              <th>Lon</th>
              <th>Velocidad</th>
              <th>Fecha/Hora</th>
            </tr>
          </thead>
          <tbody>
            @foreach($puntos as $i => $p)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $p->lat }}</td>
                <td>{{ $p->lon }}</td>
                <td>{{ $p->velocidad ?? '-' }}</td>
                <td>{{ $p->fecha_hora }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    {{-- Leaflet --}}
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""
    />
    <script
      src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
      integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
      crossorigin=""
    ></script>

    <script>
      const raw = @json($puntos);

      const puntos = raw.map(p => ({
        lat: parseFloat(p.lat),
        lon: parseFloat(p.lon),
        velocidad: p.velocidad,
        fecha_hora: p.fecha_hora
      }));

      const first = puntos[0];
      const last = puntos[puntos.length - 1];

      // label última posición
      document.getElementById('lastPoint').textContent =
        `Último punto: ${last.lat.toFixed(6)}, ${last.lon.toFixed(6)} | ${last.velocidad ?? '-'} km/h | ${last.fecha_hora}`;

      // mapa
      const map = L.map('map').setView([first.lat, first.lon], 13);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
      }).addTo(map);

      const latlngs = puntos.map(p => [p.lat, p.lon]);

      // marcadores inicio/fin
      L.marker(latlngs[0]).addTo(map).bindPopup('Inicio');

      if (latlngs.length > 1) {
        L.marker(latlngs[latlngs.length - 1]).addTo(map).bindPopup('Último punto');
      }

      // ruta
      const ruta = L.polyline(latlngs).addTo(map);
      map.fitBounds(ruta.getBounds(), { padding: [20, 20] });
    </script>

  @endif

</div>

@endsection
