<?php 
	include("../login/login_check.php");
	include("../../conexi.php");
	$link = Conectarse();
	include("../../funciones/generar_select.php");
	include("../../funciones/empresas_accesibles.php");
	
	
	$cat_empresas = [1=> "TAXI DRIVER VIAJE CONFIABLE", 2=> "TAXI DRIVER VIAJE CONFIABLE" ];
	
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Catálogo de Unidades</title>
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
						<li class="breadcrumb-item active">Unidades</li>
					</ol>
					
					<div class="row mb-2">
						<div class="col-12">
							<form id="form_filtro" autocomplete="off">
								<div class="row mb-2">
									<div class="col-12">
										<div class="col-12 mb-3">
											<button class="btn btn-primary btn-sm" >
												<i class="fas fa-search"></i> Buscar
											</button>
											<button type="button" class="btn btn-success nuevo btn-sm" >
												<i class="fas fa-plus"></i> Nuevo
											</button>
											
										</div>
										
									</div>
								</div>
								
								
								<div class="row mb-2"> 
									<div class="col-sm-3">
										<label for="">No. Economico:</label>
										<input class="form-control" type="number" name="num_eco"  >
									</div>	
									
									<div class="col-sm-3 d-none">
										
										
										<label>Empresa:</label>
										
										<input class="form-control" type="text" readonly value="<?php echo $cat_empresas[$_COOKIE["empresa_asignada"]]?>">
										<input class="form-control" type="hidden" name="id_empresas" value="<?php echo $_COOKIE["empresa_asignada"]?>">
										<?php 
											
											// echo generar_select($link, "empresas", "id_empresas", "nombre_empresas", true, false, false);
											
											
										?>
										
									</div>	
									<div class="col-sm-3">
										
										<label >Estatus:</label>
										
										<select class="form-control" name="estatus_unidades">
											<option value="">Todos</option>
											<option>Activo</option>
											<option>Inactivo</option>
										</select>
										
									</div>	
								</div>
							</div>
							
						</form>
						<hr>
					</div>
					
					
					<div class="table-responsive" id="lista_registros">
						<h3 >Cargando...</h3>
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
	
	
	
	<?php include("forms/form_unidades.php")?>
	<?php include("forms/modal_historial.php")?>
	<?php include("../../scripts.php")?>
	<script src="js/unidades.js?v=<?= date("d-m-Y-H-i-s")?>"></script>
	
	<script src="https://upload-widget.cloudinary.com/global/all.js" type="text/javascript"></script>  
	
	<script>
		
		
		var active_button;
		var myWidget = cloudinary.createUploadWidget({
			cloudName: 'dynskweb4', 
			cropping: false,
			language: "es", 
			text: {
				"es": {
					"menu": {
						files: "Mis Archivos",
						web: "URL",
						camera: "Cámara",
						
					},
					"actions": {
						upload: "Cargar",
						next: "Siguiente",
						clear_all: "Borrar todo",
						log_out: "Salir",
					},
					"aria_label": {
						close: "Cerrar",
						back: "Back",
					},
					"crop": {
						title: "Recortar",
						crop_btn: "Recortar",
						skip_btn: "Saltar",
						reset_btn: "Reiniciar",
						close_btn: "Si",
						close_prompt: "Closing will cancel all uploads, Are you sure?",
						image_error: "Error loading image",
						corner_tooltip: "Arrastra esquina para redimencionar",
						handle_tooltip: "Arrastra manija para redimencionar",
					},
					camera: {
						capture: "Capturar",
						cancel: "Cancelar",
						take_pic: "Toma una foto y cárgala",
						explanation: "Make sure that your camera is connected and that your browser allows camera capture. When ready, click Capture.",
						camera_error: "Failed to access camera",
						retry: "Retry Camera",
						file_name: "Camera_{{time}}",
					},
					"queue": {
						"title": "Archivos a Cargar",
						"title_uploading_with_counter": "Cargando {{num}} archivos"
					},
					
					"local": {
						"browse": "Buscar",
						"dd_title_single": "Arrastra y suelta una imagen aqui",
						dd_title_multi: "Arrastra y suelta imagenes aqui",
						drop_title_single: "Suelta un archivo para cargarlo",
						drop_title_multiple: "Suelta archivos para cargarlos",
					},
				}
			}, 
			default_source: 'local',
			sources: [ 'local', 'camera','url', ],
		uploadPreset: 'vwqzgg3d'}, 
		(error, result) => { 
			
			if (!error && result && result.event === "success") { 
				active_button.closest("div").find("input").val(result.info.secure_url)
				active_button.closest("div").find("img").prop("src", result.info.thumbnail_url)
				console.log('Done! Here is the image info: ', result.info); 
				console.log('Done! Here is the image info: ', result); 
			}
			
		}  
		)
		
		$(".upload_widget").on("click", function(){
			active_button = $(this); 
			myWidget.open();
		});
		
		
	</script>
</body>
</html>
