<?php
	include("../../../conexi.php");
	$link = Conectarse();
	
	
	
	$consulta = "UPDATE boletos SET 
	{$_GET["campo"]} = CURTIME(),
	usuario_salida = '{$_COOKIE["nombre_usuarios"]}'
	WHERE id_boletos = '{$_GET["folio"]}'";
	
	$result = mysqli_query($link,$consulta);
	
	if(!$result){
		$respuesta["estatus"] = "error";
		$respuesta["error"] = mysqli_error($link) ;
		$respuesta["consulta"] = $consulta ;
	}
	else{
		$respuesta["estatus"] = "success";
		
	}
	
	echo json_encode($filas);
	
	
	
	
	
?>
