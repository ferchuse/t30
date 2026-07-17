<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	$insert ="INSERT INTO corridas SET 
	fecha_corridas= '{$_POST['fecha_corridas']}',
	hora_corridas= '{$_POST['hora_corridas']}',
	id_unidades = '{$_POST['id_unidades']}',
	id_taquillas = '{$_POST['id_recaudaciones']}',
	id_usuarios = '{$_POST["id_usuarios"]}',
	id_origenes = '{$_POST["id_origenes"]}',
	id_destinos = '{$_POST["id_destinos"]}',
	id_administrador = '{$_COOKIE["id_administrador"]}',
	estatus_corridas = 'Activo'
	";
	
	$result = 	mysqli_query($link,$insert);
	
	if($result){
		$respuesta["estatus"] = "success";
		$respuesta["mensaje_insert"] = "Guardado Correctamente";
		$respuesta["insert_id"] = mysqli_insert_id($link);
		$respuesta["insert"] = $insert;
	}
	else{
		$respuesta["estatus_insert"] = "error";
		$respuesta["mensaje_insert"] = "Error en insert: $insert  ".mysqli_error($link);		
	}
	
	
	
	echo json_encode($respuesta);
	
?>