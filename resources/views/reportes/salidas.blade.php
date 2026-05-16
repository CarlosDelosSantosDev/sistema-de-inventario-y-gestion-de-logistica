@extends('layouts.app_dark', ['title' => 'Reporte: Salidas por día'])

@section('content')

<div class="panel">
  <div style="display:flex; gap:12px; align-items:flex-end; justify-content:space-between; flex-wrap:wrap;">
    <div>
      <div style="color:var(--muted); font-size:12px;">Reporte</div>
      <div style="font-size:16px; font-weight:800;">Cuántos camiones/viajes salieron en una fecha</div>
      <div style="color:var(--muted); font-size:12px; margin-top:6px;">Selecciona una fecha para consultar.</div>
    </div>

    <div style="display:flex; gap:10px; align-items:center;">
      <div class="chip">
        <b>Total:</b> <span id="total">0</span>
      </div>

      <input type="date" id="fecha"
        style="padding:10px; border-radius:12px; border:1px solid var(--border); background:rgba(18,28,59,.7); color:var(--text);">

      <button class="btn" type="button" onclick="cargar()">Ver</button>
    </div>
  </div>

  <div style="margin-top:14px;">
    <table>
      <thead>
        <tr>
          <th>Viaje</th>
          <th>Fecha/Hora salida</th>
          <th>Vehículo</th>
          <th>Chofer</th>
          <th>Destino</th>
          <th>Estatus</th>
        </tr>
      </thead>
      <tbody id="tbody">
        <tr><td colspan="6">Selecciona una fecha.</td></tr>
      </tbody>
    </table>
  </div>
</div>

<script>
  async function cargar(){
    const fecha = document.getElementById('fecha').value;
    if(!fecha){
      alert('Selecciona una fecha');
      return;
    }

    const res = await fetch(`/reportes/salidas/data?fecha=${fecha}`);
    const data = await res.json();

    document.getElementById('total').textContent = data.total;

    const tbody = document.getElementById('tbody');
    if(!data.viajes.length){
      tbody.innerHTML = `<tr><td colspan="6">No hubo salidas en esa fecha.</td></tr>`;
      return;
    }

    tbody.innerHTML = data.viajes.map(v => `
      <tr>
        <td><b>${v.id_viaje}</b></td>
        <td>${v.fecha_salida ?? '-'}</td>
        <td>${v.id_vehiculo} (${v.modelo_marca ?? '-'})</td>
        <td>${v.id_conductor} - ${v.nombre_completo}</td>
        <td>${v.destino_final ?? '-'}</td>
        <td>${v.estatus_viaje}</td>
      </tr>
    `).join('');
  }
</script>

@endsection