<?php
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header("Content-Type: application/json");
	session_start();
	
	
	// Se desactivan los mensajes de debug
	// error_reporting(~(E_WARNING|E_NOTICE));
	error_reporting(0);
	// error_reporting(E_ALL);
	
	include_once("../../../conexi.php");
	
	
	$link = Conectarse();
	
	setlocale(LC_ALL,"en_US"); 
	
	$respuesta = array();
	$respuesta["locale"] = localeconv();
	$cfdi = array();
	
	$consulta_emisores = "SELECT * FROM emisores WHERE id_emisores = 1";
	
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
	
	
	$serie = $_POST["serie"];
	$folio = $_POST["folio"];
	$folio_facturas = $serie.$folio ;
	if($folio_facturas == ''){
		
		$folio_facturas = date("dmY_Hi");
	}
	
	$id_emisores = 1;
	
	
	$lugar_expedicion = $_POST["lugar_expedicion"];
	$metodo_pago = $_POST["metodo_pago"];
	
	$forma_pago = $_POST["forma_pago"];
	$tipo_comprobante =$_POST["tipocomprobante"];
	
	$subtotal =  $_POST["subtotal"];
	$descuento_total =  $_POST["descuento_total"];
	// $iva_total = $_POST["iva_total"];
	$total = $_POST["total_pagos"];
	
	$saldo_actual = $metodo_pago == "PPD" ? $total : 0;
	
	$observaciones = $_POST["observaciones"];
	$conceptos = array();
	
	
	
	date_default_timezone_set('America/Mexico_City');
	
	
	
	$prefix = isset($_POST["modo_pruebas"]) ? "pruebas" : "";
	$path = "timbrados/$prefix_".$rfc_emisores."_".$folio_facturas;
	$ruta_xml = $path.'.xml';
	$respuesta["ruta_xml"] = $ruta_xml;
	
	
	$cfdi['cfdi']= $ruta_xml;
	
	
	$cfdi['Version'] = '3.3';
	
	$cfdi['xml_debug']='timbrados/sin_timbrar'.$rfc."_".$folio_facturas.'.xml';
	
	
	$produccion = isset($_POST["modo_pruebas"])? "NO" : "SI";
	$timbrada = isset($_POST["modo_pruebas"])? 0 : 1;
	
	
	$id_clientes = $_POST["id_clientes"];
	
	
	
	// Datos de la Factura
	$cfdi['Fecha'] = date('Y-m-d\TH:i:s', time() - 120);
	$cfdi['Serie'] = $serie;
	$cfdi['folio'] = $folio;
	
	$cfdi['FormaPago'] = $forma_pago;
	$cfdi['LugarExpedicion'] = $lugar_expedicion; 
	$cfdi['TipoDeComprobante'] = $tipo_comprobante;
	$cfdi['MetodoPago'] = $metodo_pago;
	$cfdi['Subtotal'] = $subtotal;
	$cfdi['Moneda'] = 'MXN';
	$cfdi['Total'] = $total;
	
	// $cfdi['descuento'] = $descuento_total; 
	
	
	
	// Datos del Emisor
	$cfdi['Emisor']['Rfc'] = $rfc; 
	$cfdi['Emisor']['Nombre'] = $emisor["razon_social_emisores"];  
	$cfdi['Emisor']['RegimenFiscal'] =  $emisor["regimen_emisores"];  
	
	// Datos del Receptor
	$cfdi['Receptor']['Rfc'] = $_POST["rfc_clientes"];
	$cfdi['Receptor']['Nombre'] = $_POST["nombre_rem"];
	$cfdi['Receptor']['UsoCFDI'] = $_POST["uso_cfdi"];
	
	
	//Conceptos
	if(isset($_POST["descripcion"])){
		$conceptos = array();
		$retenciones_isr =0;
		$retenciones_iva =0;
		
		foreach($_POST["descripcion"] as $i_concepto => $descripcion){
			$i_traslados= 0; 
			$i_retenciones = 0;
			
			$cfdi['conceptos'][$i_concepto]['cantidad'] = $_POST["cantidad"][$i_concepto];
			$cfdi['conceptos'][$i_concepto]['ClaveUnidad'] = $_POST["clave_unidad"][$i_concepto];
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
						// $cfdi['impuestos']['Traslados'][$i_traslados]['Impuesto'] = $_POST["impuesto"][$i_concepto][$i_impuesto];
						// $cfdi['impuestos']['Traslados'][$i_traslados]['Importe'] = $_POST["total_traslados"];
						// $cfdi['impuestos']['Traslados'][$i_traslados]['TipoFactor'] =  $_POST["tipo_factor"][$i_concepto][$i_impuesto];
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
						$cfdi['impuestos']['retenciones'][0]['impuesto'] = "001";
						$cfdi['impuestos']['retenciones'][0]['importe']= $retenciones_isr;
					}
					else{
						$retenciones_iva+= $_POST["impuesto_importe"][$i_concepto][$i_impuesto];
						$cfdi['impuestos']['retenciones'][1]['impuesto'] = "002";
						$cfdi['impuestos']['retenciones'][1]['importe'] = $retenciones_iva;
					}
					
				}
			}
		}
		
		
		$respuesta["conceptos"] = $cfdi['conceptos'];
	}
	else{
		
		$respuesta["estatus"] = "Error";
		$respuesta["mensaje"] = "No hay Conceptos";
		
	}
	
	if($_POST["total_traslados"] >= 0 ){
		$cfdi['impuestos']['TotalImpuestosTrasladados'] = $_POST["total_traslados"];
	}
	if($_POST["total_retenciones"] > 0 ){
		$cfdi['impuestos']['TotalImpuestosRetenidos'] = $_POST["total_retenciones"];
	}
	
	
	$respuesta["datos_enviados"]  = $cfdi;
	
	// $respuesta["post"]= $_POST; 
	$respuesta["cfdi"]= $cfdi;
	// $respuesta["timbrado"]= mf_genera_cfdi($cfdi);+++
	
	
	
	// $enviromentenviroment ="sandbox";
	
	if(isset($_POST["modo_pruebas"])){
		$url = "https://services.test.sw.com.mx";
		
		$token ="T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGQrNzJ3UWh4TVVxb0g3TU5KV0Q2Um5rb2VpQlZibFk2b3JLeURxQmU5TGhudldsdjExeGpvaDBEQVZYWUhWTE5nKzh5MENnVm9MRjNwRE5MU0xuOWtRdTNGMktEajgrSlVtcVNPbWpLSE9hajJCZC9zOFBEOVp3VG9BbFRaMkFsSHl4ZkoxSWlQYnRERi9kTCtaMkhWeHROSmlUemxHbEhHbDBIMEdueTh0ZmtSOHUwMVNaempVNnlDNTRLRzhxNmU5VlpIdlhJVDMyZ2V2aDVvQzNjRW1YUFVJeXdHcmdvUmhBdVhCS0xyYi9hYjc5Mm40RE1GRUc1MGRkcTg2S0dGSUhVMkhKek5GUTZWRTZpWmlrWG5uZnFLUis1RUZCVmlONjM5YXlXRWRuQjdOK1dTMExnQ2pyWTRwTmdUeW1lRkFLL3UwUFh1Rk9xcytPMlZaN2dLUjNCNEo5aWpGZWFPUnJBQmh2QVhrZVpNa0g2TFZialZvOURwbEdocmgvVXA.SG1wPsd0gqgJFXQXZivd_o86L1E0ERiWpTlaE-yvkgQ";
	}
	else{
		$url = "https://services.sw.com.mx";
		
		$token = "T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGQ1MmJPVW1nQ2J3NDk0Tys0ZmorOUlkNFdzdVZXV2pMa21pZHBLeVNYUmt0WTNscnVKQytsU0ZpUkFGZXNBYnBJMDMreDdKd3VXcS82NTh3cFlzb0UzWWZka1hXZG9EMWJFcnFwQU1PN3FyU0w2UjBiM3B5Qm9MNG01cVRtaHFLM0FoUEhUZXhHQUxGYWFJNVMrcXhsQTdlek82QVg1RVVtWUQ1K0JtWTM5YzE0Y2h3VTdVUkh4cC8yZUluc1YwbzBRc2tQT0o3US9IK0FLM3NWdnhuSEptY0RCOTg5dDQvZUp3d25ienVtN0hnNHlxZ2RreG5mRThkMmU2aVUrelNUS0ptc0FDR3U5bkJUVGhOa3VJV3J2RGJUcXJyeGkrVGpYSndJcy9HYUxqaE9mUVdUSFNjeWtIZXFnOURzL2N0a0hzWWdjbFRmQVlPSlFyekx1akZ1dVNMVkI0Q1FDVHQyVG81L2J0bWxMUjU2MlVQWWdCcUZKZTl6SDlFcU82TWtzaTUwZFJJbnYzcjRuNTNOcHFQQXNBPT0.kBCEI7THLmiwbA2gWJfrlsyMT-Fi7_5nIuVoa51be2s";
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
		
		
		
		// if(isset($_POST["activa_addenda"])){
		// $respuesta["tiene_addenda"] = 1;
		// $respuesta["addenda"] = $_POST["addenda"];
		
		// addenda($ruta_xml);	
		// }
		// else{
		
		// $respuesta["tiene_addenda"] = 0;
		// }
		
		$new_path = "timbrados/".$prefix."_".$rfc."_".$folio_facturas."_".substr($respuesta["timbrado"]["data"]["uuid"], -5);
		$respuesta["new_path"] = $new_path;
		rename( $respuesta["timbrado"]["archivo_xml"], "$new_path.xml");
		rename( $respuesta["timbrado"]["archivo_png"], "$new_path.png");
		
		$respuesta["export_xml"] = file_put_contents("$new_path.xml",$respuesta["timbrado"]["data"]["cfdi"]);
		$respuesta["export_png"] = file_put_contents("$new_path.png",base64_decode($respuesta["timbrado"]["data"]["qrCode"]));
		
		
		
		
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
		
		$insert_facturas.=" serie = '{$_POST["serie"]}',";
		$insert_facturas.=" folio = '{$_POST["folio"]}',";
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
			$update_ventas = "UPDATE boletos SET id_facturas = '$id_facturas' WHERE boletos IN ({$_POST["id_ventas"]})";
			
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
	
	function addenda($ruta_xml){
		$xml_original = file_get_contents($ruta_xml);
		$addenda = trim($_POST["addenda"]);
		
		$xml_addenda = str_replace("</cfdi:Complemento>", "</cfdi:Complemento>".$addenda, $xml_original);
		
		file_put_contents($ruta_xml, $xml_addenda);
		
	}
	
