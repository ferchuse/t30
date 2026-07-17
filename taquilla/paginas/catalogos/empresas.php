<?php
	include("../login/login_check.php");
	$nombre_pagina = "Empresas";
	$id= "id_empresas";
	$tabla = "empresas";
?>


<!DOCTYPE html>
<html lang="en">
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
						<li class="breadcrumb-item active">Empresas</li>
					</ol> 
					
					<div class="row"> 
						<div class="col-md-12">
							<button class="btn btn-success btn-sm" id="nuevaEmpresa"><i class="fas fa-plus"></i> Nueva</button>
						</div>
					</div>
					<br>
					
					<div class="card mb-3">
						<div class="card-header">
							<i class="fas fa-table"></i>
							Lista de Empresas
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered" id="tabla_empresas" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th class="text-center">No. Empresa</th>
											<th class="text-center">Nombre Empresa</th>
											<th class="text-center">Usuario</th>
											<th></th>
										</tr> 
									</thead>
									<thead>
										<tr>
											<th class="text-center"></th>
											<th class="text-center">
												<input type="text" data-indice="1" class="form-control" placeholder="Buscar Empresa" id="buscar_empresa">
											</th>
											<th colspan=2 class="text-center"></th>
										</tr> 
									</thead>
									<tbody id="containerLista">
										<tr>
											<td colspan="4"><h3 class="text-center">Cargando...</h3></td>
										</tr>
									</tbody>
								</table>
								<div id="mensaje"></div>
							</div>
						</div>
						<div class="card-footer small text-muted"></div>
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
			include("forms/form_empresas.php");
		?>
    <script src="js/empresas.js?v=<?= date("Ymdhis")?>"></script>
    <script src="js/buscar.js"></script>
	</body>
</html>
