<?php
	header("Content-Type: application/json");
	include ("../taquilla/conexi.php");
	$link = Conectarse();
	$respuesta = Array();
	
	// $base64image= urldecode($_POST["base64image"]);
	
	$consulta = "UPDATE empleados SET 
	template = '{$_POST["base64image"]}'
	WHERE nombre_empleado = '{$_POST["nombre_empleado"]}'
	";
	$result = mysqli_query($link, $consulta);
	
	
	
	if($result){
		$respuesta["consulta"] = $consulta;
		$respuesta["status"] = "success";
		$respuesta["mensaje"] = "Guardado";
		
		if($_POST["id_empleado"] == ""){
			
			$respuesta["id_empleado"] = mysqli_insert_id($link);
		}
		else{
			$respuesta["id_empleado"] = $_POST["id_empleado"];
			
		}
	}	
	else{
		$respuesta["status"] = "error";
		$respuesta["mensaje"] = "Error $consulta  ".mysqli_error($link);		
	}
	
	echo json_encode($respuesta);
?>