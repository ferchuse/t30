<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	
	
	$query ="INSERT INTO taquillas SET 
	id_taquilla = '{$_POST["id_taquilla"]}',
	nombre_taquilla = '{$_POST["nombre_taquilla"]}',
	hora_salida = '{$_POST["hora_salida"]}'
	
	ON DUPLICATE KEY UPDATE
	
	id_taquilla = '{$_POST["id_taquilla"]}',
	nombre_taquilla = '{$_POST["nombre_taquilla"]}',
	hora_salida = '{$_POST["hora_salida"]}'
	";	
	
	
	
	$exec_query = 	mysqli_query($link,$query);
	$respuesta["query"] = $query;
	
	if($exec_query){
		$respuesta["estatus"] = "success";
		$respuesta["mensaje"] = "Guardado";
		
		
    }else{
		
		$respuesta["estatus"] = "error";
		$respuesta["mensaje"] = "Error en insert: $query  ".mysqli_error($link);		
	}
	
	echo json_encode($respuesta);
	
?>