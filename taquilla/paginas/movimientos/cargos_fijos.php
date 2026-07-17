<?php
	
	include("../../conexi.php");
	include("../../funciones/generar_select.php");
	include("../../paginas/login/login_check.php");
	$link = Conectarse();
	
		$cat_empresas = [1=> "TAXI DRIVER VIAJE CONFIABLE", 2=> "TAXI DRIVER VIAJE CONFIABLE", ]
	
?>

<!DOCTYPE html>
<html lang="en">
	
	<head>
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		
		<title>Cargos</title>
		<?php include("../../styles.php")?>
	</head>
	
	<body id="page-top">
		
		<?php include("../../navbar.php")?>
		
		
		<div id="wrapper" class="d-print-none">
			
			<!-- Sidebar -->
			<?php include("../../menu.php")?>
			
			<div id="content-wrapper">
				
				<div class="container-fluid">
					
					
					<legend>Cargos</legend>
					
					
					<!--Form Filtro !-->
					<form id="form_filtro" autocomplete="off">
						
						
						<div class="row mb-2">
							<div class="col-sm-2">
								<label for="nombre_condonaciones">Mes:</label>
								<select class="form-control" name="mes_cargos">
									<?php
										for ($i = 1; $i <= 12; $i++) {
											$yearMonth = date("Ym", strtotime("2025-$i-01"));
											$selected = (date("Ym") == $yearMonth) ? "selected" : "";
											$label = ucfirst(strftime("%B %Y", strtotime("2025-$i-01")));
											echo "<option $selected value=\"$yearMonth\">$label</option>\n";
										}
									?>
									<?php
										for ($i = 1; $i <= 12; $i++) {
											$yearMonth = date("Ym", strtotime("2024-$i-01"));
											$selected = (date("Ym") == $yearMonth) ? "selected" : "";
											$label = ucfirst(strftime("%B %Y", strtotime("2024-$i-01")));
											echo "<option $selected value=\"$yearMonth\">$label</option>\n";
										}
									?>
									<option <?php echo date("Ym") == "202301" ? "selected" : "" ?> value="202301">Enero 2023</option>
									<option <?php echo date("Ym") == "202302" ? "selected" : "" ?> value="202302">Febrero 2023</option>
									<option <?php echo date("Ym") == "202303" ? "selected" : "" ?> value="202303">Marzo 2023</option>
									<option <?php echo date("Ym") == "202304" ? "selected" : "" ?> value="202304">Abril 2023</option>
									<option <?php echo date("Ym") == "202305" ? "selected" : "" ?> value="202305">Mayo 2023</option>
									<option <?php echo date("Ym") == "202306" ? "selected" : "" ?> value="202306">Junio 2023</option>
									<option <?php echo date("Ym") == "202307" ? "selected" : "" ?> value="202307">Julio 2023</option>
									<option <?php echo date("Ym") == "202308" ? "selected" : "" ?> value="202308">Agosto 2023</option>
									<option <?php echo date("Ym") == "202309" ? "selected" : "" ?> value="202309">Septiembre 2023</option>
									<option <?php echo date("Ym") == "202310" ? "selected" : "" ?> value="202310">Octubre 2023</option>
									<option <?php echo date("Ym") == "202311" ? "selected" : "" ?> value="202311">Noviembre 2023</option>
									<option <?php echo date("Ym") == "202312" ? "selected" : "" ?> value="202312">Diciembre 2023</option>
									
								</select>
							</div>
							
							
							
							
							<div class="col-sm-2">
								<label for="num_eco">Num Eco:</label>
								
								<input class="form-control" type="text" name="num_eco" id="num_eco" >
							</div>  
							
							
							<div class="col-sm-2 mt-4">
								<button class="btn btn-primary btn-sm" >
									<i class="fas fa-search"></i> Buscar
								</button>
							</div>
						</div>
					</form>
					
					
					<div class="table-responsive" id="tabla_registros">
						<h4 class="text-center">
							Cargando...	
						</h4>
					</div>
					
				</div>
				
				
			</div>
			<!-- /.content-wrapper -->
			
		</div>
		<!-- /#wrapper -->
		
		<!-- Scroll to Top Button-->
		<a class="scroll-to-top rounded" href="#page-top">
			<i class="fas fa-angle-up"></i>
		</a>
		
		
		
		<?php include("../../scripts.php")?>
		<script src="js/cargos_fijos.js?v=<?= date("Y-m-d-H-s")?>"></script>
	</body>
	
</html>																																		