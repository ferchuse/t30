<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	$folios_boletos = implode(",", $_POST["id_boletos"]);
	
	
	$insert =
	"INSERT INTO recibossalidas
	SET 
	id_empresas = '{$_POST['id_empresas']}',
	id_beneficiarios = '{$_POST['id_beneficiarios']}',
	id_motivosSalida = '20', 
	monto_reciboSalidas = '{$_POST['monto_recibo']}',
	observaciones_reciboSalidas = 'PAGO TAQUILLA',
	fecha_reciboSalidas = NOW(),
	id_usuarios = '{$_COOKIE['id_usuarios']}',
	id_administrador = 1 
	";
	
	
	
	
	$result = 	mysqli_query($link,$insert);
	
	if($result){
		$respuesta["estatus"] = "success";
		// $respuesta["mensaje_insert"] = "Guardado Correctamente";
		// $respuesta["insert_id"] = mysqli_insert_id($link);
		$respuesta["folio"] = mysqli_insert_id($link);
		// $respuesta["insert"] = $insert;
	}
	else{
		$respuesta["estatus_insert"] = "error";
		$respuesta["errnum"] = mysqli_errno($link);		
		$respuesta["error"] = "Error en insert: $insert  ".mysqli_error($link);		
	}
	
	
	//Actualizar los boletos con el folio del recibossalidas
	
		
	$actualiza_boletos =
	"UPDATE sencillos_boletos 
	SET 
	folio_recaudacion = '{$respuesta["folio"]}'
	
	WHERE id_boletos IN ({$folios_boletos})
	";
	
	
	
	
	$resultactualiza_boletos = 	mysqli_query($link,$actualiza_boletos);
	
	// $respuesta["actualiza_boletos"] = $actualiza_boletos;
	
	if($resultactualiza_boletos){
		// $respuesta["estatus"] = "success";
		// $respuesta["mensaje_insert"] = "Guardado Correctamente";
		// $respuesta["insert_id"] = mysqli_insert_id($link);
		// $respuesta["folio"] = mysqli_insert_id($link);
		// $respuesta["insert"] = $insert;
	}
	else{
		// $respuesta["estatus_insert"] = "error";
		$respuesta["errnum"] = mysqli_errno($link);		
		$respuesta["error"] = "Error en  $resultactualiza_boletos  ".mysqli_error($link);		
	}
	
	
	//Agrupar por destino y agregar a las observaciones del recibo
	
	
	
	
	echo json_encode($respuesta);
	
?>