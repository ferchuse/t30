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
		
    <title>Catálogo de Motivos de Abono General de Unidades</title>
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
						<li class="breadcrumb-item active">Motivos de Abono General de Unidades</li>
					</ol>
					<div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-outline-success" id="nuevoMotivo"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <br>
					<div class="card mb-3">
						<div class="card-header">
							<i class="fas fa-table"></i>
							Lista de Motivos de Abandono de Unidades
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table table-bordered" id="tabla_motivos" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th class="text-center">Motivos</th>
											<th></th>
										</tr>
                                        <tr>
											<th class="text-center">
                                                <input type="text" data-indice="0" class="form-control" placeholder="Nombre del motivo" id="nombre_motivo">
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
                                <div id="mensaje"></div>
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
    <?php 
        include("../../scripts.php");
        include("forms/form_modal.php");
    ?>
    <script src="js/motivos_abono_general.js"></script>
    <script src="js/buscar.js"></script>
	</body>
	
</html>
