<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	$q_usuario =" 
	
	UPDATE usuarios SET
	
	pass_usuarios = '{$_POST['password']}' 
	WHERE id_usuarios  = '{$_COOKIE['id_usuarios']}' 
	
	";	
	
	$result_usuarios = 	mysqli_query($link,$q_usuario);
	
	if($result_usuarios){
		
		$respuesta["action"] = "insert";
		$respuesta["estatus"] = "success";
		$respuesta["mensaje"] = "Guardado";
	}
	else{
		$respuesta["estatus"] = "error";
		$respuesta["mensaje"] = "Error en $q_usuario ".mysqli_error($link);		
	}
	
	
	
	
	
	
	
	echo json_encode($respuesta);
	
?>