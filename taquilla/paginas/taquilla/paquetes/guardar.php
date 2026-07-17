<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	
	
	$query ="INSERT INTO paquetes SET 
	id_corridas = '{$_POST["id_corridas"]}',
	id_usuarios = '{$_COOKIE["id_usuarios"]}',
	id_taquilla = '{$_COOKIE["id_taquilla"]}',
	id_taquilla_destino = '{$_POST["id_taquilla"]}',
	fecha_paquetes = NOW(),
	tipo_paquete = '{$_POST["tipo_paquete"]}',
	remitente = '{$_POST["remitente"]}',
	destinatario = '{$_POST["destinatario"]}',
	contenido = '{$_POST["contenido"]}',
	costo = '{$_POST["costo"]}'
	
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