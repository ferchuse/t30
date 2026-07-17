<?php 
	session_start();
	if(count($_COOKIE) == 0){
		die("<div class='alert alert-danger'>Tu Sesión ha caducado, recarga la página.</div>");
	}
	include('../../../conexi.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	
	
	$consulta = "SELECT num_asiento FROM boletos 
	
	WHERE id_corridas= {$_GET["id_corridas"]}
	";
  
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros</div>");
			
		}
		
		
		while($fila = mysqli_fetch_assoc($result)){
			
			$respuesta["asientos_ocupados"][] = $fila["num_asiento"] ;
			
			
		}
		echo (json_encode($respuesta ));
		
	}
	
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>