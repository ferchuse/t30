<?php
	
	include('../../../conexi.php');
	include('../../../lib/numero_a_letras.php');
	include('../../../funciones/normalize_chars.php');
	
	$link = Conectarse();
	
	$esc = "\x1B";
	$comando_fuenteA = "\x1B\x4D\x00";
	$comando_fuenteB = "\x1B\x4D\x01";
	$comando_fuenteC = "\x1B\x4D\x02";
	
	$leftAlign = $esc . "a" . chr(0);
	$centerAlign = $esc . "a" . chr(1);
	$rightAlign = $esc . "a" . chr(2);
	
	
	$consulta = "SELECT * FROM boletos
	
	LEFT JOIN usuarios USING (id_usuarios)
	LEFT JOIN unidades USING (num_eco)
	LEFT JOIN empresas USING (id_empresas)
	LEFT JOIN conductores USING (id_conductores)
	WHERE id_boletos={$_GET["folio"]} ";
	
	$result = mysqli_query($link, $consulta) or die(mysqli_Error($link));
	
	while ($row = mysqli_fetch_assoc($result)) {
		$fila_venta = $row;
	}
	
	
	
	
	$respuesta = "";
	
	
	
	if(isset($_GET["tipo_ticket"])){
		
		
		//Solo ticket operador
		
		$respuesta.= $fila_venta["nombre_empresas"]. "\n";
		$respuesta.= "Folio:        " . $fila_venta["id_boletos"]. "\n";
		$respuesta.= "Fecha:        " . date("d/m/Y", strtotime($fila_venta["fecha_boletos"]))."\n";
		$respuesta.= "Hora:         " . date('H:i:s', strtotime($fila_venta["fecha_boletos"]))."\n";
		$respuesta.= "Num Eco:      " .$fila_venta["num_eco"]."\n";
		$respuesta.= "Placas:       " . $fila_venta["placas"]."\n";
		$respuesta.= "Taquilla:     " .$fila_venta["taquilla"]."\n";
		$respuesta.= "Pasajeros:    " .$fila_venta["pasajeros"]."\n";
		$respuesta.= "Pasajero:     " .$fila_venta["nombre_pasajero"]."\n";
		$respuesta.= "Atendido Por: " . normalizeChars($fila_venta["nombre_usuarios"])."\n";
		$respuesta.= "Conductor:    ". normalizeChars($fila_venta["nombre_conductores"]). "\n\n";
		
		$respuesta.=  "\x1b"."E".chr(1); //negrita
		$respuesta.= "Origen:       ". $fila_venta["origen"]. "\n";
		$respuesta.= "Destino:      ". normalizeChars($fila_venta["destino"]). "\n\n";
		
		$respuesta.= "Domicilio: \n ". normalizeChars($fila_venta["domicilio"]). "\n\n";
		$respuesta.=  "\x1b"."E".chr(0)."\n";
		
		$respuesta.="Forma de Pago:        ". $fila_venta["forma_pago"]. "\n";
		$respuesta.="Total:                $" .str_pad(number_format($fila_venta["total"],2),10," ",STR_PAD_LEFT)."\n";
		
		$importe_letra = str_split("(".NumeroALetras::convertir($fila_venta["total"], "pesos", "centavos")." 00/100 M.N.)\n\n", 40);
		$respuesta.= $importe_letra[0]."\n";
		
		if(count($importe_letra) > 1){
			$respuesta.= $importe_letra[1]."\n";
			
		}
		
		$respuesta.= "OPERADOR: ". strtoupper(normalizeChars($fila_venta["nombre_conductores"])). "\n\n\n";
		
		$respuesta.= "VA"; // Cut
		
		$respuesta_sin_acentos = normalizeChars($respuesta);
		
		
	}
	else{
		
		
		
		
		$respuesta.=   "\x1b"."@";
		$respuesta.= "\x1b"."E".chr(1); // Bold
		
		$respuesta.= $centerAlign;
		$respuesta.= $fila_venta["nombre_empresas"]. "\n\n";
		$respuesta.=  "\x1b"."E".chr(0); // Not Bold	
		
		$respuesta.= convertBmpToEscpos("../../../img/logo_200.jpg?v=123");
		$respuesta.=  "\x1b"."@";
		$respuesta.=$leftAlign ;
		// $respuesta.= "Whatsapp: 56 14 61 17 66 \n\n";
		
		$respuesta.= "Reservaciones 24 hrs. {$fila_venta["telefono"]}\n\n";
		$respuesta.= "Folio:        ". $fila_venta["id_boletos"]. "\n";
		$respuesta.= "Fecha:        " . date("d/m/Y", strtotime($fila_venta["fecha_boletos"]))."\n";
		$respuesta.= "Hora:         " . date('H:i:s', strtotime($fila_venta["fecha_boletos"]))."\n";
		$respuesta.= "Num Eco:      " .$fila_venta["num_eco"]."\n";
		$respuesta.= "Placas:       " . $fila_venta["placas"]."\n";
		$respuesta.= "Pasajeros:    " .$fila_venta["pasajeros"]."\n";
		$respuesta.= "Conductor:    " .normalizeChars($fila_venta["nombre_conductores"])."\n";
		$respuesta.= "Atendido Por: " . normalizeChars($fila_venta["nombre_usuarios"])."\n\n";
		
		$respuesta.=  "\x1b"."E".chr(1);
		$respuesta.= "Origen:        ". $fila_venta["origen"]. "\n";
		$respuesta.= "Destino:       ". strtoupper(normalizeChars($fila_venta["destino"])). "\n\n";
		$respuesta.=  "\x1b"."E".chr(0);
		
		$respuesta.= "Domicilio: \n ". normalizeChars($fila_venta["domicilio"]). "\n\n";
		$respuesta.= "Forma de Pago:        ". $fila_venta["forma_pago"]. "\n";
		$respuesta.= "Total:               $" .str_pad(number_format($fila_venta["total"],2),10," ",STR_PAD_LEFT)."\n";
		
		$importe_letra = str_split("(".NumeroALetras::convertir($fila_venta["total"], "pesos", "centavos")." 00/100 M.N.)\n\n", 40);
		$respuesta.= $importe_letra[0]."\n";
		
		if(count($importe_letra) > 1){
			$respuesta.= $importe_letra[1]."\n";
			
		}
		
		
		$respuesta.= "Para obtener su factura escanear el QR\n";
		$respuesta.= "O Ingresar a {$fila_venta["dominio"]}/facturacion \n\n";
		
		$qr_code  = generateQRCodeESCpos("https://{$fila_venta["dominio"]}/facturacion/nueva_factura.php?id_emisores=1&folio={$fila_venta["id_boletos"]}&fecha=".date("Y-m-d", strtotime($fila_venta["fecha_boletos"]))."&total={$fila_venta["total"]}");
		// $qr_code  = generateQRCodeESCpos("https://taxidriveraifa.com/facturacion/nueva_factura.php");
		
		// $qr_code = generarQR("https://taxidriveraifa.com/facturacion/nueva_factura.php?id_emisores=1&folio={$fila_venta["id_boletos"]}&fecha=".date("Y-m-d", strtotime($fila_venta["fecha_boletos"]))."&monto={$fila_venta["total"]}");
		$respuesta.= $centerAlign;
		$respuesta.= $qr_code; 
		$respuesta.=$leftAlign ;
		$respuesta.= "\n"; // Bold
		
		$respuesta.="Cuenta con 24 horas para facturar y solo \ndentro del mes en curso \n\n";
		$respuesta.= "\x1b"."E".chr(1); // Bold
		$respuesta.= "Los peajes que no incluye son: Segundo piso del periferico, Siervo de la nacion, Rio de los \nRemedios-Naucalpan. Si desea  que su ruta sea \npor estas casetas el pago debera ser cubierto \npor el cliente. \n\n";
		$respuesta.=  "\x1b"."E".chr(0); // Not Bold
		$respuesta.= "PASAJERO \n\n";
		$respuesta.= "GRACIAS POR SU COMPRA\n\n";
		
		$respuesta.= "VA"; // Cut
		
		
		//////////////////////////////////////
		
		
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
		$respuesta.= "Placas:       " . $fila_venta["placas"]."\n";
		$respuesta.= "Taquilla:     " .$fila_venta["taquilla"]."\n";
		$respuesta.= "Pasajeros:    " .$fila_venta["pasajeros"]."\n";
		$respuesta.= "Pasajero:     " .$fila_venta["nombre_pasajero"]."\n";
		$respuesta.= "Atendido Por: " . normalizeChars($fila_venta["nombre_usuarios"])."\n";
		$respuesta.= "Conductor:    ". normalizeChars($fila_venta["nombre_conductores"]). "\n\n";
		
		$respuesta.=  "\x1b"."E".chr(1); //negrita
		$respuesta.= "Origen:       ". $fila_venta["origen"]. "\n";
		$respuesta.= "Destino:      ". normalizeChars($fila_venta["destino"]). "\n\n";
		
		$respuesta.= "Domicilio Completo: \n ". normalizeChars($fila_venta["domicilio"]). "\n\n";
		$respuesta.=  "\x1b"."E".chr(0)."\n";
		
		$respuesta.="Forma de Pago:        ". $fila_venta["forma_pago"]. "\n";
		$respuesta.="Total:                $" .str_pad(number_format($fila_venta["total"],2),10," ",STR_PAD_LEFT)."\n";
		
		$importe_letra = str_split("(".NumeroALetras::convertir($fila_venta["total"], "pesos", "centavos")." 00/100 M.N.)\n\n", 40);
		$respuesta.= $importe_letra[0]."\n";
		
		if(count($importe_letra) > 1){
			$respuesta.= $importe_letra[1]."\n";
			
		}
		
		$respuesta.= "OPERADOR: ". strtoupper(normalizeChars($fila_venta["nombre_conductores"])). "\n\n\n";
		
		$respuesta.= "VA"; // Cut
		
		$respuesta_sin_acentos = normalizeChars($respuesta);
		
		
	}
	
	
	echo base64_encode($respuesta);
	// echo base64_encode($respuesta_sin_acentos);
	
	
	function convertBmpToEscpos($imagePath, $maxWidth = 384) {
		if (!function_exists('imagecreatefromjpeg')) {
			die("PHP no tiene soporte para JPG. Activa la extensionn GD.");
		}
		
		$img = imagecreatefromjpeg($imagePath);
		if (!$img) {
			return false;
		}
		
		$width = imagesx($img);
		$height = imagesy($img);
		
		// Redimensionar si excede el ancho mÃ¡ximo
		if ($width > $maxWidth) {
			$scale = $maxWidth / $width;
			$newW = $maxWidth;
			$newH = (int)($height * $scale);
			$tmp = imagecreatetruecolor($newW, $newH);
			imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newW, $newH, $width, $height);
			imagedestroy($img);
			$img = $tmp;
			$width = $newW;
			$height = $newH;
		}
		
		// Convertir a monocromo
		$threshold = 128;
		for ($y = 0; $y < $height; $y++) {
			for ($x = 0; $x < $width; $x++) {
				$rgb = imagecolorat($img, $x, $y);
				$r = ($rgb >> 16) & 0xFF;
				$g = ($rgb >> 8) & 0xFF;
				$b = $rgb & 0xFF;
				$gray = (int)(0.3 * $r + 0.59 * $g + 0.11 * $b);
				$color = ($gray < $threshold) ? 0 : 255;
				$col = imagecolorallocate($img, $color, $color, $color);
				imagesetpixel($img, $x, $y, $col);
			}
		}
		
		// Comenzar comandos ESC/POS
		$output = "\x1B\x40"; // Inicializar
		$output .= "\x1B\x61\x01"; // Centrado
		
		for ($y = 0; $y < $height; $y += 24) {
			$line = "";
			for ($x = 0; $x < $width; $x++) {
				$colBytes = "";
				for ($b = 0; $b < 24; $b++) {
					$yy = $y + $b;
					$bit = 0;
					if ($yy < $height) {
						$rgb = imagecolorat($img, $x, $yy);
						$bit = ($rgb & 0xFF) == 0 ? 1 : 0;
					}
					if ($b % 8 == 0) $colBytes .= chr(0);
					$index = strlen($colBytes) - 1;
					$current = ord($colBytes[$index]);
					$current |= $bit << (7 - ($b % 8));
					$colBytes[$index] = chr($current);
				}
				$line .= $colBytes;
			}
			$nL = $width & 0xFF;
			$nH = ($width >> 8) & 0xFF;
			$output .= "\x1B\x2A" . chr(33) . chr($nL) . chr($nH) . $line;
			$output .= "\x0A";
		}
		
		// $output .= "\x1B\x61\x00"; // AlineaciÃ³n izq
		// $output .= "\x1D\x56\x42\x00"; // Cortar papel
		imagedestroy($img);
		
		return $output;
	}
	
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
	
	
	function generarQR($textoQR, $tamano = 5) {
		// Validar el tamaño (rango permitido por ESC/POS: 1 a 16)
		if ($tamano < 1) $tamano = 1;
		if ($tamano > 16) $tamano = 16;
		
		$comando = '';
		
		// Inicializar impresora
		$comando .= "\x1B\x40";
		
		// Modelo QR (modelo 2)
		$comando .= "\x1D\x28\x6B\x04\x00\x31\x41\x32\x00";
		
		// Tamaño de módulo
		$comando .= "\x1D\x28\x6B\x03\x00\x31\x43" . chr($tamano);
		
		// Nivel de corrección de error (2 = Q)
		$comando .= "\x1D\x28\x6B\x03\x00\x31\x45\x32";
		
		// Datos a almacenar
		$len = strlen($textoQR) + 3;
		$pL = $len % 256;
		$pH = intdiv($len, 256);
		
		$comando .= "\x1D\x28\x6B" . chr($pL) . chr($pH) . "\x31\x50\x30" . $textoQR;
		
		// Imprimir QR almacenado
		$comando .= "\x1D\x28\x6B\x03\x00\x31\x51\x30";
		
		// Salto de línea final
		$comando .= "\x0A";
		
		return $comando;
	}
?>

