<?php
	header("Content-Type: application/json");
	include ("../taquilla/conexi.php");
	$link = Conectarse();
	$respuesta = Array();
	
	$consulta = "INSERT INTO empleados_accesos SET 
	fecha_acceso = NOW(),
	tipo_acceso = '{$_GET["tipo_acceso"]}',
	id_empleado = '{$_GET["id_empleado"]}'
	";
	
	$result = mysqli_query($link, $consulta);
	
	
	
	if($result){
		$respuesta["consulta"] = $consulta;
		$respuesta["status"] = "success";
		$respuesta["mensaje"] = "Guardado";
		
		
	}	
	else{
		$respuesta["status"] = "error";
		$respuesta["mensaje"] = "Error $consulta  ".mysqli_error($link);		
	}
	
	echo json_encode($respuesta);
?>