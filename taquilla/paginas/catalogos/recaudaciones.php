<?php
	include("../login/login_check.php");
	$nombre_pagina = "Recaudaciones";
	$id= "id_recaudaciones ";
	$tabla = "recaudaciones";
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
						<li class="breadcrumb-item active"><?php echo $nombre_pagina?></li>
					</ol>
					<div class="row mb-2">
						<div class="col-12">
							<button type="button" class="btn btn-success nuevo" >
								<i class="fas fa-plus"></i> Nuevo
							</button>
						</div>
					</div>
					<!-- Modal formulario-->
					<form id="form_edicion">
						<div class="modal " id="modal_edicion">
							<div class="modal-dialog modal-md">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title">Edición de <?php echo $nombre_pagina?></h4>
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>
									
									<!-- Modal body -->
									<div class="modal-body">
										<input class="d-none" name="id_recaudaciones" id="id_recaudaciones" value="">
										
										<div class="form-group">
											<label >Nombre :</label>
											<input class="form-control" type="text" name="nombre_recaudaciones" id="nombre_recaudaciones">
										</div>
									</div>
									
									<!-- Modal footer -->
									<div class="modal-footer">
										<button type="button" class="btn btn-danger" data-dismiss="modal">
										<i class="fas fa-times"></i> Cancelar</button>
										<button type="submit" class="btn btn-success " >
										<i class="fas fa-save"></i> Guardar </button>
								</div>
							</div>
						</div>
					</div>
				</form>	
				
				
				<!-- Modal formulario-->
				
				
				
				<div class="card mb-3">
					<div class="card-header">
						<i class="fas fa-table"></i>
						Lista de <?php echo $nombre_pagina?>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th class="text-center">Nombre</th>
										<th class="text-center"></th>
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
	<script src="js/recaudaciones.js"></script>
</body>
</html>
