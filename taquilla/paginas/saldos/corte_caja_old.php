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
							<a href="#">Saldos</a>
						</li>
						<li class="breadcrumb-item active"><?php echo $nombre_pagina;?></li>
					</ol>
					
					<form class="form-inline d-print-none" id="form_filtro">
						<div class="form-group mx-sm-3 mb-2">
							<label for="" class="col-sm col-form-label">Fecha Inicial:</label>
							<input type="date" class="form-control" value="<?php echo date("Y-m-d");?>" name="fecha_inicial" id="fecha_inicio">
						</div>
						<div class="form-group mx-sm-3 mb-2">
							<label for="" class="col-sm col-form-label">Fecha Final:</label>
							<input type="date" class="form-control" value="<?php echo date("Y-m-d");?>" name="fecha_final" id="fecha_final">
							<button type="submit" id="btn_fechas" title="Buscar por Fecha" class="btn btn-outline-dark">
									<i class="fas fa-search"></i>
							</button>
						</div>
						<div class=" col-md-2">
							<button type="button" id="btn_imprimir" title="Imprimir" class="btn btn-outline-primary mb-2" onclick="window.print();">
								<i class="fas fa-print"></i> Imprimir
							</button>
						</div>
					</form>
					
					
					
					<div class="card mb-3 d-print-block" id="tableCard">
						<div class="card-header">
							<i class="fas fa-table"></i>
							<?php echo $nombre_pagina;?>
						</div>
						<div class="card-body">
							<div class="table-responsive" id="tabla_registros">
								
							</div>
						</div>
						<!--<div class="card-footer small text-muted">Ultima Modificación Ayer 12pm</div>-->
					</div>
				</div>
				<!-- /.container-fluid -->
				
				<!-- Sticky Footer -->
				<footer class="sticky-footer">
					<div class="container my-auto ">
						<div class="copyright text-center my-auto">
							<span class="d-print-none">Copyright © Glifo Media 2018</span>
						</div>
					</div>
				</footer>
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
					url: 'control/lista_corte_caja.php',
					data: $("#form_filtro").serialize()
					}).done(function(respuesta){
					
					$("#tabla_registros").html(respuesta)
					// $("#dataTable").dataTable();
					// $(".imprimir").click(imprimirTicket);
					// $(".cancelar").click(confirmaCancelacion);
					
					
					}).always(function(){
					
					boton.prop('disabled',false);
					icono.toggleClass('fa-save fa-spinner fa-pulse fa-fw');
					
				});
				
			}
			
		</script>
		
	</body>
</html>