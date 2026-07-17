<?php
	include("../../paginas/login/login_check.php");
	include('../../conexi.php');
	include('../../funciones/generar_select.php');
	$link = Conectarse();
	$nombre_pagina = "Boletos";
	
	
	$dt_fecha_final = new DateTime("last day of this month");
	
	$date_final = $dt_fecha_final->format("Y-m-d");
	
	$cat_empresas = [1=> "TAXI DRIVER VIAJE CONFIABLE", 2=> "TAXI DRIVER VIAJE CONFIABLE", ]
	
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Boletos Vendidos</title>
		<?php include('../../styles.php')?>
		<link href="../../css/corrida.less" type="text/css"  rel="stylesheet/less" >
	</head>
	<body id="page-top">
		<?php include("../../navbar.php")?>
		<div id="wrapper" class="d-print">
			<?php include("../../menu.php")?>	
			<div id="content-wrapper">		
				<div class="container-fluid">		
					
					
					
					<div class="card-header d-print-none mb-2 d-flex justify-content-between">
						<h5>Boletos Vendidos</h5>
						<div class="btn-group float-right">
							<button type="button" onclick="window.print();"  title="Imprimir" class="btn btn-sm btn-info float-right d-print-none">
								<i class="fas fa-print"></i> Imprimir
							</button>
							<button type="button" class="btn btn-primary btn-sm float-right " id="btn_exportar" >
								<i class="fa fa-file-excel"></i> Exportar  
							</button>
							<button type="button" class="btn btn-primary btn-sm float-right " id="btn_exportar_aifa" >
								<i class="fa fa-file-excel"></i> Reporte AIFA  
							</button>
						</div>
					</div>
					
					
					<form id="form_filtros" >
						<div class="row">
							
							
							<div class="col-sm-2 ">
								<div class="mb-2">
									
									<label for="" >Fecha Inicial:</label>
									<input type="datetime-local" class="form-control" value="<?php echo date("Y-m-d 00:00:00");?>" name="fecha_inicial" id="fecha_inicial" step="any">
								</div>
								<div class="form-group ">
									<label for="" >Fecha Final:</label>
									<input type="datetime-local" class="form-control" value="<?php echo date("Y-m-d 23:59:59");?>" name="fecha_final" id="fecha_final" step="any">
								</div>
							</div>
							
							
							<div class="col-md-1">
								<div class="mb-2">
									
									<label>Usuario:</label>
									<?php 
										if(in_array(dame_permiso("boletos_vendidos.php", $link), array('Supervisor', "Administrador", "Lectura"))){
											echo generar_select($link, "usuarios", "id_usuarios", "nombre_usuarios", true, false, false);
										}
										// elseif (dame_permiso("boletos_vendidos.php", $link) == "Lectura"){
										// echo generar_select($link, "usuarios", "id_usuarios", "nombre_usuarios", true, false, false);
										// }
										else{
											echo "<input class='form-control' readonly value='{$_COOKIE["nombre_usuarios"]}'>";
											echo "<input hidden name='id_usuarios'  value='{$_COOKIE["id_usuarios"]}'>";	
										}
									?>
								</div>
								<label>Taquilla:</label>
								<select  class="form-control"  name="taquilla">
									<option selected value="">Todos</option>
									<option  value="NACIONAL">NACIONAL</option>
									<option  value="INTERNACIONAL">INTERNACIONAL</option>
								</select>
							</div>
							<div class="form-group col-md-1">
								<div class="mb-2">
									<label>Num Eco:</label>
									<?php 
										
										echo generar_select($link, "unidades", "num_eco", "num_eco", true, false, false);
										
									?>
								</div>
								<div class="mb-2">
									<label>Folio:</label>
									<input type="number" name="id_boletos" class="form-control">
								</div>
							</div>
							<div class="form-group col-md-2 d-none">
								<label>Empresa:</label>
								
								<input class="form-control" type="text" readonly value="<?php echo $cat_empresas[$_COOKIE["empresa_asignada"]]?>">
								<input class="form-control" type="hidden" name="id_empresas" value="<?php echo $_COOKIE["empresa_asignada"]?>">
								<?php 
									
									// echo generar_select($link, "empresas", "id_empresas", "nombre_empresas", true, false, false);
								?>
							</div>
							<div class="col-sm-1">
									<div class="mb-2">
									<label>Facturar:</label>
								<select  class="form-control"  name="facturar">
									<option selected value="">Todos</option>
									<option  value="SI">SI</option>
									<option  value="NO">NO</option>
								</select>
								</div>
								<div class="mb-2">
									<label>Terminal:</label>
									<?php 
										
										echo generar_select($link, "cat_terminales", "id_terminal", "terminal", true, false, false);
										
									?>
								</div>
							
								
							</div>
							<div class="col-sm-1">
								<label>F de Pago:</label>
								<select class="form-control" id="filtro_forma_pago" name="forma_pago" >
									<option value="" >Todos</option>
									<option  >Efectivo</option>
									<option  >Transferencia</option>
									<option >Tarjeta</option>
									<option  >Mixto</option>
								</select>
							</div>
							<div class="form-group col-md-2">
								<label class="">Operador:</label>
								<?php 
									echo generar_select($link, "conductores", "id_conductores", "nombre_conductores", true, false, false, 0 , 0 , "id_conductores", "filtro_conductores");
								?>
							</div>
							<div class="col-sm-1">
								
								<label>Estatus:</label>
								<select  class="form-control"  name="estatus">
									<option selected value="">Todos</option>
									<option  value="Activo">Activo</option>
									<option  value="Cancelado">Cancelado</option>
								</select>
								
							</div>
							<div class="form-groupcol-md-1 pt-4"> 
								<button type="submit"  title="Buscar" class="btn btn-primary  d-print-none btn-sm ">
									<i class="fas fa-search"></i> Buscar
								</button>	
								
							</div>
							
						</div>
						
					</form>
					<div  id="lista_boletos" class="table-responsive d-print-block">
						
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
		<?php include("../../scripts.php")?>
		<?php include("gastos/form_gastos.php");?>
		<?php include("forms/form_editar_boleto.php");?>
		<?php include("forms/modal_historial.php");?>
		<script src="../../lib/websocket-printer.js" > </script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/less.js/3.9.0/less.min.js" ></script>
		<script src="js/boletos_vendidos.js?v=<?= date("Y-m-d-H-i-s")?>"></script>
		
	</body>
</html>																												