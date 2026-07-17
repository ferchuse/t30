<?php
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header("Content-Type: application/json");
	// error_reporting(E_ERROR);   
	date_default_timezone_set('America/Mexico_City');
	
	
	
	/*
	201

Solicitud de cancelación exitosa	Se considera una solicitud de cancelación exitosa, sin embargo esto no asegura su cancelación
202

Folio Fiscal Previamente Cancelado	Se considera solicitud de cancelación previamente enviada. Estatus Cancelado ante el SAT.
203

Folio Fiscal No Correspondiente al Emisor	 
204

Folio Fiscal No Aplicable a Cancelación	 
205

Folio Fiscal No Existente	El SAT da una prorroga de 48 hrs para que el comprobante aparezca con estatus Vigente posterior al envió por parte del Proveedor de Certificación de CFDI. Puede que algunos comprobantes no aparezcan al momento, es necesario esperar por lo menos 48 hrs.
206

UUID no corresponde a un CFDI del Sector Primario	 
207

No se especificó el motivo de cancelación o el motivo no es valido	El UUID sustitución no existe, está cancelado o tiene una fecha de emisión anterior a la fecha de emisión del comprobante original.
208

Folio Sustitución invalido	 
209

Folio Sustitución no requerido	 
210

La fecha de solicitud de cancelación es mayor a la fecha de declaración	.
211

La fecha de solicitud de cancelación límite para factura global	 
212

Relación no valida o inexistente	 
300

Usuario No Válido	 
301

XML Mal Formado	Este código de error se regresa cuando el request posee información invalida, ejemplo: un RFC de receptor no válido.
302

Sello Mal Formado	 
304

Certificado Revocado o Caduco	El certificado puede ser inválido por múltiples razones como son el tipo, la vigencia, etc.
305

Certificado Inválido	El certificado puede ser inválido por múltiples razones como son el tipo, la vigencia, etc.
309

Certificado Inválido	El certificado puede ser inválido por múltiples razones como son el tipo, la vigencia, etc.
310

CSD Inválido	 
311

Motivo inválido	Clave de motivo de cancelación no válida
312

UUID no relacionado	UUID no relacionado de acuerdo a la clave de motivo de cancelación
	*/
	
	
	
	include("../../../conexi.php");
	// include "sdk2.php";
	
	$link = Conectarse();
	$respuesta = array();
	$docs_relacionados = array();
	
	function getEmisor($link,$id_emisores ){
		$respuesta = "";
		$query = "SELECT * FROM emisores 
		WHERE id_emisores = '$id_emisores'
		";
		
		$result = mysqli_query($link,$query) ;
		
		if(!$result){
			return "<option value=''>Ocurrio un error".mysqli_error($link)."</option>"; 
		}
		else{
			while($fila = mysqli_fetch_assoc($result)){
				$respuesta = $fila;
			}
		}
		return $respuesta; 
	}
	
	$emisor = getEmisor($link,1);
	
	$rfc = $emisor["rfc_emisores"];
	
	$uuid = strtoupper($_POST["uuid"]);
	
	
	if(isset($_POST["modo_pruebas"])){
		$url = "https://services.test.sw.com.mx/cfdi33/cancel/{$rfc}/{$_POST["uuid"]}/{$_POST["motivo_cancelacion"]}/{$_POST["folio_sustituye"]}";
		
		
		
		$token ="T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGQrNzJ3UWh4TVVxb0g3TU5KV0Q2Um5rb2VpQlZibFk2b3JLeURxQmU5TGhudldsdjExeGpvaDBEQVZYWUhWTE5nKzh5MENnVm9MRjNwRE5MU0xuOWtRdTNGMktEajgrSlVtcVNPbWpLSE9hajJCZC9zOFBEOVp3VG9BbFRaMkFsSHl4ZkoxSWlQYnRERi9kTCtaMkhWeHROSmlUemxHbEhHbDBIMEdueTh0ZmtSOHUwMVNaempVNnlDNTRLRzhxNmU5VlpIdlhJVDMyZ2V2aDVvQzNjRW1YUFVJeXdHcmdvUmhBdVhCS0xyYi9aSkIxSVIwUXljZjR5UWN1QlRuazNsKzgvSzRXYlZEeXBDTVhwSmtqNSt3dXVjL1B6N0FRMWk1QXRDa3FVTjNSOXlFbjI0Z24yUkJHN2tTUU5RTzFZT25WelJKMDlidWt0T2c1WU55dDMwZUt3ZTRocGlXcGd5YXFyc05oUytFRHpJOXZ0YjhQb0tBN2g4eXY0dC80SEc.nt6tdBAWFLIqWWXAO3bkfPBXN19lUcjA1HZgfdavATo";
	}
	else{
		$url = "https://services.sw.com.mx/cfdi33/cancel/{$rfc}/{$uuid}/{$_POST["motivo_cancelacion"]}/{$_POST["folio_sustituye"]}";
		
		$token = "T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGQ1MmJPVW1nQ2J3NDk0Tys0ZmorOUlkNFdzdVZXV2pMa21pZHBLeVNYUmt0WTNscnVKQytsU0ZpUkFGZXNBYnBJMDMreDdKd3VXcS82NTh3cFlzb0UzWWZka1hXZG9EMWJFcnFwQU1PN3FyU0w2UjBiM3B5Qm9MNG01cVRtaHFLM0FoUEhUZXhHQUxGYWFJNVMrcXhsQTdlek82QVg1RVVtWUQ1K0JtWTM5YzE0Y2h3VTdVUkh4cC8yZUluc1YwbzBRc2tQT0o3US9IK0FLM3NWdnhuSEptY0RCOTg5dDQvZUp3d25ienVtN0hnNHlxZ2RreG5mRThkMmU2aVUrelNUS0ptc0FDR3U5bkJUVGhOa3VJV3J2RGJUcXJyeGkrVGpYSndJcy9HYUxqaE9mUVdUSFNjeWtIZXFnOURzL2N0a0hzWWdjbFRmQVlPSlFyekx1akZ1dVNMVkI0Q1FDVHQyVG81L2J0bWxMUjU2MlVQWWdCcUZKZTl6SDlFcU82TWtzaTUwZFJJbnYzcjRuNTNOcHFQQXNBPT0.kBCEI7THLmiwbA2gWJfrlsyMT-Fi7_5nIuVoa51be2s";
	}
	
	//EKU9003173C9/f7392818-aca8-44b0-9942-120d862a71ea/02 
	// $url.= "/v3/cfdi33/issue/json/v4";
	
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
	// CURLOPT_POSTFIELDS => json_encode($cfdi),
	CURLOPT_HTTPHEADER => array(
	"Authorization: bearer ".$token
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
	
	
	
	
	if($respuesta["timbrado"]["status"] == "success" && $respuesta["timbrado"]["data"]["uuid"][$uuid] == "201"){ 
		
		// $mensaje_original_pac_json =  json_decode($respuesta["respuesta_pac"]["mensaje_original_pac_json"] , true);
		
		// $acuse = $mensaje_original_pac_json["CancelarCSDResult"];
		
		$fecha_cancelacion = date("Y-m-d H:i:s");
		
		$datos_cancelacion = "Usuario: {$_COOKIE["nombre_usuarios"]} <br> Fecha: {$fecha_cancelacion} ,<br> Motivo: {$_POST["motivo_cancelacion"]}, ,<br>Folio Sutituye: {$_POST["folio_sustituye"]}";
		
		
		
		// Actualizar estatus de Factura a CANCELADO
		$update_factura	= "UPDATE facturas SET 
		cancelada = 1, 
		motivo_cancelacion = '{$datos_cancelacion}' 
		WHERE id_facturas = '".$_POST["id_facturas"]."'";
		
		if(mysqli_query($link, $update_factura)){
			$respuesta["update_factura"]["estatus"]  = "success";
			$respuesta["update_factura"]["mensaje"]  = "CFDI CANCELADO CORRECTAMENTE";
			$respuesta["update_factura"]["query"]  = $update_factura;
		}
		else{
			$respuesta["update_factura"]["estatus"]  = "error";
			$respuesta["update_factura"]["mensaje"]  = mysqli_error($link);
			
		}
		
		//SELECCIONA los pagos con el id factura, 
		
		$consulta_dr = "
		SELECT * FROM pagos
		
		LEFT JOIN facturas ON pagos.uuid_dr = uuid
		WHERE pagos.id_facturas = '{$_POST["id_facturas"]}'";
		
		
		$result_dr = mysqli_query($link, $consulta_dr);
		
		while($row = mysqli_fetch_assoc($result_dr)){
			$docs_relacionados[] = $row;
		}
		
		$respuesta["consulta_dr"]  = $consulta_dr;
		
		//por cada pago sumar el saldo de lafactura del documento relacionado con el monto del pago
		
		foreach($docs_relacionados as $i => $doc_relacionado){
			
			$update_docs_relacionados	= "UPDATE facturas SET 
			saldo_actual = saldo_actual + {$doc_relacionado["importe_pagado"]} 
			WHERE uuid = '{$doc_relacionado["uuid"]}' 
			
			
			";
			
			if(mysqli_query($link, $update_docs_relacionados)){
				$respuesta["docs_relacionados"]["estatus"][]  = "success";
				$respuesta["docs_relacionados"]["mensaje"][]  = "PAGO CANCELADO CORRECTAMENTE";
				$respuesta["docs_relacionados"]["query"][]  = $update_docs_relacionados;
			}
			else{
				$respuesta["docs_relacionados"]["estatus"][]  = "error";
				$respuesta["docs_relacionados"]["mensaje"][]  = mysqli_error($link);
				
			}
		}
		
		
		
		
		// if(!file_put_contents("acuses/".$_POST["folio_facturas"].'.xml',$acuse )){
		// $respuesta["acuse"]["estatus"]  = "success";
		// $respuesta["acuse"]["mensaje"]  = "Acuse Creado Correctamente";
		// $respuesta["acuse"]["ruta"]  = "acuses/".$_POST["folio_facturas"].'.xml';
		// }
		// else{
		// $respuesta["acuse"]["estatus"]  = "error";
		// }
	}
	
	
	
	
	echo json_encode($respuesta);
	
	
?>
