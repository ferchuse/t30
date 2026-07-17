<?php 
	include("../../../conexi.php");
	$link = Conectarse();
	
	//verificar si el boleto ya ha sido pagado
	
	
	//si ahy alguno que ya esta pagado mandar error y salir
	
	
	
	//Guarda EL pago
	
	$insert = "INSERT INTO comisiones_pago SET
	fecha_pago = NOW(),
	id_usuarios = '{$_COOKIE["id_usuarios"]}',
	id_conductores = '{$_POST["id_conductores"]}',
	num_eco = '{$_POST["num_eco"]}',
	total_viajes = '{$_POST["total_viajes"]}',
	comision = '{$_POST["comision"]}',
	observaciones = '{$_POST["observaciones"]}'
	
	";
	
	// $respuesta["insert"] = $insert;
	$exec_query = mysqli_query($link,$insert);
	
	if($exec_query){
		$respuesta["estatus"] = "success";
		$id_pago = 	 mysqli_insert_id($link);
		$respuesta["folio"] =$id_pago ;
		
	}
	else{
		$respuesta["estatus"] = "error";
		$respuesta["error"] = mysqli_error($link);	
		
	}
	
	
	//Por cada folio boleto actualiza el boleto como pagado comision e inserta en comisione pago
	
	
	// Convertir a arreglo
	$boletos = explode(",", $_POST["folios_pago"]);
	
	foreach($boletos AS $id_boletos){
		
		$insert = "INSERT INTO comisiones_boletos SET
		id_pago = '{$id_pago}',
		id_boletos = '{$id_boletos}'
		";
		
		$result_comisiones_boletos = mysqli_query($link,$insert);
		
		if(!$result_comisiones_boletos){
			$respuesta["comisiones_boletos"] = "error";
			$respuesta["error"] = mysqli_error($link);	
		}
		
		
		$update = "UPDATE boletos SET
		estatus_comision = 'Pagado'
		WHERE id_boletos = '{$id_boletos}'
		";
		
		$result_boletos = mysqli_query($link,$update);
		
		if(!$result_boletos){
			$respuesta["estatus_comision"] = "error";
			$respuesta["error"] = mysqli_error($link);	
		}
		
		
		$respuesta["update"] = $update;
	}
	
	
	
	$insert_traspaso  ="INSERT INTO traspasos_utilidad SET 
	id_empresas = '{$_COOKIE["empresa_asignada"]}',
	id_beneficiarios = '1',
	id_usuarios = '{$_COOKIE["id_usuarios"]}',
	fecha_traspaso = NOW(),	
	fecha_aplicacion = CURDATE(),
	id_cat_gastos = '11', #comision operador
	observaciones = 'Pago Comision Boletos: {$_POST["folios_pago"]}',
	forma_pago = 'Efectivo',
	importe_traspaso = '{$_POST["comision"]}'
	";
	
	$result_insert = 	mysqli_query($link,$insert_traspaso);
	
	if($result_insert){
		$respuesta["estatus"] = "success";
		$respuesta["mensaje"] = "Guardado Correctamente";
		$id_traspaso = mysqli_insert_id($link);
		$respuesta["id_traspaso"] = $id_traspaso;
		// $respuesta["insert"] = $insert_traspaso;
		
	}
	else{
		
		$respuesta["estatus"] = "error";
		$respuesta["error"] = "Error en insert: $insert_traspaso  ".mysqli_error($link);		
	}
	
	
	foreach($_POST["num_eco"] AS $i => $num_eco){
		
		$insert_monto_unidades ="INSERT into traspasos_utilidad_unidades SET 
		id_traspaso = '{$id_traspaso}',
		monto = '{$_POST['comision_unidad'][$i]}',
		num_eco = '{$num_eco}'
		";	
		
		$result_monto_unidades = 	mysqli_query($link,$insert_monto_unidades);
		
		if($result_monto_unidades){
			$respuesta["result_monto_unidades"] = "success";
			$respuesta["insert_monto_unidades"] = $insert_monto_unidades;
			
		}
		else{
			
			$respuesta["result_monto_unidades"] = "error";
			$respuesta["insert_monto_unidades"] = "Error en: $insert_monto_unidades  ".mysqli_error($link);		
		}
	}
	
	
	
	
	
	
	
	
	echo json_encode($respuesta);
?>	