<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	
	$insert ="INSERT INTO ordenes_trabajo SET 
	
	
	id_conductores= '{$_POST['id_conductores']}',
	num_eco= '{$_POST['num_eco']}',
	fecha_vencimiento = '{$_POST['fecha_vencimiento']}',
	dias = '{$_POST['dias']}',
	fecha_inicio = '{$_POST["fecha_inicio"]}',
	fecha_fin = '{$_POST["fecha_fin"]}'
	
	";
	
	$result = 	mysqli_query($link,$insert);
	
	if($result){
		$respuesta["estatus"] = "success";
		$respuesta["mensaje_insert"] = "Guardado Correctamente";
		$respuesta["insert_id"] = mysqli_insert_id($link);
		$respuesta["folio"] =$nuevo_folio;
		$respuesta["insert"] = $insert;
	}
	else{
		$respuesta["estatus_insert"] = "error";
		$respuesta["errnum"] = mysqli_errno($link);		
		$respuesta["mensaje_insert"] = "Error en insert: $insert  ".mysqli_error($link);		
	}
	
	
	
	
	echo json_encode($respuesta);
	
?>