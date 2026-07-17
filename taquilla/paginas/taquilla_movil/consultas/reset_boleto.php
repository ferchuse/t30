<?php 
	header("Content-Type: application/json");
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	
	
	
	$update ="UPDATE  sencillos_boletos SET 
	fecha_ponchado = NULL,
	usuario_ponchado = NULL,
	estatus_ponchado = 'Activo'
	
	WHERE id_boletos = '{$_POST["folio"]}'
	";
	
	$result = 	mysqli_query($link,$update);
	
	if($result){
		$respuesta["estatus"] = "success";
		$respuesta["update"] = "$update";
		// $respuesta["folios"][] = mysqli_insert_id($link);
	}
	else{ 
		$respuesta["estatus"] = "error";		
		$respuesta["error"] = "Error en insert: $update  ".mysqli_error($link);		
	}		
	
	
	
	echo json_encode($respuesta);
	
?>