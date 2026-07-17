<?php 
	
	include("funciones/dame_permiso.php");
	include_once("conexi.php");
	
	// if(!function_exists("Conectarse")){
	
	$link = Conectarse();
	// }
	
	
	if(!isset($_COOKIE["tipo_usuario"])){
		$_COOKIE["tipo_usuario"] = "recaudacion";
	}
	if($_COOKIE["tipo_usuario"] == "propietario"){ 
		$oculto = "hidden";
	}
	else{
		$oculto = "";
	}
?>

<nav class="navbar navbar-expand navbar-dark bg-purple static-top d-print-none justify-content-between">
	
	<a class="navbar-brand mr-1" href="index.html"><?php //echo (explode("/", $_SERVER["SCRIPT_NAME"])[3])?></a>
	
	<button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
		<i class="fas fa-bars"></i>
	</button>
	
	<!-- Navbar Search d-md-inline-block-->
	<form class="d-none  form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
		<div class="input-group">
			<input type="text" class="form-control" placeholder="Buscar">
			<div class="input-group-append">
				<button class="btn btn-primary" type="button">
					<i class="fas fa-search"></i> 
				</button>
			</div>
		</div>
	</form>
	
	<!-- Navbar -->
	<ul class="navbar-nav ml-auto ">
		<?php if (dame_permiso(basename($_SERVER['PHP_SELF']), $link) == "Supervisor") {?>
			
			<li class="nav-item dropdown no-arrow mx-1" <?php echo $oculto;?>>
				<a class="nav-link dropdown-toggle" href="#" id="alertsDropdown"  data-toggle="dropdown" >
					<span id="badge" class="badge badge-danger"></span>
					<i class="fas fa-bell fa-fw"></i>
					
				</a>
				<div id="contentNotifications" style="width: 200px; overflow: hidden;" class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
				</div>
			</li>
			<?php 
			}
		?>
		
		
		<li class="nav-item dropdown no-arrow">
			<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" >
				<i class="fas fa-user-circle fa-fw"></i>
			</a>
			<div class="dropdown-menu dropdown-menu-right" >
				<input hidden value="<?php echo $_COOKIE["id_usuarios"]?>" id="id_usuarios">
				<input hidden value="<?php echo $_COOKIE["id_usuarios"]?>" id="sesion_id_usuarios">
				<input hidden id="permiso" value="<?php echo dame_permiso(basename($_SERVER['PHP_SELF']), $link);?>">
				<a class="dropdown-item" href="#">
					<?php echo "<b>Usuario</b>: <span id='sesion_nombre_usuarios'>". $_COOKIE["nombre_usuarios"]."</span>"?>
				</a>	
				<a class="dropdown-item" href="#">
					<?php echo "<b>Permiso</b>: ". dame_permiso(basename($_SERVER['PHP_SELF']), $link);?>
				</a>	
				<a class="dropdown-item d-none" href="#">
					<?php echo "<b>Taquilla: </b>: ". $_COOKIE["id_taquilla"];?>
					
				</a>	
				
				
				<a class="dropdown-item" id="cambiar_contrasena" data-toggle="modal" data-target="#modal_contrasena" href="#">Cambiar Contraseña</a>
				
				
				<a  class="dropdown-item d-none" href="#">
					<?php echo  date('Y-m-d\TH:i:s', time() - 3600);?>
					
				</a>
				<a hidden class="dropdown-item" href="#">Historial</a>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Salir</a>
			</div>
		</li>
	</ul>
</nav>
