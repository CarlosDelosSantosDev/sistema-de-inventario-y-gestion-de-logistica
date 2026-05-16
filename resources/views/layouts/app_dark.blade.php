<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Sistema Logística' }}</title>

  <style>
    :root{
      --bg:#0b1020; --panel:#0f1730; --card:#121c3b; --border:#1e2a52;
      --text:#e6e9f2; --muted:#9aa7c0; --accent:#4f8cff; --accent2:#00d4ff;
    }
    *{box-sizing:border-box}
    body{margin:0;font-family:Arial,system-ui,-apple-system,Segoe UI,Roboto; background:linear-gradient(180deg,var(--bg),#060916); color:var(--text);}
    a{color:var(--accent); text-decoration:none}
    a:hover{opacity:.9}
    .wrap{display:grid; grid-template-columns:260px 1fr; min-height:100vh;}
    .side{background:rgba(15,23,48,.9); border-right:1px solid var(--border); padding:18px; position:sticky; top:0; height:100vh;}
    .brand{font-weight:800; letter-spacing:.6px; font-size:14px; color:var(--accent2);}
    .brand span{color:var(--text)}
    .nav{margin-top:18px; display:flex; flex-direction:column; gap:10px;}
    .nav a{padding:10px 12px; border:1px solid transparent; border-radius:12px; color:var(--text); background:transparent}
    .nav a:hover{border-color:var(--border); background:rgba(79,140,255,.08)}
    .nav .muted{color:var(--muted); font-size:12px; margin:10px 4px 0}
    .main{padding:22px;}
    .topbar{display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:18px;}
    .topbar .title{font-size:20px; font-weight:800}
    .chip{border:1px solid var(--border); background:rgba(18,28,59,.7); padding:8px 10px; border-radius:999px; color:var(--muted); font-size:12px}
    .grid{display:grid; gap:14px;}
    .cards{grid-template-columns:repeat(4, minmax(0,1fr));}
    .card{background:rgba(18,28,59,.75); border:1px solid var(--border); border-radius:16px; padding:14px; box-shadow:0 10px 30px rgba(0,0,0,.25);}
    .card .k{color:var(--muted); font-size:12px;}
    .card .v{font-size:26px; font-weight:900; margin-top:6px;}
    .panel{background:rgba(18,28,59,.6); border:1px solid var(--border); border-radius:18px; padding:16px;}
    table{width:100%; border-collapse:collapse; overflow:hidden; border-radius:14px;}
    th,td{padding:12px; border-bottom:1px solid var(--border); font-size:13px; text-align:left;}
    th{color:var(--muted); font-weight:700; background:rgba(15,23,48,.7)}
    tr:hover td{background:rgba(79,140,255,.06)}
    .btn{display:inline-block; padding:9px 12px; border-radius:12px; border:1px solid var(--border); background:rgba(79,140,255,.12); color:var(--text); font-size:13px}
    .btn:hover{background:rgba(79,140,255,.18)}
    .msg{border:1px solid rgba(0,212,255,.35); background:rgba(0,212,255,.08); padding:10px 12px; border-radius:14px; color:var(--text); margin:12px 0;}
    @media (max-width: 980px){
      .wrap{grid-template-columns:1fr;}
      .side{position:relative; height:auto;}
      .cards{grid-template-columns:repeat(2,minmax(0,1fr));}
    }
  </style>
</head>

<body>
  <div class="wrap">
    <aside class="side">
      <div class="brand">LOGI<span>TRACK</span></div>
      <div class="chip" style="margin-top:10px;">
        {{ auth()->user()->name }} · {{ auth()->user()->rol }}
      </div>

      <nav class="nav">
        <div class="muted">Navegación</div>
        <a href="/dashboard">Dashboard</a>
        <a href="/paquetes">Inventario</a>
        <a href="/traslados">Traslados</a>

        @if(auth()->user()->rol === 'Admin' || auth()->user()->rol === 'Supervisor')
          <div class="muted">Reportes</div>
          <a href="/reportes/salidas">Salidas por día</a>
          <a href="/reportes/cajas-por-viaje">Cajas por viaje</a>
        @endif

        @if(auth()->user()->rol === 'Admin')
          <div class="muted">Administración</div>
          <a href="/usuarios">Usuarios</a>
        @endif

        <div class="muted">Cuenta</div>
        <a href="/profile">Perfil</a>
        <form method="POST" action="/logout" style="margin:0;">
          @csrf
          <button type="submit" class="btn" style="width:100%; text-align:left; margin-top:8px;">Cerrar sesión</button>
        </form>
      </nav>
    </aside>

    <main class="main">
      <div class="topbar">
        <div class="title">{{ $title ?? 'Panel' }}</div>
        <div class="chip">Estatus: {{ auth()->user()->estatus_usuario ?? 'Activo' }}</div>
      </div>

      @yield('content')
    </main>
  </div>
</body>
</html>
