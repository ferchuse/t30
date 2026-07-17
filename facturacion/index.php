<?php 
	date_default_timezone_set('America/Mexico_City');
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Facturar Boleto</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
		<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
		
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.9.0/css/all.css" integrity="sha384-i1LQnF23gykqWXg6jxC2ZbCbUMxyw5gLZY6UiUS98LYV5unm8GWmfkIS6jqJfb4E" crossorigin="anonymous">
		
		
	</head>
	<body>
		
		<div class="jumbotron text-center" >
			<h1>Facturar Boleto</h1>
			
		</div>
		
		
		<div class="container">
			<div class="row">
				<div class="col text-center">
					<img class="w-25" class="img-fluid" src="../taquilla/img/logo.png">
				</div>
			</div>
			<h4 class="text-center">
			Elige una opción
		</h4>
			<div class="row">
				<div class="col-6 d-inline">
						<h2>Boleto  Ruta Aduanas-18 de Marzo: </h2>
					<a href="factura_ruta.php" class="btn btn-success btn-lg align-middle d-inline-block">
						Capturar Folio Facturación
					</a>
				</div>
				<div class="col-6">
					<h2>Boleto de Taxi AIFA: </h2>
					<form id="form_buscar" action="encuesta.php">
						<div class="form-group">
							<label>Folio:</label>
							<input class="form-control text-center" id="folio" name="folio" type="number" required>
						</div>
						<div class="form-group">
							<label>Fecha:</label>
							<input class="form-control text-center" required id="fecha" name="fecha" type="date"  value="<?php echo date("Y-m-d")?>">
						</div>
						
						<button type="submit"  class="btn btn-success btn-lg float-right next">
							<i class="fa fa-search"></i> Buscar 
						</button>
					</form>
				</div>
				
				
			</div>
		</div>
		
		
	</body>
</html>		