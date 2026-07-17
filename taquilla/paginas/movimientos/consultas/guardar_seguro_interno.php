<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	
	
	$consulta  ="INSERT INTO seguro_interno SET 

	id_empresas = '{$_POST['id_empresas']}',
	id_beneficiarios = '{$_POST["id_beneficiarios"]}',
	fecha= NOW(),
	monto = '{$_POST["monto"]}',
	observaciones = '{$_POST["observaciones"]}',
	id_unidades = '{$_POST["id_unidades"]}',
	id_usuarios = '{$_COOKIE["id_usuarios"]}',
	id_administrador = '{$_COOKIE["id_administrador"]}'
	";
	
	$result_insert = 	mysqli_query($link,$consulta);
	
	$respuesta["consulta"] = $consulta;
	
	if($result_insert){
		$respuesta["estatus"] = "success";
		$respuesta["mensaje_insert"] = "Guardado Correctamente";
		$respuesta["folio"] = mysqli_insert_id($link);
		
		
	}
	else{
		 
		$respuesta["estatus_insert"] = "error";
		$respuesta["mensaje_insert"] = "Error en insert: $consulta  ".mysqli_error($link);		
	}

	
	
	echo json_encode($respuesta);
	
?>