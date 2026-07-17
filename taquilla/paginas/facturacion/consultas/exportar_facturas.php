<?php
	include("../../../lib/SimpleXLSXGen2.php");
	include("../../../lib/normalize_chars.php");
	include('../../../conexi.php');
	use Shuchkin\SimpleXLSXGen;
	
	$xlsx = new SimpleXLSXGen();
	$link = Conectarse();
	$filas = array();
	$totales = array("subtotal" => 0, "traslados" => 0,"total" => 0,"saldo" => 0);
	
	
	$consulta ="SELECT * FROM facturas 
	LEFT JOIN usuarios USING(id_usuarios) 
	LEFT JOIN emisores USING(id_emisores) 
	LEFT JOIN clientes USING(id_clientes)
	
	";
	
	
	if(isset($_GET['year_facturas'])){
		
		$consulta.=" WHERE YEAR(fecha_facturas) = '".$_GET['year_facturas']."' ";
		if($_GET['mes_facturas'] != ""){
			$consulta.=" AND MONTH(fecha_facturas) = '".$_GET['mes_facturas']."' ";
			
		}
		}elseif(isset($_GET['mes_facturas'])){
		if($_GET['mes_facturas'] != ""){
			$consulta.=" WHERE  MONTH(fecha_facturas) = '".$_GET['mes_facturas']."' ";
		}
		
	}
	
	if($_GET['metodo_pago']){
		$consulta.=" AND  metodo_pago = '{$_GET['metodo_pago']}' ";
	}
	
	if($_GET['tipo_comprobante']){
		$consulta.=" AND  tipo_comprobante = '{$_GET['tipo_comprobante']}' ";
	}
	

	$consulta.=" ORDER BY  folio_facturas ";
	
	
	
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
	
	"<b>Folio</b>",
	"<b>Folio SAT</b>",
	"<b>Fecha</b>",
	"<b>Razon Social</b>",
	"<b>Metodo de Pago</b>",
	"<b>Subtotal</b>",
	"<b>IVA</b>",
	"<b>Total</b>",
	"<b>Saldo</b>",
	"<b>Estatus SAT</b>",
	"<b>Usuario</b>",
	
	]
	];
	
	foreach($filas as $i=> $fila){
		$totales["subtotal"]+= $fila["subtotal"];
		$totales["traslados"]+= $fila["total_traslados"];
		$totales["total"]+= $fila["total"];
		$totales["saldo"]+= $fila["saldo_actual"];
		
		if( $fila["timbrado"] == 1){
			$estatus = "ACTIVA";
			
			if( $fila["cancelada"] == 1){
				$estatus = "CANCELADA";
			}
		}
		else{
			$estatus = "BORRADOR";
		}
		
		$export[] = [
		
		$fila["folio_facturas"], 
		$fila["uuid"], 
		date("d/m/Y", strtotime($fila["fecha_facturas"])),
		$fila["razon_social_clientes"], 
		$fila["metodo_pago"], 
		"$".$fila["subtotal"],
		"$".$fila["total_traslados"],
		"$".$fila["total"],
		"$".$fila["saldo_actual"],
		$estatus,
		$fila["nombre_usuarios"]
		
		
		];
	}
	
	$export[] = [
	"",
	"",
	"",
	"",
	"",
	"<b>$".$totales["subtotal"]."</b>",
	"<b>$".$totales["traslados"]."</b>",
	"<b>$" .$totales["total"]."</b>",
	"<b>$" .$totales["saldo"]."</b>",
	"",
	"",
	""
	
	];
	
	// print_r("<pre>");
	// print_r($export);
	// print_r("</pre>");
	
	$xlsx = SimpleXLSXGen::fromArray( $export );
	
	
	
	$xlsx->downloadAs("Reporte Facturas {$_GET["mes_facturas"]} ".date("Y").'.xlsx');
	
	
	
	
	// $xlsx->saveAs('filas.xlsx');
	// $xlsx->download();
	
	// SimpleXLSXGen::download() or SimpleXSLSXGen::downloadAs('table.xlsx');
	// SimpleXSLSXGen::downloadAs('books.xlsx');
?>