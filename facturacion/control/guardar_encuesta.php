<?php
	header("Content-Type: application/json");
	include ("../conexi.php");
	$link = Conectarse();
	$respuesta = Array();
	
	$consulta = "INSERT INTO encuestas SET 
	id_boletos = '{$_POST["id_boletos"]}',
	fecha_encuesta = NOW(),
	tipo_unidad = '{$_POST["tipo_unidad"]}',
	frequencia_viaje = '{$_POST["frequencia_viaje"]}',
	rating_taquilla = '{$_POST["rating_taquilla"]}',
	rating_modulacion = '{$_POST["rating_modulacion"]}',
	rating_conductor = '{$_POST["rating_conductor"]}',
	limpieza = '{$_POST["limpieza"]}',
	volveria_viajar = '{$_POST["volveria_viajar"]}',
	comentarios = '{$_POST["comentarios"]}'

	
	";
	$result = mysqli_query($link, $consulta);
	
	
	
	if($result){
		$respuesta["estatus"] = "success";
		$respuesta["mensaje"] = "Guardado";
		
		
	}	
	else{
		$respuesta["estatus"] = "error";
		$respuesta["error"] = "Error $consulta  ".mysqli_error($link);		
	}
	
	echo json_encode($respuesta);
?>