<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	
	$insert ="INSERT INTO sencillos_precios SET 
	id_precio = '{$_POST['id_precio']}' ,
	destino = UPPER('{$_POST['destino']}'),
	precio = '{$_POST["precio"]}',
	tipo_viaje = '{$_POST["tipo_viaje"]}',
	estatus_precio = '{$_POST["estatus_precio"]}'
	
	ON DUPLICATE KEY UPDATE
	
	id_precio = '{$_POST['id_precio']}' ,
	destino = UPPER('{$_POST['destino']}'),
	precio = '{$_POST["precio"]}',
	tipo_viaje = '{$_POST["tipo_viaje"]}',
	estatus_precio = '{$_POST["estatus_precio"]}'
	
	";
	
	
	$result = 	mysqli_query($link,$insert);
	
	if($result){
		$respuesta["estatus"] = "success";
		
	}
	else{ 
		$respuesta["insert"] = "$insert";		
		$respuesta["estatus"] = "error";		
		$respuesta["error"] = mysqli_error($link);		
	}		
	
	
	
	
	echo json_encode($respuesta);
	
?>