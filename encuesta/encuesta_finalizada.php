<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Gracias por tu Encuesta</title>
		<!-- Vinculación de Bootstrap 4 -->
		<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
	</head>
	<body>
		<div class="container mt-5">
			
			<div class="row justify-content-center">
				<img src="img/encabezado.png" class="img-fluid">
				<div class="col-md-8">
					<div class="card shadow-lg">
						<div class="card-body text-center">
							<h2 class="card-title text-success">¡Gracias por participar en nuestra encuesta!</h2>
							<p class="card-text">Tu opinión es muy importante para nosotros. Como agradecimiento por tu tiempo, te informamos que puedes cambiar tu folio por un boleto presentándolo en taquilla.</p>
							<p class="card-text font-weight-bold h3">Folio Encuesta: <span class="text-info" id="folio">
								<?php echo $_GET["uid"]?>
								
								
							</span>
							</p>
							
							
							<hr>
							<p class="card-text">Recuerda presentar este folio en taquilla para obtener tu boleto. ¡Esperamos verte pronto!</p>
							<a href="index.php" class="btn btn-primary">Volver a la página principal</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Scripts de Bootstrap 4 -->
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	</body>
</html>
