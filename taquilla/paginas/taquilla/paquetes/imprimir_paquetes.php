<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	// $boletos_id= implode("," ,$_GET['boletos']);
	
	$consulta = "SELECT * FROM paquetes
	LEFT JOIN usuarios  USING(id_usuarios)
	LEFT JOIN taquillas ON taquillas.id_taquilla = paquetes.id_taquilla_destino
	LEFT JOIN corridas USING(id_corridas)
	WHERE id_paquetes = '{$_GET["id_paquetes"]}' 
	";
	
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
		$respuesta.= "!"; //Double Height
		$respuesta.= "\x1b"."E".chr(1); // Bold
		
		$respuesta.=  "  ENVIO DE PAQUETERIA \n";
		$respuesta.=  "\x1b"."E".chr(0); // Not Bold
		$respuesta.= "!".chr(0);
			// $respuesta.= "!"; //Double Height
		
		
		// $respuesta.=  "  ENVIO DE PAQUETERIA \n";
	
		// $respuesta.= "!".chr(0);
		// $respuesta.= "!".chr(0). "FONTA A \n";
		// $respuesta.= "!".chr(1). "FONTA B \n";
		
		$respuesta.= "\x1b"."d".chr(1); // 4 Blank lines
		$respuesta.= "  Folio:        ". $registro["id_paquetes"]. "\n";
		$respuesta.= "Fecha de Corrida: ".  date("d-m-Y", strtotime($registro["fecha_corridas"]))."\n";
		$respuesta.= "Hora de Corrida:  ". date("H:i:s", strtotime($registro["hora_corridas"]))."\n";
		$respuesta.= "  Num Eco:      ". $registro["num_eco"]. "\n";
		$respuesta.= "  Fecha:        " . date("d/m/Y", strtotime($registro["fecha_paquetes"]))."\n";
		$respuesta.= "  Hora:         " . date("H:i:s", strtotime($registro["fecha_paquetes"]))."\n";
		$respuesta.= "  Destino :     ". $registro["nombre_taquilla"]."\n";
		$respuesta.= "  Tamano:       ". $registro["tipo_paquete"]."\n";
		$respuesta.= "  Contenido:    ". $registro["contenido"]."\n";
		$respuesta.= "  Remitente: ". $registro["remitente"]."\n";
		$respuesta.= "  Destinatario: ". $registro["destinatario"]."\n";
		$respuesta.= "  Costo:      $ ". $registro["costo"]."\n";
		$respuesta.= "  Usuario:      " . $_COOKIE["nombre_usuarios"]."\n\n\n\n";
		
		$respuesta.= "VA"; // Cut
		
		
		$respuesta.=   "\x1b"."@";
		$respuesta.= "!"; //Double Height
		$respuesta.= "\x1b"."E".chr(1); // Bold
		
		$respuesta.=  "  ENVIO DE PAQUETERIA \n";
		$respuesta.=  "\x1b"."E".chr(0); // Not Bold
		$respuesta.= "!".chr(0);
		
		$respuesta.= "\x1b"."d".chr(1); // 4 Blank lines
		$respuesta.= "  Folio:        ". $registro["id_paquetes"]. "\n";
		$respuesta.= "  Fecha de Corrida: ".  date("d-m-Y", strtotime($registro["fecha_corridas"]))."\n";
		$respuesta.= "  Hora de Corrida:  ". date("H:i:s", strtotime($registro["hora_corridas"]))."\n";
		$respuesta.= "  Num Eco:      ". $registro["num_eco"]. "\n";
		$respuesta.= "  Fecha:        " . date("d/m/Y", strtotime($registro["fecha_paquetes"]))."\n";
		$respuesta.= "  Hora:         " . date("H:i:s", strtotime($registro["fecha_paquetes"]))."\n";
		$respuesta.= "  Destino :     ". $registro["nombre_taquilla"]."\n";
		$respuesta.= "  Tamano:       ". $registro["tipo_paquete"]."\n";
		$respuesta.= "  Contenido:    ". $registro["contenido"]."\n";
		$respuesta.= "  Destinatario: ". $registro["destinatario"]."\n";
		$respuesta.= "  Costo:      $ ". $registro["costo"]."\n";
		$respuesta.= "  Usuario:      " . $_COOKIE["nombre_usuarios"]."\n\n";
		$respuesta.= "       COPIA \n\n\n";
		
		$respuesta.= "VA"; // Cut
		
	
		
		echo base64_encode ( iconv('UTF-8', 'CP437//TRANSLIT//IGNORE', $respuesta) );
		exit(0);
		
		
		
	}
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>


