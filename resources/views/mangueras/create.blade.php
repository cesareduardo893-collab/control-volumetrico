<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Crear Manguera</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <h1>Crear Manguera</h1>
  <form action="{{ route('mangueras.store') }}" method="POST">
    @csrf
    <!-- Campos básicos; ajusta a tu modelo -->
    <div>
      <label for="clave">Clave</label>
      <input type="text" name="clave" id="clave">
    </div>
    <div>
      <label for="descripcion">Descripción</label>
      <input type="text" name="descripcion" id="descripcion">
    </div>
    <div>
      <label for="medidor_id">Medidor</label>
      <input type="number" name="medidor_id" id="medidor_id">
    </div>
    <button type="submit">Crear</button>
  </form>
</body>
</html>
