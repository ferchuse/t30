<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	

	$consulta  ="UPDATE aportaciones SET 
	
	aportacion_mensual = '{$_POST["aportacion"]}'
	
	WHERE 
	fecha = '{$_POST['fecha']}'
	AND id_empresas = '{$_POST['id_empresas']}'
	
	
	";
	
	$result = 	mysqli_query($link,$consulta);
	
	if($result){
		$respuesta["estatus"] = "success";
		$respuesta["mensaje_insert"] = "Guardado Correctamente";
		$respuesta["insert_id"] = mysqli_insert_id($link);
		$respuesta["insert"] = $consulta;
		
	}
	else{
		
		$respuesta["estatus_insert"] = "error";
		$respuesta["mensaje_insert"] = "Error en insert: $consulta  ".mysqli_error($link);		
	}
	
	
	
	echo json_encode($respuesta);
	
?>