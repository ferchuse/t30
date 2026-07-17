<?php
	include('../../conexi.php');
	$link = Conectarse();
	$nombre_pagina = "Recibos Pachuca";
	include('../../funciones/generar_select.php');
	include("../../paginas/login/login_check.php");
?>


<!DOCTYPE html>
<html lang="es_mx">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title><?php echo $nombre_pagina;?></title>
		<?php include('../../styles.php')?>
	</head>
	<body id="page-top">
		<?php include("../../navbar.php")?>
		<div id="wrapper" class="d-print-none">
			<?php include("../../menu.php")?>	
			<div id="content-wrapper">		
				<div class="container-fluid">		
					<!-- Breadcrumbs-->
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="#">Movimientos</a> 
						</li>
						<li class="breadcrumb-item active"><?php echo $nombre_pagina; ?></li>
					</ol>
					
					<!--Form Filtro !-->
					<form id="form_filtro" autocomplete="off">
						<div class="row mb-2">
							<div class="col-12">
								<div class="col-12 mb-3">
									<button class="btn btn-primary btn-sm" >
										<i class="fas fa-search"></i> Buscar
									</button>
									<button type="button" class="btn btn-success btn-sm" id="nuevoSalida">
										<i class="fas fa-plus"></i> Nuevo
									</button>
									<button hidden class="btn btn-info btn-sm" onclick="window.print()" type="button">
										<i class="fas fa-print"></i> Imprimir
									</button>
								</div>
								
							</div>
						</div>
						
						<div class="row mb-2">
							
							<div class="col-sm-3">	
								<label for="fecha_inicial">Fecha Inicial:</label>
								<input class="form-control" type="date" name="fecha_inicial" id="fecha_inicial" value="<?php echo date("Y-m-d");?>">
							</div>  
							
							<div class="col-sm-3">	
								<label for="fecha_final">Fecha Final:</label>
								<input class="form-control" type="date" name="fecha_final" id="fecha_final" value="<?php echo date("Y-m-d");?>">
							</div> 
							<div class="col-sm-2">
								<label for="referencia">Referencia:</label>
								<input class="form-control" type="text" name="referencia" >
							</div>	
							<div class="col-sm-3">	
								<label for="id_beneficiarios">Beneficiario</label>
								<?php echo generar_select($link, "beneficiarios", "id_beneficiarios", "nombre_beneficiarios",  true); ?>
							</div> 
						</div>
						<div class="row mb-2">
							<div class="col-3">
								<label for="nombre_condonaciones">Empresa:</label>
								<?= generar_select ($link, "empresas", "id_empresas", "nombre_empresas", true);?>
							</div>
							
						</div>
						
					</form>
					<hr>
					
					
					<div class="card mb-3">
						<div class="card-header">
							<i class="fas fa-table"></i>
							Lista de <?php echo $nombre_pagina; ?>
						</div>
						<div class="card-body">
							
							<div class="table-responsive" id="tabla_registros">
								<table class="table table-bordered" id="tabla_recibos" width="100%" cellspacing="0" >
									<thead>
										<tr>
											<th class="text-center">Fecha</th>
											<th class="text-center">Empresa</th>
											<th class="text-center">Beneficiario</th>
											<th class="text-center">Motivo Salida</th>
											<th class="text-center">Saldo</th>
											<th class="text-center">Monto</th>
											<th class="text-center">Observaciones</th>
											<th class="text-center">Estatus</th>
											<th class="text-center"></th>
										</tr>
									</thead>
									<thead>
										<tr>
											<th class="text-center">
												<input type="date" class="form-control" data-indice="0" id="fecha_recibo">
											</th>
											<th class="text-center">
												<input type="text" data-indice="1" class="form-control" placeholder="Buscar empresa" id="nombre_empresa">
											</th>
											<th class="text-center">
											<input type="text" data-indice="2" class="form-control" placeholder="Buscar beneficiario" id="nombre_beneficiario"></th>
											<th class="text-center">
												<input type="text" data-indice="3" class="form-control" placeholder="Buscar motivo de salida" id="buscar_salida">
											</th>
											<th class="text-center"></th>
											<th class="text-center"></th>
											<th class="text-center"></th>
											<th class="text-center"></th>
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
				
				<!-- Sticky Footer -->
				<footer class="sticky-footer">
					<div class="container my-auto">
						<div class="copyright text-center my-auto">
							<span>Copyright  Glifo Media 2020</span>
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
		<div class="d-print-inline d-none p-2 carta"   id="impresion">
			
		</div>
		
		<?php 
			include("../../scripts.php");
			include('forms/form_pachuca.php');
		?>
		<script src="js/recibos_pachuca.js?v=<?= date("d-m-Y-H-i-s")?>"></script>
		<script src="../catalogos/js/buscar.js"></script>
	</body>
</html>
