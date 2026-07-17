<?php
	
	include('../../../funciones/numero_a_letras.php');
	include("../../../conexi.php");
	
	$link = Conectarse();
	$respuesta = "";
	
	
	
	$consulta = "SELECT * FROM sencillos_boletos
	LEFT JOIN usuarios USING (id_usuarios)
	WHERE id_boletos = '{$folio}'";
	
	$result = mysqli_query($link, $consulta) or die(mysqli_error($link));
	
	while ($row = mysqli_fetch_assoc($result)) {
		$fila = $row;
	}
	
	$respuesta.=   "\x1b"."@";
	$respuesta.= "\x1b"."E".chr(1); // Bold
	$respuesta.= "!";
	$respuesta.=  "AUTOTRANSPORTES \nZITLALLI SA DE CV\n";
	$respuesta.=  "CORTE DE USUARIO \n";
	$respuesta.=  "\x1b"."E".chr(0); // Not Bold
	$respuesta.=  "\x1b"."@" .chr(10).chr(13);
	
	
	$respuesta.= "Usuario:     ". $_GET["nombre_usuarios"]. "\n";
	$respuesta.= "Boletos Vendidos:    ". $fila["id_boletos"]. "\n";
	$respuesta.= "Folio Inicial:       ". $fila["id_boletos"]. "\n";
	$respuesta.= "Importe Total:       $" . number_format($fila["precio"])."\n";
	
	
	$respuesta.= "Fecha Impresion:     " . date("d/m/Y", strtotime($fila["fecha_boletos"]))."\n";
	$respuesta.= "Hora Impresion:      " . date('H:i:s', strtotime($fila["fecha_boletos"]))."\n";
	$respuesta.= "Usuario Impresion:      " . date('H:i:s', strtotime($fila["fecha_boletos"]))."\n";
	$respuesta.= "Destino:     " . $fila["destino"]."\n";
	
	
	$respuesta.= "VA"; // Cut
	
	echo base64_encode ( $respuesta );
	exit(0);
	
?>

