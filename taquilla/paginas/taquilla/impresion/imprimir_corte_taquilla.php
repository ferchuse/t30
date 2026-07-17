<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = "";
		
	
	$consulta = "
	SELECT
	*, 
	COALESCE(importe_abonos, 0) AS abonos
	FROM usuarios
	LEFT JOIN 
	(
	SELECT
	id_usuarios,
	SUM(efectivo) AS total_efectivo,
	SUM(tarjeta) AS total_tarjeta,
	SUM(transferencia) AS total_transferencia,
	COUNT(*) AS boletos_vendidos
	FROM
	boletos
	WHERE
	estatus_boletos  = 'Activo'
	AND fecha_boletos BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}' ";
	
	if($_GET["id_usuarios"] != ""){
		
		$consulta .= " AND id_usuarios = '{$_GET["id_usuarios"]}'";
	}
	
	$consulta .= "
	GROUP BY
	id_usuarios
	) AS t_boletos
	USING(id_usuarios)
	
	
	####################### Recolecciones
	LEFT JOIN (
	SELECT 
    id_usuarios,
    SUM(CASE WHEN forma_pago = 'Efectivo' THEN anticipo ELSE 0 END) AS recol_efectivo,
    SUM(CASE WHEN forma_pago = 'Tarjeta' THEN anticipo ELSE 0 END) AS recol_tarjeta,
    SUM(CASE WHEN forma_pago = 'Transferencia' THEN anticipo ELSE 0 END) AS recol_transferencia
	FROM 
    recolecciones
	WHERE 
	DATE(fecha_captura) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}' 
	GROUP BY 
    id_usuarios
	
	) AS t_recolecciones USING (id_usuarios)
	
	
	
	#######################Abonos
	LEFT JOIN (
	SELECT
	id_usuarios,
	SUM(monto) AS importe_abonos
	FROM
	recibos_entradas
	WHERE
	estatus_deposito = 'Activo'
	AND DATE(fecha_deposito) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}' 
	GROUP BY
	id_usuarios
	) AS t_abonos USING (id_usuarios)
	
	
	#######################Gastos
	LEFT JOIN (
	SELECT
	id_usuarios,
	SUM(importe) AS importe_gastos
	FROM
	gastos_corrida
	WHERE
	estatus_gastos = 'Activo'
	AND fecha_gastos BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}' ";
	if($_GET["id_usuarios"] != ""){
		$consulta .= " AND id_usuarios = '{$_GET["id_usuarios"]}'";
	}
	$consulta .= "
	GROUP BY
	id_usuarios
	) AS t_gastos USING (id_usuarios)
	
	
	#######################Traspasos Efectivo
	LEFT JOIN (
	SELECT
	id_usuarios,
	SUM(importe_traspaso) AS traspasos_efectivo
	FROM
	traspasos_utilidad
	WHERE
	estatus_traspaso = 'Activo'
	AND forma_pago = 'Efectivo'
	AND DATE(fecha_traspaso) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}' 
	GROUP BY
	id_usuarios
	) AS tras_ef USING (id_usuarios)
	
	
	#######################Traspasos Transferencia
	LEFT JOIN (
	SELECT
	id_usuarios,
	SUM(importe_traspaso) AS traspasos_transferencia
	FROM
	traspasos_utilidad
	WHERE
	estatus_traspaso = 'Activo'
	AND forma_pago = 'Transferencia'
	AND DATE(fecha_traspaso) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}' 
	GROUP BY
	id_usuarios
	) AS t_trasp_tra USING (id_usuarios)
	
	
	####################### Recibos Salida
	LEFT JOIN (
	SELECT
	id_usuarios,
	SUM(monto_reciboSalidas) AS recibos_salida
	FROM
	recibos_salidas
	WHERE
	estatus_reciboSalidas = 'Activo'
	AND DATE(fecha_reciboSalidas) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}' 
	GROUP BY
	id_usuarios
	) AS t_recibos USING (id_usuarios)
	
	
	
	
	WHERE 
	estatus_usuarios = 'Activo'
	AND empresa_asignada = '{$_COOKIE["empresa_asignada"]}'
	";
	
	if($_GET["id_usuarios"] != ""){
		
		$consulta .= " AND id_usuarios = '{$_GET["id_usuarios"]}'";
	}
	
	$consulta .= "
	ORDER BY nombre_usuarios
	
	";
	
	
	
	$result = mysqli_query($link,$consulta) or die("Error".mysqli_error($link));
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros</div>");
			
		}
		while($row = mysqli_fetch_assoc($result)){
			$fila = $row;
		}
	}
	
	$saldo_efectivo = $fila["total_efectivo"] + $fila["importe_abonos"] - $fila["importe_gastos"] - $fila["recibos_salida"] - $fila["traspasos_efectivo"];
	
	$gran_total = $saldo_efectivo + $fila["recol_tarjeta"] +$fila["recol_transferencia"] +$fila["total_tarjeta"] + $fila["total_transferencia"] - $fila["traspasos_transferencia"];
	
	$respuesta.=   "\x1b"."@";
	
	// $respuesta.= "!";
	
	// $respuesta.=  $fila["nombre_empresas"] ." \n";
	$respuesta.=   "CORTE DE CAJA\n\n";

	$respuesta.= "Fecha Inicio:      ". date("d-m-Y H:i:s", strtotime($_GET["fecha_inicial"]))."\n";
	$respuesta.= "Fecha Fin:         ". date("d-m-Y H:i:s", strtotime($_GET["fecha_final"]))."\n";
	
	$respuesta.= "Usuario:           ". $fila["nombre_completo_usuarios"]."\n";
	$respuesta.= "Fecha Impresion:   ". date("d-m-Y H:i:s")."\n";
	$respuesta.= "Usuario Impresion: ". $_COOKIE["nombre_usuarios"]."\n\n";
	$respuesta.= "Boletos Vendidos:   ". $fila["boletos_vendidos"]."\n\n";
	$respuesta.= "VENTAS:           \n";
	$respuesta.= "  Efectivo:           $". str_pad(number_format($fila["total_efectivo"],2), 10 ," ", STR_PAD_LEFT)."\n";
	$respuesta.= "  Tarjeta:            $". str_pad(number_format($fila["total_tarjeta"],2), 10," ",STR_PAD_LEFT)."\n";
	$respuesta.= "  Transferencia:      $". str_pad(number_format($fila["total_transferencia"],2),10, " ",STR_PAD_LEFT)."\n\n";
	$respuesta.= "RECOLECCIONES:           \n";
	$respuesta.= "  Efectivo:           $". str_pad(number_format($fila["recol_efectivo"],2), 10 ," ", STR_PAD_LEFT)."\n";
	$respuesta.= "  Tarjeta:            $". str_pad(number_format($fila["recol_tarjeta"],2), 10," ",STR_PAD_LEFT)."\n";
	$respuesta.= "  Transferencia:      $". str_pad(number_format($fila["recol_transferencia"],2),10, " ",STR_PAD_LEFT)."\n\n";
	
	$respuesta.= "Abonos Caja:          $". str_pad(number_format($fila["importe_abonos"],2),10," ",STR_PAD_LEFT)."\n\n";
	$respuesta.= "Gastos:               $". str_pad(number_format($fila["importe_gastos"],2),10," ",STR_PAD_LEFT)."\n\n";
	$respuesta.= "TRASPASOS:           \n";
	$respuesta.= "  Efectivo:           $". str_pad(number_format($fila["traspasos_efectivo"],2),10," ",STR_PAD_LEFT)."\n";
	$respuesta.= "  Transferencia:      $". str_pad(number_format($fila["traspasos_transferencia"],2),10," ",STR_PAD_LEFT)."\n\n";
	$respuesta.= "Recibos Salida:       $". str_pad(number_format($fila["recibos_salida"],2),10," ",STR_PAD_LEFT)."\n\n";
	$respuesta.= "Saldo Efectivo:       $". str_pad(number_format($saldo_efectivo ,2),10, " ",STR_PAD_LEFT)."\n\n";
	$respuesta.= "Gran Total:           $". str_pad(number_format($gran_total ,2),10, " ",STR_PAD_LEFT)."\n\n";
	
	
	
	$respuesta.= "\n\n\n"; 
	
	$respuesta.= "VA"; // Cut
	
	echo base64_encode ( $respuesta );
	
	exit(0);
?>					