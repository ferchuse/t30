<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	$insert_boletaje ="INSERT INTO boletaje SET 
	fecha_boletaje = CURTIME(),
	id_unidades = {$_POST['id_unidades']},
	total_boletaje = '{$_POST["total_boletaje"]}',
	cantidad_boletos = '{$_POST["cantidad_boletos"]}', 
	id_usuarios = '{$_POST["id_usuarios"]}',
	id_recaudaciones = '{$_POST["id_recaudaciones"]}'
	";
	
	$result_boletaje = 	mysqli_query($link,$insert_boletaje);
	
	if($result_boletaje){
		$respuesta["estatus"] = "success";
		$respuesta["mensaje_insert"] = "Guardado Correctamente";
		$respuesta["insert_id"] = mysqli_insert_id($link);
		$respuesta["insert"] = $insert_boletaje;
	}
	else{
		$respuesta["estatus_insert"] = "error";
		$respuesta["mensaje_insert"] = "Error en insert: $insert_boletaje  ".mysqli_error($link);		
	}
	
	//Inserta detalle de boletos
	foreach($_POST["denominacion"] as $i=> $item){
		$insert_boletaje_detalle ="INSERT INTO boletaje_detalle SET 
		id_boletaje = {$respuesta["insert_id"]} ,
		denominacion = {$_POST['denominacion'][$i]},
		cantidad = '{$_POST["cantidad"][$i]}',
		importe = '{$_POST["importe"][$i]}'
		";
		$result_detalle = 	mysqli_query($link,$insert_boletaje_detalle);
		
		if($result_detalle){
			$respuesta["result_detalle"] = "success";
		}
		else{
			$respuesta["result_detalle"] = "Error en insert: $insert_boletaje  ".mysqli_error($link);		
		}		
	}
	
	
	//Busca tarjeta Sin recaudar donde unidad = id_unidades 
	
	
	$update_tarjeta = "UPDATE tarjetas SET 
	cantidad_termicos = '{$_POST['cantidad_boletos']}', 
	importe_termicos = '{$_POST['total_boletaje']}'
	
	WHERE id_unidades = {$_POST['id_unidades']} AND estatus_tarjetas = 'Sin Recaudar'";
	
	$result_tarjeta = mysqli_query($link, $update_tarjeta);
	
	if(!$result_tarjeta){
		$respuesta["result_tarjeta"] ="Error en $update_tarjeta ". mysqli_error($link);		
	}
	
	//actualiza 
	
	// $update ="UPDATE tarjetas SET 
	// estatus_tarjetas = 'Recaudada'
	// WHERE tarjeta = {$_POST['tarjeta']}
	// ";	
	
	
	// $result_update = 	mysqli_query($link,$update);
	
	// if($result_update){
	// $respuesta["estatus_update"] = "success";
	// $respuesta["mensaje_update"] = "Tarjeta Actualizada";
	// $respuesta["update"] = $update;
	
	// }
	// else{
	
	// $respuesta["estatus_update"] = "error";
	// $respuesta["mensaje_update"] = "Error en update: $update  ".mysqli_error($link);		
	// }
	
	
	// Obtiene saldo anterior
	// $q_saldo_anterior = "SELECT id_unidades, saldo_unidades FROM tarjetas LEFT JOIN unidades USING(id_unidades ) WHERE tarjeta = '{$_POST['tarjeta']}'";
	
	// $result_saldo_anterior = mysqli_query($link, $q_saldo_anterior) ;
	
	// if($result_saldo_anterior){
	// $respuesta["result_saldo_anterior"] ="success";
	// while($fila = mysqli_fetch_assoc($result_saldo_anterior)){
	
	// $saldo_anterior = $fila["saldo_unidades"];
	// $id_unidades = $fila["id_unidades"];
	// $respuesta["id_unidades"] = $id_unidades;
	// $respuesta["saldo_anterior"] = $saldo_anterior;
	// }
	
	
	// }
	// else{
	
	// $respuesta["result_saldo_anterior"] = "Error en $q_saldo_anterior, ". mysqli_error($link);
	// }
	
	// Inserta en estado de cuenta 
	
	// $saldo_unidades_restante =  $saldo_anterior + $_POST["total_recaudado"];
	
	// $insert_estado_cuenta = "INSERT INTO estado_cuenta 
	// SET motivo = 'Abono #{$respuesta["insert_id"]}',
	// fecha_estado_cuenta = CURDATE(),
	// id_unidades =  {$id_unidades},
	// abono =  {$_POST["total_recaudado"]},
	// saldo = '$saldo_unidades_restante',
	// saldo_anterior = '$saldo_anterior'
	
	// ";
	
	// $result_estado = mysqli_query($link,$insert_estado_cuenta) ;
	
	// if($result_estado){
	// $respuesta["result_estado"] = "success";
	// }
	// else{
	// $respuesta["result_estado"] = "Error $insert_estado_cuenta, ".mysqli_Error($link);
	// }
	
	// Actualiza Saldo restante
	
	// $update_saldo = "UPDATE unidades SET saldo_unidades = '$saldo_unidades_restante' WHERE id_unidades = $id_unidades";
	
	// $result_saldo = mysqli_query($link,$update_saldo) ;
	
	// if($result_saldo){
	// $respuesta["result_saldo"] = "success";
	// }
	// else{
	// $respuesta["result_saldo"] = "Error en $update_saldo". mysqli_Error($link);
	// }
	
	
	echo json_encode($respuesta);
	
?>