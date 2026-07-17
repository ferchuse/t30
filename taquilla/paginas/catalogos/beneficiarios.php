<?php
	include("../login/login_check.php");
	
?>
<!DOCTYPE html>
<html lang="es_mx">
	
	<head>
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		
		<title>Catálogo de Beneficiarios</title>
		<?php include('../../styles.php');?>
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
						<li class="breadcrumb-item active">Beneficiarios</li>
					</ol>
					<div class="row">
						<div class="col-md-12">
							<button class="btn btn-success" id="nuevoBeneficiario"><i class="fas fa-plus"></i> Nuevo</button>
						</div>
					</div>
					<br>
					
					<div class="table-responsive">
						<table class="table table-bordered" id="tabla_beneficiarios" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th class="text-center">Beneficiarios</th>
									<th>Acciones</th>
								</tr>
								<tr>
									<th class="text-center">
										<input type="text" data-indice="0" class="form-control" placeholder="Nombre del Beneficiario" id="nombre_beneficiario">
									</th>
									<th></th>
								</tr>
							</thead>
							<tbody id="containerLista">
								<tr>
									<td colspan="2"><h3 class="text-center">Cargando...</h3></td>
								</tr>
							</tbody>
						</table>
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
		include("forms/form_modal.php");
	?>
    <script src="js/beneficiarios.js?v=<?= date("Ymdhis")?>"></script>
    <script src="js/buscar.js"></script>
	</body>
	
</html>
