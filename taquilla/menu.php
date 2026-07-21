<?php 
	$cat_paginas = [
	
	["nomrbre_pagina" => "Catálogos", "icono" => "fa-folder"],
	["nomrbre_pagina" => "Movimientos", "icono" => "fa-exchange-alt"],
	["nomrbre_pagina" => "Recursos Humanos", "icono" => "fa-users"],
	["nomrbre_pagina" => "Bases", "icono" => "fa-home"],
	["nomrbre_pagina" => "Saldos", "icono" => "fa-dollar-sign"],
	["nomrbre_pagina" => "Catemaco", "icono" => "fa-ticket-alt"],
	["nomrbre_pagina" => "Jurídico", "icono" => "fa-briefcase"],
	["nomrbre_pagina" => "Administración", "icono" => "fa-cogs"]
	
	
	];
	
	
	
	if(!isset($_COOKIE["tipo_usuario"])){
		$_COOKIE["tipo_usuario"] = "recaudacion";
	}
	if($_COOKIE["tipo_usuario"] == "empresa"){?>
	<ul class="sidebar navbar-nav d-print-none">
		<li class="nav-item active"> 
			<a class="nav-link" href="../../paginas/movimientos/estado_cuenta.php">
				<i class="fas fa-fw fa-dollar-sign"></i>
				<span>
					Estado de Cuenta 
				</span>
			</a>
		</li>
	</ul>
	
	<?php
	}
	else{
	?>
	
	<ul class="sidebar navbar-nav d-print-none">
		<li class="nav-item active"> 
			<a class="nav-link" href="../../index.php">
				<i class="fas fa-fw fa-home"></i>
				<span>
					Inicio 
					
				</span>
			</a>
		</li>
		<li class="nav-item dropdown ">
			<a class="nav-link dropdown-toggle" href="#"  data-toggle="dropdown" >
				<i class="fas fa-fw fa-folder"></i>
				<span>Catálogos</span>
			</a>
			<div class="dropdown-menu " >
				<?php 
					$q_catalogos = "SELECT * FROM paginas WHERE categoria_paginas = 'Catálogos' ORDER BY orden_paginas, nombre_paginas";	
					
					$result_catalogos = mysqli_query($link, $q_catalogos);
					if(!$result_catalogos){
						echo mysqli_error($link);
					}
					
					while($fila = mysqli_fetch_assoc($result_catalogos)){
						echo "<a class='dropdown-item' href='../../paginas/catalogos/{$fila["url_paginas"]}' ";
						echo dame_permiso($fila["url_paginas"], $link).">-{$fila['nombre_paginas']}</a>";
						
					}
				?> 
			</div>
		</li> 
		
		<li class="nav-item dropdown ">
			<a class="nav-link dropdown-toggle" href="#"  data-toggle="dropdown" >
				<i class="fas fa-fw fa-exchange-alt"></i>
				<span>Movimientos</span>
			</a>
			<div class="dropdown-menu " >
				<?php 
					$q_catalogos = "SELECT * FROM paginas WHERE categoria_paginas = 'Movimientos' ORDER BY orden_paginas, nombre_paginas";	
					
					$result_catalogos = mysqli_query($link, $q_catalogos);
					if(!$result_catalogos){
						echo mysqli_error($link);
					}
					
					while($fila = mysqli_fetch_assoc($result_catalogos)){
						echo "<a class='dropdown-item' href='../../paginas/movimientos/{$fila["url_paginas"]}' ";
						echo dame_permiso($fila["url_paginas"], $link).">-{$fila['nombre_paginas']}</a>";
						
					}
				?> 
			</div>
		</li> 
		
		
		
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#"  data-toggle="dropdown" >
				<i class="fas fa-fw fa-ticket-alt "></i>
				<span>Taquilla</span>
			</a>
			<div class="dropdown-menu" >
				<?php 
					$q_catalogos = "SELECT * FROM paginas WHERE categoria_paginas = 'Taquilla' ORDER BY orden_paginas, nombre_paginas";	
					$result_catalogos = mysqli_query($link, $q_catalogos);
					while($fila = mysqli_fetch_assoc($result_catalogos)){
						echo "<a class='dropdown-item' href='../../paginas/taquilla/{$fila["url_paginas"]}' ";
						echo dame_permiso($fila["url_paginas"], $link).">-{$fila['nombre_paginas']}</a>";
					}
				?>
			</div>
		</li>
		
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#"  data-toggle="dropdown" >
				<i class="fas fa-fw fa-truck "></i>
				<span>Taquilla Móvil</span>
			</a>
			<div class="dropdown-menu" >
				<?php 
					$q_catalogos = "SELECT * FROM paginas WHERE categoria_paginas = 'Taquilla Móvil' ORDER BY orden_paginas, nombre_paginas";	
					$result_catalogos = mysqli_query($link, $q_catalogos);
					while($fila = mysqli_fetch_assoc($result_catalogos)){
						echo "<a class='dropdown-item' href='../../paginas/taquilla_movil/{$fila["url_paginas"]}' ";
						echo dame_permiso($fila["url_paginas"], $link).">-{$fila['nombre_paginas']}</a>";
					}
				?>
			</div>
		</li>
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#"  data-toggle="dropdown" >
				<i class="fas fa-fw fa-qrcode "></i>
				<span>Facturación</span>
			</a>
			<div class="dropdown-menu" >
				<?php 
					$q_catalogos = "SELECT * FROM paginas WHERE categoria_paginas = 'Facturación' ORDER BY orden_paginas, nombre_paginas";	
					$result_catalogos = mysqli_query($link, $q_catalogos);
					while($fila = mysqli_fetch_assoc($result_catalogos)){
						echo "<a class='dropdown-item' href='../../paginas/facturacion/{$fila["url_paginas"]}' ";
						echo dame_permiso($fila["url_paginas"], $link).">-{$fila['nombre_paginas']}</a>";
					}
				?>
			</div>
		</li>
		
		<li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#"  data-toggle="dropdown" >
				<i class="fas fa-fw fa-cog "></i>
				<span>Administración</span>
			</a>
			<div class="dropdown-menu" >
				<?php 
					$q_catalogos = "SELECT * FROM paginas WHERE categoria_paginas = 'Administración' ORDER BY orden_paginas";	
					$result_catalogos = mysqli_query($link, $q_catalogos);
					while($fila = mysqli_fetch_assoc($result_catalogos)){
						echo "<a class='dropdown-item' href='../../paginas/administracion/{$fila["url_paginas"]}' ";
						echo dame_permiso($fila["url_paginas"], $link).">-{$fila['nombre_paginas']}</a>";
					}
				?>
			</div>
		</li>
		
		
	</ul>
	
	<?php
	}
?>
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Confirmar</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span >×</span>
				</button>
			</div>
			<div class="modal-body">¿Estás seguro que deseas cerrar sesión?</div>
			<div class="modal-footer">
				<button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
				<a class="btn btn-primary" href="../../paginas/login/logout.php">Salir</a>  
			</div>
		</div>
	</div>
</div>			

<?php include("../usuarios/form_contrasena.php");?>