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
		
		// for ($x = 0 ; $x < 2; $x++){
			
			
			$print.= "@";
			$print.= "!".chr(16)."Desglose de Dinero"."!".chr(0)."\n";
			$print.= "Folio: ".$filas["id_desglose"]."\n";
			$print.= "Usuario: ".$filas["nombre_usuarios"]."\n";
			$print.= "Fecha: ".$filas["fecha_desglose"]."\n";
			
			$print.="Denom    Cantidad       Importe \n";
			
			
			
			foreach($denominaciones as $i => $denominacion){
				$print.= "$".str_pad($denominacion, 10)." ". str_pad(number_format($filas[$denominacion]), 10, " ", STR_PAD_BOTH ). "  $" .str_pad(number_format($filas[$denominacion] * $denominacion),8," ", STR_PAD_LEFT )."\n" ;
				
				
			}
			
			$print.= "\nIMPORTE TOTAL:            $".number_format($filas["importe_desglose"], 2) ."\n";
			
			
			
			$print.="\n\nVB";
			
			
			echo base64_encode($print);
			
			
			
			
			
		// }
		
		// echo base64_encode ( $respuesta );
		// echo  ( $respuesta );
		
		
		
	}
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>			