<?php 
	include("../../../../conexi.php");
	$link = Conectarse();
	$recoleccion = array();
	
	
	
	
	//Actualiza Recoleccion
	
	$update = "UPDATE recolecciones SET
	
	estatus_recoleccion = 'ASIGNADA',
	num_eco = '{$_POST["num_eco"]}',
	id_conductores = '{$_POST["id_conductores"]}'
	
	
	WHERE id_recoleccion = '{$_POST["id_recoleccion"]}'
	";
	
	// $respuesta["insert"] = $insert;
	$exec_query = mysqli_query($link,$update);
	
	if($exec_query){
		$respuesta["estatus"] = "success";
	
		
	}
	else{
		$respuesta["estatus"] = "error";
		$respuesta["error"] = mysqli_error($link);	
		
	}
	
	
	
	
	
	echo json_encode($respuesta);
?>	