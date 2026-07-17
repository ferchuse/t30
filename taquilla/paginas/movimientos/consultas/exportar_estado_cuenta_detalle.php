<?php
	include("../../../lib/SimpleXLSXGen2.php");
	// include("../../../lib/normalize_chars.php");
	include('../../../conexi.php');
	use Shuchkin\SimpleXLSXGen;
	
	$xlsx = new SimpleXLSXGen();
	$link = Conectarse();
	$filas = array();
		$consulta= 
	"
	SELECT 
	1 AS orden,
	'{$_GET["fecha_inicial"]}' AS fecha,
	'SALDO ANTERIOR' AS motivo,
	0 AS cargo,
	COALESCE(total_boletos,0) +
	COALESCE(total_abonos,0) - 
	COALESCE(total_cargos,0) - 
	COALESCE(total_cargos_fijos,0) - 
	COALESCE(total_gastos,0) -
	COALESCE(total_traspasos,0)  -
	COALESCE(total_casetas,0)  -
	COALESCE(total_comision_tarjetas,0)  
	AS abono,
	'' AS observaciones
	
	FROM (
	SELECT num_eco, SUM(total) AS total_boletos 
	FROM boletos 
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND DATE(fecha_boletos) < '{$_GET["fecha_inicial"]}'
	AND estatus_boletos = 'Activo'
	) AS t_boletos 
	
	LEFT JOIN (
	SELECT num_eco, SUM(monto) AS total_cargos
	FROM cargos 
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND DATE(fecha_cargos) < '{$_GET["fecha_inicial"]}'
	AND estatus = 'Activo'
	) AS t_cargos
	USING(num_eco)
	
	LEFT JOIN (
	SELECT num_eco, SUM(monto) AS total_cargos_fijos
	FROM cargos_fijos
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND DATE(fecha_cargos) < '{$_GET["fecha_inicial"]}'
	AND estatus = 'Activo'
	) AS t_cargos_fijos
	USING(num_eco)
	
	LEFT JOIN (
	SELECT num_eco, SUM(importe) AS total_gastos
	FROM gastos_corrida 
	LEFT JOIN boletos
	USING(id_boletos)
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND DATE(fecha_gastos) < '{$_GET["fecha_inicial"]}'
	AND estatus_gastos = 'Activo'
	) AS t_gastos
	USING(num_eco)
	
	LEFT JOIN (
	SELECT num_eco, SUM(monto) AS total_traspasos
	FROM traspasos_utilidad
	LEFT JOIN traspasos_utilidad_unidades USING(id_traspaso)
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND DATE(fecha_aplicacion) < '{$_GET["fecha_inicial"]}'
	AND estatus_traspaso = 'Activo'
	) AS t_traspasos
	USING(num_eco)
	
	LEFT JOIN (
	SELECT num_eco, SUM(monto) AS total_abonos
	FROM recibos_entradas
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND DATE(fecha_aplicacion) < '{$_GET["fecha_inicial"]}'
	AND estatus_deposito = 'Activo'
	) AS t_abonos
	USING(num_eco)
	
	LEFT JOIN (
	SELECT num_eco, SUM(importe) AS total_casetas
	FROM casetas_tag
	LEFt JOIN unidades USING(tag)
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND DATE(fecha_viaje) < '{$_GET["fecha_inicial"]}'
	) AS t_casetas_anterior
	USING(num_eco)
	
	LEFT JOIN (
	SELECT
	num_eco,
	SUM(tarjeta) * 0.04  AS total_comision_tarjetas
	FROM boletos
	WHERE num_eco = '{$_GET["num_eco"]}'
	AND DATE(fecha_boletos) < '{$_GET["fecha_inicial"]}'
	AND DATE(fecha_boletos) > '2023-11-30'
	AND estatus_boletos = 'Activo'
	) as t_comision_tarjeta_anterior
	USING (num_eco)
	
	
	
	
	#######################SALDO NUEVO########################
	
	
	
	UNION
	
	SELECT
	2 AS orden,
	fecha_boletos AS fecha, 
	CONCAT(
	'BOLETO #',
	id_boletos
	)  AS motivo,
	0 AS cargo,
	total AS abono,
	destino AS observaciones
	FROM
	boletos
	WHERE
	num_eco = '{$_GET['num_eco']}' 
	AND estatus_boletos = 'Activo'
	AND DATE(fecha_boletos) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	
	UNION 
	
	SELECT
	3 AS orden,
	DATE(fecha_cargos) AS fecha, 
	CONCAT('Cargo por ', concepto ) AS motivo,
	monto AS cargo,
	0 AS abono,
	'' AS observaciones
	FROM
	cargos_fijos
	WHERE
	num_eco = '{$_GET['num_eco']}' 
	AND estatus = 'Activo'
	AND DATE(fecha_cargos) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	
	UNION
	
	SELECT
	3 AS orden,
	DATE(fecha_cargos) AS fecha, 
	CONCAT('Cargo por ', concepto ) AS motivo,
	monto AS cargo,
	0 AS abono,
	'' AS observaciones
	FROM
	cargos
	WHERE
	num_eco = '{$_GET['num_eco']}' 
	AND estatus = 'Activo'
	AND DATE(fecha_cargos) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	
	
	UNION
	
	SELECT 
	4 AS orden,
	DATE(fecha_gastos) AS fecha, 
	CONCAT('Gasto #', id_gastos , 
	' ', descripcion_gastos ) AS motivo, 
	importe AS cargo,
	0 AS abono,
	'' AS observaciones
	FROM
	gastos_corrida
	LEFT JOIN boletos USING(id_boletos)
	LEFT JOIN cat_gastos USING(id_cat_gastos)
	WHERE
	num_eco = '{$_GET['num_eco']}' 
	AND DATE(fecha_gastos) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	AND estatus_gastos = 'Activo'
	AND estatus_boletos= 'Activo'
	
	
	UNION
	
	SELECT
	5 AS orden,
	DATE(fecha_aplicacion) AS fecha,
	CONCAT(
	'Abono #',
	id_deposito
	) AS motivo,
	0 AS cargo,
	monto AS abono,
	'' AS observaciones
	FROM
	recibos_entradas
	WHERE
	num_eco = '{$_GET['num_eco']}' 
	AND DATE(fecha_aplicacion) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	AND estatus_deposito = 'Activo'
	
	
	UNION
	
	
	SELECT
	6 AS orden,
	DATE(fecha_aplicacion) AS fecha,
	CONCAT(
	'Traspaso #',
	id_traspaso
	) AS motivo,
	monto AS cargo,
	0 AS abono,
	observaciones
	FROM
	traspasos_utilidad
	LEFT JOIN traspasos_utilidad_unidades USING (id_traspaso)
	WHERE
	num_eco = '{$_GET['num_eco']}' 
	AND DATE(fecha_aplicacion) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	AND estatus_traspaso <> 'Cancelado'
	
	UNION
	
	SELECT
	7 AS orden,
	DATE(fecha_viaje) AS fecha,
	'Casetas TELEVIA ' AS motivo,
	SUM(importe) AS cargo,
	0 AS abono,
	'' as observaciones
	FROM
	casetas_tag
	LEFT JOIN unidades USING (tag)
	WHERE
	num_eco = '{$_GET['num_eco']}' 
	AND DATE(fecha_viaje) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	
	GROUP BY DATE(fecha_viaje)
	
	
	
	UNION
	
	SELECT
	3.1 AS orden,
	fecha_boletos AS fecha, 
	CONCAT(
	'COMISION TARJETA #',
	id_boletos
	)  AS motivo,
	tarjeta * 0.04 AS cargo,
	0 AS abono,
	'' as observaciones
	FROM
	boletos
	WHERE
	num_eco = '{$_GET['num_eco']}' 
	AND estatus_boletos = 'Activo'
	AND tarjeta > 0 
	AND DATE(fecha_boletos) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	AND DATE(fecha_boletos) > '2023-11-30'
	
	
	ORDER BY fecha,orden
	";
	
	
	
	
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
	"<b>#</b>",
	"<b>Fecha</b>",
	"<b>Concepto</b>",
	"<b>Cargo</b>",
	"<b>Abono</b>",
	"<b>Saldo</b>",
	"<b>Observaciones</b>"
	
	]
	];
	
	
	$total_cargos= 0; 
	$total_abonos= 0; 
	$total_saldo= 0; 
	$saldo = $filas[0]["saldo_anterior"];
	
	foreach($filas as $i=> $fila){
		
		$total_cargos+= $fila["cargo"];
		$total_abonos+= $fila["abono"];
		
		if($fila["cargo"] > 0){
			$saldo-= $fila["cargo"];
		}
		else{
			$saldo+= $fila["abono"];
		}
		
		$export[] = [
		$i + 1, 
		$fila["fecha"], 
		$fila["motivo"], 
		$fila["cargo"] > 0 ? "$".$fila["cargo"] : "",
		$fila["abono"] > 0 ? "$".$fila["abono"] : "",
		"$".$saldo,
		$fila["observaciones"]
		
		];
	}
	
	$export[] = [
	"",
	"",
	"",
	"<b>$".$total_cargos."</b>",
	"<b>$".$total_abonos."</b>",
	"<b>$".$saldo."</b>",
	""
	
	];
	
	// print_r("<pre>");
	// print_r($export);
	// print_r("</pre>");
	
	$xlsx = SimpleXLSXGen::fromArray( $export );
	
	
	$xlsx->downloadAs("Estado de Cuenta Num Eco {$_GET["num_eco"]} del ".date("d-m-Y" , strtotime($_GET["fecha_inicial"]))." al ". date("d-m-Y" , strtotime($_GET["fecha_final"])).".xlsx");
	
	
	// $xlsx->saveAs('filas.xlsx');
	// $xlsx->download();
	
	// SimpleXLSXGen::download() or SimpleXSLSXGen::downloadAs('table.xlsx');
	// SimpleXSLSXGen::downloadAs('books.xlsx');
?>