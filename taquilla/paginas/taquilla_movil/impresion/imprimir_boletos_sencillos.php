<?php
	
	// include('../funciones/numero_a_letras.php');
	include("../../../conexi.php");
	
	$link = Conectarse();
	$respuesta = "";
	
	$esc = "\x1B";
	$centerAlign = $esc . "a" . chr(1);
	
	$fontA = $esc . "M" . chr(0); // Fuente A (normal)
	// Seleccionar fuente B
	$fontB = $esc . "M" . chr(1); // Fuente B (más pequeña)
	$GS = chr(29); //GS
	
	
	
	function generateQRCodeESCpos($url) {
		// Elegir tamaño del QR (1-8, siendo 1 el más pequeño)
		$size = 5;
		
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
	
	// $url = "https://amsanrod.com/facturacion";
	
	// $respuesta.= "Para Facturar ingrese a \n";
	// $respuesta.= $url."\n";
	
	
	// Generar el comando ESC/POS
	// $qrCommand = generateQRCodeESCpos($url);
	
	// $respuesta.= $qrCommand;
	
	// $respuesta.= "VA"; // Cut
	
	
	
	
	foreach($_GET["folios"] AS $folio){
		
		
		$consulta = "SELECT * FROM sencillos_boletos
		LEFT JOIN usuarios USING (id_usuarios)
		WHERE id_boletos = '{$folio}'";
		
		$result = mysqli_query($link, $consulta) or die(mysqli_error($link));
		
		while ($row = mysqli_fetch_assoc($result)) {
			$fila = $row;
		}
		
		// $folios = explode(",", $_GET["id_ventas"]);
		
		$respuesta.=   $esc."@";
		$respuesta.= $centerAlign; 
		// $respuesta.=  file_get_contents("logo_test.tmb");
		
		$respuesta.=   "\x1b"."@";
		$respuesta.= "\x1b"."E".chr(1); // Bold
		$respuesta.= "!";
		$respuesta.=  "SANROD\n";
		$respuesta.=  "\x1b"."E".chr(0); // Not Bold
		$respuesta.=  "\x1b"."@" .chr(10).chr(13);
		
		
		$respuesta.= "Folio:       ". $fila["id_boletos"]. "\n";
		$respuesta.= "Usuario:     ". $fila["nombre_usuarios"]. "\n";
		$respuesta.= "Fecha:       " . date("d/m/Y", strtotime($fila["fecha_boletos"]))."\n";
		$respuesta.= "Hora:        " . date('H:i:s', strtotime($fila["fecha_boletos"]))."\n";
		$respuesta.= "Destino:     " . $fila["destino"]."\n";
		$respuesta.= "Monto:       $" . number_format($fila["precio"])."\n";
		$respuesta.= "F. de Pago:  ".   $fila["forma_pago"]."\n";
		$respuesta.= "Nombre:      " . $fila["nombre"]."\n";
		
		$respuesta.= "\nOPERADOR \n";
		
		// $respuesta.= NumeroALetras::convertir($fila["total_ventas"], "pesos", "centavos").chr(10).chr(13).chr(10).chr(13);
		
		$codigo = str_pad($fila["id_boletos"], 10,0,STR_PAD_LEFT);
		
		$respuesta.=chr(29)."h".chr(80).chr(29)."H".chr(2).chr(29)."k".chr(4).$codigo.chr(0);
		
		$respuesta.= "\n\n";
		
		//Corte Parcial
		$respuesta.= chr(29).chr(86).chr(66).chr(0);
		
		
		
		// fwrite($fp,chr(29).chr(86).chr(66).chr(0));
		// $respuesta.= "VA"; // Cut
		
		
		
		
		/// 2o BOLETO
		//////////////////////////////////////////////////////////////////////////////////////
		
		$respuesta.=   $esc."@";
		$respuesta.= $centerAlign; 
		// $respuesta.=  file_get_contents("logo_test.tmb");
		
		$respuesta.=   "\x1b"."@";
		$respuesta.= "\x1b"."E".chr(1); // Bold
		$respuesta.= "!";
		$respuesta.=  "SANROD\n";
		$respuesta.=  "\x1b"."E".chr(0); // Not Bold
		$respuesta.=  "\x1b"."@" .chr(10).chr(13);
		
		
		$respuesta.= "Folio:       ". $fila["id_boletos"]. "\n";
		$respuesta.= "Usuario:     ". $fila["nombre_usuarios"]. "\n";
		$respuesta.= "Fecha:       " . date("d/m/Y", strtotime($fila["fecha_boletos"]))."\n";
		$respuesta.= "Hora:        " . date('H:i:s', strtotime($fila["fecha_boletos"]))."\n";
		$respuesta.= "Destino:     " . $fila["destino"]."\n";
		$respuesta.= "Monto:       $" . number_format($fila["precio"])."\n";
		$respuesta.= "F. de Pago:  ".   $fila["forma_pago"]."\n";
		
		
		$respuesta.= "\n PASAJERO \n\n";
		
		// $respuesta.= "\x1b"."d".chr(1); // Blank line
		// $respuesta.= "aSeguro de Viajero\n"; // Blank line
		// $respuesta.= "\x1b"."d".chr(1). "\n"; // Blank line
		
		
		// URL para el QR
		$url = "https://amsanrod.com/facturacion";
		
		$respuesta.= "Para facturar ingrese a \n";
		$respuesta.= $url."\n\n";
		
		$respuesta.= "Folio Facturacion:  ".   $fila["folio_facturacion"]."\n\n";
		$respuesta.= "O escanea el siguiente c".chr(243)."digo \n\n";
		
		
		// Generar el comando ESC/POS
		$qrCommand = generateQRCodeESCpos($url);
		
		$respuesta.= $centerAlign; 
		
		$respuesta.= $qrCommand;
		
		$respuesta.= "\n\n";
		
		$respuesta.= chr(29).chr(86).chr(66).chr(0);
		
		
	}
	
	// echo $respuesta;
	echo base64_encode ( $respuesta );
	exit(0);
	
?>

