<?php
	include("../../paginas/login/login_check.php");
	include('../../conexi.php');
	$link = Conectarse();
	$nombre_pagina = "Boletos Vendidos ";
	$id = "id_precios";
	$tabla = "precios_tickets";
	include('../../funciones/generar_select.php');
	
?>


<!DOCTYPE html>
<html lang="es_mx">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title><?php echo $nombre_pagina ?></title>
		<?php include('../../styles.php') ?>
	</head>
	<body id="page-top">
		<?php include("../../navbar.php") ?>
		<div id="wrapper" class="d-print-none">
			<?php include("../../menu.php") ?>
			<div id="content-wrapper">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-10">
							<!-- Breadcrumbs-->
							<ol class="breadcrumb">
								<li class="breadcrumb-item">
									<a href="#">Taquilla</a>
								</li>
								<li class="breadcrumb-item active"><?php echo $nombre_pagina ?></li>
							</ol>
						</div>
						<div class="col-sm-2">
							<button type="button" id="btn_ponchar" class="btn btn-info btn-sm" > 
								<i class="fas fa-stamp"></i> Ponchar Boletos
							</button>
						</div>
					</div>
					
					
					<form id="form_filtros">
						<div class="row mb-2">
							<div class="col-sm-1">
								<label>Folio:</label>
								<input type="number" class="form-control" name="id_boletos">
							</div>	
							<div class="form-group col-sm-2">
								<label> <input type="checkbox" checked id="activar_fecha"> Fecha Inicial:</label>
								<input type="date" class="form-control" value="<?php echo date("Y-m-d");?>" name="fecha_inicial" id="fecha_inicial">
							</div>
							
							<div class="form-group col-sm-2">
								<label> Fecha Final:</label>
								<input type="date" class="form-control" value="<?php echo date("Y-m-d");?>" name="fecha_final" id="fecha_final">
							</div>
							<div class="col-sm-2">
								<label>Usuario:</label>
								<?php echo generar_select($link, "usuarios", "id_usuarios", "nombre_usuarios", true, false, false, $_COOKIE["id_usuarios"])?>
							</div>
							
							<div class="col-sm-2">
								<div class=" form-group">
									<label>Forma de Pago:	</label>
									<select class="form-control" id="forma_pago" name="forma_pago" >
										<option value=""> Todos</option>
										<option value="Efectivo"> Efectivo</option>
										<option value="Tarjeta">Tarjeta</option>
										<option value="Transferencia">Transferencia</option>
										<option value="Boletaje">Boletaje</option>
									</select >
								</div>
							</div>
							<div class="col-sm-1">
								<label>Ponchado:</label>
								<select name="estatus_ponchado" id="estatus_ponchado" class="form-control">
									<option value="">Todos</option>
									<option value="Activo">Activo</option>
									<option value="Ponchado">Ponchado</option>
								</select>
								
							</div>
							<div class="col-sm-1">
								<label>Pagado:</label>
								<select name="folio_recaudacion" id="folio_recaudacion" class="form-control">
									<option value="">Todos</option>
									<option value="IS NOT NULL">Pagado</option>
									<option value="IS NULL">Pendiente</option>
								</select>
							</div>
							<div class="col-sm-1">
								<button type="submit" class="btn btn-primary" >
									<i class="fas fa-search"></i> Buscar
								</button>
							</div>
							
							
						</div>
					</form>
					
					
					<div class="table-responsive"  id="tabla_registros">
						<table class="table table-bordered" width="100%" cellspacing="0">
							<tbody id="containerLista">
								<tr>
									<td colspan="8"><h3 class="text-center">Cargando...</h3></td>
								</tr>
							</tbody>
						</table>
						
					</div>
				</div>
				<!-- /.container-fluid -->
			</div>
			<!-- /.content-wrapper -->
		</div>
		<!-- /#wrapper -->
		
		<!-- Scroll to Top Button-->
		<a class="scroll-to-top rounded" href="#page-top">
			<i class="fas fa-angle-up"></i>
		</a>
		
		<div class="d-print-inline d-none p-2 carta"   id="impresion">
			
		</div>
		
		<?php include("forms/modal_ponchar.php");?>
		
		
		
		<?php include("../../scripts.php");?>
		
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		
		<script src="js/ponchar_boletos.js?v=<?php echo date("d-m-Y-i")?>"></script>
		<script src="js/boletos_sencillos.js?v=<?php echo date("d-m-Y-i")?>"></script>
	</body>
</html>
