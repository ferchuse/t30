<?php
	
	include('../../../../conexi.php');
	include('../../../../lib/numero_a_letras.php');
	include('../../../../funciones/normalize_chars.php');
	
	$link = Conectarse();
	
	$esc = "\x1B";
	$comando_fuenteA = "\x1B\x4D\x00";
	$comando_fuenteB = "\x1B\x4D\x01";
	$comando_fuenteC = "\x1B\x4D\x02";
	
	$leftAlign = $esc . "a" . chr(0);
	$centerAlign = $esc . "a" . chr(1);
	$rightAlign = $esc . "a" . chr(2);
	
	$consulta = "SELECT *, 
	recolecciones.forma_pago AS recolecciones_forma_pago ,
	boletos.forma_pago AS boletos_forma_pago , 
	boletos.origen AS boletos_origen ,
	boletos.destino AS boletos_destino 
	
	FROM boletos
	
	LEFT JOIN usuarios USING (id_usuarios)
	LEFT JOIN unidades USING (num_eco)
	LEFT JOIN empresas USING (id_empresas)
	LEFT JOIN conductores USING (id_conductores)
	LEFT JOIN recolecciones USING (id_boletos)
	WHERE id_boletos={$_GET["folio"]} ";
	
	$result = mysqli_query($link, $consulta) or die(mysqli_Error($link));
	
	while ($row = mysqli_fetch_assoc($result)) {
		$fila_venta = $row;
	}
	
	
	
	
	$respuesta = "";
	
	
	$respuesta.=  "\x1b"."@";
	$respuesta.= $fila_venta["nombre_empresas"]. "\n";
	// $respuesta.= "Whatsapp: 56 14 61 17 66 \n\n";
	$respuesta.= "Folio:        ". $fila_venta["id_boletos"]. "\n";
	$respuesta.= "Fecha:        " . date("d/m/Y", strtotime($fila_venta["fecha_boletos"]))."\n";
	$respuesta.= "Hora:         " . date('H:i:s', strtotime($fila_venta["fecha_boletos"]))."\n";
	$respuesta.= "Num Eco:      " .$fila_venta["num_eco"]."\n";
	$respuesta.= "Pasajeros:      " .$fila_venta["pasajeros"]."\n";
	$respuesta.= "Conductor:    " .normalizeChars($fila_venta["nombre_conductores"])."\n";
	$respuesta.= "Atendido Por: " . $fila_venta["nombre_usuarios"]."\n\n";
	$respuesta.=  "\x1b"."E".chr(1);
	$respuesta.= "Origen:        ". $fila_venta["boletos_origen"]. "\n";
	$respuesta.= "Destino:       ". strtoupper(normalizeChars($fila_venta["boletos_destino"])). "\n\n";
	$respuesta.=  "\x1b"."E".chr(0);
	
	
	
	$respuesta.= "Anticipo:        $". str_pad(number_format($fila_venta["anticipo"],2),10," ",STR_PAD_LEFT)."\n";
	$respuesta.= "Forma de Pago:   ". $fila_venta["recolecciones_forma_pago"]. "\n\n";
	
	$respuesta.= "2o Pago:         $". str_pad(number_format($fila_venta["total"] - $fila_venta["anticipo"],2),10," ",STR_PAD_LEFT)."\n";
	$respuesta.= "Forma de Pago:   ". $fila_venta["boletos_forma_pago"]. "\n\n";
	
	$respuesta.= "Total:           $" .str_pad(number_format($fila_venta["total"],2),10," ",STR_PAD_LEFT)."\n\n";
	
	$importe_letra = str_split("(".NumeroALetras::convertir($fila_venta["total"], "pesos", "centavos")." 00/100 M.N.)\n\n", 40);
	$respuesta.= $importe_letra[0]."\n";
	
	if(count($importe_letra) > 1){
		$respuesta.= $importe_letra[1]."\n";
		
	}
	
	
	$qr_code  = generateQRCodeESCpos("https://taxidriveraifa.com/facturacion/nueva_factura.php?id_emisores=1&folio={$fila_venta["id_boletos"]}&fecha=".date("Y-m-d", strtotime($fila_venta["fecha_boletos"]))."&total={$fila_venta["total"]}");
	// $qr_code  = generateQRCodeESCpos("https://taxidriveraifa.com/facturacion/nueva_factura.php");
	
	// $qr_code = generarQR("https://taxidriveraifa.com/facturacion/nueva_factura.php?id_emisores=1&folio={$fila_venta["id_boletos"]}&fecha=".date("Y-m-d", strtotime($fila_venta["fecha_boletos"]))."&monto={$fila_venta["total"]}");
	$respuesta.= $centerAlign;
	$respuesta.= $qr_code; 
	$respuesta.=$leftAlign ;
	$respuesta.= "\n"; // Bold
	
	$respuesta.="Cuenta con 24 horas para facturar y solo \ndentro del mes en curso \n\n";
	$respuesta.= "\x1b"."E".chr(1); // Bold
	$respuesta.= "Los peajes que no incluye son: Segundo piso del periferico, Siervo de la nacion, Rio de los \nRemedios-Naucalpan. Si desea  que su ruta sea \npor estas casetas el pago debera ser cubierto \npor el cliente. \n\n";
	
	$respuesta.= "PASAJERO \n";
	
	$respuesta.= "VA"; // Cut
	
	
	
	
	
	if(isset($_GET["reimpresion"])){
		
		$respuesta.= "\nREIMPRESION  " .date("d/m/Y H:i:s")."\n";
		$respuesta.= "Usuario: " .$_COOKIE["nombre_usuarios"]."\n";
	}
	
	
	//ticket operador
	
	$respuesta.= $fila_venta["nombre_empresas"]. "\n";
	$respuesta.= "Folio:        ". $fila_venta["id_boletos"]. "\n";
	$respuesta.= "Fecha:        " . date("d/m/Y", strtotime($fila_venta["fecha_boletos"]))."\n";
	$respuesta.= "Hora:         " . date('H:i:s', strtotime($fila_venta["fecha_boletos"]))."\n";
	$respuesta.= "Num Eco:      " .$fila_venta["num_eco"]."\n";
	$respuesta.= "Pasajeros:    " .$fila_venta["pasajeros"]."\n";
	$respuesta.= "Nombre Pasajero: " .$fila_venta["nombre_pasajero"]."\n";
	$respuesta.= "Atendido Por:  " . $fila_venta["nombre_usuarios"]."\n\n\n";
	$respuesta.=  "\x1b"."E".chr(1); //negrita
	$respuesta.= "Origen:        ". $fila_venta["boletos_origen"]. "\n";
	$respuesta.= "Destino:       ". $fila_venta["boletos_destino"]. "\n";
	$respuesta.=  "\x1b"."E".chr(0)."\n";
	
	$respuesta.= "Forma de Pago:        ". $fila_venta["forma_pago"]. "\n";
	$respuesta.="Total:                $" .str_pad(number_format($fila_venta["total"],2),10," ",STR_PAD_LEFT)."\n";
	
	$importe_letra = str_split("(".NumeroALetras::convertir($fila_venta["total"], "pesos", "centavos")." 00/100 M.N.)\n\n", 40);
	$respuesta.= $importe_letra[0]."\n";
	
	if(count($importe_letra) > 1){
		$respuesta.= $importe_letra[1]."\n";
		
	}
	
	$respuesta.= "OPERADOR: ". strtoupper(normalizeChars($fila_venta["nombre_conductores"])). "\n\n\n";
	
	$respuesta.= "VA"; // Cut
	
	$respuesta_sin_acentos = normalizeChars($respuesta);
	
	echo base64_encode($respuesta_sin_acentos);
	
	;
	
	
	function generateQRCodeESCpos($url) {
		// Elegir tamaño del QR (1-8, siendo 1 el más pequeño)
		$size = 4;
		
		// Nivel de corrección de error (L=0, M=1, Q=2, H=3)
		$errorCorrection = 1; // M
		
		// Convertir la URL a bytes
		$urlBytes = str_split($url);
		$length = strlen($url);
		
		// Comandos ESC/POS para QR code
		// Inicializar el sistema QR
		$cmd = "\x1D\x28\x6B\x03\x00\x31\x43".chr($size);
		
		// Establecer nivel de corrección de error
		$cmd .= "\x1D\x28\x6B\x03\x00\x31\x45".chr($errorCorrection);
		
		// Almacenar datos del QR
		$cmd .= "\x1D\x28\x6B".chr($length + 3)."\x00\x31\x50\x30";
		foreach ($urlBytes as $byte) {
			$cmd .= $byte;
		}
		
		// Imprimir el QR
		$cmd .= "\x1D\x28\x6B\x03\x00\x31\x51\x30";
		
		return $cmd;
	}
?>

