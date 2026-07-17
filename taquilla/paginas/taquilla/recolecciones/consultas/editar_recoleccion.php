<?php 
	include("../../../../conexi.php");
	$link = Conectarse();
	$recoleccion = array();
	
	
	
	
	//Actualiza Recoleccion
	
	$update = "UPDATE recolecciones SET
	
	
	id_recoleccion =  '{$_POST["id_recoleccion"]}',
	fecha_recoleccion = '{$_POST["fecha_recoleccion"]}',
	fecha_captura =  NOW(),
	id_usuarios = '{$_COOKIE["id_usuarios"]}',
	tipo_origen = '{$_POST["tipo_origen"]}',
	destino = '{$_POST["destino"]}',
	tipo_recoleccion = '{$_POST["tipo_recoleccion"]}',
	nombre_pasajero = UPPER('{$_POST["nombre_pasajero"]}'),
	pasajeros = '{$_POST["pasajeros"]}',
	celular = '{$_POST["celular"]}',
	total = '{$_POST["total"]}',
	anticipo = '{$_POST["anticipo"]}',
	restante = '{$_POST["restante"]}',
	forma_pago = '{$_POST["forma_pago"]}',
	referencia = '{$_POST["referencia"]}'
	
	
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