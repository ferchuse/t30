<?php 
	session_start();
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	$fecha_cancelacion = date("Y-m-d H:i:s");
	//Cancelar Abono
	
	$cancela_abono = "UPDATE recibos_pachuca
	
	SET
	estatus_reciboSalidas = 'Cancelado' ,
	datos_cancelacion='Usuario: {$_COOKIE["nombre_usuarios"]} <br> Fecha: $fecha_cancelacion'
	WHERE  id_reciboSalidas = {$_GET["id_registro"]}";
	
	$result_abono = mysqli_query($link,$cancela_abono) ;
	
	if($result_abono){
		$respuesta["result"] = "success";
	}
	else{
		$respuesta["result"] = "Error en $cancela_abono". mysqli_Error($link);
	}
	
	
	
	echo json_encode($respuesta);
	
?>