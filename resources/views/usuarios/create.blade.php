<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Crear usuario</title>
</head>
<body style="font-family:Arial; margin:24px;">

<h1>Nuevo usuario</h1>

<form method="POST" action="/usuarios">
  @csrf

  <label>Nombre:</label><br>
  <input type="text" name="nombre" required><br><br>

  <label>Email:</label><br>
  <input type="email" name="email"><br><br>

  <label>Rol:</label><br>
  <select name="rol">
    <option>Admin</option>
    <option>Supervisor</option>
    <option selected>Chofer</option>
  </select><br><br>

  <label>Estatus:</label><br>
  <select name="estatus_usuario">
    <option selected>Activo</option>
    <option>Inactivo</option>
  </select><br><br>

  <button type="submit">Guardar</button>
</form>

<br>
<a href="/usuarios">Volver</a>

</body>
</html>