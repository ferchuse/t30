<?php
	include("../../paginas/login/login_check.php");
	include('../../conexi.php');
	include('../../funciones/generar_select.php');
	$link = Conectarse();
	$nombre_pagina = "Gastos por Unidad";
	
	
	$dt_fecha_final = new DateTime("last day of this month");
	
	$date_final = $dt_fecha_final->format("Y-m-d");
	
	$cat_empresas = [1=> "TAXI DRIVER VIAJE CONFIABLE", 2=> "TAXI DRIVER VIAJE CONFIABLE" ];
	
	
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Gastos </title>
		<?php include('../../styles.php')?>
		
	</head>
	<body id="page-top">
		<?php include("../../navbar.php")?>
		<div id="wrapper" class="d-print-none">
			<?php include("../../menu.php")?>	
			<div id="content-wrapper">		
				<div class="container-fluid">		
					
					
					<div class="card card-secondary ">
						<div class="card-header h4">
							Gastos por Unidad
						</div>
						<div class="card-body">
							
							<form id="form_filtros" >
								<div class="row">
									
									
									<div class="form-group col-sm-2">
										<label> Fecha Inicial:</label>
										<input type="date" class="form-control" value="<?php echo date("Y-m-d");?>" name="fecha_inicial" id="fecha_inicial">
									</div>
									
									<div class="form-group col-sm-2">
										<label> Fecha Final:</label>
										<input type="date" class="form-control" value="<?php echo date("Y-m-d");?>" name="fecha_final" id="fecha_final">
									</div>
									<div class="form-group col-sm-1">
										<label>Usuario:</label>
										<?php 
											if (dame_permiso("gastos_unidad.php", $link) == "Supervisor"){
												echo generar_select($link, "usuarios", "id_usuarios", "nombre_usuarios", true, false, false);
											}
											elseif (dame_permiso("gastos_unidad.php", $link) == "Lectura"){
												echo generar_select($link, "usuarios", "id_usuarios", "nombre_usuarios", true, false, false);
											}
											else{
												echo "<input class='form-control' readonly value='{$_COOKIE["nombre_usuarios"]}'>";
												echo "<input hidden name='id_usuarios'  value='{$_COOKIE["id_usuarios"]}'>";	
											}
										?>
									</div>
									<div class="form-group col-sm-2">
										<label>Num Eco:</label>
										<?php 
											
											echo generar_select($link, "unidades", "num_eco", "num_eco", true, false, false);
											
										?>
									</div>
									<div class="form-group col-sm-2 d-none">
										<label>Empresa:</label>
										
										<input class="form-control" type="text" readonly value="<?php echo $cat_empresas[$_COOKIE["empresa_asignada"]]?>">
										<input class="form-control" type="hidden" name="id_empresas" value="<?php echo $_COOKIE["empresa_asignada"]?>">
										
										
									</div>
									
									<div class="form-group col-sm-2 pt-4">
										<button type="submit"  title="Buscar" class="btn btn-primary  d-print-none">
											<i class="fas fa-search"></i> Buscar
										</button>
									</div>
									
								</div>
								
							</form>
							<div  id="lista_registros" class="table-responsive">
								
							</div>
						</div>
					</div> 
					
					
					
				</div><!-- /.container-fluid --> 
			</div> 
			<!-- /.content-wrapper -->
		</div>
		<!-- /#wrapper -->
		
		<!-- Scroll to Top Button-->
		<a class="scroll-to-top rounded d-print-none" href="#page-top">
			<i class="fas fa-angle-up"></i>
		</a>
		
		<?php include("../../scripts.php")?>
		
		<script src="../../lib/websocket-printer.js" > </script>
		<script src="js/gastos_unidad.js?v=<?= date("Y-m-d-H-i-s")?>"></script>
		
	</body>
</html>																												