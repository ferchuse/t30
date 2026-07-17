<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Encuesta de Satisfacción</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    .hidden {
      display: none;
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <h2>Encuesta de Satisfacción</h2>
  <form id="encuestaForm">
    <div class="mb-3">
      <label for="tipoUnidad" class="form-label">¿Cuál es el tipo de unidad que lo llevó a su destino?</label>
      <select class="form-select" id="tipoUnidad" name="tipoUnidad">
        <option value="1">Camioneta</option>
        <option value="2">Carro</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="viajesUltimos6Meses" class="form-label">En los últimos 6 meses, ¿cuántas veces viajó con nosotros?</label>
      <select class="form-select" id="viajesUltimos6Meses" name="viajesUltimos6Meses">
        <option value="">Elige</option>
        <option value="1">1 ocasión</option>
        <option value="2">2 a 4 ocasiones</option>
        <option value="3">Más de 5 ocasiones</option>
      </select>
    </div>
    <div class="mb-3 " id="preguntaCalidadTaquilla">
      <label for="viajesUltimos6Meses" class="form-label">En los últimos 6 meses, ¿cuántas veces viajó con nosotros?</label>
      <select class="form-select" id="viajesUltimos6Meses" name="viajesUltimos6Meses">
        <option value="">Elige</option>
        <option value="1">1 ocasión</option>
        <option value="2">2 a 4 ocasiones</option>
        <option value="3">Más de 5 ocasiones</option>
      </select>
    </div>

	<div class="container mt-5">
  <h2>Encuesta de Satisfacción</h2>
  <form id="encuestaForm">
    <div class="mb-3">
      <label class="form-label">¿Cuál es el tipo de unidad que lo llevó a su destino?</label>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="tipoUnidad" id="camioneta" value="Camioneta">
        <label class="form-check-label" for="camioneta">
          Camioneta
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="tipoUnidad" id="carro" value="Carro">
        <label class="form-check-label" for="carro">
          Carro
        </label>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">En los últimos 6 meses, ¿cuántas veces viajó con nosotros?</label>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="viajesUltimos6Meses" id="unaVez" value="1">
        <label class="form-check-label" for="unaVez">
          1 ocasión
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="viajesUltimos6Meses" id="dosCuatroVeces" value="2-4">
        <label class="form-check-label" for="dosCuatroVeces">
          2 a 4 ocasiones
        </label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="viajesUltimos6Meses" id="masCincoVeces" value="5+">
        <label class="form-check-label" for="masCincoVeces">
          Más de 5 ocasiones
        </label>
      </div>
    </div>
    <!-- Otras preguntas aquí... -->

    <button type="submit" class="btn btn-primary">Enviar Encuesta</button>
  </form>
</div>

<script>
  $(document).ready(function() {
    $('#viajesUltimos6Meses').change(function() {
		console.log("change", $(this).val())
      if ($(this).val() === '2') {
        $('#preguntaCalidadTaquilla').removeClass('hidden');
      } else {
        $('#preguntaCalidadTaquilla').addClass('hidden');
      }
    });

    // Agrega lógica similar para otras preguntas según sea necesario
  });
</script>

</body>
</html>
