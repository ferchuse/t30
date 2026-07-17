<?php 
	
	//create cron job
	//domains/amsanrod.com/public_html/taquilla/funciones/notificar_recoleccion.php
	//https://amsanrod.com/taquilla/paginas/taquilla/recolecciones/consultas/notificar_recoleccion.php
	//curl -s https://amsanrod.com/taquilla/paginas/taquilla/recolecciones/consultas/notificar_recoleccion.php
	
	header("Content-Type: application/json");
	include('../../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	$recolecciones = array();
	
	
	$consulta = "SELECT * FROM recolecciones
	
	
	WHERE 
	estatus_recoleccion = 'PENDIENTE' 
	AND fecha_recoleccion BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 3 HOUR);";
	
	
	$result = mysqli_query($link, $consulta);
	
	while($row = mysqli_fetch_assoc($result)){
		$recolecciones[] = $row;
	}
	
	if($result){
		
		$respuesta["estatus"] = "success";
		$respuesta["mensaje"] = "Guardado";
		
	}
	else{
		$respuesta["estatus"] = "error";
		$respuesta["mensaje"] = "Error en $consulta ".mysqli_error($link);		
	}
	
	
	
	if(empty($recolecciones)){
		echo "No hay recolecciones";
		exit();
	}
	
	//enviar notificacion 
	
	
	$consulta_apikey = "SELECT * FROM api_keys WHERE  tipo = 'pushalert'";
	
	$result_apikey = mysqli_query($link, $consulta_apikey);
	if($result_apikey){
		
		while($fila = mysqli_fetch_assoc($result_apikey)){
			$api_key = $fila["api_key"];
			$row_api_key = $fila;
		}
	}
	else{
		
		$respuesta["api_key"]["status"] = "error";
		$respuesta["api_key"]["mensaje"] = mysqli_error($link);
	}
	
	$respuesta["api_key"] = $api_key;
	
	$action1_url = "https://taxidriveraifa.com/taquilla/paginas/taquilla/recolecciones.php";
	
	
	foreach($recolecciones AS $recoleccion){
		
		$title = "Próxima Recolección ";
		$message = "{$recoleccion["nombre_pasajero"]} \n {$recoleccion["destino"]} \n".date("H:i", strtotime($recoleccion['fecha_recoleccion']));
		$icon = "https://taxidriveraifa.com/taquilla/img/logo.jpg";
		$url = $action1_url;
		$action1 = '{"title":"Ver Detalle", "url":"'.$action1_url.'"} ';
		
		
		/*
			"action1" => ["title" => "Autorizar" , "url" => "https://quijote.com.mx/corte/resumen.php?param1=value1"],
			"action2" => ["title" => "Negar" , "url" => "https://quijote.com.mx/corte/resumen.php?param1=value1"]
			
		*/
		
		$apiKey = $api_key ;
		
		$segment_id =  $row_api_key["secret"];
		
		$curlUrl = "https://api.pushalert.co/rest/v1/segment/{$segment_id}/send"; //place your segment id instead of 999
		
		//POST variables
		$post_vars = array(
		"icon" => $icon,
		"title" => $title,
		"message" => $message,
		"url" => $url,
		"action1" => $action1,
		// "action2" => $action2
		
		);
		
		$headers = Array();
		$headers[] = "Authorization: api_key=".$apiKey;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $curlUrl);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_vars));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		$result = curl_exec($ch);
		
		$output = json_decode($result, true);
		if($output["success"]) {
			$respuesta["id_notification"] = $output["id"]; //Sent Notification ID
		}
		else {
			//Others like bad request
			
			
		}
		
		
		
		$respuesta["push_alert_raw"]= $result;
		$respuesta["push_alert"]= $output;
		$respuesta["recoleccion"]= $recoleccion;
		
	}
	
	
	echo json_encode($respuesta);
	
?>	