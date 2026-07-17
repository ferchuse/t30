<?php 
	session_start();
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	if($_POST['id_usuarios'] == '') {
		//inserta
		$q_usuario ="INSERT INTO usuarios SET  
		id_recaudaciones = '{$_POST['id_recaudaciones']}' , 
		fecha_creacion = NOW() , 
		estatus_usuarios = '{$_POST['estatus_usuarios']}' , 
		nombre_usuarios = '{$_POST['nombre_usuarios']}' , 
		nombre_completo_usuarios = '{$_POST['nombre_completo_usuarios']}' , 
		pass_usuarios ='{$_POST['pass_usuarios']}',
		taquilla_predet ='{$_POST['taquilla_predet']}',
		tipo_usuario ='{$_POST['tipo_usuario']}',
		id_administrador ='1'
		
		";	
		
		$result_usuarios = 	mysqli_query($link,$q_usuario);
		
		if($result_usuarios){
			
			$respuesta["action"] = "insert";
			$respuesta["estatus"] = "success";
			$respuesta["mensaje"] = "Guardado";
			$id_usuarios = mysqli_insert_id($link);
		}
		else{
			$respuesta["estatus"] = "error";
			$respuesta["mensaje"] = "Error en $q_usuario ".mysqli_error($link);		
		}
	}
	else{
		
		//actualiza
		$q_usuario ="UPDATE usuarios SET 
		id_recaudaciones = '{$_POST['id_recaudaciones']}' , 
		estatus_usuarios = '{$_POST['estatus_usuarios']}' , 
		nombre_usuarios = '{$_POST['nombre_usuarios']}' , 
		nombre_completo_usuarios = '{$_POST['nombre_completo_usuarios']}' , 
		pass_usuarios = '{$_POST['pass_usuarios']}',
		tipo_usuario ='{$_POST['tipo_usuario']}',
		taquilla_predet ='{$_POST['taquilla_predet']}'
		WHERE
		id_usuarios = '{$_POST['id_usuarios']}'
		";
		$result_usuarios = 	mysqli_query($link,$q_usuario);
		
		
		if($result_usuarios){
			$respuesta["action"] = "update";
			$respuesta["estatus"] = "success";
			$respuesta["mensaje"] = "Actualizado";
			$id_usuarios = $_POST["id_usuarios"];
		}
		else{
			$respuesta["estatus"] = "error";
			$respuesta["mensaje"] = "Error en $q_usuario ".mysqli_error($link);		
		}
		
	}
	
	
	
	//inserta en permisos paginas
	
	foreach ($_POST{"id_paginas"} as $i => $pagina){
		
		$q_permisos = "INSERT INTO permisos SET
		id_paginas = '{$_POST["id_paginas"][$i]}',
		id_usuarios = '$id_usuarios',
		permiso = '{$_POST["permisos"][$i]}'
		
		ON DUPLICATE KEY UPDATE permiso =   '{$_POST["permisos"][$i]}'
		";
		
		$result_permisos = 	mysqli_query($link,$q_permisos);
		
		if($result_permisos){
			$respuesta["result_permisos"] = "success";
			
		}
		else{
			$respuesta["result_permisos"] = "Error". mysqli_Error($link);
		}
	}
	
	
		
	
	
	
	
	echo json_encode($respuesta);
	
?>