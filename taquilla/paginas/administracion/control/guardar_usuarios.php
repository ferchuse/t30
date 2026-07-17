<?php 
	session_start();
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	if($_POST['id_usuarios'] == '') {
		//inserta
		$q_usuario ="INSERT INTO usuarios SET  
		id_recaudaciones = '{$_POST['id_recaudaciones']}' , 
		estatus_usuarios = '{$_POST['estatus_usuarios']}' , 
		nombre_usuarios = '{$_POST['nombre_usuarios']}' , 
		empresa_asignada = '{$_POST['empresa_asignada']}' , 
		nombre_completo_usuarios = '{$_POST['nombre_completo_usuarios']}' , 
		pass_usuarios ='{$_POST['pass_usuarios']}',
		taquilla_predet ='{$_POST['taquilla_predet']}',
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
		estatus_usuarios = '{$_POST['estatus_usuarios']}' , 
		empresa_asignada = '{$_POST['empresa_asignada']}' , 
		nombre_usuarios = '{$_POST['nombre_usuarios']}' , 
		nombre_completo_usuarios = '{$_POST['nombre_completo_usuarios']}' , 
		pass_usuarios = '{$_POST['pass_usuarios']}'
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
		id_usuarios = '{$id_usuarios}',
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
	
	
	//inserta en permisos empresas
	if(isset($_POST["acceso_empresas"]))
	{
		foreach ($_POST["acceso_empresas"] as $id_empresas => $empresa){
			
			$insert_accesos = "INSERT INTO usuarios_empresas SET
			id_empresas = '{$id_empresas}',
			id_usuarios = '{$id_usuarios}',
			acceso = '{$empresa}'
			
			ON DUPLICATE KEY UPDATE acceso =  '{$empresa}'
			";
			
			$result_permisos = 	mysqli_query($link,$insert_accesos);
			
			if($result_permisos){
				$respuesta["result_accesos"] = "success";
				
			}
			else{
				$respuesta["result_accesos"][] = "Error". mysqli_Error($link);
			}
			$respuesta["consulta_accesos"][] = $insert_accesos;
		}
	}
	
	
	
	
	echo json_encode($respuesta);
	
?>