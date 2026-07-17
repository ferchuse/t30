<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	

	$consulta = "SELECT * FROM cargos 
	LEFT JOIN empresas USING(id_empresas)
	LEFT JOIN usuarios  USING(id_usuarios)
	LEFT JOIN unidades USING(num_eco)
	
	WHERE id_cargos = '{$_GET['folio']}'";
	
	
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
		$respuesta.=   "CARGO A UNIDAD \n";
		$respuesta.=  "\x1b"."E".chr(0); // Not Bold
		$respuesta.= "!\x10";
		$respuesta.= "\x1b"."d".chr(1); // 4 Blank lines
		$respuesta.=  "TAXIMAR AIFA"."\n\n";
		$respuesta.= $registro["nombre_empresas"]. "\n";
		$respuesta.= "Folio:     ". $registro["id_cargos"]. "\n";
		$respuesta.= "Num Eco:   ". $registro["num_eco"]. "\n";
		$respuesta.= "Fecha:     " . date("d/m/Y", strtotime($registro["fecha_cargos"]))."\n";
		$respuesta.= "Concepto:  ". $registro["concepto"]."\n";
		$respuesta.= "Importe: $ ". $registro["monto"]."\n";
		$respuesta.=  "Usuario:  "  . $registro["nombre_completo_usuarios"]."\n\n";
		$respuesta.= "\x1b"."d".chr(1); // Blank line
		$respuesta.= "  _________________\n\n"; // Blank line
		$respuesta.= "aFIRMA \n"; // Blank line
		$respuesta.= "\x1b"."d".chr(1). "\n"; // Blank line
		$respuesta.= "VA"; // Cut
		
		
		
		
		echo base64_encode ( $respuesta );
		exit(0);
		
		
		
	}
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>


