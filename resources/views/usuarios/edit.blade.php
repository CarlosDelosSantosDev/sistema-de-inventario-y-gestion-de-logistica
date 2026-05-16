<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar usuario</title>
</head>
<body style="font-family:Arial; margin:24px;">

<h1>Editar usuario #{{ $usuario->id_usuario }}</h1>

<form method="POST" action="/usuarios/{{ $usuario->id_usuario }}">
  @csrf

  <label>Nombre:</label><br>
  <input type="text" name="nombre" value="{{ $usuario->nombre }}" required><br><br>

  <label>Email:</label><br>
  <input type="email" name="email" value="{{ $usuario->email }}"><br><br>

  <label>Rol:</label><br>
  <select name="rol">
    <option @if($usuario->rol==='Admin') selected @endif>Admin</option>
    <option @if($usuario->rol==='Supervisor') selected @endif>Supervisor</option>
    <option @if($usuario->rol==='Chofer') selected @endif>Chofer</option>
  </select><br><br>

  <label>Estatus:</label><br>
  <select name="estatus_usuario">
    <option @if($usuario->estatus_usuario==='Activo') selected @endif>Activo</option>
    <option @if($usuario->estatus_usuario==='Inactivo') selected @endif>Inactivo</option>
  </select><br><br>

  <button type="submit">Guardar cambios</button>
</form>

<br>
<a href="/usuarios">Volver</a>

</body>
</html>