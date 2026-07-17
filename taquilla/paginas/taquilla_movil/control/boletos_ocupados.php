<?php 
	session_start();
	if(count($_COOKIE) == 0){
		die("<div class='alert alert-danger'>Tu Sesión ha caducado, recarga la página.</div>");
	}
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	
	
	$consulta = "SELECT * FROM precios_boletos 
	
	WHERE id_administrador= '{$_COOKIE["id_administrador"]}'
	";
  
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros</div>");
			
		}
		
		
		while($fila = mysqli_fetch_assoc($result)){
			
			$precios_boletos[] = $fila ;
			
			
		}
		echo (json_encode($precios_boletos))
		
	}
	
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>