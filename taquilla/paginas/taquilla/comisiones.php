<?php
	include("../../paginas/login/login_check.php");
	include('../../conexi.php');
	include('../../funciones/generar_select.php');
	$link = Conectarse();
	$nombre_pagina = "Comisiones por Operador";
	
	
	$dt_fecha_final = new DateTime("last day of this month");
	
	$date_final = $dt_fecha_final->format("Y-m-d");
	
	$cat_empresas = [1=> "TAXI DRIVER VIAJE CONFIABLE", 2=> "TAXI DRIVER VIAJE CONFIABLE" ];
	
	
	$empresa = array();
	
	$consulta = "SELECT * FROM empresas WHERE id_empresas = '{$_COOKIE["empresa_asignada"]}'";
	
	$result = mysqli_query($link,$consulta);
	
	if(!$result){
		return ("Error en $consulta" . mysqli_error($link) );
	}
	else{
		
		while($row = mysqli_fetch_assoc($result)){
			$empresa = $row;        
		}
		
	}
	
	
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Comisiones por Operador </title>
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
							Comisiones por Operador
							<div class="btn-group float-right">
								
								<button type="button" class="btn btn-primary btn-sm float-right " id="btn_exportar" >
									<i class="fa fa-file-excel"></i> Exportar  
								</button>
							</div>
						</div>
						<div class="card-body">
							
							<form id="form_filtros" >
								<div class="row">
									
									
									<div class="form-group col-sm-2">
										<label> Fecha Inicial:</label>
										<input type="datetime-local" class="form-control" value="<?php echo date("Y-m-d 00:00:00");?>" name="fecha_inicial" id="fecha_inicial" step="any">
									</div>
									
									<div class="form-group col-sm-2">
										<label> Fecha Final:</label>
										<input type="datetime-local" class="form-control" value="<?php echo date("Y-m-d 23:59:59");?>" name="fecha_final" id="fecha_final" step="any">
									</div>
									
									
									<div class="form-group col-sm-2">
										<label>Comisión:</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
											</div>
											<input type="number" class="form-control text-right" 
											value="<?php echo $empresa['comision'];?>" 
											name="comision" id="comision">
										</div>
									</div>
									
									<div class="form-group col-sm-2">
										<label>Límite Incentivo:</label>
										<div class="input-group">
											<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-dollar-sign"></i></i></span>
										</div>
										<input type="number" class="form-control text-right" 
										value="<?php echo $empresa['limite_incentivo'];?>" 
										name="limite_incentivo" id="limite_incentivo">
									</div>
								</div>
								
								<div class="form-group col-sm-2">
									<label>Porcentaje Incentivo:</label>
									<div class="input-group">
										<input type="number" class="form-control text-right" 
										value="<?php echo $empresa['porc_incentivo'];?>" 
										name="porc_incentivo" id="porc_incentivo">
										<div class="input-group-append">
											<span class="input-group-text">%</span>
										</div>
									</div>
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
	<script src="js/comisiones.js?v=<?= date("Y-m-d-H-i-s")?>"></script>
	
</body>
</html>																												