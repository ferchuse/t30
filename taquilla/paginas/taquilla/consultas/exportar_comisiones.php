<?php
	include("../../../lib/SimpleXLSXGen2.php");
	// include("../../../lib/normalize_chars.php");
	include('../../../conexi.php');
	use Shuchkin\SimpleXLSXGen;
	
	$xlsx = new SimpleXLSXGen();
	$link = Conectarse();
	$filas = array();
	
	
		
	$consulta_conductores = "SELECT 
    conductores.*,
    t_viajes.*,
	
    IFNULL(t_gastos_operador.suma_combustible_operador, 0) AS suma_combustible_operador,
    (
	IFNULL(t_viajes.suma_gasolina, 0) +
	IFNULL(t_gastos_operador.suma_combustible_operador, 0)
    ) AS total_combustible,
	
    IFNULL(t_gastos_operador.suma_casetas_operador, 0) AS suma_casetas_operador,
    (
	IFNULL(t_viajes.suma_casetas, 0) +
	IFNULL(t_gastos_operador.suma_casetas_operador, 0)
    ) AS total_casetas
	
	FROM conductores
	
	LEFT JOIN
	(
    SELECT
	b.id_conductores,
	GROUP_CONCAT(DISTINCT b.num_eco ORDER BY b.num_eco SEPARATOR ', ') AS num_ecos,
	COUNT(*) AS viajes,
	
	SUM(b.efectivo + IFNULL(r.recol_efectivo,0)) AS suma_efectivo,
	SUM(b.tarjeta + IFNULL(r.recol_tarjeta,0)) AS suma_tarjeta,
	SUM(b.transferencia + IFNULL(r.recol_transferencia,0)) AS suma_transferencia,
	
	SUM(b.total) AS monto_viajes,
	
	SUM(IFNULL(g.suma_gastos,0)) AS total_gastos,
	SUM(IFNULL(g.suma_gasolina,0)) AS suma_gasolina,
	SUM(IFNULL(g.suma_casetas,0)) AS suma_casetas
	
    FROM boletos b
	
    LEFT JOIN
    (
	SELECT
	id_boletos,
	SUM(CASE WHEN id_cat_gastos = 7 THEN importe ELSE 0 END) AS suma_gasolina,
	SUM(CASE WHEN id_cat_gastos = 17 THEN importe ELSE 0 END) AS suma_casetas,
	SUM(importe) AS suma_gastos
	FROM gastos_corrida
	WHERE estatus_gastos = 'Activo'
	GROUP BY id_boletos
    ) g
    USING(id_boletos)
	
    LEFT JOIN
    (
	SELECT
	id_boletos,
	SUM(CASE WHEN forma_pago='Efectivo' THEN anticipo ELSE 0 END) AS recol_efectivo,
	SUM(CASE WHEN forma_pago='Tarjeta' THEN anticipo ELSE 0 END) AS recol_tarjeta,
	SUM(CASE WHEN forma_pago='Transferencia' THEN anticipo ELSE 0 END) AS recol_transferencia
	FROM recolecciones
	GROUP BY id_boletos
    ) r
    USING(id_boletos)
	
    WHERE
	b.fecha_boletos BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	AND b.estatus_boletos = 'Activo'
	
    GROUP BY b.id_conductores
	
	) AS t_viajes
	USING(id_conductores)
	
	LEFT JOIN
	(
    SELECT
	id_conductores,
	SUM(CASE WHEN id_cat_gastos = 7 THEN monto_gasto ELSE 0 END) AS suma_combustible_operador,
	SUM(CASE WHEN id_cat_gastos = 17 THEN monto_gasto ELSE 0 END) AS suma_casetas_operador
    FROM gastos_operador
    WHERE
	fecha_gasto BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
    GROUP BY id_conductores
	) AS t_gastos_operador
	USING(id_conductores)
	";
	
	
	$consulta_conductores.=" 
	
	WHERE estatus_conductores = 'Activo'
	
	GROUP BY conductores.id_conductores
	
	ORDER BY num_ecos ASC";
	
	$result_conductores = mysqli_query($link,$consulta_conductores) or die("Error en $consulta_conductores ". mysqli_error($link));
	
	while($row = mysqli_fetch_assoc($result_conductores)){
		$filas[] = $row;
	}
	
	
	$result = mysqli_query($link,$consulta_conductores);
	
	if(!$result){
		die("Error en $consulta_conductores" . mysqli_error($link) );
	}
	// else{
	// $num_rows = mysqli_num_rows($result);
	// if($num_rows != 0){
	// while($row = mysqli_fetch_assoc($result)){
	// $filas[] = $row;        
	// }
	// }
	// }
	
	
	
	$export = [
	[
	"<b>Num Eco</b>",
	"<b>Operador</b>",
	"<b>Num Viajes</b>",
	"<b>Efectivo</b>",
	"<b>Tarjeta</b>",
	"<b>Transferencia</b>",
	"<b>Importe Bruto</b>",
	"<b>Comisión Tarjeta</b>",
	"<b>Gasolina</b>",
	"<b>Casetas</b>",
	"<b>Total Gastos</b>",
	"<b>Bruto - Gastos</b>",
	"<b>Comisión</b>",
	]
	];
	
	foreach($filas AS $i => $fila){ 
		
		
		$comision_tarjeta  = $fila["suma_tarjeta"] * .039;
		$total_gastos =  $fila["total_combustible"] + $fila["total_casetas"] +$comision_tarjeta;
		$total =  $fila["monto_viajes"] - $total_gastos;
		
		if($total > $_GET["limite_incentivo"]){
			$comision = $total * $_GET["porc_incentivo"] / 100; 
		}
		else{
			$comision = $_GET["comision"]; 
		}
		
		$totales[0]+=  $fila["viajes"];
		$totales[1]+=  $fila["suma_efectivo"];
		$totales[2]+=  $fila["suma_tarjeta"];
		$totales[3]+=  $fila["suma_transferencia"];
		$totales[4]+=  $fila["monto_viajes"];
		$totales[5]+=  $comision_tarjeta;
		$totales[6]+=  $fila["total_combustible"];
		$totales[7]+=  $fila["total_casetas"];
		$totales[8]+=  $total_gastos;
		$totales[9]+=  $total;
		$totales[10]+=  $comision;
		
		
		$export[] = [
		$fila["num_ecos"], 
		$fila["nombre_conductores"], 
		$fila["viajes"], 
		"$".($fila["suma_efectivo"]),
		"$".($fila["suma_tarjeta"]),
		"$".($fila["suma_transferencia"]),
		"$".($fila["monto_viajes"]),
		"$".($comision_tarjeta),
		"$".($fila["total_combustible"]),
		"$".($fila["total_casetas"]),
		"$".($total_gastos),
		"$".($total),
		"$".($comision),
		];
		
	}
	
	$export[] = [
	"",
	"",
	
	"<b>".$totales[0]."</b>",
	"<b>$".$totales[1]."</b>",
	"<b>$".$totales[2]."</b>",
	"<b>$".$totales[3]."</b>",
	"<b>$".$totales[4]."</b>",
	"<b>$".$totales[5]."</b>",
	"<b>$".$totales[6]."</b>",
	"<b>$".$totales[7]."</b>",
	"<b>$".$totales[8]."</b>",
	"<b>$".$totales[9]."</b>",
	"<b>$".$totales[10]."</b>",
	
	
	];
	
	$xlsx = SimpleXLSXGen::fromArray( $export );
	
	$xlsx->downloadAs("Reporte Comisiones de Operadores  del ". date("d-m-Y H:i:s", strtotime($_GET["fecha_inicial"]))." al ".  date("d-m-Y H:i:s", strtotime($_GET["fecha_final"])).'.xlsx');
	
	
	
	// $xlsx->saveAs('filas.xlsx');
	// $xlsx->download();
	
	// SimpleXLSXGen::download() or SimpleXSLSXGen::downloadAs('table.xlsx');
	// SimpleXSLSXGen::downloadAs('books.xlsx');
?>