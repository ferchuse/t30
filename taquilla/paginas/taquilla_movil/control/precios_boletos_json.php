<?php 
	session_start();
	if(count($_COOKIE) == 0){
		die("<div class='alert alert-danger'>Tu Sesión ha caducado, recarga la página.</div>");
	}
	include('../../../conexi.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	
	$consulta = "SELECT * FROM precios_boletos 
	LEFT JOIN origenes USING(id_origenes) 
	LEFT JOIN (
		SELECT id_origenes AS id_destinos,
		nombre_origenes AS nombre_destinos
		FROM origenes) t_destinos
		USING(id_destinos)
	WHERE precios_boletos.id_administrador = {$_COOKIE["id_administrador"]}
	";
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros</div>");
			
		}
		
		
		while($fila = mysqli_fetch_assoc($result)){
			
			$respuesta["precios_boletos"][] = $fila ;
			
			
		}
		echo (json_encode($respuesta));
		
	}
	
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>