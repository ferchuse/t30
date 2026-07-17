<?php
	include("../../paginas/login/login_check.php");
	include('../../conexi.php');
	$link = Conectarse();
	$nombre_pagina = "Pagos de Taquilla";
	//include('control/select_general.php');
	include('../../funciones/generar_select.php');
	
	//$dt_fecha_inicial = new DateTime("first day of this month");
	$dt_fecha_final = new DateTime("last day of this month");
	
	//$date_inicial = $dt_fecha_inicial->format("Y-m-d");
	$date_final = $dt_fecha_final->format("Y-m-d");
	
?>
<!DOCTYPE html>
<html lang="es_mx">
	<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title> <?php echo $nombre_pagina;?></title>
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
					
					<form class="form-inline d-print-none" id="form_filtros">
						
						<div class="form-group mx-sm-3 mb-2">
							<label for="" class="col-sm col-form-label">Desde:</label>
							<input type="date" class="form-control" value="<?php echo date("Y-m-d");?>" name="fecha_inicial" id="fecha_inicial">
						</div>
						<div class="form-group mx-sm-3 mb-2">
							<label for="" class="col-sm col-form-label">Hasta:</label>
							<input type="date" class="form-control" value="<?php echo $date_final;?>" name="fecha_final" id="fecha_final">
							
						</div>
						<div class="form-group mx-sm-3 mb-2">
							<label>Usuario: </label>
							<?php echo generar_select($link, "usuarios", "id_usuarios", "nombre_usuarios", true, false, false, $_COOKIE["id_usuarios"])?>
						</div>
						
						<div class=" col-md-2" hidden>
							<button type="button" id="btn_imprimir" title="Imprimir" class="btn btn-outline-primary mb-2"><i class="fas fa-print"></i></button>
							<button type="button" id="btn_excel" title="Exportar a Excel" class="btn btn-outline-success mb-2"><i class="far fa-file-excel"></i></button>
						</div>
						<button type="submit"  title="Buscar" class="btn btn-primary">
							<i class="fas fa-search"></i> Buscar
						</button>
					</form>
					
					<div class="card mb-3" id="tableCard">
						<div class="card-header">
							<i class="fas fa-table"></i>
							Lista de <?php echo $nombre_pagina;?>
						</div>
						<div class="card-body" id="lista_pagos">
							<div class="table-responsive">
								<table class="table table-bordered" id="tableData" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th class="text-center">Folio</th>
											<th class="text-center">Usuario</th>
											<th class="text-center">Fecha</th>
											<th class="text-center">Empresa</th>
											<th class="text-center">Importe</th>
											<th class="text-center d-print-none"></th>
										</tr>
										<tr class="d-print-none no_exportar">
											<th class="text-center"></th>
											<th class="text-center">
												<input type="text" id="buscar_usuario" class="form-control" data-indice="1" placeholder="Buscar Usuario">
											</th>
											<th class="text-center"></th>
											<th class="text-center"></th>
											<th class="text-center"></th>
											<th class="text-center"></th>
											<th class="text-center"></th>
										</tr>
									</thead>
									<tbody id="tabla_DB">
										<tr>
											<td colspan="7"><h3 class="text-center">Cargando...</h3></td>
										</tr>
									</tbody>
									<tr>
										<th colspan="5" >Total</th>
										<th class="text-center" id="total"></th>
										<th colspan="1" class="d-print-none" ></th>
									</tr>
								</table>
								<div class="mensaje"></div>
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
		
		<div class="d-print-block p-2" hidden id="ticket"></div>
    <?php include("../../scripts.php")?>
		<script src="../../plugins/pos_print/websocket-printer.js" > </script>
    <script src="pagos_taquilla/pagos_taquilla.js?v=<?= date("Y-m-d-H-i-s")?>"></script>
	</body>
</html>