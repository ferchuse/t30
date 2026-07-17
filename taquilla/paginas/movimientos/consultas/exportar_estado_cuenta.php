<?php
	include("../../../lib/SimpleXLSXGen2.php");
	// include("../../../lib/normalize_chars.php");
	include('../../../conexi.php');
	use Shuchkin\SimpleXLSXGen;
	
	$xlsx = new SimpleXLSXGen();
	$link = Conectarse();
	$filas = array();
	
		$consulta = "
	SELECT * FROM unidades
	
	
	#######################SALDO ANTERIOR ################
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(total)  AS  importe_boletos_anterior
	FROM boletos
	WHERE 
	DATE(fecha_boletos) < '{$_GET["fecha_inicial"]}'
	AND estatus_boletos = 'Activo'
	GROUP BY num_eco
	) as t_boletos_anterior
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(importe)  AS importe_gastos_operativos_anterior
	FROM gastos_corrida
	LEFT JOIN boletos
	USING(id_boletos)
	WHERE 
	DATE(fecha_gastos) < '{$_GET["fecha_inicial"]}'
	AND estatus_gastos= 'Activo'
	GROUP BY num_eco
	) as t_gastos_operativos_anterior
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(monto)  AS abono_caja_anterior
	FROM recibos_entradas
	WHERE 
	DATE(fecha_aplicacion)  < '{$_GET["fecha_inicial"]}'
	AND estatus_deposito = 'Activo'
	GROUP BY num_eco
	) as t_depositos_anterior
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(monto)  AS traspasos_anterior
	FROM traspasos_utilidad
	LEFT JOIN traspasos_utilidad_unidades USING (id_traspaso)
	WHERE 
	DATE(fecha_aplicacion)  < '{$_GET["fecha_inicial"]}'
	AND estatus_traspaso = 'Activo'
	GROUP BY num_eco
	) as t_traspaso_anterior
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(monto)  AS cargos_anterior
	FROM cargos
	WHERE 
	DATE(fecha_cargos) <  '{$_GET["fecha_inicial"]}'
	AND estatus = 'Activo'	
	GROUP BY num_eco
	) as t_cargos_anterior
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(monto)  AS cargos_fijos_anterior
	FROM cargos_fijos
	WHERE 
	DATE(fecha_cargos) <  '{$_GET["fecha_inicial"]}'
	AND estatus = 'Activo'	
	GROUP BY num_eco
	) as t_cargos_fijos_anterior
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(importe)  AS casetas_anterior
	FROM casetas_tag
	LEFT JOIN unidades USING (tag)
	WHERE 
	DATE(fecha_viaje) <  '{$_GET["fecha_inicial"]}'
	GROUP BY num_eco
	) as t_casetas_anterior
	
	USING (num_eco)
	
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(tarjeta) * 0.04  AS comision_tarjeta_anterior
	FROM boletos
	WHERE 
	DATE(fecha_boletos) < '{$_GET["fecha_inicial"]}'
	AND DATE(fecha_boletos) > '2023-11-30'
	AND estatus_boletos = 'Activo'
	GROUP BY num_eco
	) as t_comision_tarjeta_anterior
	USING (num_eco)
	
	
	
	
	#######################SALDO NUEVO########################
	
	
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(total)  AS importe_boletos 
	FROM boletos
	WHERE 
	DATE(fecha_boletos) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	AND estatus_boletos = 'Activo'
	GROUP BY num_eco
	) as t_boletos
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(importe)  AS importe_gastos_operativos
	FROM gastos_corrida
	LEFT JOIN boletos
	USING(id_boletos)
	WHERE 
	DATE(fecha_gastos) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	AND estatus_gastos= 'Activo'
	GROUP BY num_eco
	) as t_gastos_operativos
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(monto)  AS abono_caja 
	FROM recibos_entradas
	WHERE 
	DATE(fecha_aplicacion)  BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	AND estatus_deposito = 'Activo'
	GROUP BY num_eco
	) as t_depositos
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(monto)  AS traspasos
	FROM traspasos_utilidad
	LEFT JOIN traspasos_utilidad_unidades USING (id_traspaso)
	WHERE 
	DATE(fecha_aplicacion)  BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	AND estatus_traspaso = 'Activo'
	GROUP BY num_eco
	) as t_traspaso
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(monto)  AS cargos 
	FROM cargos
	WHERE 
	DATE(fecha_cargos) BETWEEN  '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	AND estatus = 'Activo'	
	GROUP BY num_eco
	) as t_cargos
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(monto)  AS cargos_fijos
	FROM cargos_fijos
	WHERE 
	DATE(fecha_cargos) BETWEEN  '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	AND estatus = 'Activo'	
	GROUP BY num_eco
	) as t_cargos_fijos
	
	USING (num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(importe)  AS casetas
	FROM casetas_tag
	LEFT JOIN unidades USING (tag)
	WHERE 
	DATE(fecha_viaje) BETWEEN  '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	GROUP BY num_eco
	) as t_casetas
	
	USING (num_eco)
	
	
	
	LEFT JOIN (
	SELECT
	num_eco,
	
	CASE
	WHEN DATE('{$_GET["fecha_inicial"]}') > '2023-11-30' THEN SUM(tarjeta) * 0.04
	ELSE 0
    END AS comision_tarjeta
	
	
	FROM boletos
	WHERE 
	DATE(fecha_boletos) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	AND estatus_boletos = 'Activo'
	GROUP BY num_eco
	) as t_comision_tarjeta
	USING (num_eco)
	
	
	WHERE unidades.id_empresas = {$_COOKIE["empresa_asignada"]}
	"; 
	
	
	if($_GET["num_eco"] != ''){
		
		$consulta.=  " AND  num_eco = '{$_GET["num_eco"]}'"; 
	}
	
	$consulta.=  " ORDER BY num_eco"; 
	
	$result = mysqli_query($link,$consulta);
	
	if(!$result){
		die("Error en $consulta" . mysqli_error($link) );
	}
	else{
		$num_rows = mysqli_num_rows($result);
		if($num_rows != 0){
			while($row = mysqli_fetch_assoc($result)){
				$filas[] = $row;        
			}
		}
	}
	
	
	
	$export= [
	[
	"<b>Num Eco</b>",
	"<b>Saldo Anterior</b>",
	"<b>Efectivo</b>",
	"<b>Tarjeta</b>",
	"<b>Transferencia</b>",
	"<b>Boletos</b>",
	"<b>Gastos</b>",
	"<b>Abonos de Caja</b>",
	"<b>Traspaso de Utilidad</b>",
	"<b>Cargos</b>",
	"<b>Casetas</b>",
	"<b>Saldo</b>"
	
	]
	];
	
	
	
	foreach($filas as $i=> $fila){
		$saldo_anterior = $fila["importe_boletos_anterior"] + $fila["abono_caja_anterior"] - $fila["importe_gastos_operativos_anterior"] - $fila["traspasos_anterior"] - $fila["cargos_anterior"] - $fila["cargos_fijos_anterior"] - $fila["casetas_anterior"];
		
		
		$saldo_restante= $saldo_anterior + $fila["importe_boletos"] +  $fila["abono_caja"] - $fila["importe_gastos_operativos"] -  $fila["traspasos"] - $fila["cargos"] - $fila["cargos_fijos"]- $fila["casetas"];
		
		$export[] = [
		$fila["num_eco"],
		"$".$saldo_anterior,
		"$".$fila["efectivo"],
		"$".$fila["tarjeta"],
		"$".$fila["transferencia"],
		"$".$fila["importe_boletos"],
		"$".$fila["importe_gastos_operativos"],
		"$".$fila["abono_caja"],
		"$".$fila["traspasos"],
		"$".$fila["cargos"] + $fila["cargos_fijos"],
		"$".$fila["casetas"],
		"$".$saldo_restante
		
		];
	}
	
	// $export[] = [
	// "",
	// "",
	// "",
	// "",
	// "",
	// "",
	// "<b>$".$totales["importe_bruto"]."</b>",
	// "<b>$".$totales["comision"]."</b>",
	// "<b>$" .$totales["importe_neto"]."</b>",
	// "",
	// "",
	// ""
	
	// ];
	
	// print_r("<pre>");
	// print_r($export);
	// print_r("</pre>");
	
	$xlsx = SimpleXLSXGen::fromArray( $export );
	
	
	$xlsx->downloadAs("Estado de Cuenta del ".date("d-m-Y" , strtotime($_GET["fecha_inicial"]))." al ". date("d-m-Y" , strtotime($_GET["fecha_final"])).".xlsx");
	
	
	// $xlsx->saveAs('filas.xlsx');
	// $xlsx->download();
	
	// SimpleXLSXGen::download() or SimpleXSLSXGen::downloadAs('table.xlsx');
	// SimpleXSLSXGen::downloadAs('books.xlsx');
?>