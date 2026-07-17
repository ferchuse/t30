<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	
	
	$query ="INSERT INTO descuentos SET 
	id_descuento = '{$_POST["id_descuento"]}',
	tipo_descuento = '{$_POST["tipo_descuento"]}'

	ON DUPLICATE KEY UPDATE
	
	id_descuento = '{$_POST["id_descuento"]}',
	tipo_descuento = '{$_POST["tipo_descuento"]}'

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