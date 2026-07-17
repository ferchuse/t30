<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	// $boletos_id= implode("," ,$_GET['boletos']);
	
	$consulta = "SELECT * FROM gastos_corrida 
	LEFT JOIN usuarios  USING(id_usuarios)
	LEFT JOIN cat_gastos USING(id_cat_gastos)
	LEFT JOIN boletos USING(id_boletos)
	
	WHERE id_gastos = '{$_GET['id_gasto']}'";
  
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			
			die("<div class='alert alert-danger'>Registro no encontrado</div>");
			
			
		}
		
		while($fila = mysqli_fetch_assoc($result)){
			
			$registro = $fila ;
			
		}
		
		$respuesta = "";
		
		
		$respuesta.=   "\x1b"."@";
		$respuesta.= "\x1b"."E".chr(1); // Bold
		$respuesta.= "!";
		$respuesta.=   "VALE DE GASTOS \n";
		$respuesta.=  "\x1b"."E".chr(0); // Not Bold
		$respuesta.= "!\x10";
		$respuesta.= "\x1b"."d".chr(1); // 4 Blank lines
		$respuesta.= "Folio:". $registro["id_gastos"]. "\n";
		$respuesta.= "Boleto:". $registro["id_boletos"]. "\n";
		$respuesta.= "Num Eco:". $registro["num_eco"]. "\n";
		$respuesta.= "Fecha:" . date("d/m/Y", strtotime($registro["fecha_gastos"]))."\n";
		$respuesta.= "Recibe :". $registro["recibe"]."\n";
		$respuesta.= "Concepto :". $registro["descripcion_gastos"]."\n";
		$respuesta.= "Descripcion :". $registro["detalles"]."\n";
		$respuesta.= "Importe: $ ". $registro["importe"]."\n";
		$respuesta.=  "Taquillero:" . $_COOKIE["nombre_usuarios"]."\n\n";
		$respuesta.= "\x1b"."d".chr(1); // Blank line
		$respuesta.= "  _________________\n\n"; // Blank line
		$respuesta.= "aFIRMA DE RECIBIDO\n"; // Blank line
		$respuesta.= "\x1b"."d".chr(1). "\n"; // Blank line
		$respuesta.= "VA"; // Cut
		
	
	// /* Output an example receipt */
	// echo ESC."@"; // Reset to defaults
	// echo ESC."E".chr(1); // Bold
	// echo "FOO CORP Ltd.\n"; // Company
	// echo ESC."E".chr(0); // Not Bold
	// echo ESC."d".chr(1); // Blank line
	// echo "Receipt for whatever\n"; // Print text
	// echo ESC."d".chr(4); // 4 Blank lines
	
	// /* Bar-code at the end */
	// echo ESC."a".chr(1); // Centered printing
	
	// echo ESC."d".chr(1); // Blank line
	// echo "987654321\n"; // Print number
	// $respuesta.= " \x1d"."V\x41".chr(3); // Cut
	
	// $respuesta = "@@aHello World
	// !aESC/POS Printer Test
	// !aGoodbye World
	// VA"; 
	
	echo base64_encode ( $respuesta );
	exit(0);
	
	
	
}
else {
	echo "Error en ".$consulta.mysqli_Error($link);
	
}


?>


