<?php
	// Se desactivan los mensajes de debug
	// error_reporting(0);
	// session_start();
	date_default_timezone_set('America/Mexico_City');
	include_once("../../../conexi.php");
	
	
	$link = Conectarse();
	$respuesta = array();
	$cfdi = array();
	$conceptos = array();
	$totales = array();
	
	$q_emisor = "SELECT * FROM emisores WHERE id_emisores = 1";
	
	$result_emisor = mysqli_query($link,$q_emisor );
	
	if($result_emisor){
		while($fila = mysqli_fetch_assoc($result_emisor)){
			$emisor = $fila;		
		}		
	}
	
	$id_facturas = $_POST["id_facturas"];
	$rfc = $emisor["rfc_emisores"];
	$pass_timbrado = $emisor["password"];
	$clave_privada = $emisor["password"];
	$serie = $_POST["serie"];
	$folio = $_POST["folio"];
	$folio_facturas = $serie.$folio ;
	if($folio_facturas == ''){
		
		$folio_facturas = date("dmY_Hi");
	}
	
	$id_emisores = 1;
	$rfc_emisores = $emisor["rfc_emisores"];
	$metodo_pago = isset($_POST["metodo_pago"]) ? $_POST["metodo_pago"] : "PUE";
	$subtotal = isset($_POST["subtotal"]) ? $_POST["subtotal"] : 0;
	$total = isset($_POST["total"]) ? $_POST["total"] : 0;
	$razon_social_emisores=  $emisor["razon_social_emisores"];
	$regimen_emisores= $emisor["regimen_emisores"];
	
	
	//datos de la factura anterior
	$consulta_factura = "SELECT * FROM facturas
	
	LEFT JOIN clientes USING(id_clientes)
	
	WHERE id_facturas IN ($id_facturas)";
	$respuesta["consulta_factura"] = $consulta_factura;
	$result_factura = mysqli_query($link, $consulta_factura);
	
	
	if($result_factura){
		
		while($fila_factura = mysqli_fetch_assoc($result_factura)){
			
			$dctos_relacionados[] = $fila_factura;
			$id_clientes = $fila_factura["id_clientes"];
			$rfc_clientes =  $fila_factura["rfc_clientes"];
			$cp_clientes =  $fila_factura["cp_clientes"];
			$razon_social_clientes =  $fila_factura["razon_social_clientes"];
			$regimen_clientes =  $fila_factura["regimen_clientes"];
			$uuid_dr =$fila_factura["uuid"];
			$metodo_pago_dr =$fila_factura["metodo_pago"];
			$lugar_expedicion = $fila_factura["lugar_expedicion"];
			$BaseDR = $fila_factura["subtotal"];
			$ImporteDR = $fila_factura["total_traslados"];
			
		}
		
	}
	
	$respuesta["dctos_relacionados"] = $dctos_relacionados;
	$dctos_relacionados = $_POST["dctos"];
	
	//datos del pago
	$saldo_anterior =  $_POST["saldo_anterior"];
	$abono =  $_POST["abono"];
	$saldo_restante = $_POST["saldo_restante"];
	$forma_pago = $_POST["forma_pago"];
	
	$observaciones =  isset($_POST["observaciones"]) ? $_POST["observaciones"] : ""; 
	$produccion = isset($_POST["modo_pruebas"])? "NO" : "SI";
	$timbrado = isset($_POST["modo_pruebas"])? 0 : 1;
	
	// Datos de la Factura
	
	
	$cfdi['Version'] = '4.0';
	$cfdi['Serie'] = $emisor["serie_pago"]; 
	$cfdi['Folio'] = $emisor["folio_pago"];	
	$cfdi['Fecha'] = date('Y-m-d\TH:i:s', time() - 3600);
	$cfdi['Subtotal'] = '0';
	$cfdi['Moneda'] = 'XXX';
	$cfdi['Total'] = '0';
	$cfdi['Exportacion'] = '01';
	$cfdi['LugarExpedicion'] = $lugar_expedicion; 
	$cfdi['TipoDeComprobante'] = 'P';
	
	// Datos del Emisor
	$cfdi['Emisor']['Rfc'] = $rfc_emisores; 
	$cfdi['Emisor']['Nombre'] = $razon_social_emisores;  
	$cfdi['Emisor']['RegimenFiscal'] = $regimen_emisores;
	
	// Datos del Receptor
	$cfdi['Receptor']['rfc'] = trim(strtoupper($rfc_clientes));
	$cfdi['Receptor']['nombre'] = trim(strtoupper($razon_social_clientes));
	$cfdi['Receptor']['DomicilioFiscalReceptor'] = trim(strtoupper($cp_clientes));
	$cfdi['Receptor']['RegimenFiscalReceptor'] = trim(strtoupper($regimen_clientes));
	$cfdi['Receptor']['UsoCFDI'] = 'CP01'; 
	
	//$cfdi['conceptos'][0]['unidad'] = 'ACT';
	$cfdi['Conceptos'][0]['Cantidad'] = '1';
	$cfdi['Conceptos'][0]['ClaveProdServ'] = '84111506';
	$cfdi['Conceptos'][0]['ClaveUnidad'] = 'ACT';
	$cfdi['Conceptos'][0]['Descripcion'] = "Pago"; 
	$cfdi['Conceptos'][0]['ValorUnitario'] = '0';
	$cfdi['Conceptos'][0]['Importe'] = '0';
	$cfdi['Conceptos'][0]["ObjetoImp"] = "01";
	
	// Complemento de Pagos 2.0	
	
	
	
	
	
	
	$pago= array();
	
	$base_iva= 0;
	$total_base_iva_16 = 0;
	$total_traslado_iva_16 = 0;
	$total_traslado_exento = 0;
	
	// Complemento de Pagos 1.0
	foreach($_POST["dctos"] as $i => $dcto_relacionado){
		
		$pago['DoctoRelacionado'][$i]['IdDocumento'] = $dcto_relacionado["uuid"];
		$pago['DoctoRelacionado'][$i]['MonedaDR'] = 'MXN';
		$pago['DoctoRelacionado'][$i]['MetodoDePagoDR'] = $dcto_relacionado["MetodoDePagoDR"];
		$pago['DoctoRelacionado'][$i]['NumParcialidad'] = $_POST["num_parcialidad"];
		
		//Si solo es una factura usar el saldo anterior y cantidad de pago sino tomar el total del saldo pendiente
		// if(count($dctos_relacionados) == 1 || $_POST["saldo_restante"] > 0){
		// $abono = $_POST["abono"];
		// $pago['DoctoRelacionado'][$i]['ImpSaldoAnt']= $_POST["saldo_anterior"];
		// $pago['DoctoRelacionado'][$i]['ImpPagado'] =  $_POST["abono"];
		// $pago['DoctoRelacionado'][$i]['ImpSaldoInsoluto'] = $_POST["saldo_restante"];
		// } 
		// else{
		// $abono = $dcto_relacionado["saldo_actual"];
		// $pago['DoctoRelacionado'][$i]['ImpSaldoAnt']= $dcto_relacionado["ImpSaldoAnt"];
		// $pago['DoctoRelacionado'][$i]['ImpPagado'] = $dcto_relacionado["ImpPagado"];
		// $pago['DoctoRelacionado'][$i]['ImpSaldoInsoluto'] = $dcto_relacionado["ImpSaldoInsoluto"];
		// }
		
		$pago['DoctoRelacionado'][$i]['ImpSaldoAnt']= $dcto_relacionado["ImpSaldoAnt"];
		$pago['DoctoRelacionado'][$i]['ImpPagado'] = $dcto_relacionado["ImpPagado"];
		$pago['DoctoRelacionado'][$i]['ImpSaldoInsoluto'] = $dcto_relacionado["ImpSaldoInsoluto"];
		
		
		$pago['DoctoRelacionado'][$i]['ObjetoImpDR'] = "02";
		$pago['DoctoRelacionado'][$i]["EquivalenciaDR"] = "1";
		
		
		$pago['DoctoRelacionado'][$i]["ImpuestosDR"]["TrasladosDR"][0]["BaseDR"] = $dcto_relacionado["subtotal"];
		$pago['DoctoRelacionado'][$i]["ImpuestosDR"]["TrasladosDR"][0]["ImpuestoDR"] = "002";
		$pago['DoctoRelacionado'][$i]["ImpuestosDR"]["TrasladosDR"][0]["TipoFactorDR"] = $_POST["tipo_factor"];
		
		
		if($_POST["tipo_factor"] == "Tasa"){
			$pago['DoctoRelacionado'][$i]["ImpuestosDR"]["TrasladosDR"][0]["ImporteDR"] = round($dcto_relacionado["subtotal"] * $_POST["tasa_iva"],6);
			$pago['DoctoRelacionado'][$i]["ImpuestosDR"]["TrasladosDR"][0]["TasaOCuotaDR"] = $_POST["tasa_iva"];
		}
		
		if($_POST["tipo_factor"] == "Exento"){
			$total_traslado_exento = $dcto_relacionado["subtotal"];
		}
		
		
		$total_base_iva_16+= $dcto_relacionado["subtotal"];
		$total_traslado_iva_16+= $dcto_relacionado["subtotal"] * $_POST["tasa_iva"];
	}
	
	
	
	$pago['ImpuestosP']["TrasladosP"][0]["BaseP"]= round($total_base_iva_16, 6);
	$pago['ImpuestosP']["TrasladosP"][0]["ImpuestoP"]= "002";
	$pago['ImpuestosP']["TrasladosP"][0]["TipoFactorP"]= $_POST["tipo_factor"];
	
	if($_POST["tipo_factor"] == "Tasa"){
		
		$pago['ImpuestosP']["TrasladosP"][0]["ImporteP"]= round($total_base_iva_16 * $_POST["tasa_iva"],6);
		$pago['ImpuestosP']["TrasladosP"][0]["TasaOCuotaP"]= $_POST["tasa_iva"];
	}
	
	if($_POST["tipo_factor"] == "Tasa"){
		if($_POST["tasa_iva"] == "0.000000"){
			$totales["TotalTrasladosBaseIVA0"]= round($total_base_iva_16,6);
			$totales["TotalTrasladosImpuestoIVA0"]= "0";
		}
		else{
			
			$totales["TotalTrasladosBaseIVA16"]= round($total_base_iva_16,6);
			$totales["TotalTrasladosImpuestoIVA16"]= round($total_traslado_iva_16,2);
			
		}
	}
	if($_POST["tipo_factor"] == "Exento"){
		
		$totales["TotalTrasladosBaseIVAExento"]  = $total_traslado_exento;
	}
	
	
	
	
	
	
	$totales["MontoTotalPagos"]= $_POST["abono"];
	
	$cfdi['Complemento']["Any"][0]["Pago20:Pagos"]["Totales"]= $totales;
	$cfdi['Complemento']["Any"][0]["Pago20:Pagos"]["Version"]= "2.0";
	
	
	
	$cfdi['Complemento']["Any"][0]['Pago20:Pagos']["Pago"][0] = $pago;
	$cfdi['Complemento']["Any"][0]["Pago20:Pagos"]["Pago"][0]['FechaPago']= date($_POST["fecha_pago"].'\TH:i:s', time() - 120);
	$cfdi['Complemento']["Any"][0]["Pago20:Pagos"]["Pago"][0]['FormaDePagoP']= $forma_pago;
	$cfdi['Complemento']["Any"][0]["Pago20:Pagos"]["Pago"][0]['MonedaP']= 'MXN';
	$cfdi['Complemento']["Any"][0]["Pago20:Pagos"]["Pago"][0]["TipoCambioP"]= "1";
	$cfdi['Complemento']["Any"][0]["Pago20:Pagos"]["Pago"][0]['Monto']= $_POST["abono"];
	
	
	
	
	/*
		"ImpuestosP": {
		
	    "BaseP": "10.00",
		"ImpuestoP": "002",
		"TipoFactorP": "Tasa",
		"ImporteP": "1.600000",
		"TasaOCuotaP": "0.160000"
		"TrasladosP": [
		{
		"BaseP": "46.80",
		"ImpuestoP": "002",
		"TipoFactorP": "Exento"
		},
	*/
	
	
	
	
	if(isset($_POST["modo_pruebas"])){
		$url = "https://services.test.sw.com.mx";
		
		$token = $emisor["token_pruebas"];
	}
	else{
		$url = "https://services.sw.com.mx";
		
		$token =  $emisor["token_produccion"];
	}
	$url.= "/v3/cfdi33/issue/json/v4";
	
	$respuesta["url"] = $url;
	$respuesta["cfdi"] = $cfdi;
	
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
	
	
	
	if($respuesta["timbrado"]["status"] == "success"){
		
		$prefix = isset($_POST["modo_pruebas"]) ? "pruebas" : "";
		
		$ruta_timbrados = "../../../../facturacion/facturacion/timbrados/";
		$new_path = "".$prefix."_".$rfc."_".$folio_facturas."_".substr($respuesta["timbrado"]["data"]["uuid"], -5);
		
		
		// rename( $respuesta["timbrado"]["archivo_xml"], "$new_path.xml");
		// rename( $respuesta["timbrado"]["archivo_png"], "$new_path.png"); 
		
		$respuesta["export_xml"] = file_put_contents($ruta_timbrados ."$new_path.xml",$respuesta["timbrado"]["data"]["cfdi"]);
		// $respuesta["export_png"] = file_put_contents($ruta_timbrados ."$new_path.png",base64_decode($respuesta["timbrado"]["data"]["qrCode"]));
		
		
		$insert_facturas =" INSERT INTO facturas SET ";
		$insert_facturas.=" folio_facturas = '". $folio_facturas . "',";
		$insert_facturas.=" id_emisores = '". $id_emisores . "',";
		$insert_facturas.=" fecha_facturas = CURDATE(),";
		$insert_facturas.=" id_clientes = '". $id_clientes . "',";
		$insert_facturas.=" metodo_pago = '". $metodo_pago . "',";
		$insert_facturas.=" forma_pago = '". $forma_pago . "',";
		$insert_facturas.=" lugar_expedicion = '". $lugar_expedicion . "',";
		$insert_facturas.=" subtotal = '". $subtotal . "',";
		$insert_facturas.=" saldo_actual = 0,";
		$insert_facturas.=" total = '". $total . "',";
		$insert_facturas.=" tipo_comprobante = 'P',";
		$insert_facturas.=" uso_cfdi = 'P01',";
		$insert_facturas.=" archivo_xml = 'timbrados/$new_path.xml',";
		$insert_facturas.=" archivo_png = '$new_path.png',";
		$insert_facturas.=" uuid = '{$respuesta["timbrado"]["data"]["uuid"]}',";
		$insert_facturas.=" representacion_impresa_cadena = '{$respuesta["timbrado"]["data"]["cadenaOriginalSAT"]}',";
		$insert_facturas.=" representacion_impresa_certificado_no = '{$respuesta["timbrado"]["data"]["noCertificadoCFDI"]}',";
		$insert_facturas.=" representacion_impresa_fecha_timbrado = '{$respuesta["timbrado"]["data"]["fechaTimbrado"]}',";
		$insert_facturas.=" representacion_impresa_sello = '{$respuesta["timbrado"]["data"]["selloCFDI"]}',";
		$insert_facturas.=" representacion_impresa_selloSAT = '{$respuesta["timbrado"]["data"]["selloSAT"]}',";
		$insert_facturas.=" representacion_impresa_certificadoSAT = '{$respuesta["timbrado"]["data"]["noCertificadoSAT"]}',";
		$insert_facturas.=" serie = '{$emisor["serie_pago"]}',";
		$insert_facturas.=" folio = '{$emisor["folio_pago"]}',";
		$insert_facturas.=" url_pdf = 'timbrados/{$new_path}.pdf',";
		$insert_facturas.=" timbrado = '$timbrado', ";
		$insert_facturas.=" id_usuarios = '{$_COOKIE["id_usuarios"]}', ";
		// $insert_facturas.=" fecha_timbrado =  NOW(), ";
		$insert_facturas.=" qr_code = '{$respuesta["timbrado"]["data"]["qrCode"]}',";
		
		$insert_facturas.=" observaciones = '{$observaciones}'";
		
		$respuesta["result_factura"] = mysqli_query($link, $insert_facturas);
		$respuesta["error"] = mysqli_error($link);
		$respuesta["id_factura_nueva"] = mysqli_insert_id($link);
		
		foreach($dctos_relacionados as $i => $dcto_relacionado){
			
			
			$insert_pagos = "INSERT INTO pagos SET
			id_facturas = '{$respuesta["id_factura_nueva"]}',
			fecha_pago = '{$_POST["fecha_pago"]}',
			moneda_pago = 'MXN',
			forma_pago = '{$_POST["forma_pago"]}',
			num_parcialidad = '{$_POST['num_parcialidad']}',
			saldo_anterior = '{$dcto_relacionado["ImpSaldoAnt"]}',
			importe_pagado ='{$dcto_relacionado["ImpPagado"]}',
			saldo_restante = '{$dcto_relacionado["ImpSaldoInsoluto"]}',
			uuid_dr = '{$dcto_relacionado["uuid"]}',
			metodo_pago_dr = '{$dcto_relacionado["MetodoDePagoDR"]}'
			
			";
			if(mysqli_query($link, $insert_pagos) && $produccion == "SI"){
				
				$respuesta["result_pagos"] = "OK";
				
				
				//Actualiza Folios
				
				
				
			}
			
			else{
				$respuesta["result_pagos"] = mysqli_error($link);
				
			}
		}
		
		//Actualiza Saldo Actual de DR
		if($produccion == "SI"){
			
			foreach($dctos_relacionados as $i => $dcto_relacionado){
				
				$update_saldo = "UPDATE facturas SET saldo_actual = '{$dcto_relacionado["ImpSaldoInsoluto"]}'
				WHERE id_facturas = {$dcto_relacionado["id_facturas"]}";
				
				if(mysqli_query($link, $update_saldo)){
					$respuesta["update_saldo"][] = "OK";
				}
				else{
					$respuesta["update_saldo"][] = mysqli_error($link);	
				}
			}
			
			
			
			
			$update_folios = "UPDATE emisores
			
			SET 
			folio_pago = folio_pago + 1 
			
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
			
			
		}
		
		
		
	}
	
	
	
	echo json_encode($respuesta);
	
	
	
	
?>