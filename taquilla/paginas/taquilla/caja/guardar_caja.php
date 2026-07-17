<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	
	
	$query ="INSERT INTO boletos_abiertos SET 
	fecha_boleto = NOW(),
	id_corridas = '{$_POST["id_corridas"]}',
	id_usuarios = '{$_COOKIE["id_usuarios"]}',
	nombre_pasajero = '{$_POST["nombre_pasajero"]}',
	importe = '{$_POST["importe"]}',
	id_taquilla = '{$_COOKIE["id_taquilla"]}'
	
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