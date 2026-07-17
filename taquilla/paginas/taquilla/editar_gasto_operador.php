<?php
	include("../../paginas/login/login_check.php");
	include('../../conexi.php');
	include('../../funciones/generar_select.php');
	$link = Conectarse();
	
	
	
	$id_gasto_operador = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
	
	$query = "
	SELECT *
	FROM gastos_operador
	WHERE id_gasto_operador = '$id_gasto_operador'
	LIMIT 1
	";
	
	$result = mysqli_query($link, $query);
	
	if(!$result || mysqli_num_rows($result) == 0){
		die("No se encontró el gasto");
	}
	
	$gasto = mysqli_fetch_assoc($result);
	
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Gastos </title>
		<?php include('../../styles.php')?>
		
	</head>
	<body id="page-top">
		<?php include("../../navbar.php")?>
		<div id="wrapper" class="d-print-none">
			<?php include("../../menu.php")?>	
			<div id="content-wrapper">		
				<div class="container">		
					
					<form id="form_gasto_operador" autocomplete="off" class="was-validated">
						
						<input type="hidden" name="id_gasto_operador" value="<?php echo $gasto["id_gasto_operador"]; ?>">
						
						<div class="container-fluid mt-4">
							
							<div class="row mb-3">
								<div class="col-12 text-left">
									<a href="#" onclick="window.history.back();" class="btn btn-secondary">
										<i class="fas fa-arrow-left"></i> Regresar a Gastos Operador
									</a>
								</div>
							</div>
							
							<div class="row justify-content-center">
								
								<div class="col-md-6 col-lg-5">
									
									<div class="card shadow">
										
										<div class="card-header bg-warning">
											<h4 class="mb-0 text-center">
												Editar Gasto Operador
											</h4>
										</div>
										
										<div class="card-body">
											
											<div class="form-group">		
												<label>Folio:</label>
												<input 
												readonly 
												type="number" 
												class="text-right form-control" 
												id="id_gasto" 
												name="id_gasto" 
												value="<?php echo $gasto["id_gasto_operador"]; ?>">
											</div>
											
											<div class="form-group">		
												<label>Fecha:</label>
												<input  
												type="date" 
												class="form-control" 
												id="fecha_gasto" 
												name="fecha_gasto" 
												value="<?php echo $gasto["fecha_gasto"]; ?>" 
												required>
											</div>
											
											<div class="form-group">		
												<label>Operador:</label>
												<?php echo generar_select($link, "conductores", "id_conductores", "nombre_conductores", false, false, true, $gasto["id_conductores"]); ?>
											</div>
											
											<div class="form-group">		
												<label>Num Eco:</label>
												<?php echo generar_select($link, "unidades", "id_unidades", "num_eco", false, false, true, $gasto["id_unidades"]); ?>
											</div>
											
											<div class="form-group">		
												<label>Concepto:</label>
												<?php echo generar_select($link, "cat_gastos", "id_cat_gastos", "descripcion_gastos", false, false, true, $gasto["id_cat_gastos"]); ?>
											</div>
											
											<div class="form-group">		
												<label>Importe:</label>
												
												<div class="input-group mb-3">
													<div class="input-group-prepend">
														<span class="input-group-text">$</span>
													</div>
													
													<input 
													class="form-control text-right" 
													type="number" 
													name="monto_gasto" 
													id="monto_gasto" 
													required 
													min="0" 
													step="any" 
													value="<?php echo $gasto["monto_gasto"]; ?>">
												</div>
											</div>
											
											<div class="form-group">		
												<label>Observaciones:</label>
												
												<input 
												class="form-control" 
												type="text" 
												name="observaciones" 
												id="observaciones" 
												value="<?php echo htmlspecialchars($gasto["observaciones"]); ?>">
											</div>
											
										</div>
										
										<div class="card-footer text-center">
											
											<a href="#" onclick="window.history.back();" class="btn btn-secondary">
												<i class="fas fa-arrow-left"></i> Regresar
											</a>
											
											<button type="submit" class="btn btn-success">
												<i class="fas fa-save"></i> Guardar
											</button>
											
										</div>
										
									</div>
									
								</div>
								
							</div>
							
						</div>
						
					</form>
					
					
				</form>
				
				
				
				
			</div><!-- /.container-fluid --> 
		</div> 
		<!-- /.content-wrapper -->
	</div>
	<!-- /#wrapper -->
	
	<!-- Scroll to Top Button-->
	<a class="scroll-to-top rounded d-print-none" href="#page-top">
		<i class="fas fa-angle-up"></i>
	</a>
	
	
	<?php include("../../scripts.php")?>
	
	<script src="//cdnjs.cloudflare.com/ajax/libs/less.js/3.9.0/less.min.js" ></script>
	
	<script>
		$("#form_gasto_operador").on("submit", function(e){
			e.preventDefault();
			
			
			
			$.ajax({
				url: "gastos/guardar_gasto_operador.php",
				type: "POST",
				data: $(this).serialize(),
				dataType: "json",
				success: function(respuesta){
					
					if(respuesta.estatus == "success"){
						
						alertify.success("Gasto guardado correctamente");
						setTimeout(function(){
							window.history.back();
							
						}, 1000)
						
					}
					else{
						
						alert(respuesta.mensaje || "Ocurrió un error al guardar");
						
					}
					
				},
				error: function(xhr){
					alert("Error en la petición AJAX");
					console.log(xhr.responseText);
				}
			});
		});
	</script>
	
</body>
</html>																												