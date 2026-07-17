<?php 
	include("../login/login_check.php");
	include("../../conexi.php");
	include("../../funciones/generar_select.php");
	
	$link = Conectarse();
	
	$id_unidades = $_GET["id_unidades"];
	
	
		
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
		
		<style>
			.aacustom-card {
			background-color: #33287F;
			border-radius: 15px;
			color: white;
			}
			
			.custom-card {
			background-image: url('../../fileupload/files/fondo.png'); /* Ruta de tu imagen de fondo */
			background-size: cover; /* Ajusta el tamaño de la imagen para cubrir la card */
			background-position: center; /* Centra la imagen */
			background-repeat: no-repeat; /* Evita la repetición de la imagen */
			border-radius: 15px;
			color: white;
			position: relative; /* Asegura que las imágenes y el contenido estén posicionados correctamente */
			}
			
			.car-image {
			border-radius: 15px;
			overflow: hidden;
			margin-x: 20px;
			}
			
			.details-list {
			list-style: none;
			margin-top: 50px;
			padding: 0;
			text-align: left; /* Centra el texto */
			padding-left: 150px; /* Centra el texto */
			color: red; /* Color de fuente rojo */
			}
			
			.details-list li {
			margin-bottom: 15px; /* Aumenté el espacio entre elementos de la lista */
			font-size: 18px; /* Ajusté el tamaño de la fuente */
			}
			
			.details-list img {
			width: 50px; /* Ajusté el tamaño de las imágenes a 100px */
			margin-right: 10px;
			}
		</style>
	</head>
	<body id="page-top">
		<div class="container mt-5">
			<div class="card custom-card">
				<div class="card-header text-center">
					<img src="../../img/logo_sanrod_100.jpg" alt="Logo de la Empresa" class="img-fluid " style="max-width: 150px;">
				</div>
				<div class="card-body">
					<div class="car-image">
						<img src="../../fileupload/files/3308.jpg" alt="Foto del Automóvil" class="img-fluid">
					</div>
					<ul class="details-list ">
						<li><img src="../../fileupload/files/ico 1.png" alt="Icono Teléfono"> <strong>Juan Pérez</strong> </li>
						<li><img src="../../fileupload/files/ico 2.png" alt="Icono Teléfono"> <strong>55490450121</strong> </li>
						<li><img src="../../fileupload/files/ico 3.png" alt="Icono Teléfono"> <strong>Versa </strong> </li>
						<li><img src="../../fileupload/files/ico 4.png" alt="Icono Teléfono"> <strong>HA0-27-12</strong> </li>
						<li><img src="../../fileupload/files/ico 5.png" alt="Icono Teléfono"> <strong>3308</strong> </li>
					</ul>
				</ul>
			</div>
		</div>
	</div>
	
	<?php include('../../scripts.php')?>
</body>
</html>
