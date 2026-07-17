<?php 
	include("../../../conexi.php");
	$link = Conectarse();
	
	
	if(!isset($_COOKIE["id_usuarios"])){
		
		$respuesta["error"] = "La sesión a caducado, vuelva a iniciar sesión";
		echo json_encode($respuesta);
		
		exit();
	}
	
	
	$insert = "INSERT INTO boletos SET
	fecha_boletos = NOW(),
	id_usuarios = '{$_COOKIE["id_usuarios"]}',
	origen = '{$_POST["origen"]}',
	destino = UPPER('{$_POST["destino"]}'),
	nombre_pasajero = UPPER('{$_POST["nombre_pasajero"]}'),
	pasajeros = '{$_POST["pasajeros"]}',
	cp_destino = '{$_POST["cp_destino"]}',
	celular = '{$_POST["celular"]}',
	id_conductores = '{$_POST["id_conductores"]}',
	total = '{$_POST["total"]}',
	num_eco = '{$_POST["num_eco"]}',
	efectivo = '{$_POST["efectivo"]}',
	tarjeta = '{$_POST["tarjeta"]}',
	transferencia = '{$_POST["transferencia"]}',
	facturar = '{$_POST["facturar"]}',
	forma_pago = '{$_POST["forma_pago"]}',
	taquilla = '{$_POST["taquilla"]}',
	id_terminal = '{$_POST["id_terminal"]}',
	domicilio = '{$_POST["domicilio"]}'
	
	
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