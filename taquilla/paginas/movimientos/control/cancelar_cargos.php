<?php 
	session_start();
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	$fecha_cancelacion = date("Y-m-d H:i:s");
	
	$cancelar = "
	UPDATE cargos
	SET
	estatus = 'Cancelado' ,
	datos_cancelacion='Usuario: {$_COOKIE["nombre_usuarios"]} <br> Fecha: $fecha_cancelacion'
	WHERE  id_cargos = {$_GET["id_registro"]}";
	
	$result = mysqli_query($link,$cancelar) ;
	
	if($result){
		$respuesta["result"] = "success";
	}
	else{
		$respuesta["result"] = "Error en $cancelar". mysqli_Error($link);
	}
	
	
	
	echo json_encode($respuesta);
	
?>