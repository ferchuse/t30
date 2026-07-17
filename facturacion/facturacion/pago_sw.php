<?php
	// Se desactivan los mensajes de debug
	error_reporting(0);
	session_start();
	date_default_timezone_set('America/Mexico_City');
	include_once("../../conexi.php");
	require_once 'sdk2.php';
	
	
	$link = Conectarse();
	$respuesta = array();
	$datos = array();
	$conceptos = array();
	
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
	$razon_social_emisores=  $emisor["razon_social_emisores"];
	$regimen_emisores= $emisor["regimen_emisores"];
	
	
	//datos de la factura anterior
	$consulta_factura = "SELECT * FROM facturas
	
	LEFT JOIN remitentes ON remitentes.id_rem = facturas.id_clientes
	
	WHERE id_facturas IN ($id_facturas)";
	$respuesta["consulta_factura"] = $consulta_factura;
	$result_factura = mysqli_query($link, $consulta_factura);
	
	
	if($result_factura){
		
		while($fila_factura = mysqli_fetch_assoc($result_factura)){
			
			$dctos_relacionados[] = $fila_factura;
			$id_clientes = $fila_factura["id_rem"];
			$rfc_clientes =  $fila_factura["rfc_rem"];
			$razon_social_clientes =  $fila_factura["empresa_rem"];
			
			$uuid_dr =$fila_factura["uuid"];
			$metodo_pago_dr =$fila_factura["metodo_pago"];
			
			$lugar_expedicion = $fila_factura["lugar_expedicion"];
			
		}
		
	}
	
	$respuesta["dctos_relacionados"] = $dctos_relacionados;
	
	//datos del pago
	$saldo_anterior =  $_POST["saldo_anterior"];
	$abono =  $_POST["abono"];
	$saldo_restante = $_POST["saldo_restante"];
	$forma_pago = $_POST["forma_pago"];
	
	$observaciones = $_POST["observaciones"];
	$produccion = isset($_POST["modo_pruebas"])? "NO" : "SI";
	$timbrado = isset($_POST["modo_pruebas"])? 0 : 1;
	
	$datos['Version'] = '3.3';
	
	// SE ESPECIFICA EL COMPLEMENTO
	$datos['complemento'] = 'pagos10';
	$datos['validacion_local'] = 'NO';
	$datos['cfdi']='timbrados/'.$rfc."_".$folio_facturas.'.xml';
	
	$datos['xml_debug']='timbrados/sin_timbrar'.$rfc."_".$folio_facturas.'.xml';
	
	$datos['PAC']['usuario'] = $rfc;
	$datos['PAC']['pass'] = $pass_timbrado;
	$datos['PAC']['produccion'] = $produccion;
	
	// Rutas y clave de los CSD
	$datos['conf']['cer'] = "certificados/$rfc.cer.pem";
	$datos['conf']['key'] = "certificados/$rfc.key.pem";
	$datos['conf']['pass'] = $pass_timbrado;
	
	
	// Datos del Emisor
	$datos['emisor']['rfc'] = $rfc_emisores; 
	$datos['emisor']['nombre'] = $razon_social_emisores;  
	$datos['factura']['RegimenFiscal'] = $regimen_emisores;
	
	// Datos del Receptor
	$datos['receptor']['rfc'] = $rfc_clientes;
	$datos['receptor']['nombre'] = $razon_social_clientes;
	$datos['receptor']['UsoCFDI'] = 'P01'; 
	
	
	// Datos de la Factura
	
	// Datos de la Factura
	$datos['factura']['fecha_expedicion'] = date('Y-m-d\TH:i:s', time() - 120);
	$datos['factura']['serie'] = $serie;
	$datos['factura']['folio'] = $folio;	
	$datos['factura']['moneda'] = 'XXX';
	$datos['factura']['subtotal'] = '0';
	$datos['factura']['total'] = '0';
	$datos['factura']['LugarExpedicion'] = $lugar_expedicion; 
	$datos['factura']['tipocomprobante'] = 'P';
	
	
	// Se agregan los conceptos
	//$datos['conceptos'][0]['unidad'] = 'ACT';
	$datos['conceptos'][0]['cantidad'] = '1';
	$datos['conceptos'][0]['ClaveProdServ'] = '84111506';
	$datos['conceptos'][0]['ClaveUnidad'] = 'ACT';
	$datos['conceptos'][0]['descripcion'] = "Pago";
	$datos['conceptos'][0]['valorunitario'] = '0.0';
	$datos['conceptos'][0]['importe'] = '0.0';
	
	$pago= array();
	
	
	
	// Complemento de Pagos 1.0
	foreach($dctos_relacionados as $i => $dcto_relacionado){
		
		$pago['DoctoRelacionado'][$i]['IdDocumento'] = $dcto_relacionado["uuid"];
		$pago['DoctoRelacionado'][$i]['MonedaDR'] = 'MXN';
		$pago['DoctoRelacionado'][$i]['MetodoDePagoDR'] =  $dcto_relacionado["metodo_pago"];
		$pago['DoctoRelacionado'][$i]['NumParcialidad'] = $_POST["num_parcialidad"];
		$pago['DoctoRelacionado'][$i]['ImpSaldoAnt']= $dcto_relacionado["saldo_actual"];
		$pago['DoctoRelacionado'][$i]['ImpPagado'] = $dcto_relacionado["saldo_actual"];
		$pago['DoctoRelacionado'][$i]['ImpSaldoInsoluto'] = $saldo_restante;
		$pago['FechaPago']= date($_POST["fecha_pago"].'\TH:i:s', time() - 120);
		$pago['FormaDePagoP']= $forma_pago;
		$pago['MonedaP']= 'MXN';
		$pago['Monto']= $abono;
		
	}
	
	$cfdi['Complemento']["Any"][0]['Pago10:Pagos']["Pago"][0] = $pago;
	
	 
	
	// Complemento de Pagos 1.0
	// $datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['IdDocumento'] = '970e4f32-0fe0-11e7-93ae-92361f002671';
	// $datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['MonedaDR'] = 'MXN';
	// $datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['MetodoDePagoDR'] = 'PPD';
	// $datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['NumParcialidad'] = '1';
	// $datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['ImpSaldoAnt']= '10000';
	// $datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['ImpPagado'] = '5000';
	// $datos['pagos10']['Pagos'][0]['DoctoRelacionado'][0]['ImpSaldoInsoluto'] = '5000';
	// $datos['pagos10']['Pagos'][0]['FechaPago']= date('Y-m-d\TH:i:s', time() - 120);
	// $datos['pagos10']['Pagos'][0]['FormaDePagoP']= '06';
	// $datos['pagos10']['Pagos'][0]['MonedaP']= 'MXN';
	// $datos['pagos10']['Pagos'][0]['Monto']= '10000';
	
	
	//$datos['pagos10']['Pagos'][0]['NumOperacion']= '0.0';
	// $datos['pagos10']['Pagos'][0]['RfcEmisorCtaOrd']= 'XAXX010101000';
	// $datos['pagos10']['Pagos'][0]['NomBancoOrdExt']= '0.0';
	// $datos['pagos10']['Pagos'][0]['CtaOrdenante']= '1234567890';
	//$datos['pagos10']['Pagos'][0]['RfcEmisorCtaBen']= '0.0';
	//$datos['pagos10']['Pagos'][0]['CtaBeneficiario']= '0.0';
	//$datos['pagos10']['Pagos'][0]['TipoCadPago']= '0.0';
	//$datos['pagos10']['Pagos'][0]['CertPago']= '0.0';
	//$datos['pagos10']['Pagos'][0]['CadPago']= '0.0';
	//$datos['pagos10']['Pagos'][0]['SelloPago']= '0.0';
	
	
	
	// Se ejecuta el SDK
	$respuesta["datos"]= $datos;
	
	
	
		
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	if($respuesta["timbrado"]["codigo_mf_numero"] == 0 ){
		
		//actualizar saldo de factura
		
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
		$insert_facturas.=" archivo_xml = '". $respuesta["timbrado"]["archivo_xml"] . "',";
		$insert_facturas.=" archivo_png = '". $respuesta["timbrado"]["archivo_png"] . "',";
		$insert_facturas.=" uuid = '". $respuesta["timbrado"]["uuid"] . "',";
		$insert_facturas.=" representacion_impresa_cadena = '". $respuesta["timbrado"]["representacion_impresa_cadena"] . "',";
		$insert_facturas.=" representacion_impresa_certificado_no = '". $respuesta["timbrado"]["representacion_impresa_certificado_no"] . "',";	
		$insert_facturas.=" representacion_impresa_fecha_timbrado = '". $respuesta["timbrado"]["representacion_impresa_fecha_timbrado"] . "',";$insert_facturas.=" representacion_impresa_sello = '". $respuesta["timbrado"]["representacion_impresa_sello"] . "',";
		$insert_facturas.=" representacion_impresa_selloSAT = '". $respuesta["timbrado"]["representacion_impresa_selloSAT"] . "',";
		$insert_facturas.=" representacion_impresa_certificadoSAT = '". $respuesta["timbrado"]["representacion_impresa_certificadoSAT"] . "',";
		$insert_facturas.=" url_pdf = '". 'timbrados/'.$rfc."_".$folio_facturas.'.pdf' . "',";
		$insert_facturas.=" timbrado = '$timbrado', ";
		$insert_facturas.=" observaciones = '$observaciones'";
		
		$respuesta["result_factura"] = mysqli_query($link, $insert_facturas);
		$respuesta["id_factura_nueva"] = mysqli_insert_id($link);
		
		foreach($dctos_relacionados as $i => $dcto_relacionado){
			
			
			$insert_pagos = "INSERT INTO pagos SET
			id_facturas = '{$respuesta["id_factura_nueva"]}',
			fecha_pago = '{$_POST["fecha_pago"]}',
			moneda_pago = 'MXN',
			importe_pagado = '{$dcto_relacionado["saldo_actual"]}',
			forma_pago = '$forma_pago',
			num_parcialidad = '{$_POST['num_parcialidad']}',
			saldo_anterior = '{$dcto_relacionado["saldo_actual"]}',
			saldo_restante = '$saldo_restante',
			uuid_dr = '{$dcto_relacionado["uuid"]}',
			metodo_pago_dr = '$metodo_pago_dr'
			
			";
			if(mysqli_query($link, $insert_pagos)){
				
				$respuesta["result_pagos"] = "OK";
				
				
				//Actualiza Folios
				if($folio_facturas != ""){
					$folio_facturas++;
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
					$respuesta["folio_facturas"]  = $folio_facturas;
					
				}
				
				
			}
			
			else{
				$respuesta["result_pagos"] = mysqli_error($link);
				
			}
		}
		
		//Actualiza Saldo Actual de DR
		if($produccion == "SI"){
			
			$update_saldo = "UPDATE facturas SET saldo_actual = $saldo_restante WHERE id_facturas IN ($id_facturas)";
			
			if(mysqli_query($link, $update_saldo)){
				$respuesta["update_saldo"] = "OK";
			}
			else{
				$respuesta["update_saldo"] = mysqli_error($link);	
			}
		}
		
		
		
	}
	
	
	
	echo json_encode($respuesta);
	
	
	
	
?>