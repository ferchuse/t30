<?php 
	session_start();
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	$fecha_cancelacion = date("Y-m-d H:i:s");

	$cancelar = "UPDATE corridas
	LEFT JOIN boletos 
	USING(id_corridas)
	SET
	estatus_corridas = 'Cancelado',
	estatus_boletos = 'Cancelado',
	corridas.datos_cancelacion='Usuario: {$_GET["nombre_usuarios"]} <br> Fecha: $fecha_cancelacion',
	boletos.datos_cancelacion='Usuario: {$_GET["nombre_usuarios"]} <br> Fecha: $fecha_cancelacion'
	WHERE  id_corridas = '{$_GET["id_registro"]}'";
	
	$result = mysqli_query($link,$cancelar) ;
	
	$respuesta["consulta"] = "$cancelar";
	if($result){
		$respuesta["result"] = "success";
	}
	else{
		$respuesta["result"] = "Error en ". mysqli_Error($link);
	}
	
	
	echo json_encode($respuesta);
	
?>