<?php
	include("../login/login_check.php");
	include('../../conexi.php');
	$link = Conectarse();
	$nombre_pagina = "Destinos";
	$id = "id_origenes";
	$tabla = "origenes";
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
		<title>Catálogo de <?php echo $nombre_pagina ?></title>
		<?php include('../../styles.php') ?>
	</head>
	<body id="page-top">
		<?php include("../../navbar.php") ?>
		<div id="wrapper">
			<?php include("../../menu.php") ?>
			<div id="content-wrapper">
				<div class="container-fluid">
					<!-- Breadcrumbs-->
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="#">Catálogos</a>
						</li>
						<li class="breadcrumb-item active"><?php echo $nombre_pagina ?></li>
					</ol>
					<div class="row mb-2">
						<div class="col-12">
							<button type="button" class="btn btn-outline-success nuevo" >
								<i class="fas fa-plus"></i> Nuevo
							</button>
						</div>
					</div>
					<form id="form_filtros">
						<div class="row mb-2">
							<div class="col-2">
								<label>Ordenar Por</label>
								<select class="form-control" name="order_by">
									<option value="precio">Precio</option>
									<option selected value="zona">Zona</option>
									<option value="destino">Destino</option>
								</select>
							</div>
							<div class="col-2">
								<label>Destino:</label>
								<input type="search" class="form-control" name="destino">
								</div>
							<div class="col-2">
								<button type="submit" class="btn btn-primary " >
									<i class="fas fa-search"></i> Buscar
								</button>
							</div>
							
						</div>
					</form>
					
					<div class="card mb-3">
						<div class="card-header">
							<i class="fas fa-table"></i>
							Lista de <?php echo $nombre_pagina; ?>
						</div>
						<div class="card-body">
							<div class="table-responsive" id="lista_registros">
								<table class="table table-bordered" width="100%" cellspacing="0"
								id="tabla_origenes">
									<thead>
										<tr>
											<th class="text-center">Nombre</th>
											<th class="text-center"></th>
										</tr>
									</thead>
									
									<tbody id="containerLista">
										<tr>
											<td colspan="8"><h3 class="text-center">Cargando...</h3></td>
										</tr>
									</tbody>
								</table>
								<div id="mensaje"></div>
							</div>
						</div>
						
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
		<?php
			include("../../scripts.php");
			include("forms/form_origenes.php");
		?>
		<script src="js/origenes.js?v=<?= date("Ymdhis")?>"></script>
		<script src="js/buscar.js"></script>
	</body>
</html>
