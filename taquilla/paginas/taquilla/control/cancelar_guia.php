<?php 
	session_start();
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	$fecha_cancelacion = date("Y-m-d H:i:s");

	$cancelar = "UPDATE boletaje
	SET
	estatus_boletaje = 'Cancelado',
	datos_cancelacion='Usuario: {$_GET["nombre_usuarios"]} <br> Fecha: $fecha_cancelacion'
	WHERE  id_boletaje = {$_GET["id_registro"]}";
	
	$result = mysqli_query($link,$cancelar) ;
	
	$respuesta["consulta"] = "$onsulta";
	if($result){
		$respuesta["result"] = "success";
	}
	else{
		$respuesta["result"] = "Error". mysqli_Error($link);
	}
	
	
	echo json_encode($respuesta);
	
?>