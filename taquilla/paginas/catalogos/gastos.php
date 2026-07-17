<?php 
	include("../login/login_check.php");
	include("../../conexi.php");
	include("../../funciones/generar_select.php");
	
	$link = Conectarse();
	
	
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Catálogo de Gastos</title>
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
						<li class="breadcrumb-item active">Gastos</li>
					</ol>
					<button type="button" class="btn btn-success  btn-sm" id="nuevo">
						<i class="fas fa-plus"></i> Nuevo
					</button>
				</div>
				
				
				<div class="card mb-3">
					<div class="card-header">
						<i class="fas fa-table"></i>
						Lista de Gastos
					</div>
					<div class="card-body">
						<div class="table-responsive" id="lista_registros">
							<h3 >Cargando...</h3>
						</div>
					</div>
					<div class="card-footer small text-muted"></div>
				</div>
			</div>
			<!-- /.container-fluid -->
			
			<!-- Sticky Footer -->
			<footer class="sticky-footer">
				<div class="container my-auto">
					<div class="copyright text-center my-auto">
						<span>Copyright © Glifo Media 2020</span>
					</div>
				</div>
			</footer>
			
		</div>
		<!-- /.content-wrapper -->
	</div>
	<!-- /#wrapper -->
	
	<!-- Scroll to Top Button-->
	<a class="scroll-to-top rounded" href="#page-top">
		<i class="fas fa-angle-up"></i>
	</a>		
	
	
	
	<?php include("forms/form_gastos.php")?>
	<?php include("../../scripts.php")?>
	<script src="js/gastos.js?v=<?= date("d-m-Y-H-i-s")?>"></script>
</body>
</html>
