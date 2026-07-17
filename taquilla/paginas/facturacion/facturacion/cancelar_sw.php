<?php
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	header("Content-Type: application/json");
	error_reporting(E_ERROR);   
	date_default_timezone_set('America/Mexico_City');
	
	include("../../../conexi.php");
	include "sdk2.php";
	
	$link = Conectarse();
	$respuesta = array();
	
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
	
	$emisor = getEmisor($link, $_POST["id_emisores"]);
	
	$rfc = $emisor["rfc_emisores"];
	
	// $datos['cancelar']='SI';
	// $datos['PAC']['usuario'] = $rfc;
	// $datos['PAC']['pass'] =  $emisor["password"];
	
	// $datos['accion']="cancelar";   
	// $datos["produccion"]="SI"; 
	
	// $datos['modulo']="cancelacion2022"; 
	// $datos["motivo"]=  $_POST["motivo_cancelacion"];
	// $datos["folioSustitucion"]=$_POST["folio_sustituye"];
	
	// $datos["uuid"] = $_POST["uuid"];
	// $datos["rfc"] = $rfc;
	// $datos["password"] =  $emisor["password"];
	// $datos["b64Cer"] = "certificados/$rfc.cer";
	// $datos["b64Key"] = "certificados/$rfc.key";
	
	// $respuesta["datos"]= $datos;
	
	
	
	
	if(isset($_POST["modo_pruebas"])){
		$url = "https://services.test.sw.com.mx/cfdi33/cancel/{$rfc}/{$_POST["uuid"]}/{$_POST["motivo_cancelacion"]}/{$_POST["folio_sustituye"]}";
		
		
		
		$token ="T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGQrNzJ3UWh4TVVxb0g3TU5KV0Q2Um5rb2VpQlZibFk2b3JLeURxQmU5TGhudldsdjExeGpvaDBEQVZYWUhWTE5nKzh5MENnVm9MRjNwRE5MU0xuOWtRdTNGMktEajgrSlVtcVNPbWpLSE9hajJCZC9zOFBEOVp3VG9BbFRaMkFsSHl4ZkoxSWlQYnRERi9kTCtaMkhWeHROSmlUemxHbEhHbDBIMEdueTh0ZmtSOHUwMVNaempVNnlDNTRLRzhxNmU5VlpIdlhJVDMyZ2V2aDVvQzNjRW1YUFVJeXdHcmdvUmhBdVhCS0xyYi9hYjc5Mm40RE1GRUc1MGRkcTg2S0dGSUhVMkhKek5GUTZWRTZpWmlrWG5uZnFLUis1RUZCVmlONjM5YXlXRWRuQjdOK1dTMExnQ2pyWTRwTmdUeW1lRkFLL3UwUFh1Rk9xcytPMlZaN2dLUjNCNEo5aWpGZWFPUnJBQmh2QVhrZVpNa0g2TFZialZvOURwbEdocmgvVXA.SG1wPsd0gqgJFXQXZivd_o86L1E0ERiWpTlaE-yvkgQ";
	}
	else{
		$url = "https://services.sw.com.mx/cfdi33/cancel/{$rfc}/{$_POST["uuid"]}/{$_POST["motivo_cancelacion"]}/{$_POST["folio_sustituye"]}";
		
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
	CURLOPT_POSTFIELDS => json_encode($cfdi),
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
	
	
	
	
	if($respuesta["timbrado"]["status"] == "success"){ 
		
		// $mensaje_original_pac_json =  json_decode($respuesta["respuesta_pac"]["mensaje_original_pac_json"] , true);
		
		// $acuse = $mensaje_original_pac_json["CancelarCSDResult"];
		
		// Actualizar estatus de Factura a CANCELADO
		$update_factura	= "UPDATE facturas SET 
		cancelada = 1, 
		motivo_cancelacion = '".$_POST["motivo_cancelacion"]."' 
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
		
		
		$update_boletos	= "UPDATE boletos SET 
		id_facturas = NULL
		WHERE id_facturas = '{$_POST["id_facturas"]}'";
		
		if(mysqli_query($link, $update_boletos)){
			$respuesta["update_boletos"]["estatus"]  = "success";
		}
		else{
			$respuesta["update_boletos"]["error"]  = mysqli_error($link);
			$respuesta["update_boletos"]["consulta"]  = $update_boletos;
			
		}
		
		
	}
	
	
	
	
	echo json_encode($respuesta);
	
	// switch (json_last_error()) {
	
	
	/*
		
		$datos['PAC']['usuario'] = "DEMO700101XXX";
		$datos['PAC']['pass'] = "DEMO700101XXX";
		$datos['modulo']="cancelacion2022"; 
		$datos['accion']="cancelar";                                                  
		$datos["produccion"]="NO"; 
		//$datos["xml"]="../../timbrados/cfdi_ejemplo_factura.xml";
		$datos["uuid"]="e95c803b-47da-433d-aafd-0cf90f3df1d6";
		$datos["rfc"] ="EKU9003173C9";
		$datos["password"]="12345678a";
		$datos["motivo"]="02";
		//$datos["folioSustitucion"]="";
		$datos["b64Cer"]="../../certificados/EKU9003173C9.cer";
		$datos["b64Key"]="../../certificados/EKU9003173C9.key";
		echo "<pre>";
		print_r($datos);
		echo "</pre>";
		$res = mf_ejecuta_modulo($datos);
		echo "<pre>";
		print_r($res);
		
		
	*/
	
	
	
	
	
	
	
	
	
	
	// case JSON_ERROR_NONE:
	// echo ' - No errors';
	// break;
	// case JSON_ERROR_DEPTH:
	// echo ' - Maximum stack depth exceeded';
	// break;
	// case JSON_ERROR_STATE_MISMATCH:
	// echo ' - Underflow or the modes mismatch';
	// break;
	// case JSON_ERROR_CTRL_CHAR:
	// echo ' - Unexpected control character found';
	// break;
	// case JSON_ERROR_SYNTAX:
	// echo ' - Syntax error, malformed JSON';
	// break;
	// case JSON_ERROR_UTF8:
	// echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
	// break;
	// default:
	// echo ' - Unknown error';
	// break;
    // }
	
?>
