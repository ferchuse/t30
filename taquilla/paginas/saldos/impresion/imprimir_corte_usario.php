<?php 
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = "";
	
	
	$denominaciones = ["1000", "500", "200", "100", "50", "20", "10", "5", "2", "1", "0.5"];
	$consulta = "SELECT * FROM desglose_dinero 
	
	LEFT JOIN usuarios USING(id_usuarios)
	WHERE id_desglose= '{$_GET['id_registro']}'";
  
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			
			die("<div class='alert alert-danger'>Registro No encontrado</div>");
			
			
		}
		
		while($fila = mysqli_fetch_assoc($result)){
			
			$filas = $fila ;
			
		}
		
		for ($x = 0 ; $x < 2; $x++){
			$respuesta.= file_get_contents('../../img/logo_brujaz.tmb');
			
			$respuesta.=   "\x1b"."@";
			$respuesta.= "\x1b"."E".chr(1); // Bold
			
			$respuesta.= "!\x10"; //font size
			$respuesta.=   "Desglose de Dinero \n";
			$respuesta.=  "\x1b"."E".chr(0); // Not Bold
			$respuesta.=   "\x1b"."@";
			$respuesta.= "Folio:". $filas["id_desglose"] . "\n";
			$respuesta.= "Fecha:". $filas["fecha_desglose"] . "\n";
			$respuesta.= "Taquillero:". $filas["nombre_usuarios"]."\n";
			
			
			foreach($denominaciones as $i => $denominacion){
				$respuesta.= $denominacion .chr(9);
				
				$respuesta.=number_format($filas[$denominacion]) .chr(9) ;
				
				$respuesta.= number_format($filas[$denominacion] * $denominacion) .chr(9);
				$respuesta.= "\n";
				
			}
			
			$respuesta.=  "\nIMPORTE TOTAL:" .chr(9). number_format($filas["importe_desglose"]);
			
			
			$respuesta.= "\x1b"."d".chr(2); // 4 Blank lines
			$respuesta.= "\nVA"; // Cut
		}
		
		echo base64_encode ( $respuesta );
		// echo  ( $respuesta );
		
		
		
	}
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>			