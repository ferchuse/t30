<?php
	include("../../paginas/login/login_check.php");
	include('../../conexi.php');
	include('../../funciones/generar_select.php');
	$link = Conectarse();
	$nombre_pagina = "Boletos";
	
	
	$hoy = new DateTime();
	
	// Obtener número del día de la semana:
	// 1=Lunes ... 7=Domingo
	$dia_semana = $hoy->format('N');
	
	// Queremos llegar al jueves (4)
	$dias_retroceder = $dia_semana - 4;
	
	// Si estamos antes del jueves (lun, mar, mié)
	// nos regresamos a la semana pasada
	if($dias_retroceder < 0){
		$dias_retroceder += 7;
	}
	
	// Fecha inicial = jueves más reciente
	$fecha_inicial = clone $hoy;
	$fecha_inicial->modify("-{$dias_retroceder} days");
	
	// Fecha final = miércoles siguiente (6 días después)
	$fecha_final = clone $fecha_inicial;
	$fecha_final->modify("+6 days");
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
						<div class="card-header">
							Combustible por Operador
							
							<button type="button" id="btn_nuevo" title="Nuevo" class="btn btn-success  d-print-none float-right">
								<i class="fas fa-plus"></i> Nuevo
							</button>
						</div>
						<div class="card-body">
							
							<form id="form_filtros" >
								<div class="row">
									
									
									<div class="form-group col-sm-2">
										<label>Fecha Inicial:</label>
										<input 
										type="date" 
										class="form-control" 
										value="<?php echo $fecha_inicial->format('Y-m-d');?>" 
										name="fecha_inicial" 
										id="fecha_inicial">
									</div>
									
									<div class="form-group col-sm-2">
										<label>Fecha Final:</label>
										<input 
										type="date" 
										class="form-control" 
										value="<?php echo $fecha_final->format('Y-m-d');?>" 
										name="fecha_final" 
										id="fecha_final">
									</div>
									
									
									<div class="form-group col-sm-2 pt-4">
										<button type="submit"  title="Buscar" class="btn btn-primary  d-print-none">
											<i class="fas fa-search"></i> Buscar
										</button>
									</div>
									
								</div>
								
							</form>
							<div  id="lista_gastos" class="table-responsive">
								
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
		
		<div class="d-print-block p-2" hidden id="ticket">
		</div>
		<?php include("gastos/form_gastos_operador.php")?>
		<?php include("../../scripts.php")?>
		
		<script src="../../lib/websocket-printer.js" > </script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/less.js/3.9.0/less.min.js" ></script>
		<script src="gastos/gastos_operador.js?v=<?= date("Y-m-d-H-i-s")?>"></script>
		
		
		
	</body>
</html>																												