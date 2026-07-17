<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	// include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	$consulta = "
	SELECT 
	num_eco,
	COALESCE(total_boletos,0) +
	COALESCE(total_abonos,0) - 
	COALESCE(total_cargos,0) - 
	COALESCE(total_cargos_fijos,0) - 
	COALESCE(total_gastos,0) -
	COALESCE(total_traspasos,0)
	AS saldo_actual
	
	FROM (
	SELECT num_eco, SUM(total) AS total_boletos 
	FROM boletos 
	WHERE num_eco = '{$_GET["num_eco"]}'
	
	AND estatus_boletos = 'Activo'
	) AS t_boletos 
	
	LEFT JOIN (
	SELECT num_eco, SUM(monto) AS total_cargos
	FROM cargos 
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND estatus = 'Activo'
	) AS t_cargos
	USING(num_eco)
	
	LEFT JOIN (
	SELECT num_eco, SUM(monto) AS total_cargos_fijos
	FROM cargos_fijos
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND estatus = 'Activo'
	) AS t_cargos_fijos
	USING(num_eco)
	
	LEFT JOIN (
	SELECT num_eco, SUM(importe) AS total_gastos
	FROM gastos_corrida 
	LEFT JOIN boletos
	USING(id_boletos)
	WHERE num_eco = '{$_GET["num_eco"]}'
	
	AND estatus_gastos = 'Activo'
	) AS t_gastos
	USING(num_eco)
	
	LEFT JOIN (
	SELECT num_eco, SUM(monto) AS total_traspasos
	FROM traspasos_utilidad
	LEFT JOIN traspasos_utilidad_unidades 
	USING(id_traspaso)
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND estatus_traspaso = 'Activo'
	) AS t_traspasos
	USING(num_eco)
	
	LEFT JOIN (
	SELECT num_eco, SUM(monto) AS total_abonos
	FROM recibos_entradas
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND estatus_deposito = 'Activo'
	) AS t_abonos
	USING(num_eco)
	
	
	
	
	";
  
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			$respuesta["num_rows"] = 0;
		}
		
		while($fila = mysqli_fetch_assoc($result)){
			//console_log($fila);
			$filas = $fila ;
			
		}
		$respuesta["filas"] = $filas;
		
	}
	else {
		
		$respuesta["consulta"] = "$consulta";
		$respuesta["estatus"] = "error";
		$respuesta["mensaje"] = "Error en ".$consulta.mysqli_Error($link);
	}
	
	echo json_encode($respuesta);
	
?>	