<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	

	$insert_traspaso  ="INSERT INTO cargos SET 
	fecha_cargos = '{$_POST['fecha_cargos']}',
	num_eco = '{$_POST['num_eco']}',
	id_empresas = '{$_POST['id_empresas']}',
	concepto = UPPER('{$_POST['concepto']}'),
	monto = '{$_POST["monto"]}',
	id_usuarios = '{$_COOKIE["id_usuarios"]}'
	";
	
	$result_insert = 	mysqli_query($link,$insert_traspaso);
	
	if($result_insert){
		$respuesta["estatus"] = "success";
		$respuesta["mensaje_insert"] = "Guardado Correctamente";
		$respuesta["folio"] = mysqli_insert_id($link);
		
	}
	else{
		
		$respuesta["estatus_insert"] = "error";
		$respuesta["mensaje_insert"] = "Error en insert: $insert_traspaso  ".mysqli_error($link);		
	}
	
	
	
	echo json_encode($respuesta);
	
?>