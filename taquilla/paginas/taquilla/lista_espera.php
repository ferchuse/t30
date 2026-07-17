<?php
	include("../login/login_check.php");
	include("../../funciones/generar_select.php");
	
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Lista de Espera</title>
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
							<a href="#">Taquilla</a>
						</li>
						<li class="breadcrumb-item active">Lista de Espera</li>
					</ol>
					<div class="row mb-2">
						<div class="col-3">
							<button type="button" id="btn_modal" class="btn btn-success" > <i class="fas fa-plus"></i> Nuevo</button>
						</div>
					</div>	
					
					<form id="form_filtros" >
						<div class="row">
							
							
							<div class="form-group col-sm-2">
								<label> Nombre:</label>
								<input type="search" autocomplete="off" class="form-control"  name="nombre_propietarios" >
							</div>
							
							
							
							<div class="col-sm-1">
								<div class="form-group">
									<label>Estatus:</label>
									<select  class="form-control"  name="estatus">
										<option value="">Todos</option>
										<option selected value="En Espera">En Espera</option>
										<option  value="Finalizado">Finalizado</option>
										<option  value="Cancelado">Cancelado</option>
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
					
					<div class="table-responsive" id="tabla_DB">
						<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
							<thead>
								<tr>
									
									<th class="text-center">Nombre</th>
									<th></th>
								</tr>
								
							</thead>
							<tbody >
								<tr>
									<td colspan="3"><h3 class="text-center">Cargando...</h3></td>
								</tr>
							</tbody>
						</table>
						<div class="mensaje"></div>
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
		<?php include("../../scripts.php")?>
		<?php include("forms/form_lista_espera.php")?>
		<script src="js/lista_espera.js?<?= date("dmYhis")?>"></script>
	</body>
</html>
