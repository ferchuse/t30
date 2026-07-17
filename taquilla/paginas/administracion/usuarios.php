<?php 
	include_once("../login/login_check.php");
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
		<title>Usuarios</title>
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
							<a href="#">Administración</a>
						</li>
						<li class="breadcrumb-item active">Usuarios</li>
					</ol>
					
					<div class="row mb-2">
						<div class="col-12">
							<button type="button" class="btn btn-success nuevo" >
								<i class="fas fa-plus"></i> Nuevo
							</button>
						</div>
					</div>
					
					
					<form id="form_filtros" >
						<div class="row mb-2">
							<div class="col-sm-2">
								<label> Nombre:</label>
								<input type="search" class="form-control" value="" name="nombre_usuarios" id="nombre_usuarios">
							</div>
							<div class="col-sm-2">
								<div class="form-group">
									<label>Estatus:</label>
									<select  class="form-control"  name="estatus_usuarios">
										<option value="">Todos</option>
										<option selected value="Activo">Activo</option>
										<option  value="Inactivo">Inactivo</option>
									</select>
								</div>
							</div>
							<div class="form-group col-sm-2 pt-4"> 
								<button type="submit"  title="Buscar" class="btn btn-primary  d-print-none btn-sm ">
									<i class="fas fa-search"></i> Buscar
								</button>	
								
							</div>
						</div>
						
					</form>
					
					<hr>
					
					
					
					<div class="table-responsive" id="lista_registros">
						<h3 >Cargando...</h3>
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
		
		
		<!-- The Modal -->
		
		
		
		<?php include("forms/form_usuarios.php")?>
		<?php include("../../scripts.php")?>
		<script src="js/usuarios.js?v=<?= date("dmYHi")?>" ></script>
		
	</body>
</html>
