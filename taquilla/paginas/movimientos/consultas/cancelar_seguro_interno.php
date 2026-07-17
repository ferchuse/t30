<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	$fecha_cancelacion = date("Y-m-d H:i:s");
	
	
	$cancela_abono = "UPDATE seguro_interno
	
	SET
	estatus = 'Cancelado' ,
	datos_cancelacion='Usuario: {$_COOKIE["nombre_usuarios"]} <br> Fecha: $fecha_cancelacion'
	WHERE  id_seguro_interno = {$_GET["folio"]}";
	
	$result_abono = mysqli_query($link,$cancela_abono) ;
	
	if($result_abono){
		$respuesta["result"] = "success";
	}
	else{
		$respuesta["result"] = "Error en $cancela_abono". mysqli_Error($link);
	}
	
	
	
	echo json_encode($respuesta);
	
?>