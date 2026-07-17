<?php
	include("../../paginas/login/login_check.php");
	include('../../conexi.php');
	$link = Conectarse();
	$nombre_pagina = "Precios";
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
							<?php  if(dame_permiso("precios_boletos.php", $link) != 'Lectura'){ ?>
								<button type="button" class="btn btn-success nuevo" >
									<i class="fas fa-plus"></i> Nuevo
								</button>
							<?php 
								}
								?>
						</div>
						
					</div>
					
					<div class="card mb-3">
						<div class="card-header">
							<i class="fas fa-table"></i>
							Lista de <?php echo $nombre_pagina; ?>
						</div>
						<div class="card-body">
							<div class="table-responsive"  id="tabla_registros">
								<table class="table table-bordered" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th class="text-center">Nombre</th>
											<th class="text-center">Precio</th>
										</tr>
									</thead>
									
									<tbody id="containerLista">
										<tr>
											<td colspan="8"><h3 class="text-center">Cargando...</h3></td>
										</tr>
										</tbody>
									</table>
									
								</div>
							</div>
							
						</div>
					</div>
					<!-- /.container-fluid -->
					
					<!-- Sticky Footer -->
					<footer class="sticky-footer">
						<div class="container my-auto">
							<div class="copyright text-center my-auto">
								<span>Copyright  Glifo Media 2018</span>
							</div>
						</div>
					</footer>
					
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
				include("forms/precios_boletos.php");
			?>
			<script src="js/precios_boletos.js"></script>
		</body>
	</html>
