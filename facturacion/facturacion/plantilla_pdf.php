<?php
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	include("../../taquilla/conexi.php");
	
	$link = Conectarse();
	
	if(isset($_POST["id_facturas"])){
		
		$id_facturas = $_POST["id_facturas"];
		
		// error_reporting(0);
		
	}
	else{
		$id_facturas = $_GET["id_facturas"];
	}
	
	//busca datos de factura 
	$consulta_facturas	= "SELECT * FROM facturas
	LEFT JOIN cat_uso_cfdi USING(uso_cfdi)
	LEFT JOIN emisores USING(id_emisores)
	LEFT JOIN clientes USING(id_clientes)
	WHERE id_facturas = '$id_facturas'";
	
	$result = mysqli_query($link, $consulta_facturas);
	
	if($result && mysqli_num_rows($result)){
		$respuesta["consulta_facturas_estatus"] = "success";
		while($fila = mysqli_fetch_assoc($result)){
			$factura = $fila;
		}
		
	}
	else{
		$respuesta["consulta_facturas_estatus"] = "error";
		$respuesta["consulta_facturas_mensaje"] = mysqli_error($link);
		$respuesta["consulta_facturas_query"] = $consulta_facturas;
	}
	
	
	
	//Buscar Conceptos
	$consulta_detalle	= "SELECT * FROM facturas_detalle
	WHERE id_facturas = '$id_facturas'";
	
	$result = mysqli_query($link, $consulta_detalle);
	if($result){
		while($fila = mysqli_fetch_assoc($result)){	
			$conceptos[] = $fila;
		}
	}
	else{
		die("Error al generar Conceptos").mysqli_error($link);
	}
	
	//Buscar Conceptos
	$consulta_pagos	= "SELECT * FROM pagos
	WHERE id_facturas = '$id_facturas'";
	
	$result = mysqli_query($link, $consulta_pagos);
	if($result){
		while($fila = mysqli_fetch_assoc($result)){	
			$pagos[] = $fila;
		}
	}
	else{
		die("Error al cargar Pagos").mysqli_error($link);
	}
	
	
	
	
	
	$cat_tipo_comprobante =  ["I"=>"Ingreso", "P" => "Pago", "E"=> "Egreso"];
	
	
	
	$cat_metodo_pago =  array("PUE"=>"Pago en una sola exhibición" , "PPD"=>"Pago en parcialidades o diferido");
	$cat_forma_pago = array("01"=> "Efectivo", 
	"02"=>	"Cheque nominativo",
	"03"=>	"Transferencia electrónica de fondos",
	"04"=>	"Tarjeta de Crédito",
	"28"=>	"Tarjeta de Débito",
	"31"=>	"Intermediario de Pagos",
	"99"=>	"Por definir");
	
	$cat_regimen=  [
	"601" =>
	"General de Ley Personas Morales",
	"603"=>
	"Personas Morales con Fines no Lucrativos",
	"605"=>
	"Sueldos y Salarios e Ingresos Asimilados a Salarios",
	"606"=>
	"Arrendamiento",
	"607" =>
	"Régimen de Enajenación o Adquisición de Bienes",
	"608" =>
	"Demás ingresos",
	"609"=>
	"Consolidación", 
	"610"=>
	"Residentes en el Extranjero sin Establecimiento Permanente en México",
	"611"=>
	"Ingresos por Dividendos (socios y accionistas)",
	"612"=>
	"Personas Físicas con Actividades Empresariales y Profesionales",
	"614"=>
	"Ingresos por intereses",
	"615"=>
	"Régimen de los ingresos por obtención de premios",
	"616"=>
	"Sin obligaciones fiscales", 
	"620"=>
	"Sociedades Cooperativas de Producción que optan por diferir sus ingresos",
	"621"=>
	"Incorporación Fiscal",
	"622"=>
	"Actividades Agrícolas, Ganaderas, Silvícolas y Pesqueras",
	"623"=>
	"Opcional para Grupos de Sociedades",
	"624"=>
	"Coordinados",
	"625"=>
	"Régimen de las Actividades Empresariales con ingresos a través de Plataformas Tecnológicas",
	"626"=>
	"Régimen Simplificado de Confianza",
	"628"=>
	"Hidrocarburos",
	"629"=>
	"De los Regímenes Fiscales Preferentes y de las Empresas Multinacionales",
	"630"=>
	"Enajenación de acciones en bolsa de valores"];
	
	
	if($_POST["orden_pago"] == 1){
		
		
		$orden_pago = "hidden";
	}
	else{
		
		$orden_pago = " ";
	}
