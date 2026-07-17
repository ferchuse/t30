<?php
	include("../../paginas/login/login_check.php");
	include('../../conexi.php');
	include('../../funciones/generar_select.php');
	$link = Conectarse();
	$nombre_pagina = "Taquilla Aduanas";
	$precios = array();
	
	$consulta_precios = "SELECT * FROM sencillos_precios WHERE estatus_precio = 'Activo' ORDER BY destino";
	
	$result= mysqli_query($link, $consulta_precios);
	
	while($row = mysqli_fetch_assoc($result)){
		$precios[] =  $row;
		
	}
	
?>
<!DOCTYPE html>
<html lang="es_mx">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Taquilla </title>
		<?php include('../../styles.php')?>
	</head>
	<body id="page-top">
		<?php include("../../navbar.php")?>
		<div id="wrapper" class="d-print-none">
			<?php include("../../menu.php")?>	
			<div id="content-wrapper">		
				<div class="container-fluid">		
					<!-- Breadcrumbs-->
					<ol class="breadcrumb d-print-none">
						<li class="breadcrumb-item">
							<a href="#">Taquilla</a>
						</li>
						<li class="breadcrumb-item active"><?php echo $nombre_pagina;?></li>
					</ol>
					
					<form id="form_boletos" class="was-validated" autocomplete="off">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label>Cantidad:	</label>
									<input min="1" required id="cantidad" type="number" name="cantidad" value="1" class="form-control cantidad text-center" >
								</div>
								<div class=" form-group">
									<label>Destino:	</label>
									<select class="form-control" id="destino" name="destino" required>
										<option value=""> Seleccione...</option>
										<?php foreach($precios AS $fila){ ?>
											<option VALUE="<?php echo $fila["id_precio"]?>" data-precio="<?php echo $fila["precio"]?>">
												<?php echo $fila["destino"]. "- $" .$fila["precio"]?> (<?php echo $fila["tipo_viaje"]?>)
											</option>
											<?php
											}
										?>
										
									</select >
								</div>
								<div class=" form-group">
									<label>Forma de Pago:	</label>
									<select class="form-control" id="forma_pago" name="forma_pago" >
										
										<option value="Efectivo"> Efectivo</option>
										<option value="Tarjeta">Tarjeta</option>
										<option value="Transferencia">Transferencia</option>
										<option value="Boletaje">Boletaje</option>
									</select >
								</div>
								<div class=" form-group">
									<label>Nombre Pasajero:	</label>
									<input id="nombre" name="nombre" class="form-control  h1" >
								</div>
								<div class=" form-group">
									<label>Precio:	</label>
									<input id="precio" name="precio" readonly class="form-control precio text-right h1" >
								</div>
								<div class=" form-group">
									<label>Importe:	</label>
									<input id="importe" name="importe" readonly class="form-control importe text-right h1" >
								</div>
								<div class=" form-group ">
									<button class="btn btn-success mt-4 btn-block" >
										<i class="fas fa-save"></i> Guardar
									</button>
								</div>
							</div>
						</div>
					</form>
					
				</div>
			</div>
			<!-- /.container-fluid -->
			
			
		</div> 
		<!-- /.content-wrapper -->
	</div>
	<!-- /#wrapper -->
	
	<!-- Scroll to Top Button-->
	<a class="scroll-to-top rounded d-print-none" href="#page-top">
		<i class="fas fa-angle-up"></i>
	</a>
	
	<?php include("../../scripts.php")?>
	
	<script src="../../lib/websocket-printer.js"></script>
	<script >
	
		try{
			var printService = new WebSocketPrinter();
		}
		catch(error){
			// alert("Error al conectar con el servicio de impresion Abra Web hardware Bridge" + error  )
			
		}
		
	</script>
	<script src="js/taquilla_sencillos.js?v=<?php echo date("d-m-Y-s")?>"></script>
	
</body>
</html>		