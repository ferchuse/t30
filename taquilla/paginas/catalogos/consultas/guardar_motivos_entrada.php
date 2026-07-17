<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	
	
	$query ="INSERT INTO motivos_entrada SET 
	id_motivo_entrada = '{$_POST["id_motivo_entrada"]}',
	motivo = '{$_POST["motivo"]}'
	
	ON DUPLICATE KEY UPDATE
	
	id_motivo_entrada = '{$_POST["id_motivo_entrada"]}',
	motivo = '{$_POST["motivo"]}'
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