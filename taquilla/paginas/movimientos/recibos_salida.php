<?php
	include('../../conexi.php');
	$link = Conectarse();
	$nombre_pagina = "Recibos de Salidas";
	include('../../funciones/generar_select.php');
	include("../../paginas/login/login_check.php");
	
	$cat_empresas = [1=> "TAXI DRIVER VIAJE CONFIABLE", 2=> "TAXI DRIVER VIAJE CONFIABLE", ]
	
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
									
								</div>
								
							</div>
						</div>
						
						<div class="row mb-2">
							<div class="col-sm-2">
								<label for="fecha_inicial">Fecha Inicial:</label>
								<input class="form-control" type="date" name="fecha_inicial" id="fecha_inicial" value="<?php echo date("Y-m-d");?>">	
							</div>
							<div class="col-sm-2">
								<label for="fecha_final">Fecha Final:</label>
								<input class="form-control" type="date" name="fecha_final" id="fecha_final" value="<?php echo date("Y-m-d");?>">
							</div>
							<div class="col-sm-2">
								<label>Año Aplicación:</label>
								<select class="form-control filtro" id="year" name="year" >
									<option <?= date("Y") == "2020" ? "selected": "";?> value="2020">2020</option>
									<option <?= date("Y") == "2021" ? "selected": "";?> value="2021">2021</option>
									<option <?= date("Y") == "2022" ? "selected": "";?> value="2022">2022</option>
									<option <?= date("Y") == "2023" ? "selected": "";?> value="2023">2023</option>
									<option <?= date("Y") == "2024" ? "selected": "";?> value="2024">2024</option>
									<option <?= date("Y") == "2025" ? "selected": "";?> value="2025">2025</option>
								</select>
							</div>
							<div class="col-sm-2">
								<label>Mes Aplicación:</label>
								<select class="form-control filtro" id="mes" name="mes" >
									<option value="">Todos</option>
									<option <?= date("n") == "1" ? "selected": "";?> value="1">Enero</option>
									<option <?= date("n") == "2" ? "selected": "";?> value="2">Febrero</option>
									<option <?= date("n") == "3" ? "selected": "";?> value="3">Marzo</option>
									<option <?= date("n") == "4" ? "selected": "";?> value="4">Abril</option>
									<option <?= date("n") == "5" ? "selected": "";?> value="5">Mayo</option>
									<option <?= date("n") == "6" ? "selected": "";?> value="6">Junio</option>
									<option <?= date("n") == "7" ? "selected": "";?> value="7">Julio</option>
									<option <?= date("n") == "8" ? "selected": "";?> value="8">Agosto</option>
									<option <?= date("n") == "9" ? "selected": "";?> value="9">Septiembre</option>
									<option <?= date("n") == "10" ? "selected": "";?> value="10">Octubre</option>
									<option <?= date("n") == "11" ? "selected": "";?> value="11">Noviembre</option>
									<option <?= date("n") == "12" ? "selected": "";?> value="12">Diciembre</option>
									
								</select>
							</div>
							<div class="col-sm-4">
								<label for="nombre_condonaciones">Beneficiario:</label>
								<?= generar_select ($link, "beneficiarios", "id_beneficiarios", "nombre_beneficiarios", true, false, false, 0, 0 ,"id_beneficiarios",  "filtro_id_beneficiarios");?>
							</div>
							<div class="col-sm-3">
								<label for="">Motivo:</label>
								<?= generar_select ($link, "motivos_salida", "id_motivosSalida", "nombre_motivosSalida", true, false, false, 0, 0 ,"id_motivosSalida",  "filtro_id_motivosSalida");?>
							</div>	
							<div class="col-sm-2">
								<label for="id_usuarios">Usuario:</label>
								<?= generar_select ($link, "usuarios", "id_usuarios", "nombre_usuarios", true, false, false, 0, 0 ,"id_usuarios",  "filtro_id_usuarios");?>
							</div>
						</div>
						
					</form>
					<hr>
					
					
					<div class="card mb-3">
						<div class="card-header">
							<i class="fas fa-table"></i>
							Lista de <?php echo $nombre_pagina; ?>
							<button disabled id="imprimir_recibos" class="btn btn-info btn-sm float-right" type="button">
								<i class="fas fa-print"></i> Imprimir <span id="cant_seleccionados"></span>
							</button>
							<input type="hidden" id="folios_seleccionados" name="folios_seleccionados" >
						</div>
						<div class="card-body">
							
							<div class="table-responsive" id="tabla_registros">
								<table class="table table-bordered" id="tabla_recibos" width="100%" cellspacing="0" >
									<thead>
										<tr>
											<th class="text-center"><input type="checkbox" id="check_all"></th>
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
		<div class="d-print-inline d-none p-2 carta"   id="impresion">
			
		</div>
		
		<?php 
			include("../../scripts.php");
			include('forms/forms_salida.php');
		?>
		
		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		<script>
			$(document).ready(function() {
				$('#filtro_id_beneficiarios').select2({"width": "100%"});
				$('#id_beneficiarios').select2({"width": "100%"});
				$('#id_motivosSalida').select2({"width": "100%"});
			});
		</script>
		
		
		<script src="js/recibos_salida.js?v=<?= date("d-m-Y-H-i-s")?>"></script>
		<script src="../catalogos/js/buscar.js"></script>
	</body>
</html>
