<?php
	
	include('../../../conexi.php');
	// include('../../../lib/numero_a_letras.php');
	// include('../../../funciones/normalize_chars.php');
	
	$link = Conectarse();
	

	
	
	
	$respuesta = "";
	
	$respuesta.=   "\x1b"."@";
	$respuesta.= "\x1b"."E".chr(1); // Bold
	$respuesta.= "!";
	$respuesta.=  "PRUEBA DE IMPRESION"."\n\n";
	$respuesta.=  "PRUEBA DE IMPRESION"."\n\n";
	$respuesta.=  "PRUEBA DE IMPRESION"."\n\n";
	$respuesta.=  "PRUEBA DE IMPRESION"."\n\n";
	$respuesta.=  "PRUEBA DE IMPRESION"."\n\n";
	$respuesta.=  "\x1b"."E".chr(0); // Not Bold	
	$respuesta.=  "\x1b"."@";
	// $respuesta.= "Whatsapp: 56 14 61 17 66 \n\n";

	
	
	
	$respuesta.= "VA"; // Cut
	
	// $respuesta_sin_acentos = normalizeChars($respuesta);
	
	echo base64_encode($respuesta);
	
	;
	
?>

