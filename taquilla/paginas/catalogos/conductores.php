<?php
	include("../login/login_check.php");
	include('../../conexi.php');
	$link = Conectarse();
	$nombre_pagina = "Conductores";
	$id= "id_conductores";
	$tabla = "conductores";
	include('../../funciones/generar_select.php');
	
	
	
?>


<!DOCTYPE html>
<html lang="es_mx">
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
							<button type="button" class="btn btn-success btn-sm nuevo">
								<i class="fas fa-plus"></i> Nuevo
							</button>
						</div>
					</div>
					
					<form id="form_filtros" autocomplete="off">								
						<div class="row mb-2"> 
							<div class="col-sm-3">
								<label for="">Nombre:</label>
								<input class="form-control" type="search" name="nombre_conductores"  >
							</div>	
							
							<div class="col-sm-3 d-none">
								<label>Empresa:</label>
								
								<input class="form-control" type="text" readonly value="<?php echo $cat_empresas[$_COOKIE["empresa_asignada"]]?>">
								<input class="form-control" type="hidden" name="id_empresas" value="<?php echo $_COOKIE["empresa_asignada"]?>">
								
								
							</div>	
							<div class="col-sm-3">
								
								<label >Estatus:</label>
								
								<select class="form-control" name="estatus_conductores">
									<option value="">Todos</option>
									<option>Activo</option>
									<option>Inactivo</option>
								</select>
								
							</div>
							<div class="col-sm-3 m-t-4">
								
								<button class="btn btn-primary btn-sm" >
									<i class="fas fa-search"></i> Buscar
								</button>
								
							</div>
							<input type="hidden" id="order_by" name="order_by" value="nombre_conductores">
							<input type="hidden"  id="sort" name="sort" value="ASC">
						</div>
					</div>
					
				</form>
				
				
				
				<div class="table-responsive" id="tabla_registros">
					
					
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
<?php 
	include("../../scripts.php");
	include("forms/form_conductores.php");
?>
<script src="js/conductores.js?v=<?= date("Ymdhis")?>"></script>
<script src="js/buscar.js"></script>

<script src="https://upload-widget.cloudinary.com/global/all.js" type="text/javascript"></script>  
	
	<script>
		
		
		var active_button;
		var myWidget = cloudinary.createUploadWidget({
			cloudName: 'diadv5woa', 
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
			sources: ['camera', 'local', 'url', ],
		uploadPreset: 'amsanrod'}, 
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
