<?php 
	include("../../../conexi.php");
	$link = Conectarse();

	
	$insert = "INSERT INTO lista_espera SET
	fecha_captura = NOW(),
	id_usuarios = '{$_COOKIE["id_usuarios"]}',
	cliente = UPPER('{$_POST["cliente"]}'),
	telefono = '{$_POST["telefono"]}',
	pasajeros = '{$_POST["pasajeros"]}',
	estatus = '{$_POST["estatus"]}',
	num_eco = '{$_POST["num_eco"]}'
	
	";
	
	// $respuesta["insert"] = $insert;
	$exec_query = mysqli_query($link,$insert);
	
	if($exec_query){
		$respuesta["estatus"] = "success";
		$respuesta["folio"] = mysqli_insert_id($link);
		
	}
	else{
		$respuesta["estatus"] = "error";
		$respuesta["error"] = mysqli_error($link);	
		
	}
	
	echo json_encode($respuesta);
?>	