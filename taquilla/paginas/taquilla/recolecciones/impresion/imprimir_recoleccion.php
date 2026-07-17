<?php
	
	include('../../../../conexi.php');
	include('../../../../lib/numero_a_letras.php');
	include('../../../../funciones/normalize_chars.php');
	
	$link = Conectarse();
	
	
	$consulta = "SELECT * FROM recolecciones
	
	LEFT JOIN usuarios USING (id_usuarios)
	LEFT JOIN empresas ON usuarios.empresa_asignada = empresas.id_empresas

	WHERE id_recoleccion={$_GET["folio"]} ";
	
	$result = mysqli_query($link, $consulta) or die(mysqli_Error($link));
	
	while ($row = mysqli_fetch_assoc($result)) {
		$fila_venta = $row;
	}
	
	
	
	
	$respuesta = "";
	
	$respuesta.=   "\x1b"."@";
	$respuesta.= "\x1b"."E".chr(1); // Bold
	$respuesta.= "!";
	$respuesta.=  normalizeChars($fila_venta["tipo_recoleccion"])."\n\n";
	$respuesta.=  "\x1b"."E".chr(0); // Not Bold	
	$respuesta.=  "\x1b"."@";
	$respuesta.= $fila_venta["nombre_empresas"]. "\n\n";
	// $respuesta.= "Whatsapp: 56 14 61 17 66 \n\n";
	
	
	$respuesta.= "Folio:          ". $fila_venta["id_recoleccion"]. "\n";
	$respuesta.= "Fecha captura:  " . date("d/m/Y H:i:s", strtotime($fila_venta["fecha_captura"]))."\n";
	
	$respuesta.= "Pasajeros:      " .$fila_venta["pasajeros"]."\n";
	$respuesta.= "Nombre:         " .strtoupper(normalizeChars($fila_venta["nombre_pasajero"]))."\n";
	$respuesta.= "Telefono:       " .$fila_venta["celular"]."\n";
	$respuesta.= "Atendido Por:   " . $fila_venta["nombre_usuarios"]."\n\n";
	
	$respuesta.=  "\x1b"."E".chr(1);
	
	$respuesta.= "Lugar:     ". $fila_venta["destino"]. "\n";
	$respuesta.= "Fecha:     ". date("d/m/Y", strtotime($fila_venta["fecha_recoleccion"]))."\n";
	$respuesta.= "Hora:      ".  date("H:i", strtotime($fila_venta["fecha_recoleccion"]))."\n\n";
	$respuesta.=  "\x1b"."E".chr(0);
	
	$respuesta.= "Forma de Pago:    ". $fila_venta["forma_pago"]. "\n";
	$respuesta.="Total:             $" .str_pad(number_format($fila_venta["total"],2),10," ",STR_PAD_LEFT)."\n";
	$respuesta.="Anticipo:          $" .str_pad(number_format($fila_venta["anticipo"],2),10," ",STR_PAD_LEFT)."\n";
	$respuesta.="Restante:          $" .str_pad(number_format($fila_venta["restante"],2),10," ",STR_PAD_LEFT)."\n\n";
	
	$importe_letra = str_split("(".NumeroALetras::convertir($fila_venta["total"], "pesos", "centavos")." 00/100 M.N.)\n\n", 40);
	$respuesta.= $importe_letra[0]."\n";
	
	if(count($importe_letra) > 1){
		$respuesta.= $importe_letra[1]."\n";
		
	}
	
	
	
	
	
	if(isset($_GET["reimpresion"])){
		
		$respuesta.= "\nREIMPRESION  " .date("d/m/Y H:i:s")."\n";
		$respuesta.= "Usuario: " .$_COOKIE["nombre_usuarios"]."\n";
	}
	
	
	$respuesta.= "VA"; // Cut
	
	$respuesta_sin_acentos = normalizeChars($respuesta);
	
	echo base64_encode($respuesta_sin_acentos);
	
	;
	
?>

