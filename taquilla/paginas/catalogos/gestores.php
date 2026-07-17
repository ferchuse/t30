<?php //include("propietario.class.php")?>

<!DOCTYPE html>
<html lang="en">
	
  <head>
		
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
		
    <title>Catálogo de Gestores</title>
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
						<li class="breadcrumb-item active">Gestores</li>
					</ol>
					<form id="formularioP">
				    <div class="col-12 mb-3">
							<button type="submit"  class="btn btn-outline-success" title="Guardar"> <i class="fas fa-save"></i></button>
						</div>
						<div class="row mb-2">
							<div class="col-2">
								<label for="nombre_gestores">Nombre:</label>
							</div>	
							<div class="col-6">			
								<input class="form-control" type="text" name="motivo" id="motivo" placeholder="Escribe el nombre de Gestor">
							</div>
						</div>
					</form>		
					<div class="card mb-3">
						<div class="card-header">
							<i class="fas fa-table"></i>
							Lista de Gestores
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th class="text-center">Id </th>
											<th class="text-center">Nombre del Gestor</th>
											<th></th>
										</tr>
									</thead>
									<tbody id="tabla_DB">
										<tr>
											<td colspan="3"><h3 class="text-center">Cargando...</h3></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="card-footer small text-muted">Ultima Modificación Ayer 12pm</div>
					</div>
				</div>
				<!-- /.container-fluid -->
				
		    <!-- Sticky Footer -->
				<footer class="sticky-footer">
					<div class="container my-auto">
						<div class="copyright text-center my-auto">
							<span>Copyright © Glifo Media 2018</span>
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
    <?php include("../../scripts.php")?>
    <script src="motivos_condonacion.js"></script>
	</body>
	
</html>
