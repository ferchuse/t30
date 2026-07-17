<?php
	include("../login/login_check.php");
	include('../../conexi.php');
	$link = Conectarse();
	$nombre_pagina = "Descuentos";
	$id= "id_descuento";
	$tabla = "tipo_descuento";
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
		<title>Catálogo de <?php echo $nombre_pagina?></title>
		<?php include('../../styles.php')?>
	</head>
	<body id="page-top">
		<?php include("../../navbar.php")?>
		<div id="wrapper">
			<?php include("../../menu.php")?>	
			<div id="content-wrapper">		
				<div class="container-fluid">		
					<!-- Breadcrumbs-->
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="#">Catálogos</a> 
						</li>
						<li class="breadcrumb-item active"><?php echo $nombre_pagina?></li>
					</ol>
					<div class="row mb-2">
						<div class="col-12">
							<button type="button" class="btn btn-success btn-sm nuevo">
								<i class="fas fa-plus"></i> Nuevo
							</button>
						</div>
					</div>
					
					<form id="form_filtros" autocomplete="off">								
						<div class="row mb-2"> 
							<div class="col-sm-3">
								<label for="">Nombre:</label>
								<input class="form-control" type="search" name="tipo_descuento"  >
							</div>	
							
							
							<div class="col-sm-3">
								
								<label >Estatus:</label>
								
								<select class="form-control" name="estatus_descuento">
									<option value="">Todos</option>
									<option>Activo</option>
									<option>Inactivo</option>
								</select>
								
							</div>
							<div class="col-sm-3 m-t-4">
								
								<button class="btn btn-primary btn-sm" >
									<i class="fas fa-search"></i> Buscar
								</button>
								
							</div>
							<input type="hidden" id="order_by" name="order_by" value="tipo_descuento">
							<input type="hidden"  id="sort" name="sort" value="ASC">
						</div>
					</div>
					
				</form>
				
				
				
				<div class="table-responsive" id="tabla_registros">
					
					
				</div>
				
			</div>
		</div>
		<!-- /.container-fluid -->
		
	</div>
	<!-- /.content-wrapper -->
</div>
<!-- /#wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
	<i class="fas fa-angle-up"></i>
</a>		
<?php 
	include("../../scripts.php");
	include("forms/form_descuentos.php");
?>
<script src="js/descuentos.js?v=<?= date("Ymdhis")?>"></script>

</body>
</html>
