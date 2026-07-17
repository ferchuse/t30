<?php
	header("Content-Type: application/json");
	include ("../../../conexi.php");
	$link = Conectarse();
	$respuesta = Array();
	
	$consulta = "INSERT INTO clientes SET 
	id_clientes = '{$_POST["id_clientes"]}',
	rfc_clientes = '{$_POST["rfc_clientes"]}',
	razon_social_clientes = '{$_POST["razon_social_clientes"]}',
	regimen_clientes = '{$_POST["regimen_clientes"]}',
	cp_clientes = '{$_POST["cp_clientes"]}',
	correo_clientes = '{$_POST["correo_clientes"]}'
	
	ON DUPLICATE KEY UPDATE 
	
	rfc_clientes = '{$_POST["rfc_clientes"]}',
	razon_social_clientes = '{$_POST["razon_social_clientes"]}',
	regimen_clientes = '{$_POST["regimen_clientes"]}',
	cp_clientes = '{$_POST["cp_clientes"]}',
	correo_clientes = '{$_POST["correo_clientes"]}'
	
	";
	$result = mysqli_query($link, $consulta);
	
	
	
	if($result){
		$respuesta["status"] = "success";
		$respuesta["mensaje"] = "Guardado";
		$respuesta["id_clientes"] = mysqli_insert_id($link);
		
		
	}	
	else{
		$respuesta["status"] = "error";
		$respuesta["mensaje"] = "Error $consulta  ".mysqli_error($link);		
	}
	
	echo json_encode($respuesta);
?>