<?php
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header("Content-Type: application/json");
	
	include_once("../../taquilla/conexi.php");
	
	
	$link = Conectarse();
	
	setlocale(LC_ALL,"en_US"); 
	
	$respuesta = array();
	$respuesta["locale"] = localeconv();
	$cfdi = array();
	
	$consulta_emisores = "SELECT * FROM emisores WHERE id_emisores = '{$_POST["id_emisores"]}'";
	
	$result_emisores = mysqli_query($link, $consulta_emisores);
	if($result_emisores){
		while($row = mysqli_fetch_assoc($result_emisores)){
			$emisor = $row;
		}
		$respuesta["emisor"] = $emisor;
	}
	else{
		$respuesta["emisores_estatus"] = "error";
		$respuesta["emisores_mensaje"] =  mysqli_error($link);
	}
	
	
	
	$rfc = $emisor["rfc_emisores"];
	
	
	$serie = $emisor["serie"];
	$folio = $emisor["folio"];
	$folio_facturas = $serie.$folio ;
	if($folio_facturas == ''){
		
		$folio_facturas = date("dmY_Hi");
	}
	
	$id_emisores = $emisor["id_emisores"];
	
	
	$lugar_expedicion = $emisor["lugar_expedicion_emisores"];
	$metodo_pago = $_POST["metodo_pago"];
	
	$forma_pago = $_POST["forma_pago"];
	$tipo_comprobante =$_POST["tipo_comprobante"];
	
	$subtotal =  $_POST["subtotal"];
	$descuento_total =  $_POST["descuento_total"];
	$total = $_POST["total_pagos"];
	
	$saldo_actual = $metodo_pago == "PPD" ? $total : 0;
	
	$observaciones = $_POST["observaciones"];
	$conceptos = array();
	
	
	
	date_default_timezone_set('America/Mexico_City');
	
	
	
	$prefix = isset($_POST["modo_pruebas"]) ? "pruebas" : "";
	$path = "timbrados/$prefix_".$rfc_emisores."_".$folio_facturas;
	$ruta_xml = $path.'.xml';
	$respuesta["ruta_xml"] = $ruta_xml;
	
	
	
	
	$cfdi['Version'] = '4.0';
	
	// $cfdi['xml_debug']='timbrados/sin_timbrar'.$rfc."_".$folio_facturas.'.xml';
	
	
	$produccion = isset($_POST["modo_pruebas"])? "NO" : "SI";
	$timbrada = isset($_POST["modo_pruebas"])? 0 : 1;
	
	
	$id_clientes = $_POST["id_clientes"];
	
	
	
	// Datos de la Factura
	$cfdi['Fecha'] = date('Y-m-d\TH:i:s', time() - 3700);
	$cfdi['Serie'] = $emisor["serie"];
	$cfdi['folio'] = $emisor["folio"];
	$cfdi['Exportacion'] = "01"; //01 No aplica
	
	$cfdi['FormaPago'] = $forma_pago;
	$cfdi['LugarExpedicion'] =  $emisor["lugar_expedicion_emisores"]; 
	$cfdi['TipoDeComprobante'] = $tipo_comprobante;
	$cfdi['MetodoPago'] = $metodo_pago;
	$cfdi['Subtotal'] = $subtotal;
	$cfdi['Moneda'] = 'MXN';
	$cfdi['Total'] = $total;
	
	// $cfdi['descuento'] = $descuento_total; 
	
	
	
	// Datos del Emisor
	$cfdi['Emisor']['Rfc'] = $emisor["rfc_emisores"]; 
	$cfdi['Emisor']['Nombre'] = $emisor["razon_social_emisores"];  
	$cfdi['Emisor']['RegimenFiscal'] =  $emisor["regimen_emisores"];  
	
	// Datos del Receptor
	$cfdi['Receptor']['Rfc'] = trim(strtoupper($_POST["rfc_clientes"]));
	$cfdi['Receptor']['Nombre'] = trim(strtoupper($_POST["razon_social_clientes"]));
	$cfdi['Receptor']['DomicilioFiscalReceptor'] = $_POST["cp_clientes"];
	$cfdi['Receptor']['RegimenFiscalReceptor'] = $_POST["regimen_clientes"];
	$cfdi['Receptor']['UsoCFDI'] = $_POST["uso_cfdi"];
	
	
	//Conceptos
	if(isset($_POST["descripcion"])){
		$conceptos = array();
		$retenciones_isr =0;
		$retenciones_iva =0;
		$impuestos_tasa =0;
		$impuestos_base =0;
		
		foreach($_POST["descripcion"] as $i_concepto => $descripcion){
			$i_traslados= 0; 
			$i_retenciones = 0;
			
			$cfdi['conceptos'][$i_concepto]['cantidad'] = $_POST["cantidad"][$i_concepto];
			$cfdi['conceptos'][$i_concepto]['ClaveUnidad'] = $_POST["clave_unidad"][$i_concepto];
			$cfdi['conceptos'][$i_concepto]['ObjetoImp'] = "02"; //02 Si objeto de impuesto
			$cfdi['conceptos'][$i_concepto]['unidad'] = $_POST["nombre_unidades"][$i_concepto]; 
			$cfdi['conceptos'][$i_concepto]['ClaveProdServ'] = $_POST["clave_producto"][$i_concepto];
			$cfdi['conceptos'][$i_concepto]['descripcion'] = $_POST["descripcion"][$i_concepto];
			$cfdi['conceptos'][$i_concepto]['valorunitario'] = $_POST["precio_unitario"][$i_concepto];
			$cfdi['conceptos'][$i_concepto]['importe'] = $_POST["importe"][$i_concepto];
			
			
			
			foreach($_POST["tipo_impuesto"][$i_concepto] as $i_impuesto => $tipo_impuesto){
				if($tipo_impuesto == "Traslado"){
					
					/*
						CFDI33157 - Si el valor registrado en el campo TipoFactor que corresponde a Traslado es Exento no se deben registrar los campos TasaOCuota ni Importe.
					*/
					
					/*
						El valor seleccionado debe corresponder a un valor del catalogo donde la columna impuesto corresponda con el campo impuesto y la columna factor corresponda con el campo TipoFactor.
					*/
					if($_POST["tipo_factor"][$i_concepto][$i_impuesto] == "Tasa"){
						$cfdi['conceptos'][$i_concepto]['Impuestos']['Traslados'][$i_traslados]['Base'] = $_POST["base"][$i_concepto][$i_impuesto];
						$cfdi['conceptos'][$i_concepto]['Impuestos']['Traslados'][$i_traslados]['Impuesto'] = $_POST["impuesto"][$i_concepto][$i_impuesto];
						$cfdi['conceptos'][$i_concepto]['Impuestos']['Traslados'][$i_traslados]['TipoFactor'] =  $_POST["tipo_factor"][$i_concepto][$i_impuesto];
						$cfdi['conceptos'][$i_concepto]['Impuestos']['Traslados'][$i_traslados]['TasaOCuota'] = $_POST["tasa"][$i_concepto][$i_impuesto];
						$cfdi['conceptos'][$i_concepto]['Impuestos']['Traslados'][$i_traslados]['Importe'] = $_POST["impuesto_importe"][$i_concepto][$i_impuesto];
						
						//Total traslados
						$cfdi['impuestos']['Traslados'][$i_traslados]['Impuesto'] = $_POST["impuesto"][$i_concepto][$i_impuesto];
						$cfdi['impuestos']['Traslados'][$i_traslados]['TasaOCuota'] = $_POST["tasa"][$i_concepto][$i_impuesto];
						$cfdi['impuestos']['Traslados'][$i_traslados]['Importe'] = $_POST["total_traslados"];
						$cfdi['impuestos']['Traslados'][$i_traslados]['TipoFactor'] =  $_POST["tipo_factor"][$i_concepto][$i_impuesto];
						$i_traslados++;
						
					}
					elseif($_POST["tipo_factor"][$i_concepto][$i_impuesto] == "Exento"){
						$cfdi['conceptos'][$i_concepto]['Impuestos']['Traslados'][$i_traslados]['Base'] = $_POST["base"][$i_concepto][$i_impuesto];
						$cfdi['conceptos'][$i_concepto]['Impuestos']['Traslados'][$i_traslados]['Impuesto'] = $_POST["impuesto"][$i_concepto][$i_impuesto];
						$cfdi['conceptos'][$i_concepto]['Impuestos']['Traslados'][$i_traslados]['TipoFactor'] =  $_POST["tipo_factor"][$i_concepto][$i_impuesto];
						
						//Total traslados
						$cfdi['Impuestos']['Traslados'][$i_traslados]['Base'] = $_POST["base"][$i_concepto][$i_impuesto];
						$cfdi['Impuestos']['Traslados'][$i_traslados]['Impuesto'] = $_POST["impuesto"][$i_concepto][$i_impuesto];
						// $cfdi['Impuestos']['Traslados'][$i_traslados]['Importe'] = $_POST["total_traslados"];
						$cfdi['Impuestos']['Traslados'][$i_traslados]['TipoFactor'] =  $_POST["tipo_factor"][$i_concepto][$i_impuesto];
						
						$impuestos_base+= round($_POST["base"][$i_concepto][$i_impuesto], 2);
						
						$i_traslados++;
					}
					
					
					
					
				}
				else{
					
					//Retencion
					$cfdi['conceptos'][$i_concepto]['Impuestos']['Retenciones'][$i_retenciones]['Base'] = $_POST["base"][$i_concepto][$i_impuesto];
					$cfdi['conceptos'][$i_concepto]['Impuestos']['Retenciones'][$i_retenciones]['Impuesto'] = $_POST["impuesto"][$i_concepto][$i_impuesto];
					$cfdi['conceptos'][$i_concepto]['Impuestos']['Retenciones'][$i_retenciones]['TipoFactor'] = 'Tasa';
					$cfdi['conceptos'][$i_concepto]['Impuestos']['Retenciones'][$i_retenciones]['TasaOCuota'] = $_POST["tasa"][$i_concepto][$i_impuesto];
					$cfdi['conceptos'][$i_concepto]['Impuestos']['Retenciones'][$i_retenciones]['Importe'] = $_POST["impuesto_importe"][$i_concepto][$i_impuesto];
					$i_retenciones++;
					
					if($_POST["impuesto"][$i_concepto][$i_impuesto] == '001'){
						$retenciones_isr+= $_POST["impuesto_importe"][$i_concepto][$i_impuesto];
						$cfdi['Impuestos']['retenciones'][0]['impuesto'] = "001";
						$cfdi['Impuestos']['retenciones'][0]['importe']= $retenciones_isr;
					}
					else{
						$retenciones_iva+= $_POST["impuesto_importe"][$i_concepto][$i_impuesto];
						$cfdi['Impuestos']['retenciones'][1]['impuesto'] = "002";
						$cfdi['Impuestos']['retenciones'][1]['importe'] = $retenciones_iva;
					}
					
				}
			}
		}
		
		if($i_traslados > 0){
			
			// $cfdi['Impuestos']['Traslados'][0]['Base'] = round($cfdi['Impuestos']['Traslados'][0]['Base']);
			$cfdi['Impuestos']['Traslados'][0]['Base'] = round($impuestos_base);
		}
		
		
		$respuesta["conceptos"] = $cfdi['conceptos'];
	}
	else{
		
		$respuesta["estatus"] = "Error";
		$respuesta["mensaje"] = "No hay Conceptos";
		
	}
	
	if($_POST["total_traslados"] >= 0 ){
		// $cfdi['Impuestos']['TotalImpuestosTrasladados'] = $_POST["total_traslados"];
	}
	if($_POST["total_retenciones"] > 0 ){
		$cfdi['Impuestos']['TotalImpuestosRetenidos'] = $_POST["total_retenciones"];
	}
	
	
	$respuesta["datos_enviados"]  = $cfdi;
	
	// $respuesta["post"]= $_POST; 
	$respuesta["cfdi"]= $cfdi;
	// $respuesta["timbrado"]= mf_genera_cfdi($cfdi);+++
	
	
	
	// $enviromentenviroment ="sandbox";
	
	if(isset($_POST["modo_pruebas"])){
		$url = "https://services.test.sw.com.mx";
		
		$token =$emisor["token_pruebas"];
	}
	else{
		$url = "https://services.sw.com.mx";
		
		$token = $emisor["token_produccion"];
	}
	$url.= "/v3/cfdi33/issue/json/v4";
	
	$respuesta["url"] = $url;
	
	$curl = curl_init();
	
	// $cfdi = file_get_contents("nomina.json");
	
    curl_setopt_array($curl, array(
	CURLOPT_URL => $url,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, 
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => json_encode($cfdi),
	CURLOPT_HTTPHEADER => array(
	"Authorization: bearer ".$token,
	"Content-Type: application/jsontoxml"
	),
    ));
	
	// $respuesta["curl"] = $curl;
	
	$result = curl_exec($curl);
	if($result === FALSE){
		$respuesta["timbrado"]["error"] = "SI";
		$respuesta["timbrado"]["mensaje"] =  curl_error($curl);
		
		
	}
	else{
		$json_respuesta =json_decode($result, true);
		
		// echo ($result);
		$respuesta["timbrado"] = $json_respuesta;
	}
	
	$respuesta["result_curl"] = $result;
	
	
	
	
	
	
	
	
	
	// die(json_encode($respuesta));
	
	
	
	if($respuesta["timbrado"]["status"] == "success"){
		
		$filename = $prefix."_".$rfc."_".$folio_facturas."_".substr($respuesta["timbrado"]["data"]["uuid"], -5);
		
		$respuesta["filename"]= $filename;
		
		$new_path = "timbrados/".$filename;
		
		$respuesta["new_path"] = $new_path;
		
		rename( $respuesta["timbrado"]["archivo_xml"], "$new_path.xml");
		rename( $respuesta["timbrado"]["archivo_png"], "$new_path.png");
		
		$respuesta["url_xml"] = $new_path.".xml";
		$respuesta["url_pdf"] = $new_path.".pdf";
		$respuesta["export_xml"] = file_put_contents("$new_path.xml",$respuesta["timbrado"]["data"]["cfdi"]);
		$respuesta["export_png"] = file_put_contents("$new_path.png",base64_decode($respuesta["timbrado"]["data"]["qrCode"]));
		// $respuesta["export_jpg"] = file_put_contents("$new_path.jpg",base64_decode($respuesta["timbrado"]["data"]["qrCode"]));
		
		
		$insert_clientes = "INSERT IGNORE INTO clientes SET 
		rfc_clientes = UPPER('{$_POST["rfc_clientes"]}'),
		razon_social_clientes = UPPER('{$_POST["razon_social_clientes"]}'),
		correo_clientes = '{$_POST["correo_clientes"]}',
		cp_clientes = '{$_POST["cp_clientes"]}',
		regimen_clientes = '{$_POST["regimen_clientes"]}'
		
		
		";
		
		$result = mysqli_query($link, $insert_clientes);
		
		if($result){
			$respuesta["estatus_clientes"]  = "success";
			$id_clientes = mysqli_insert_id($link);
		}
		else{
			$respuesta["estatus_clientes"]  = mysqli_error($link);
			$respuesta["error"]  = mysqli_error($link);
		}
		
		
		
		// TODO guardar en BD
		
		$insert_facturas =" INSERT INTO facturas SET ";
		$insert_facturas.=" folio_facturas = '". $folio_facturas . "',";
		$insert_facturas.=" id_emisores = '". $id_emisores . "',";
		$insert_facturas.=" fecha_facturas = CURDATE(),";
		$insert_facturas.=" id_clientes = '". $id_clientes . "',";
		$insert_facturas.=" metodo_pago = '". $metodo_pago . "',";
		$insert_facturas.=" forma_pago = '". $forma_pago . "',";
		$insert_facturas.=" lugar_expedicion = '". $lugar_expedicion . "',";
		$insert_facturas.=" subtotal = '". $subtotal . "',";
		$insert_facturas.=" retenciones_iva = '". $retenciones_iva . "',";
		$insert_facturas.=" retenciones_isr = '". $retenciones_isr . "',";
		$insert_facturas.=" total_traslados = '". $_POST["total_traslados"] . "',";
		$insert_facturas.=" total_retenciones = '". $_POST["total_retenciones"] . "',";
		// $insert_facturas.=" iva_total = '". $iva_total . "',";
		$insert_facturas.=" descuento = '". $descuento_total . "',";
		$insert_facturas.=" total = '". $total . "',";
		$insert_facturas.=" tipo_comprobante = '". $tipo_comprobante . "',";
		$insert_facturas.=" uso_cfdi = '{$_POST["uso_cfdi"]}',";
		$insert_facturas.=" uuid = '{$respuesta["timbrado"]["data"]["uuid"]}',";
		$insert_facturas.=" representacion_impresa_cadena = '{$respuesta["timbrado"]["data"]["cadenaOriginalSAT"]}',";
		$insert_facturas.=" representacion_impresa_certificado_no = '{$respuesta["timbrado"]["data"]["noCertificadoCFDI"]}',";
		$insert_facturas.=" representacion_impresa_fecha_timbrado = '{$respuesta["timbrado"]["data"]["fechaTimbrado"]}',";
		$insert_facturas.=" representacion_impresa_sello = '{$respuesta["timbrado"]["data"]["selloCFDI"]}',";
		$insert_facturas.=" representacion_impresa_selloSAT = '{$respuesta["timbrado"]["data"]["selloSAT"]}',";
		$insert_facturas.=" representacion_impresa_certificadoSAT = '{$respuesta["timbrado"]["data"]["noCertificadoSAT"]}',";
		
		$insert_facturas.=" serie = '{$emisor["serie"]}',";
		$insert_facturas.=" folio = '{$emisor["folio"]}',";
		$insert_facturas.=" qr_code = '{$respuesta["timbrado"]["data"]["qrCode"]}',";
		$insert_facturas.=" archivo_xml = '$new_path.xml',";
		$insert_facturas.=" archivo_png = '$new_path.png',";
		$insert_facturas.=" url_pdf =  '$new_path.pdf',";
		
		$insert_facturas.=" timbrado = '$timbrada', ";
		$insert_facturas.=" saldo_actual = '$saldo_actual', ";
		$insert_facturas.=" observaciones = '$observaciones'";
		
		$result = mysqli_query($link, $insert_facturas);
		
		if($result){
			$respuesta["insert_facturas_estatus"]  = "success";
			$respuesta["insert_facturas_mensaje"]  = "Agregado a DB";
			$id_facturas =  mysqli_insert_id($link);
			$respuesta["id_facturas"]  = $id_facturas;
			$i_conceptos = 0;
			foreach($respuesta["datos_enviados"]["conceptos"] as $index=>$concepto){
				
				$clave_productos= $concepto['ClaveProdServ'];
				$clave_unidad = $concepto['ClaveUnidad'];
				$cantidad = $concepto['cantidad'];
				$unidad = $concepto['unidad'];
				$descripcion	 = $concepto['descripcion'];
				$precio	 = $concepto['valorunitario'];
				$importe	 = $concepto['importe'];
				
				$insert_detalle	= "INSERT INTO facturas_detalle SET 
				id_facturas = '$id_facturas', 
				clave_productos = '$clave_productos', 
				clave_unidad = '$clave_unidad', 
				cantidad = '$cantidad', 
				unidad = '$unidad', 
				descripcion = '$descripcion', 
				precio = '$precio', 
				importe = '$importe'";
				
				if(mysqli_query($link, $insert_detalle)){
					$respuesta["insert_detalle"][$index]["estatus"]  = "success";
					$respuesta["insert_detalle"][$index]["query"]  = $insert_detalle;
				}
				else{
					$respuesta["insert_detalle"][$index]["estatus"]  = "error";
					$respuesta["insert_detalle"][$index]["mensaje"]  = mysqli_error($link);
					$respuesta["insert_detalle"][$index]["query"]  = $insert_detalle;
					
				}
				
				
				$i_conceptos++;
			}
		}
		else{
			$respuesta["insert_facturas_estatus"]  = "error";
			$respuesta["insert_facturas_mensaje"]  = mysqli_error($link);
			
		}
		
		$respuesta["insert_facturas"]  = $insert_facturas;
		
		
		//Actualiza id_pagos como facturado 
		if(isset($_POST["id_ventas"])){
			$update_ventas = "UPDATE boletos SET id_facturas = '$id_facturas' WHERE id_boletos IN ({$_POST["id_ventas"]})";
			
			$respuesta["update_ventas"]["consulta"]  = $update_ventas;
			
			$result = mysqli_query($link, $update_ventas);
			if($result){
				$respuesta["update_ventas"]["estatus"]  = "success";
				$respuesta["update_ventas"]["mensaje"]  = "Pagos facturados";
				$respuesta["update_ventas"]["filas_afectadas"]  = mysqli_affected_rows($link);
				
			}
			else{
				$respuesta["update_ventas"]["estatus"]   = "error";
				$respuesta["update_ventas"]["mensaje"] = mysqli_error($link);
				
			}
		}
		
		
		//Actualiza Folios
		if($folio_facturas != ""){
			$folio_facturas++;
			$update_folios = "UPDATE emisores
			
			SET 
			folio = folio + 1,
			folios_restantes_emisores = folios_restantes_emisores - 1
			WHERE
			id_emisores = '$id_emisores'";
			
			
			$result = mysqli_query($link, $update_folios); 
			
			if($result){
				$respuesta["update_folios_estatus"]  = "success";
				$respuesta["update_folios_mensaje"]  = "Folios Actualizados";
				
			}
			else{
				$respuesta["update_folios_estatus"]  = "error";
				$respuesta["update_folios_mensaje"]  = mysqli_error($link);
				
			}
			$respuesta["update_folios"]  = $update_folios;
			$respuesta["folio_facturas"]  = $folio_facturas;
			
		}
		
		
	}
	else{
		
		$respuesta["datos_enviados"]  = $cfdi;
		// $respuesta["codigo_mf_numero"]  = ;
	}
	
	
	echo json_encode($respuesta);
	
