<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	
	
	$query ="INSERT INTO cat_gastos SET 
	id_cat_gastos = '{$_POST["id_cat_gastos"]}',
	descripcion_gastos = '{$_POST["descripcion_gastos"]}'

	ON DUPLICATE KEY UPDATE
	
	id_cat_gastos = '{$_POST["id_cat_gastos"]}',
	descripcion_gastos = '{$_POST["descripcion_gastos"]}'

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