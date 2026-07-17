<?php 
	include("../../../../conexi.php");
	$link = Conectarse();
	$recoleccion = array();
	
	
	//busca datos de la recolecciones
	
	$consulta = "SELECT * FROM recolecciones WHERE id_recoleccion = '{$_POST["id_recoleccion"]}' ";
	
	
	$result_recoleccion = mysqli_query($link, $consulta);
	
	
	while($row = mysqli_fetch_assoc($result_recoleccion)){
		
		$recoleccion = $row;
	}
	
	
	
	
	
	switch($_POST["forma_pago"]){
		
		case "Efectivo";
		$efectivo = $_POST["restante"] ;
		$tarjeta = 0;
		$transferencia =0;
		break;
		
		case "Tarjeta";
		$tarjeta = $_POST["restante"];
		$efectivo = 0;
		$transferencia =0;
		break;
		
		case "Transferencia";
		$transferencia = $_POST["restante"];
		$tarjeta = 0;
		$efectivo =0;
		break;
		
		
	}
	
	
	
	//Inserta Boleto
	
	$insert = "INSERT INTO boletos SET
	
	id_conductores = '{$recoleccion["id_conductores"]}',
	num_eco = '{$recoleccion["num_eco"]}',
	fecha_boletos = '{$recoleccion["fecha_recoleccion"]}',
	forma_pago = '{$_POST["forma_pago"]}',
	id_usuarios = '{$_COOKIE["id_usuarios"]}',
	nombre_pasajero = '{$recoleccion["nombre_pasajero"]}',
	pasajeros = '{$recoleccion["pasajeros"]}',
	origen = '{$recoleccion["destino"]}',
	destino = 'AIFA',
	total = '{$recoleccion["total"]}',
	celular = '{$recoleccion["celular"]}',
	efectivo = '{$efectivo}',
	tarjeta = '{$tarjeta}',
	transferencia = '{$transferencia}',
	id_terminal = '{$_POST["id_terminal"]}',
	facturar = '{$_POST["facturar"]}'
	
	
	";
	
	// $respuesta["insert"] = $insert;
	$exec_query = mysqli_query($link,$insert);
	
	if($exec_query){
		$respuesta["estatus"] = "success";
		$respuesta["id_boletos"] = mysqli_insert_id($link);
		
	}
	else{
		$respuesta["estatus"] = "error";
		$respuesta["error"] = mysqli_error($link);	
		
	}
	
	
	
	//Actualiza Recoleccion
	
	$update = "UPDATE recolecciones SET
	
	estatus_recoleccion = 'FINALIZADA',
	referencia = '{$_POST["referencia"]}',
	restante = '0',
	id_boletos = '{$respuesta["id_boletos"]}'
	
	
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