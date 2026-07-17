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
	
	
	
	$encabezados = [

	"<b>NO. VIAJE</b>",
	"<b>FECHA</b>",
	"<b>HORA DE LLEGADA AIFA</b>",
	"<b>HORARIO DE SALIDA A DESTINO</b>",
	"<b>DESTINO</b>",
	"<b>CP</b>",
	"<b>COSTO</b>",
	"<b>NO. PASAJEROS</b>",
	"<b>PLACA DE LA UNIDAD</b>",
	"<b>NOMBRE DEL OPERADOR</b>",
	"<b>NÚMERO DE LICENCIA</b>",
];
	
	$export[] = $encabezados;
	
	foreach($filas as $i=> $fila){
		
		
		// $totales["importe_bruto"]+= $fila["importe"];
		// $totales["comision"]+= $comision_redondeada;
		// $totales["importe_neto"]+= $importe_neto;
		
		
		$export[] = [
		$fila["id_boletos"], 
		$fila["fecha_boletos"], 
		$fila["hora_llegada"], 
		$fila["hora_salida"], 
		$fila["destino"], 
		$fila["cp_destino"], 
		"$".$fila["total"],
		$fila["pasajeros"], 
		$fila["placas"], 
		$fila["nombre_conductores"], 
		$fila["noLicencia_conductores"], 
	
		
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
		$xlsx->downloadAs("Reporte AIFA del {$_GET["fecha_inicial"]} al {$_GET["fecha_final"]}".'.xlsx');
		
	}
	
	
	// $xlsx->saveAs('filas.xlsx');
	// $xlsx->download();
	
	// SimpleXLSXGen::download() or SimpleXSLSXGen::downloadAs('table.xlsx');
	// SimpleXSLSXGen::downloadAs('books.xlsx');
?>