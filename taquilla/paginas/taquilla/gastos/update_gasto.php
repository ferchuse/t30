<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	
	
	$query ="UPDATE gastos_corrida SET 
		
	id_cat_gastos = '{$_POST["id_cat_gastos"]}',
	detalles = '{$_POST["detalles"]}',
	recibe = '{$_POST["recibe"]}',
	fecha_edicion = NOW(),
	usuario_edicion = '{$_COOKIE["id_usuarios"]}',
	importe = '{$_POST["importe"]}'
	
	WHERE id_gastos = '{$_POST["id_gastos"]}'
	
	";	
	
	
	
	$exec_query = 	mysqli_query($link,$query);
	$respuesta["query"] = $query;
	
	if($exec_query){
		$respuesta["estatus"] = "success";
		$respuesta["mensaje"] = "Guardado";
		$respuesta["folio"] = mysqli_insert_id($link);
		
		
    }else{
		
		$respuesta["estatus"] = "error";
		$respuesta["mensaje"] = "Error en insert: $query  ".mysqli_error($link);		
	}
	
	echo json_encode($respuesta);
	
?>