<?php
	header("Content-Type: application/json");
	$response= array();
	include("../../conexi.php");
	$link=Conectarse();
	$myusername=$_POST['nombre_usuarios'];
	$mypassword=$_POST['pass_usuarios']; 
	
	// To protect mysqli injection (more detail about mysqli injection)
	$myusername = stripslashes($myusername);
	$mypassword = stripslashes($mypassword);
	/* $myusername = mysqli_real_escape_string($myusername);
	$mypassword = mysqli_real_escape_string($mypassword); */
	$sql="SELECT 
	estatus_usuarios,
	tipo_impresora,
	id_usuarios,
	nombre_usuarios,
	id_recaudaciones, 
	id_administrador, 
	taquilla_predet, 
	empresa_asignada, 
	'recaudacion' AS tipo_usuario 
	FROM usuarios
	WHERE nombre_usuarios='$myusername' 
	AND pass_usuarios='$mypassword'
	
	
	";
	$result=mysqli_query($link, $sql);
	if (!$result){
		die('Error: ' . mysqli_error($link));
	}
	$count=mysqli_num_rows($result);
	
	$response["sql"] = $sql; 
	
	// Si la consulta devuelve 1 fila inicia la sesion
	if($count==1){
		$row = mysqli_fetch_assoc($result);
		if( $row["estatus_usuarios"] == 'Activo'){
			
			
			
			session_start();
			session_regenerate_id(true);
			$response["login"] = "valid"; 
			
			$id_sesion = session_id();
			
			
			$id_usuarios = $row["id_usuarios"];
			$nombre_usuarios= $row["nombre_usuarios"];
			$baseurl = "/t30";
			
			if($_SERVER["SERVER_NAME"] == "localhost"){
				setcookie("id_usuarios", $id_usuarios,  0, $baseurl );
				setcookie("nombre_usuarios", $nombre_usuarios,  0, $baseurl);
				// setcookie("permiso_usuarios", $row["permiso_usuarios"],  0, "/taxidriver");
				setcookie("id_administrador",  $row["id_administrador"],  0, $baseurl);
				setcookie("tipo_usuario",  $row["tipo_usuario"],  0, $baseurl);
				setcookie("id_taquilla", $row["taquilla_predet"],  0, $baseurl);
				setcookie("tipo_impresora", $row["tipo_impresora"],  0, $baseurl);
				setcookie("empresa_asignada", $row["empresa_asignada"],  0, $baseurl);
				
			}
			else{
				setcookie("id_usuarios", $id_usuarios,  0, "/");
				setcookie("nombre_usuarios", $nombre_usuarios,  0, "/");
				// setcookie("permiso_usuarios", $row["permiso_usuarios"],  0, "/");
				setcookie("id_administrador",  $row["id_administrador"],  0, "/");
				setcookie("tipo_usuario",  $row["tipo_usuario"],  0, "/");
				setcookie("id_taquilla", $row["taquilla_predet"],  0, "/");
				setcookie("tipo_impresora", $row["tipo_impresora"],  0, "/");
				setcookie("empresa_asignada", $row["empresa_asignada"],  0, "/");
				
			}
			
			
			
		}
		else{
			
			$response["login"] = "invalid"; 
			$response["mensaje"] = "El Usuario no está Activo";
		}
		
		
	}
	else{
		$response["login"] = "invalid";
		$response["mensaje"] = "usuarios y/o Contraseña Inválidos";
	}
	// $response["query"] = $sql;
	echo json_encode($response);
?>