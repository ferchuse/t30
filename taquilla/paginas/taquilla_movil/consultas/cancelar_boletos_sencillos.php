<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	$fecha_cancelacion = date("d/m/Y H:i:s");
	
	$respuesta = array();
	
	
	
	$cancelar = "UPDATE sencillos_boletos 
	SET estatus_boletos = 'Cancelado' ,
	datos_cancelacion = 'Usuario: {$_COOKIE["nombre_usuarios"]} <br> Fecha: $fecha_cancelacion <br> Motivo: {$_GET["motivo"]}'
	WHERE id_boletos = {$_GET["folio"]}";
	
	$result_abono = mysqli_query($link,$cancelar) ;
	
	if($result_abono){
		$respuesta["result"] = "success";
	}
	else{
		$respuesta["result"] = "Error en $cancelar". mysqli_Error($link);
	}
	
	
	
	
	echo json_encode($respuesta);
	
?>