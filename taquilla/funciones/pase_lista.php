<?php 
	session_start();
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	
	$insert_asistencia = "INSERT INTO personal_asistencia 
	SET
	
	id_personal = '{$_GET['id_personal']}' ,
	fecha_asistencia = NOW()";
	
	
	$result_registro = mysqli_query($link, $consultar_registro);
	
	
	$result_personal = 	mysqli_query($link,$update_personal);
	
	if($result_personal){
		
		$respuesta["estatus"] = "success";
		$respuesta["mensaje"] = "Guardado";
		
	}
	else{
		$respuesta["estatus"] = "error";
		$respuesta["mensaje"] = "Error en $update_personal ".mysqli_error($link);		
	}
	
	
	ECHO "Guardado Correctamente"
	
	
	echo json_encode($respuesta);
	
	?>	