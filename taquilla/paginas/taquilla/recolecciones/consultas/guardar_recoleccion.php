<?php 
	include("../../../../conexi.php");
	$link = Conectarse();
	
	
	$insert = "INSERT INTO recolecciones SET
	
	id_recoleccion =  '{$_POST["id_recoleccion"]}',
	fecha_recoleccion = '{$_POST["fecha_recoleccion"]}',
	fecha_captura =  NOW(),
	id_usuarios = '{$_COOKIE["id_usuarios"]}',
	tipo_origen = '{$_POST["tipo_origen"]}',
	destino = '{$_POST["destino"]}',
	nombre_pasajero = UPPER('{$_POST["nombre_pasajero"]}'),
	pasajeros = '{$_POST["pasajeros"]}',
	celular = '{$_POST["celular"]}',
	total = '{$_POST["total"]}',
	anticipo = '{$_POST["anticipo"]}',
	restante = '{$_POST["restante"]}',
	forma_pago = '{$_POST["forma_pago"]}',
	id_terminal = '{$_POST["id_terminal"]}',
	tipo_recoleccion = '{$_POST["tipo_recoleccion"]}',
	referencia = '{$_POST["referencia"]}'
	
	ON DUPLICATE KEY UPDATE
	
	fecha_recoleccion = '{$_POST["fecha_recoleccion"]}',
	fecha_captura =  NOW(),
	id_usuarios = '{$_COOKIE["id_usuarios"]}',
	tipo_origen = '{$_POST["tipo_origen"]}',
	destino = '{$_POST["destino"]}',
	nombre_pasajero = UPPER('{$_POST["nombre_pasajero"]}'),
	pasajeros = '{$_POST["pasajeros"]}',
	celular = '{$_POST["celular"]}',
	total = '{$_POST["total"]}',
	num_eco = '{$_POST["num_eco"]}',
	anticipo = '{$_POST["anticipo"]}',
	restante = '{$_POST["restante"]}',
	forma_pago = '{$_POST["forma_pago"]}',
	id_terminal = '{$_POST["id_terminal"]}',
	tipo_recoleccion = '{$_POST["tipo_recoleccion"]}',
	referencia = '{$_POST["referencia"]}'
	
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