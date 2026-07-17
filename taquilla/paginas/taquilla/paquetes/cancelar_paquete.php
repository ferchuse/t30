<?php 
	session_start();
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	$fecha_cancelacion = date("Y-m-d H:i:s");
	
	$datos_cancelacion = 
	"
	Usuario: {$_COOKIE["nombre_usuarios"]} <br>
	Fecha: $fecha_cancelacion <br>

	
	";
	
	$cancelar = "UPDATE paquetes
	SET
	estatus_paquetes = 'Cancelado' ,
	datos_cancelacion = '$datos_cancelacion'
	WHERE  id_paquetes = {$_POST["id_registro"]}";
	
	$result = mysqli_query($link,$cancelar) ;
	
	if($result){
		$respuesta["result"] = "success";
		$respuesta["consulta"] = "$cancelar";
	}
	else{
		$respuesta["result"] = "Error en $cancelar". mysqli_Error($link);
	}
	
	
	
	echo json_encode($respuesta);
	
?>