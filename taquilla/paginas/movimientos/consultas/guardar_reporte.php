<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	
	$insert ="INSERT INTO reportes SET 
	fecha_reportes= '{$_POST['fecha_reportes']}',
	reportes_id_ordenes= '{$_POST['reportes_id_ordenes']}',
	id_cat_incidencia= '{$_POST['id_cat_incidencia']}',
	hechos = '{$_POST['hechos']}',
	reporta = '{$_POST['reporta']}'
	
	";
	
	$result = 	mysqli_query($link,$insert);
	
	if($result){
		$respuesta["estatus"] = "success";
		$respuesta["mensaje_insert"] = "Guardado Correctamente";
		$respuesta["insert_id"] = mysqli_insert_id($link);
		$respuesta["insert_id"] =$nuevo_folio;
		$respuesta["insert"] = $insert;
	}
	else{
		$respuesta["estatus_insert"] = "error";
		$respuesta["errnum"] = mysqli_errno($link);		
		$respuesta["mensaje_insert"] = "Error en insert: $insert  ".mysqli_error($link);		
	}
	
	
	
	
	echo json_encode($respuesta);
	
?>