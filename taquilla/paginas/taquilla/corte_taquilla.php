<?php
	include("../../paginas/login/login_check.php");
	include('../../conexi.php');
	$link = Conectarse();
	$nombre_pagina = "Corte de Caja";
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
		<title><?php echo $nombre_pagina;?></title>
		<?php include('../../styles.php')?>
		
		<style>
			/* Efectivo */
			.efectivo {
			background-color: #d4edda; /* Fondo verde suave */
			color: #155724; /* Texto verde oscuro */
			}
			
			/* Transferencia */
			.transferencia {
			background-color: #cce5ff; /* Fondo azul suave */
			color: #004085; /* Texto azul oscuro */
			}
			
			/* Tarjeta */
			.tarjeta {
			background-color: #fff3cd; /* Fondo amarillo suave */
			color: #856404; /* Texto naranja oscuro */
			}
			
			/* Ingresos en Efectivo */
			.ingreso-efectivo {
			background-color: #d4edda; /* Fondo verde suave */
			color: #155724; /* Texto verde oscuro */
			}
			
			/* Egresos en Efectivo */
			.egreso-efectivo {
			background-color: #f8d7da; /* Fondo rojo suave */
			color: #721c24; /* Texto rojo oscuro */
			}
		</style>
	</head>
	<body id="page-top">
		<?php include("../../navbar.php")?>
		<div id="wrapper" class="">
			<?php include("../../menu.php")?>	
			<div id="content-wrapper">		
				<div class="container-fluid">		
					<!-- Breadcrumbs-->
					<ol class="breadcrumb d-print-none">
						<li class="breadcrumb-item">
							<a href="#">Taquilla</a>
						</li>
						<li class="breadcrumb-item active"><?php echo $nombre_pagina;?></li>
					</ol>
					<h3 class="d-none d-print-block">
						Corte
					</h3>
					<form class="" id="form_filtro">
						<div class="row">
							<div class="col-sm-2 ">
								<label for="" >Fecha inicial:</label>
								<input type="datetime-local" class="form-control" value="<?php echo date("Y-m-d 00:00:00");?>" name="fecha_inicial" id="fecha_inicial">
							</div>
							<div class="col-sm-2 ">
								<label for="" >Fecha Final:</label>
								<input type="datetime-local" class="form-control" value="<?php echo date("Y-m-d 23:59:59");?>" name="fecha_final" id="fecha_final">
							</div>
							<div class="col-sm-2">
								<label>Usuario:</label>
								<?php 
									if (in_array(dame_permiso("corte_taquilla.php", $link) , array("Supervisor","Administrador", "Lectura"))){
										echo generar_select($link, "usuarios", "id_usuarios", "nombre_usuarios", true, false, false);
									}
									else{
										echo "<input class='form-control' readonly value='{$_COOKIE["nombre_usuarios"]}'>";
										echo "<input hidden name='id_usuarios'  value='{$_COOKIE["id_usuarios"]}'>";	
									}
								?>
							</div>
							<div class="col-sm-2 ">
								<button class="btn btn-primary">
									<i class="fas fa-search"></i> Buscar
								</button>
							</div>
							
						</div>
					</form>
					
					
					<div class="d-print-block" hidden id="formato_imprimir">
					</div>
					
					
					<div class="card mb-3" id="tableCard">
						
						<div class="card-body">
							<div class="table-responsive" id="tabla_registros">
								
							</div>
						</div>
						<!--<div class="card-footer small text-muted">Ultima Modificación Ayer 12pm</div>-->
					</div>
				</div>
				<!-- /.container-fluid -->
				
				
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
		<script src="../../lib/websocket-printer.js" > </script>
		<script >
			var printService = new WebSocketPrinter();
		</script >
		<script >
			listarRegistros();
			
			$('#form_filtro').on('submit', function filtrar(event){
				event.preventDefault();
				
				listarRegistros();
				
			});
			
			function listarRegistros(){
				console.log("listarRegistros()");
				$("#tabla_registros").html("<h3 class='text-center'>Cargando <i class='fas fa-spinner fa-spin'></i></h3>")
				let form = $("#form_filtro");
				let boton = form.find(":submit");
				let icono = boton.find('.fas');
				
				boton.prop('disabled',true);
				icono.toggleClass('fa-save fa-spinner fa-pulse ');
				
				return $.ajax({
					url: 'consultas/lista_corte_taquilla.php',
					data: $("#form_filtro").serialize()
					}).done(function(respuesta){
					
					$("#tabla_registros").html(respuesta)
					// $("#dataTable").dataTable();
					$(".btn_imprimir").click(imprimirTicket);
					// $(".cancelar").click(confirmaCancelacion);
					
					
					}).always(function(){
					
					boton.prop('disabled',false);
					icono.toggleClass('fa-save fa-spinner fa-pulse fa-fw');
					
				});
				
			}
			
			
			
			function imprimirTicket(){
				console.log("imprimirTicket()");
				var id_usuarios = $(this).data("id_usuarios");
				var fecha_inicial = $("#fecha_inicial").val();
				var fecha_final = $("#fecha_final").val();
				
				
				
				var boton = $(this); 
				var icono = boton.find("fas");
				
				boton.prop("disabled", true);
				icono.toggleClass("fa-print fa-spinner fa-spin");
				
				$.ajax({
					url: "impresion/imprimir_corte_taquilla.php" ,
					data:
					{
						"id_usuarios" : id_usuarios,
						"fecha_inicial" : fecha_inicial,
						"fecha_final" : fecha_final
						
					}
					
					}).done(function (respuesta){
					
					
					if(window.AppInventor){
						window.AppInventor.setWebViewString(atob(respuesta));
					}
					
					printService.submit({
						'type': 'LABEL',
						'raw_content': respuesta
					});
					
					
					
					}).always(function(){
					
					boton.prop("disabled", false);
					icono.toggleClass("fa-print fa-spinner fa-spin");
					
				});
			}
			
			
		</script>
		
	</body>
</html>	