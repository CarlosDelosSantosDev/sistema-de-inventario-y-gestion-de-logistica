@extends('layouts.app_dark', ['title' => 'Traslados en tiempo real'])

@section('content')

<div class="panel">
  <div style="display:flex; gap:12px; align-items:center; justify-content:space-between; flex-wrap:wrap;">
    <div>
      <div style="color:var(--muted); font-size:12px;">Monitoreo</div>
      <div style="font-size:16px; font-weight:800;">Cajas en tránsito (se actualiza cada 5s)</div>
    </div>
    <div class="chip" id="lastUpdate">Actualizando…</div>
  </div>

  <div style="margin-top:14px;">
    <table>
      <thead>
        <tr>
          <th>ID Caja</th>
          <th>Viaje</th>
          <th>Vehículo / Chofer</th>
          <th>Destino</th>
          <th>Última ubicación</th>
          <th>Ruta</th>
        </tr>
      </thead>
      <tbody id="tbody">
        <tr><td colspan="6">Cargando…</td></tr>
      </tbody>
    </table>
  </div>
</div>

<script>
  async function cargar() {
    const res = await fetch('/traslados/data');
    const data = await res.json();

    const tbody = document.getElementById('tbody');

    if (!data.length) {
      tbody.innerHTML = `<tr><td colspan="6">No hay traslados en este momento.</td></tr>`;
    } else {
      tbody.innerHTML = data.map(r => {
        const ub = (r.lat && r.lon)
          ? `${Number(r.lat).toFixed(6)}, ${Number(r.lon).toFixed(6)}<br><span style="color:var(--muted); font-size:12px;">${r.fecha_hora ?? ''} | ${r.velocidad ?? '-'} km/h</span>`
          : `<span style="color:var(--muted);">Sin GPS</span>`;

        return `
          <tr>
            <td><b>${r.id_caja}</b><br><span style="color:var(--muted); font-size:12px;">${r.id_producto ?? '-'}</span></td>
            <td>${r.id_viaje}<br><span style="color:var(--muted); font-size:12px;">${r.fecha_salida ?? ''}</span></td>
            <td>${r.id_vehiculo} (${r.modelo_marca ?? '-'})<br><span style="color:var(--muted); font-size:12px;">${r.id_conductor} - ${r.nombre_completo}</span></td>
            <td>${r.destino_final ?? '-'}</td>
            <td>${ub}</td>
            <td><a href="/paquetes/${r.id_caja}/ruta">Ver Ruta</a></td>
          </tr>
        `;
      }).join('');
    }

    document.getElementById('lastUpdate').textContent =
      'Última actualización: ' + new Date().toLocaleTimeString();
  }

  cargar();
  setInterval(cargar, 5000);
</script>

@endsection