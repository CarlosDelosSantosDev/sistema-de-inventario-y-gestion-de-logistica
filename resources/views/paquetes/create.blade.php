<!doctype html>
<html>
<head>
    <title>Crear Caja</title>
</head>
<body>

<h1>Registrar Nueva Caja</h1>

<form method="POST" action="/paquetes">
    @csrf

    <label>ID Caja:</label><br>
    <input type="text" name="id_caja" required><br><br>

    <label>ID Producto:</label><br>
    <input type="text" name="id_producto"><br><br>

    <label>Cantidad Piezas:</label><br>
    <input type="number" name="cantidad_piezas"><br><br>

    <label>Peso Total:</label><br>
    <input type="number" step="0.01" name="peso_total"><br><br>

    <label>Fecha Empaque:</label><br>
    <input type="date" name="fecha_empaque"><br><br>

    <label>Origen:</label><br>
    <input type="text" name="origen"><br><br>

    <button type="submit">Guardar Caja</button>
</form>

<br>
<a href="/paquetes">Volver al Inventario</a>

</body>
</html>