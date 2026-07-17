<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	// include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	$consulta = "
	SELECT
	id_unidades ,
	estatus_unidades 
	FROM unidades
	WHERE
	unidades.num_eco = '{$_GET['num_eco']}'
	LIMIT 1
	";
  
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			$respuesta["num_rows"] = 0;
		}
		
		while($fila = mysqli_fetch_assoc($result)){
			//console_log($fila);
			$filas = $fila ;
			
		}
		$respuesta["filas"] = $filas;
		
	}
	else {
		
		$respuesta["consulta"] = "$consulta";
		$respuesta["estatus"] = "error";
		$respuesta["mensaje"] = "Error en ".$consulta.mysqli_Error($link);
	}
	
	echo json_encode($respuesta);
	
?>	