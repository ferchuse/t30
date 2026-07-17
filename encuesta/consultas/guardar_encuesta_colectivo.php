<?php
	header("Content-Type: application/json");
	include ("../../taquilla/conexi.php");
	$link = Conectarse();
	$respuesta = Array();
	
	$aspectos_importantes = isset($_POST["aspectos_importantes"]) 
    ? implode(",", $_POST["aspectos_importantes"]) 
    : "";
	
	
	function generarFolioUID($longitud = 8) {
		// Genera un valor aleatorio usando random_bytes y lo convierte en una cadena hexadecimal
		$bytes = random_bytes(6); // Puedes ajustar el tamaño de los bytes si necesitas más o menos caracteres
		$folioUID = substr(bin2hex($bytes), 0, $longitud); // Recorta el UID a la longitud deseada
		
		return strtoupper($folioUID);
	}
	
	$uid = generarFolioUID(5);
	// $uid = strtoupper(uniqid());
	
	$consulta = "INSERT INTO encuestas_colectivo SET 
    nombre = '{$_POST["nombre"]}',
    contacto = '{$_POST["contacto"]}',
    frecuencia = '{$_POST["frecuencia"]}',
    horario_llegada = '{$_POST["horario_llegada"]}',
    origen = '{$_POST["origen"]}',
    origen_otro = '{$_POST["origen_otro"]}',
    destino = '{$_POST["destino"]}',
    destino_otro = '{$_POST["destino_otro"]}',
    transporte = '{$_POST["transporte"]}',
    transporte_otro = '{$_POST["transporte_otro"]}',
    costo_actual = '{$_POST["costo_actual"]}',
    costo_otro = '{$_POST["costo_otro"]}',
    costo_dispuesto = '{$_POST["costo_dispuesto"]}',
    costo_dispuesto_otro = '{$_POST["costo_dispuesto_otro"]}',
    horario_servicio = '{$_POST["horario_servicio"]}',
    horario_servicio_otro = '{$_POST["horario_servicio_otro"]}',
    tiempo_viaje = '{$_POST["tiempo_viaje"]}',
    aspectos_importantes = '{$aspectos_importantes}',
    aspectos_importantes_otro = '{$_POST["aspectos_importantes_otro"]}',
    reservacion = '{$_POST["reservacion"]}',
    comentarios = '{$_POST["comentarios"]}',
    uid  = '{$uid}',
    fecha = NOW()";
	
	
	$result = mysqli_query($link, $consulta);
	
	
	
	if($result){
		$respuesta["estatus"] = "success";
		$respuesta["uid"] = $uid;
		$respuesta["mensaje"] = "Guardado";
		
		
	}	
	else{
		$respuesta["estatus"] = "error";
		$respuesta["error"] = "Error $consulta  ".mysqli_error($link);		
	}
	
	echo json_encode($respuesta);
?>