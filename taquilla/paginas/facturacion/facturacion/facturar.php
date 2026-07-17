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
	
	include_once("../../conexi.php");
	require_once 'sdk2.php';
	
	
	$link = Conectarse();
	
	setlocale(LC_ALL,"en_US"); 
	
	$respuesta = array();
	$respuesta["locale"] = localeconv();
	$datos = array();
	
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
	
	
	// Se especifica la version de CFDi 3.3
	$datos['version_cfdi'] = '3.3';
	
	// Ruta del XML Timbrado
	
	// Ruta del XML Timbrado
	
	$prefix = isset($_POST["modo_pruebas"]) ? "pruebas" : "";
	$path = "timbrados/$prefix_".$rfc_emisores."_".$folio_facturas;
	$ruta_xml = $path.'.xml';
	$respuesta["ruta_xml"] = $ruta_xml;
	
	
	$datos['cfdi']= $ruta_xml;
	
	// Ruta del XML de Debug
	$datos['xml_debug']='timbrados/sin_timbrar'.$rfc."_".$folio_facturas.'.xml';
	
	
	$produccion = isset($_POST["modo_pruebas"])? "NO" : "SI";
	$timbrada = isset($_POST["modo_pruebas"])? 0 : 1;
	
	
	$datos['PAC']['usuario'] = $rfc;
	$datos['PAC']['pass'] = $emisor["password"];
	$datos['PAC']['produccion'] = $produccion;
	
	// Rutas y clave de los CSD
	$datos['conf']['cer'] = "certificados/$rfc.cer.pem";
	$datos['conf']['key'] = "certificados/$rfc.key.pem";
	$datos['conf']['pass'] = $emisor["password"];
	
	$id_clientes = $_POST["id_rem"];
	// $rfc_clientes =  $_POST["rfc_clientes"];
	// $razon_social_clientes =  $_POST["razon_social_clientes"];
	
	// Datos del Emisor
	$datos['emisor']['rfc'] = $rfc; 
	$datos['emisor']['nombre'] = $emisor["razon_social_emisores"];  
	$datos['factura']['RegimenFiscal'] =  $emisor["regimen_emisores"];  
	
	// Datos del Receptor
	$datos['receptor']['rfc'] = $_POST["rfc_clientes"];
	$datos['receptor']['nombre'] = $_POST["nombre_rem"];
	$datos['receptor']['UsoCFDI'] = $_POST["uso_cfdi"];
	
	// Datos de la Factura
	$datos['factura']['fecha_expedicion'] = date('Y-m-d\TH:i:s', time() - 120);
	$datos['factura']['serie'] = $serie;
	$datos['factura']['folio'] = $folio;
	
	$datos['factura']['forma_pago'] = $forma_pago;
	$datos['factura']['LugarExpedicion'] = $lugar_expedicion; 
	$datos['factura']['tipocomprobante'] = $tipo_comprobante;
	$datos['factura']['metodo_pago'] = $metodo_pago;
	$datos['factura']['moneda'] = 'MXN';
	
	
	//Conceptos
	if(isset($_POST["descripcion"])){
		$conceptos = array();
		$retenciones_isr =0;
		$retenciones_iva =0;
		
		foreach($_POST["descripcion"] as $i_concepto => $descripcion){
			$i_traslados= 0; 
			$i_retenciones = 0;
			
			$datos['conceptos'][$i_concepto]['cantidad'] = $_POST["cantidad"][$i_concepto];
			$datos['conceptos'][$i_concepto]['ClaveUnidad'] = $_POST["clave_unidad"][$i_concepto];
			$datos['conceptos'][$i_concepto]['unidad'] = $_POST["nombre_unidades"][$i_concepto]; 
			$datos['conceptos'][$i_concepto]['ClaveProdServ'] = $_POST["clave_producto"][$i_concepto];
			$datos['conceptos'][$i_concepto]['descripcion'] = $_POST["descripcion"][$i_concepto];
			$datos['conceptos'][$i_concepto]['valorunitario'] = $_POST["precio_unitario"][$i_concepto];
			$datos['conceptos'][$i_concepto]['importe'] = $_POST["importe"][$i_concepto];
			// $datos['conceptos'][$i_concepto]['Descuento'] = $_POST["descuento"][$i_concepto];
			
			
			// $datos['impuestos']['translados'][$i_traslados]['Impuesto'] = '002';
			// $datos['impuestos']['translados'][$i_traslados]['TasaOCuota'] = '0.160000';
			// $datos['impuestos']['translados'][$i_traslados]['Importe'] = $_POST["total_traslados"];
			// $datos['impuestos']['translados'][$i_traslados]['TipoFactor'] = 'Tasa';
			
			
			
			foreach($_POST["tipo_impuesto"][$i_concepto] as $i_impuesto => $tipo_impuesto){
				if($tipo_impuesto == "Traslado"){
					
					
					$datos['conceptos'][$i_concepto]['Impuestos']['Traslados'][$i_traslados]['Base'] = $_POST["base"][$i_concepto][$i_impuesto];
					$datos['conceptos'][$i_concepto]['Impuestos']['Traslados'][$i_traslados]['Impuesto'] = $_POST["impuesto"][$i_concepto][$i_impuesto];
					$datos['conceptos'][$i_concepto]['Impuestos']['Traslados'][$i_traslados]['TipoFactor'] = 'Tasa';
					$datos['conceptos'][$i_concepto]['Impuestos']['Traslados'][$i_traslados]['TasaOCuota'] = $_POST["tasa"][$i_concepto][$i_impuesto];
					$datos['conceptos'][$i_concepto]['Impuestos']['Traslados'][$i_traslados]['Importe'] = $_POST["impuesto_importe"][$i_concepto][$i_impuesto];
					$i_traslados++;
					
					
					//Total traslados
					$datos['impuestos']['translados'][$i_traslados]['Impuesto'] = $_POST["impuesto"][$i_concepto][$i_impuesto];
					$datos['impuestos']['translados'][$i_traslados]['TasaOCuota'] = $_POST["tasa"][$i_concepto][$i_impuesto];
					$datos['impuestos']['translados'][$i_traslados]['Importe'] = $_POST["total_traslados"];
					$datos['impuestos']['translados'][$i_traslados]['TipoFactor'] = 'Tasa';
					
					
				}
				else{
					
					//Retencion
					$datos['conceptos'][$i_concepto]['Impuestos']['Retenciones'][$i_retenciones]['Base'] = $_POST["base"][$i_concepto][$i_impuesto];
					$datos['conceptos'][$i_concepto]['Impuestos']['Retenciones'][$i_retenciones]['Impuesto'] = $_POST["impuesto"][$i_concepto][$i_impuesto];
					$datos['conceptos'][$i_concepto]['Impuestos']['Retenciones'][$i_retenciones]['TipoFactor'] = 'Tasa';
					$datos['conceptos'][$i_concepto]['Impuestos']['Retenciones'][$i_retenciones]['TasaOCuota'] = $_POST["tasa"][$i_concepto][$i_impuesto];
					$datos['conceptos'][$i_concepto]['Impuestos']['Retenciones'][$i_retenciones]['Importe'] = $_POST["impuesto_importe"][$i_concepto][$i_impuesto];
					$i_retenciones++;
					
					if($_POST["impuesto"][$i_concepto][$i_impuesto] == '001'){
						$retenciones_isr+= $_POST["impuesto_importe"][$i_concepto][$i_impuesto];
						$datos['impuestos']['retenciones'][0]['impuesto'] = "001";
						$datos['impuestos']['retenciones'][0]['importe']= $retenciones_isr;
					}
					else{
						$retenciones_iva+= $_POST["impuesto_importe"][$i_concepto][$i_impuesto];
						$datos['impuestos']['retenciones'][1]['impuesto'] = "002";
						$datos['impuestos']['retenciones'][1]['importe'] = $retenciones_iva;
					}
					
				}
			}
		}
		
		
		$respuesta["conceptos"] = $datos['conceptos'];
	}
	else{
		
		$respuesta["estatus"] = "Error";
		$respuesta["mensaje"] = "No hay Conceptos";
		
	}
	//Impuestos
	// $i_traslados= 0;
	// $i_retenciones = 0;
	// $retenciones_isr = 0;
	// $retenciones_iva = 0;
	// foreach($_POST["tipo_impuesto"] as $i_concepto => $tipo_impuesto){
	
	// foreach 
	// if($tipo_impuesto == "Traslado"){
	// $datos['impuestos']['translados'][$i_traslados]['Impuesto'] = $_POST["impuesto"][0][$i_concepto];
	// $datos['impuestos']['translados'][$i_traslados]['TasaOCuota'] = $_POST["tasa"][$i_concepto];
	// $datos['impuestos']['translados'][$i_traslados]['Importe'] = $_POST["total_traslados"];
	// $datos['impuestos']['translados'][$i_traslados]['TipoFactor'] = 'Tasa';
	// $i_traslados++;
	
	// }
	// else{
	
	
	// $datos['impuestos']['retenciones'][$i_retenciones]['importe'] =  $_POST["impuesto_importe"][$i_impuesto];
	
	// if($_POST["impuesto"][$i_concepto] == '001'){
	// $retenciones_isr+= $_POST["impuesto_importe"][$i_concepto];
	// $datos['impuestos']['retenciones'][0]['impuesto'] = "001";
	// $datos['impuestos']['retenciones'][0]['impuesto']+= $_POST["impuesto"][$i_concepto];
	// }else{
	// $retenciones_iva+= $_POST["impuesto_importe"][$i_concepto];
	// $datos['impuestos']['retenciones'][1]['impuesto'] = "002";
	// $datos['impuestos']['retenciones'][1]['impuesto']+= $_POST["impuesto"][$i_concepto];
	// }
	// $i_retenciones++;
	
	// }
	
	
	// }
	
	
	// $datos['impuestos']['translados'][0]['impuesto'] = '001';
	// $datos['impuestos']['translados'][0]['tasa'] = '0.160000';
	// $datos['impuestos']['translados'][0]['importe'] = '16';
	// $datos['impuestos']['translados'][0]['TipoFactor'] = 'Tasa';*/
	
	//$datos['impuestos']['retenciones'][0]['impuesto'] = 'ISR';
	//$datos['impuestos']['retenciones'][0]['importe'] = '0.00';
	// $datos['impuestos']['translados'][0]['impuesto'] = '002';
	// $datos['impuestos']['translados'][0]['tasa'] = '0.160000';
	// $datos['impuestos']['translados'][0]['importe'] = $_POST["iva_total"];
	// $datos['impuestos']['translados'][0]['TipoFactor'] = 'Tasa';
	if($_POST["total_traslados"] >= 0 ){
		$datos['impuestos']['TotalImpuestosTrasladados'] = $_POST["total_traslados"];
	}
	if($_POST["total_retenciones"] > 0 ){
		$datos['impuestos']['TotalImpuestosRetenidos'] = $_POST["total_retenciones"];
	}
	//Totales
	$datos['factura']['subtotal'] = $subtotal;
	$datos['factura']['descuento'] = $descuento_total; 
	$datos['factura']['total'] = $total;
	
	
	$respuesta["datos_enviados"]  = $datos;
	
	// Se ejecuta el SDK
	$timbrado = mf_genera_cfdi($datos);
	
	
	// echo ;
	$respuesta["timbrado"] = $timbrado;
	// die(json_encode($respuesta));
	if($timbrado["codigo_mf_numero"] == 0){
		
		
		
		// if(isset($_POST["activa_addenda"])){
		// $respuesta["tiene_addenda"] = 1;
		// $respuesta["addenda"] = $_POST["addenda"];
		
		// addenda($ruta_xml);	
		// }
		// else{
		
		// $respuesta["tiene_addenda"] = 0;
		// }
		
		$new_path = "timbrados/".$prefix."_".$rfc."_".$folio_facturas."_".substr($respuesta["timbrado"]["uuid"], -5);
		$respuesta["new_path"] = $new_path;
		rename( $respuesta["timbrado"]["archivo_xml"], "$new_path.xml");
		rename( $respuesta["timbrado"]["archivo_png"], "$new_path.png");
		
		
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
		$insert_facturas.=" uuid = '". $respuesta["timbrado"]["uuid"] . "',";
		$insert_facturas.=" representacion_impresa_cadena = '". $respuesta["timbrado"]["representacion_impresa_cadena"] . "',";
		$insert_facturas.=" representacion_impresa_certificado_no = '". $respuesta["timbrado"]["representacion_impresa_certificado_no"] . "',";	
		$insert_facturas.=" representacion_impresa_fecha_timbrado = '". $respuesta["timbrado"]["representacion_impresa_fecha_timbrado"] . "',";$insert_facturas.=" representacion_impresa_sello = '". $respuesta["timbrado"]["representacion_impresa_sello"] . "',";
		$insert_facturas.=" representacion_impresa_selloSAT = '". $respuesta["timbrado"]["representacion_impresa_selloSAT"] . "',";
		$insert_facturas.=" representacion_impresa_certificadoSAT = '". $respuesta["timbrado"]["representacion_impresa_certificadoSAT"] . "',";
		
		$insert_facturas.=" serie = '{$_POST["serie"]}',";
		$insert_facturas.=" folio = '{$_POST["folio"]}',";
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
			$update_ventas = "UPDATE guias SET folio_factura = '$folio_facturas' WHERE guia IN ({$_POST["id_ventas"]})";
			
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
			folio = folio + 1
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
		
		$respuesta["datos_enviados"]  = $datos;
		// $respuesta["codigo_mf_numero"]  = ;
	}
	
	
	echo json_encode($respuesta);
	
	function addenda($ruta_xml){
		$xml_original = file_get_contents($ruta_xml);
		$addenda = trim($_POST["addenda"]);
		
		$xml_addenda = str_replace("</cfdi:Complemento>", "</cfdi:Complemento>".$addenda, $xml_original);
		
		file_put_contents($ruta_xml, $xml_addenda);
		
	}
	
