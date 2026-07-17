<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	// include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	$consulta = "
	SELECT
	limite 
	FROM limites_mensuales
	
	WHERE 
	
	id_motivo = '{$_GET["id_motivo"]}'
	AND MONTH(fecha) = MONTH('{$_GET["fecha"]}')
	AND YEAR ( fecha ) = YEAR ( '{$_GET["fecha"]}' )
	
	";
	
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			$respuesta["num_rows"] = 0;
		}
		
		while($fila = mysqli_fetch_assoc($result)){
			
			$filas = $fila ;
			
		}
		$respuesta["filas"] = $filas;
		
	}
	else {
		
		
		$respuesta["estatus"] = "error";
		$respuesta["mensaje"] = "Error en ".$consulta.mysqli_Error($link);
	}
	
	$respuesta["consulta"] = "$consulta";
	
	
	
	$consulta_gastado = "
	
	SELECT
	id_motivosSalida,
	COALESCE ( SUM( monto_reciboSalidas ), 0 ) AS total_gastado 
	FROM
	recibos_salidas 
	WHERE
	id_motivosSalida = '{$_GET["id_motivo"]}' 
	AND MONTH ( fecha_aplicacion ) = MONTH ( '{$_GET["fecha"]}' )
	AND YEAR ( fecha_aplicacion ) = YEAR ( '{$_GET["fecha"]}' )
	
	
	
	
	";
	
	$respuesta["consulta_gastado"] = $consulta_gastado;
	
	
	$result_gastado = mysqli_query($link,$consulta_gastado);
	if($result){
		
		
		while($fila = mysqli_fetch_assoc($result_gastado)){
			
			$respuesta["total_gastado"] = $fila["total_gastado"];
			
		}
		
		
	}
	else {
		
		
		$respuesta["estatus"] = "error";
		$respuesta["mensaje"] = "Error en ".$consulta.mysqli_Error($link);
	}
	
	
	echo json_encode($respuesta);
	
?>	