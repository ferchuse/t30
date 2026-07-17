<?php 
	session_start();
	session_destroy();
	
	// unset($_COOKIE['id_usuarios']);
	// unset($_COOKIE['permiso_usuarios']);
	// unset($_COOKIE['nombre_usuarios']);
	// unset($_COOKIE['id_turnos']);
	
	// setcookie("id_usuarios", "",  time() - 3600, "/");
	// setcookie("permiso_usuarios", "",  time() - 3600, "/");
	// setcookie("nombre_usuarios", "",  time() - 3600, "/");
	// setcookie("id_turnos", "",  time() - 3600, "/");
	
	setcookie("id_usuarios", "",  0, "/");
	setcookie("nombre_usuarios", "",  0, "/");
	setcookie("permiso_usuarios", "",  0, "/");
	setcookie("id_recaudaciones","",  0, "/"); 
	setcookie("id_administrador",  "",  0, "/");
	setcookie("id_taquilla", "",  0, "/");
	setcookie("tipo_usuario", "",  0, "/");
	
	header("location:form_login.php");
	
	
?>

