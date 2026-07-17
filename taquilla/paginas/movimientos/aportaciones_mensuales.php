<?php
	$nombre_pagina = "Aportaciones Mensuales";
	$id= "id_cargos";
	$tabla = "cargos_unidades";
	
	include("../../conexi.php");
	include("../../funciones/generar_select.php");
	include("../../paginas/login/login_check.php");
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
		
		<title>Aportaciones Mensuales</title>
		<?php include("../../styles.php")?>
	</head>
	
	<body id="page-top">
		
		<?php include("../../navbar.php")?>
		
		
		<div id="wrapper" class="d-print-none">
			
			<!-- Sidebar -->
			<?php include("../../menu.php")?>
			
			<div id="content-wrapper">
				
				<div class="container-fluid">
					
					<!-- Breadcrumbs-->
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="#">Movimientos</a>
						</li> 
						<li class="breadcrumb-item active">Aportaciones Mensuales</li>
					</ol>
					
					
					
					<!--Form Filtro !-->
					<form id="form_filtro" autocomplete="off">
						
						
						<div class="row mb-2">
							<div class="col-sm-3">
								<label for="nombre_condonaciones">Mes:</label>
								
								
								<select class="form-control" name="mes_cargos">
									<option <?php echo date("Ym") == "202301" ? "selected" : "" ?> value="202301">2023-01-Enero </option>
									<option <?php echo date("Ym") == "202302" ? "selected" : "" ?> value="202302">2023-02-Febrero </option>
									<option <?php echo date("Ym") == "202303" ? "selected" : "" ?> value="202303">2023-03-Marzo </option>
									<option <?php echo date("Ym") == "202304" ? "selected" : "" ?> value="202304">2023-04-Abril </option>
									<option <?php echo date("Ym") == "202305" ? "selected" : "" ?> value="202305">2023-05-Mayo </option>
									<option <?php echo date("Ym") == "202306" ? "selected" : "" ?> value="202306">2023-06-Junio </option>
									<option <?php echo date("Ym") == "202307" ? "selected" : "" ?> value="202307">2023-07-Julio </option>
									<option <?php echo date("Ym") == "202308" ? "selected" : "" ?> value="202308">2023-08-Agosto </option>
									<option <?php echo date("Ym") == "202309" ? "selected" : "" ?> value="202309">2023-09-Septiembre </option>
									<option <?php echo date("Ym") == "202310" ? "selected" : "" ?> value="202310">2023-10-Octubre </option>
									<option <?php echo date("Ym") == "202311" ? "selected" : "" ?> value="202311">2023-11-Noviembre </option>
									<option <?php echo date("Ym") == "202312" ? "selected" : "" ?> value="202312">2023-12-Diciembre </option>
									<option <?php echo date("Ym") == "202212" ? "selected" : ""?> value="202212">2022-12 </option>
									<option <?php echo date("Ym") == "202211" ? "selected" : ""?> value="202211">2022-11 </option>
									<option <?php echo date("Ym") == "202210" ? "selected" : ""?> value="202210">2022-10 </option>
									<option <?php echo date("Ym") == "202209" ? "selected" : ""?> value="202209">2022-09 </option>
									<option <?php echo date("Ym") == "202208" ? "selected" : ""?> value="202208">2022-08 </option>
									<option <?php echo date("Ym") == "202207" ? "selected" : ""?> value="202207">2022-07 </option>
									<option <?php echo date("Ym") == "202206" ? "selected" : ""?> value="202206">2022-06 </option>
									<option <?php echo date("Ym") == "202205" ? "selected" : ""?> value="202205">2022-05 </option>
									<option <?php echo date("Ym") == "202204" ? "selected" : ""?> value="202204">2022-04 </option>
									<option <?php echo date("Ym") == "202203" ? "selected" : ""?> value="202203">2022-03 </option>
									<option <?php echo date("Ym") == "202202" ? "selected" : ""?> value="202202">2022-02 </option>
									<option <?php echo date("Ym") == "202201" ? "selected" : ""?> value="202201">2022-01 </option>
									<option <?php echo date("Ym") == "202112" ? "selected" : ""?> value="202112">2021-12 </option>
									<option <?php echo date("Ym") == "202111" ? "selected" : ""?> value="202111">2021-11 </option>
									<option <?php echo date("Ym") == "202110" ? "selected" : ""?> value="202110">2021-10 </option>
									<option <?php echo date("Ym") == "202109" ? "selected" : ""?> value="202109">2021-09 </option>
									<option <?php echo date("Ym") == "202108" ? "selected" : ""?> value="202108">2021-08 </option>
									<option <?php echo date("Ym") == "202107" ? "selected" : ""?> value="202107">2021-07 </option>
									<option <?php echo date("Ym") == "202106" ? "selected" : ""?> value="202106">2021-06 </option>
									
									
								</select>
								
							</div>
							
							
							<div class="col-sm-2 mt-4">
								<button class="btn btn-primary " >
									<i class="fas fa-search"></i> Buscar
								</button>
							</div>
							
							
						</div>
						
					</form>
					
					<hr>
					<div class="card card-primary mb-3" >
						<div class="card-header bg-info text-white">
							<i class="fas fa-table"></i>
							<?php echo $nombre_pagina?> 
						</div>
						<div class="card-body">
							<div class="table-responsive" id="tabla_registros">
								<h4 class="text-center">
									
								</h4>
							</div>
						</div>
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
		
		<div class="d-print-block p-2 " hidden id="ticket" >
		</div>
		
		
		<?php include("../../scripts.php")?>
		<script src="js/aportaciones_mensuales.js"></script>
	</body>
	
</html>																																		