<?php
	include("../login/login_check.php");
	include_once("control/is_selected.php");
	include_once("../../conexi.php");
	$link = Conectarse();
	$menu_activo = "facturas";
	
	$year = date("Y");
	$mes = date("n");
	$cat_empresas = [1=> "TAXI DRIVER VIAJE CONFIABLE", 2=> "TAXI DRIVER VIAJE CONFIABLE", ]
	
	
	
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Facturas</title>
		
		<?php include("../../styles.php");?>
		
	</head>
	<body id="page-top">
		<?php include("../../navbar.php")?>
		<div id="wrapper" class="">
			<?php include("../../menu.php")?>	
			<div id="content-wrapper">		
				<div class="container-fluid">		
					<h3 class="text-center">Facturas</h3>
					<div class="row"  > 
						<div class="col-sm-7"  > 
							
							<form class="form-inline hidden-print" id="form_filtros">
								<div class="form-group px-2">
									<label for="id_ciclos" class="text-center">Año:</label>
									<select class="form-control filtro" id="year_facturas" name="year_facturas" >
										
										<option <?php echo is_selected("2023", $year);?> value="2023">2023</option>
										<option <?php echo is_selected("2024", $year);?> value="2024">2024</option>
										<option <?php echo is_selected("2025", $year);?> value="2025">2025</option>
										<option <?php echo is_selected("2026", $year);?> value="2026">2026</option>
										<option <?php echo is_selected("2027", $year);?> value="2027">2027</option>
										<option <?php echo is_selected("2028", $year);?> value="2028">2028</option>
									</select>
								</div>
								<div class="form-group px-2">
									<label for="mes_facturas" class="text-center">Mes:</label>
									<select class="form-control filtro" id="mes_facturas" name="mes_facturas" >
										<option value="">Todos</option>
										<option <?php echo is_selected("1", $mes);?> value="1">ENERO</option>
										<option <?php echo is_selected("2", $mes);?> value="2">FEBRERO</option>
										<option <?php echo is_selected("3", $mes);?> value="3">MARZO</option>
										<option <?php echo is_selected("4", $mes);?> value="4">ABRIL</option>
										<option <?php echo is_selected("5", $mes);?> value="5">MAYO</option>
										<option <?php echo is_selected("6", $mes);?> value="6">JUNIO</option>
										<option <?php echo is_selected("7", $mes);?> value="7">JULIO</option>
										<option <?php echo is_selected("8", $mes);?> value="8">AGOSTO</option>
										<option <?php echo is_selected("9", $mes);?> value="9">SEPTIEMBRE</option>
										<option <?php echo is_selected("10", $mes);?> value="10">OCTUBRE</option>
										<option <?php echo is_selected("11", $mes);?> value="11">NOVIEMBRE</option>
										<option <?php echo is_selected("12", $mes);?> value="12">DICIEMBRE</option>
									</select>
								</div>
								
								<div class="form-group px-2 col-sm-4 d-none">
									<label for="id_emisores" class="text-center">Emisor:</label>
									
									<input class="form-control" type="text" readonly value="<?php echo $cat_empresas[$_COOKIE["empresa_asignada"]]?>">
								<input class="form-control" type="hidden" name="id_emisores" value="1">
									
								</div>
								<div class="form-group px-2 d-none">
									<label for="metodo_pago" class="text-center">Método de Pago:</label>
									<select class="form-control filtro" name="metodo_pago" >
										<option value="">Todos</option>
										<option value="PUE">En una Exhibición</option>
										<option value="PPD">Parcialidades o Diferido</option>
									</select>
								</div>
								
								<div class="form-group px-2 d-none">
									<label for="tipo_comprobante" class="text-center">Tipo:</label>
									<select class="form-control filtro" name="tipo_comprobante" >
										<option value="">Todos</option>
										<option value="I">Facturas</option>
										<option value="P">Pago</option>
									</select>
								</div>
								
								
								
								<div class="form-group px-2">
									<input class="form-control" type="search" id="buscar_cliente" placeholder="Buscar Cliente">	
								</div>
								<div class="checkbox">
									<label >
										<input type="checkbox"  class="filtro" value="1" name="mostrar_pruebas" id="mostrar_pruebas">  Mostrar Pruebas
									</label>
								</div>
							</form>
						</div>
						<div class="col-sm-5"  > 
							<div class="float-right">
								<a  class="btn btn-success btn-sm" href="nueva_factura.php" >
									<i class="fa fa-plus" ></i> Nueva Factura
								</a>	
								<button class="btn btn-primary exportar btn-sm">
									<i class="fa fa-arrow-right" ></i> Exportar
								</button>	
								<button class="btn btn-info btn-sm" onclick="window.print()">
									<i class="fa fa-print" ></i> Imprimir
								</button>	
								<input type="hidden" id="folios_seleccionados" name="folios" form="form_pago" >
								<button disabled id="btn_pagar_varios"   type="button"  class="btn btn-warning btn-sm">
									<i class="fa fa-dollar"></i> Pagar <span id="cant_seleccionados"></span>
								</button>
							</div>
						</div>
					</div>
					
				</div>
				<hr>
				<div class="container-fluid"  > 
					<div class="row">
						<div class="col-sm-12" >
							<div class="panel panel-primary" >
								<div class="panel-body "  >
									<div class="table-responsive" id="lista_facturas">
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		
		<form id="form_correo" class="form" >
			<div id="modal_correo" class="modal fade" role="dialog">
				<div class="modal-dialog modal-sm"> 
					<!-- Modal content--> 
					<div class="modal-content">
						<div class="modal-header">
							
							<h4 class="modal-title text-center">Enviar Factura</h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						
						<div class="modal-body">
							
							<div class="form-group">
								<label for="correo">Correo:</label>
								<input  type="text" required name="correo" id="correo" class="form-control minus" >
								<input type="hidden" name="url_xml" id="url_xml" class="form-control" >
								<input type="hidden" name="url_pdf" id="url_pdf" class="form-control" >
								<input type="hidden" name="folio" id="folio_facturas" class="form-control" >
								<input type="hidden" name="id_emisores" id="id_emisores" class="form-control" >
							</div>
						</div>
						
						<div class="modal-footer">
							
							<button type="button" class="btn btn-danger" data-dismiss="modal">
								<i class="fa fa-times"></i> Cancelar
							</button>
							<button type="submit" class="btn btn-success">
								<i class="fa fa-envelope" ></i> Enviar
							</button>
							
						</div>
						
					</div>
				</div>
			</div>
		</form>
		
		
		
		
		
		<?php  include('../../scripts.php'); ?>
		<?php  include('forms/form_pago.php'); ?>
		<?php  include('forms/form_cancelar.php'); ?>
		<script src="js/facturas.js?v=<?= date("Ymdhis")?>"></script>
		
		
		
	</body>
</html>