?>

<html lang="es">
	<head>
		<meta charset="utf-8">
		<title>Factura</title>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<style>
			body { 
			font-family: DejaVu Sans, sans-serif; 
			font-size: 11px;
			}
			@page { margin: 10px; }
			.header_datos{
			padding-top: 10px;
			}
			
			.small{
			font-size: 9px;
			overflow-wrap: break-word !important;
			word-wrap: break-word !important;
			max-width: 18cm;
			}
			.tiny{
			font-size: 6px;
			overflow-wrap: break-word !important;
			word-wrap: break-word !important;
			max-width: 18cm;
			}
			@media print{
			body{
			font-size: 11px;
			}
			
			}
		</style>
	</head>
	
	<body>
		<div class="container">
			
			<?php if($factura["orden_pago"] == 1){ ?>
				<center><h4>ORDEN DE PAGO</h4></center>
				
				<?php
				}	
			?>
			<div class="row">
				
				<div class="col-xs-2">
					<br>
					<br>
					<?php 
						$url_logo = URL_SISTEMA."taquilla/paginas/facturacion/consultas/logos/".rawurlencode($factura["url_logo"]);
						
						echo "<img alt='Logo' src='$url_logo' class='img-responsive'>";
					?>
					
				</div>  
				<div class="header_datos">
					<div class="col-xs-4">
						<h6>Emisor: </h6> <a href="#"><?php echo $factura["razon_social_emisores"];?></a> <br>
						<p>
							RFC: <?php echo $factura["rfc_emisores"];?>  <br>
							Régimen:  <?php echo $cat_regimen[$factura["regimen_emisores"]];?><br>
							Certificado: <?php echo $factura["representacion_impresa_certificado_no"];?><br>
						</p>
					</div>
					<div class="col-xs-4 text-right">
						Folio: <span class="text-danger"><?php echo $factura["folio_facturas"];?></span><br>
						Fecha: <?php echo date("d/m/Y", strtotime($factura["fecha_facturas"]));?><br>
						<?php if($factura["orden_pago"] == 0){ ?>
							Folio SAT: <?php echo $factura["uuid"] ;?> <br>
							Fecha Certificación: <?php echo $factura["representacion_impresa_fecha_timbrado"];?> <br>
							Certificado SAT: <?php echo $factura["representacion_impresa_certificadoSAT"];?> <br>
							<?php 
							}
						?>
					</div>
				</div>
			</div>
			<div class="row">
				
				<div class="col-xs-5  ">
					<div class="panel panel-default">
						<div class="panel-heading">
							Receptor:
						</div>
						<div class="panel-body">
							<p>
								<b>Nombre: </b> <?php echo $factura["razon_social_clientes"];?> <br>
								<b> RFC: </b> <?php echo $factura["rfc_clientes"];?> <br>
								
								<b> Regimen: </b> <?php echo $factura["regimen_clientes"]."-". $cat_regimen[$factura["regimen_clientes"]];?>
								<br>
								<b>USO CFDI: </b><?php echo $factura["uso_cfdi"]."-".$factura["descripcion_uso_cfdi"];?> <br>
								<b>CP: </b><?php echo $factura["cp_clientes"];?> <br>
							</p>
						</div>
					</div>
				</div>
				<div class="col-xs-5 ">
					<div class="panel panel-default">
						<div class="panel-heading">
							Datos de Pago:
						</div>
						<div class="panel-body">
							
							Tipo de Comprobante: <?php echo $factura["tipo_comprobante"]."-".$cat_tipo_comprobante[$factura["tipo_comprobante"]];?> <br>
							Lugar de Expedición: <?php echo $factura["lugar_expedicion"];?><br>
							Forma de Pago: <?php echo $factura["forma_pago"]."-".$cat_forma_pago[$factura["forma_pago"]];?><br>
							Método de Pago:<?php echo $factura["metodo_pago"]."-".$cat_metodo_pago[$factura["metodo_pago"]];?>
						</div>
					</div>
				</div>
			</div>
			<!-- / Datos factura y receptor -->
			
			
			<div class="row">
				<div class="col-xs-11">
					<table border="1" class="table table-border table-condensed">
						<tr class="text-center">
							<th>
								Cantidad
							</th>
							<th>
								Unidad 
							</th>
							<th>
								Descripción
							</th>
							<th>
								Precio
							</th>
							<th>
								Importe
							</th>
						</tr>
						<?php 
							foreach($conceptos as $index => $concepto){?>
							<tr >
								<td class="col-xs-1 text-center">
									<?php echo $concepto["cantidad"];?>
								</td>
								<td class="col-xs-1 text-center">
									<?php echo $concepto["clave_unidad"];?>
								</td>
								<td class="col-xs-4">
									<?php echo nl2br($concepto["descripcion"]);?>
								</td>
								<td class="col-xs-1  ">$<?php echo number_format($concepto["precio"],2);?></td>
								<td class="col-xs-1">$<?php echo $concepto["importe"];?></td>
							</tr>
							
							<?php 
								
							}
						?>
						
						<tfoot>
							
						</tfoot>
					</table>
				</div>
			</div>
			<?php 
				
				// IF(count($conceptos) == 7){
				
				// echo "<div style='page-break-after: always;'></div>";
				// }
			?>
			<div class="row ">
				
				<div class="col-xs-4 ">
					Observaciones: 
					<?php 
						
						echo $factura["observaciones"];
						// echo $factura["qr_code"];
						
					?> <br>
					
					
					
				</div>
				<div class="col-xs-2  text-right">
					<p>
						<strong>
							Subtotal : <br>
							Descuento :  <br>
							Traslados IVA: <br>
							Retención IVA : <br>
							Retención ISR : <br>
							Total : <br>
						</strong>
					</p>
				</div>
				<div class="col-xs-2 text-right">
					<strong>
						$ <?php echo number_format($factura["subtotal"],2);?> <br>
						$ <?php echo number_format($factura["descuento"],2);?> <br>
						$ <?php echo number_format($factura["total_traslados"],2);?> <br>
						$ <?php echo number_format($factura["retenciones_iva"],2);?> <br>
						$ <?php echo number_format($factura["retenciones_isr"],2);?> <br>
						$ <?php echo number_format($factura["total"],2);?> <br>
						<br>
					</strong>
				</div>
			</div>
			<?php if(count($pagos) > 0){ ?>
				<table class="table table-bordered table-condensed">
					<caption>Complemento de Pago</caption>
					<thead>
						<tr>
							<th>
								Factura Relacionada
							</th>
							<th>
								Fecha
							</th>
							<th>
								Forma de Pago
							</th>
							<th>
								Parcialidad
							</th>
							<th>
								Saldo Anterior
							</th>
							<th>
								Importe Pagado
							</th>
							<th>
								Saldo Restante
							</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach($pagos as $i => $pago){ ?>
							<tr>
								<td><?php echo $pago["uuid_dr"]?></td>
								<td><?php echo $pago["fecha_pago"]?></td>
								<td><?php echo $cat_forma_pago[$pago["forma_pago"]]?></td>
								<td><?php echo $pago["num_parcialidad"]?></td>
								<td><?php echo $pago["saldo_anterior"]?></td>
								<td><?php echo $pago["importe_pagado"]?></td>
								<td><?php echo $pago["saldo_restante"]?></td>
							</tr>
							
							<?php 
								
							}
						?>
					</tbody>
				</table>
				
				
				<?php
				}
			?>
			
			
		 <footer <?php // if(count($conceptos)  < 7 ) echo " class='footer'";?> >
				
				<div class="row">
					<div class="col-xs-2">
						<?php echo "<img src='data:image/png;base64,{$factura["qr_code"]}'>"; ?> 
					</div>
					<div class="col-xs-8">
						Sello Digital CFDI: <div class="tiny" ><?php echo $_POST["representacion_impresa_sello"];?></div>
						Sello SAT : <div class="tiny" > <?php echo $_POST["representacion_impresa_selloSAT"];?></div>
						Cadena original del complemento de certificación digital del SAT:
						<div class="tiny" > 
							<?php echo $_POST["representacion_impresa_cadena"];?> 
						</div>
					</div>
				</div>
				<h6 class="text-center tiny">Este documento es una representación impresa de un CFDI</h6>
				<pre hidden>
					<?php //echo var_dump($_POST);?>
				</pre>
			</footer>
			
		</div>
	</body>
</html>						