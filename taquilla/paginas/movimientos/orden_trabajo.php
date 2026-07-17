<?php
	$nombre_pagina = "Ordenes de trabajo";
	
?>

<?php
	include('../../conexi.php');
	$link = Conectarse();
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
    <title>Recaudación de <?php echo $nombre_pagina;?></title>
		<?php include('../../styles.php')?>
	</head>
	<body id="page-top">
    <?php include("../../navbar.php")?>
    <div id="wrapper" class="d-print-none">
			<?php include("../../menu.php")?>	
			<div id="content-wrapper">		
				<div class="container-fluid">		
					<!-- Breadcrumbs-->
					<ol class="breadcrumb d-print-none">
						<li class="breadcrumb-item">
							<a href="#">Movimientos</a>
						</li>
						<li class="breadcrumb-item active"><?php echo $nombre_pagina;?></li>
					</ol>
					<div class="row ">
						<div class="col-12">
							<button type="button" class="btn btn-success mb-2 nuevo  d-print-none">
								<i class="fas fa-plus"></i> Nueva
							</button>
							<form id="form_filtros" class="form-inline">
								<div class="form-group">
									<label>
										Operador:
									</label>
									<?php echo generar_select($link, "conductores", "id_conductores", "nombre_conductores", true	);	?>
								</div>
								<div class="form-group mx-sm-3 mb-2">
									<label for="num_eco" >Num Eco:</label>
									<input type="number" class="form-control input-sm" name="num_eco" >
								</div>
								<div class="form-group mx-sm-3 mb-2">
									<label for="" class="col-sm col-form-label">Desde:</label>
									<input type="date" class="form-control" value="<?= date("Y-m-d");?>" name="fecha_inicial" id="fecha_inicial">
								</div>
								<div class="form-group mx-sm-3 mb-2">
									<label for="" class="col-sm col-form-label">Hasta:</label>
									<input type="date" class="form-control" value="<?= date("Y-m-d" , strtotime("+30 days"));?>" name="fecha_final" id="fecha_final">
									
								</div>
								
								<button type="submit"  title="Buscar" class="btn btn-primary  d-print-none">
									<i class="fas fa-search"></i>
								</button>
							</form>
						</div>
					</div>
					<div class="card mb-3 d-print-none" id="tableCard">
						<div class="card-header">
							<i class="fas fa-table"></i>
							Lista de <?php echo $nombre_pagina;?>
						</div>
						<div class="card-body">
							<div class="table-responsive"  id="lista_registros">
								
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
		
		<div id="impresion" class="d-print-block d-none">
		</div>
		
		
		<!-- Scroll to Top Button-->
		<a class="scroll-to-top rounded d-print-none" href="#page-top">
			<i class="fas fa-angle-up"></i>
		</a>
	
		
		<?php include("forms/form_orden.php");?>
    <?php include("../../scripts.php")?>
    <script src="js/orden_trabajo.js?v=<?= date("d-m-Y-H-i-s")?>"></script>
    <script src="js/buscar.js"></script>
	</body>
</html>