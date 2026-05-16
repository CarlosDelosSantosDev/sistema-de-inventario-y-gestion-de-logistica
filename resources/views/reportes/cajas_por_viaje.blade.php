@extends('layouts.app_dark', ['title' => 'Reporte: Cajas por viaje'])

@section('content')

<div class="panel">
  <div style="display:flex; gap:12px; align-items:flex-end; justify-content:space-between; flex-wrap:wrap;">
    <div>
      <div style="color:var(--muted); font-size:12px;">Reporte</div>
      <div style="font-size:16px; font-weight:800;">Cuántas cajas lleva cada viaje/camión</div>
      <div style="color:var(--muted); font-size:12px; margin-top:6px;">Puedes filtrar por fecha o dejar vacío para ver todo.</div>
    </div>

    <div style="display:flex; gap:10px; align-items:center;">
      <div class="chip">
        <b>Viajes:</b> <span id="total">0</span>
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
          <th>Salida</th>
          <th>Vehículo / Chofer</th>
          <th>Destino</th>
          <th>Estatus</th>
          <th>Total cajas</th>
        </tr>
      </thead>
      <tbody id="tbody">
        <tr><td colspan="6">Cargando…</td></tr>
      </tbody>
    </table>
  </div>
</div>

<script>
  async function cargar(){
    const fecha = document.getElementById('fecha').value;
    const url = fecha
      ? `/reportes/cajas-por-viaje/data?fecha=${fecha}`
      : `/reportes/cajas-por-viaje/data`;

    const res = await fetch(url);
    const rows = await res.json();

    document.getElementById('total').textContent = rows.length;

    const tbody = document.getElementById('tbody');
    if(!rows.length){
      tbody.innerHTML = `<tr><td colspan="6">Sin resultados.</td></tr>`;
      return;
    }

    tbody.innerHTML = rows.map(r => `
      <tr>
        <td><b>${r.id_viaje}</b></td>
        <td>${r.fecha_salida ?? '-'}</td>
        <td>${r.id_vehiculo} (${r.modelo_marca ?? '-'})<br>
            <span style="color:var(--muted); font-size:12px;">${r.id_conductor} - ${r.nombre_completo}</span>
        </td>
        <td>${r.destino_final ?? '-'}</td>
        <td>${r.estatus_viaje}</td>
        <td><b>${r.total_cajas}</b></td>
      </tr>
    `).join('');
  }

  cargar();
</script>

@endsection