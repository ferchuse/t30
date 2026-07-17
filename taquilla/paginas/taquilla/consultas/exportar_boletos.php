<?php
	include("../../../lib/SimpleXLSXGen2.php");
	// include("../../../lib/normalize_chars.php");
	include('../../../conexi.php');
	use Shuchkin\SimpleXLSXGen;
	
	$xlsx = new SimpleXLSXGen();
	$link = Conectarse();
	$filas = array();
	
	$consulta_boletos = "SELECT * FROM boletos 
	
	LEFT JOIN usuarios ON boletos.id_usuarios = usuarios.id_usuarios
	LEFT JOIN unidades USING (num_eco)
	LEFT JOIN empresas USING (id_empresas)
	LEFT JOIN conductores USING (id_conductores)
	LEFT JOIN (
	SELECT id_boletos, SUM(importe) AS total_gastos
	FROM gastos_corrida
	";
	if($_GET["id_usuarios"] != ""){
		$consulta_boletos.=" WHERE gastos_corrida.id_usuarios = '{$_GET["id_usuarios"]}' ";
	}
	
	$consulta_boletos.="
	GROUP BY id_boletos
	
	)  as t_gastos USING (id_boletos)
	
	
	WHERE 
	fecha_boletos BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	";
	
	if($_GET["num_eco"] != ""){
		$consulta_boletos.=" AND num_eco = '{$_GET["num_eco"]}' ";
	}
	if($_GET["id_usuarios"] != ""){
		$consulta_boletos.=" AND boletos.id_usuarios = '{$_GET["id_usuarios"]}' ";
	}
	
	if($_GET["id_empresas"] != ""){
		$consulta_boletos.=" AND unidades.id_empresas = '{$_GET["id_empresas"]}' ";
	}
	
	if($_GET["estatus"] != ''){
		$consulta_boletos.= " AND estatus_boletos = '{$_GET["estatus"]}' ";
	}
	if($_GET["facturar"] != ''){
		$consulta_boletos.= " AND facturar = '{$_GET["facturar"]}' ";
	}
	if($_GET["forma_pago"] != ''){
		$consulta_boletos.= " AND forma_pago = '{$_GET["forma_pago"]}' ";
	}
	if($_GET["id_conductores"] != ''){
		$consulta_boletos.= " AND id_conductores = '{$_GET["id_conductores"]}' ";
	}
	if($_GET["taquilla"] != ''){
		$consulta_boletos.= " AND taquilla = '{$_GET["taquilla"]}' ";
	}
	
	$consulta_boletos.=" ORDER BY boletos.id_boletos";
	
	
	
	
	
	$result = mysqli_query($link,$consulta_boletos);
	
	if(!$result){
		die("Error en $consulta_boletos" . mysqli_error($link) );
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
	"<b >Estatus</b>",
	"<b>Folio</b>",
	"<b>Fecha de Venta</b>",
	"<b>Usuario </b>",
	"<b>Unidad</b>",
	"<b>Placas</b>",
	"<b>Operador</b>",
	"<b>Num Licencia</b>",
	"<b>Destino</b>",
	"<b>CP</b>",
	"<b>Pasajeros</b>",
	"<b>Importe Total</b>",
	"<b>Efectivo</b>",
	"<b>Tarjeta</b>",
	"<b>Transferencia</b>",
	"<b>Total Gastos</b>",
	"<b>Ingreso Neto</b>",
	
	]
	];
	
	
	
	foreach($filas as $i=> $fila){
		$anticipos = array(
		"recol_efectivo" => 0,
		"recol_tarjeta" => 0,
		"recol_transferencia" => 0
		) ;
		
		$consulta_anticipos ="SELECT 
		id_usuarios,
		SUM(CASE WHEN forma_pago = 'Efectivo' THEN anticipo ELSE 0 END) AS recol_efectivo,
		SUM(CASE WHEN forma_pago = 'Tarjeta' THEN anticipo ELSE 0 END) AS recol_tarjeta,
		SUM(CASE WHEN forma_pago = 'Transferencia' THEN anticipo ELSE 0 END) AS recol_transferencia
		FROM 
		recolecciones
		WHERE 
		id_boletos = '{$fila["id_boletos"]}' ";
		
		$result_anticipos = mysqli_query($link,$consulta_anticipos) or die(mysqli_error($link));
		
		while($row = mysqli_fetch_assoc($result_anticipos)){
			$anticipos = $row ;
		}
		
		// $totales["importe_bruto"]+= $fila["importe"];
		// $totales["comision"]+= $comision_redondeada;
		// $totales["importe_neto"]+= $importe_neto;
		
		
		$export[] = [
		$fila["estatus_boletos"], 
		$fila["id_boletos"], 
		$fila["fecha_boletos"], 
		$fila["nombre_usuarios"], 
		$fila["num_eco"], 
		$fila["placas"], 
		$fila["nombre_conductores"], 
		$fila["noLicencia_conductores"], 
		$fila["destino"], 
		$fila["cp_destino"], 
		$fila["pasajeros"], 
		"$".number_format($fila["total"],2),
		"$".number_format($fila["efectivo"] + $anticipos["recol_efectivo"],2),
		"$".number_format($fila["tarjeta"] + $anticipos["recol_tarjeta"],2),
		"$".number_format($fila["transferencia"] + $anticipos["recol_transferencia"],2),
		"$".number_format($fila["total_gastos"],2),
		"$".number_format($fila["total"]- $fila["total_gastos"],2),
		
		//date("d/m/Y h:i A", strtotime($fila["fecha_captura"])),
		
		
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
	
	if(isset($_GET["semana"])){
		$consulta.= " AND WEEK(fecha) = '{$_GET["semana"]}' ";
		$xlsx->downloadAs("Corte Semana  {$_GET["semana"]} ".date("Y").'.xlsx');
		
		}else{
		$xlsx->downloadAs("Boletos Vendidos del {$_GET["fecha_inicial"]} al {$_GET["fecha_final"]}".'.xlsx');
		
	}
	
	
	// $xlsx->saveAs('filas.xlsx');
	// $xlsx->download();
	
	// SimpleXLSXGen::download() or SimpleXSLSXGen::downloadAs('table.xlsx');
	// SimpleXSLSXGen::downloadAs('books.xlsx');
?>