<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	

	
	$insert_traspaso  ="INSERT INTO traspasos_utilidad SET 
	id_empresas = '{$_COOKIE["empresa_asignada"]}',
	id_beneficiarios = '{$_POST["id_beneficiarios"]}',
	id_usuarios = '{$_COOKIE["id_usuarios"]}',
	fecha_traspaso = NOW(),	
	fecha_aplicacion = '{$_POST['fecha_aplicacion']}',
	id_cat_gastos = '{$_POST['id_cat_gastos']}',
	observaciones = '{$_POST["observaciones"]}',
	forma_pago = '{$_POST["forma_pago"]}',
	importe_traspaso = '{$_POST["importe_traspaso"]}'
	";
	
	$result_insert = 	mysqli_query($link,$insert_traspaso);
	
	if($result_insert){
		$respuesta["estatus"] = "success";
		$respuesta["mensaje"] = "Guardado Correctamente";
		$respuesta["insert_id"] = mysqli_insert_id($link);
		// $respuesta["insert"] = $insert_traspaso;
		
	}
	else{
		 
		$respuesta["estatus"] = "error";
		$respuesta["error"] = "Error en insert: $insert_traspaso  ".mysqli_error($link);		
	}
	

	
	foreach($_POST["num_eco"] as $i => $item){
		
		$insert_monto_unidades ="INSERT into traspasos_utilidad_unidades SET 
		id_traspaso = '{$respuesta["insert_id"]}',
		monto = '{$_POST['monto'][$i]}',
		num_eco = '{$_POST['num_eco'][$i]}'
		";	
		
		$result_monto_unidades = 	mysqli_query($link,$insert_monto_unidades);
		
		if($result_monto_unidades){
			$respuesta["result_monto_unidades"] = "success";
			
		}
		else{
			
			$respuesta["result_monto_unidades"] = "error";
			$respuesta["insert_monto_unidades"] = "Error en: $insert_monto_unidades  ".mysqli_error($link);		
		}
	}
	
	echo json_encode($respuesta);
	
?>