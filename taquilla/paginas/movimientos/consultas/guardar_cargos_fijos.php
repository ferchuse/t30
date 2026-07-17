<?php 
	
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	// if($_POST["tipo_cargo"] == 'gasto_administracion'){
		
		// $motivo = "Gastos Administrativos ";
	// }
	// else{
		// $motivo = "Seguro";
	// }
	
	$insert_cargo  ="	INSERT INTO cargos_fijos	SET 
	id_usuarios = '{$_COOKIE["id_usuarios"]}',
	id_empresas = '{$_COOKIE["empresa_asignada"]}',
	monto = '{$_POST["monto"]}',
	fecha_cargos = '{$_POST["fecha_cargos"]}',
	num_eco =  '{$_POST["num_eco"]}',
	concepto = '{$_POST["concepto"]}'
	
	ON DUPLICATE KEY UPDATE    
	monto = '{$_POST["monto"]}'
	
	";
	
	$result_insert = 	mysqli_query($link,$insert_cargo);
	
	if($result_insert){
		$respuesta["estatus"] = "success";
		$respuesta["mensaje_insert"] = "Guardado Correctamente";
		
		$respuesta["insert"] = $insert_cargo;
		
	}
	else{
		
		$respuesta["estatus_insert"] = "error";
		$respuesta["mensaje_insert"] = "Error en insert: ".$insert_cargo .mysqli_error($link);		
	}
	
	
	echo json_encode($respuesta);
	
?>