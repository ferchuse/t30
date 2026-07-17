<?php
	include("../../paginas/login/login_check.php");
	include('../../conexi.php');
	$link = Conectarse();
	$nombre_pagina = "Hoja de Servicio ";
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
							<button type="button" onclick="window.print()" class="btn btn-info btn-sm" > 
								<i class="fas fa-print"></i> Imprimir
							</button>
						</div>
					</div>
					
					
					<form id="form_filtros">
						<div class="row mb-2">
							
							<div class="form-group col-sm-2">
								<label> <input type="checkbox" checked id="activar_fecha"> Fecha Inicial:</label>
								<input type="date" class="form-control" value="<?php echo date("Y-m-d");?>" name="fecha_inicial" id="fecha_inicial">
							</div>
							
							<div class="form-group col-sm-2">
								<label> Fecha Final:</label>
								<input type="date" class="form-control" value="<?php echo date("Y-m-d");?>" name="fecha_final" id="fecha_final">
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
	
			
		
		<?php include("../../scripts.php");?>
		
		
		<script src="js/hoja_servicio.js?v=<?php echo date("d-m-Y-i")?>"></script>
	</body>
</html>
