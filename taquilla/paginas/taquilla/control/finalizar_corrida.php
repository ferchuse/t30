<?php 
	
	include('../../../conexi.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = "";
	$total_gastos= 0;
	$fecha_finaliza = date("Y-m-d H:i:s");
	$datos_finaliza  = "Usuario: {$_COOKIE["nombre_usuarios"]} <br> Fecha: $fecha_finaliza";
	
	
	$finalizar_guia = "UPDATE corridas SET 
	estatus_corridas = 'Finalizada',
	datos_finaliza = '$datos_finaliza'
	
	
	
	WHERE id_corridas = '{$_GET["id_corridas"]}' ";
	
	
	$result_finalizar = mysqli_query($link,$finalizar_guia);
	
	
	//Detalle Boletos por Corrida
	
	$consulta_guia = "SELECT *, nombre_origenes as destino
	
	FROM	boletos 
	LEFT JOIN usuarios USING(id_usuarios)
	LEFT JOIN corridas USING(id_corridas)
	LEFT JOIN precios_boletos USING(id_precio)
	LEFT JOIN origenes ON precios_boletos.id_destinos = origenes.id_origenes
	WHERE id_corridas = '{$_GET["id_corridas"]}' ";
	
	
	$consulta_guia.=" ORDER BY num_asiento";
	
	
	$result_guia = mysqli_query($link,$consulta_guia);
	
	while($fila_guia = mysqli_fetch_assoc($result_guia)){
		
		$guias[] = $fila_guia ;
	}
	
	
	
	//Acumulado de Boletos
	$consulta_boletos = "SELECT COUNT(id_precio) AS cantidad, nombre_origenes AS destino
	
	FROM	boletos 
	LEFT JOIN precios_boletos USING(id_precio)
	LEFT JOIN origenes ON precios_boletos.id_destinos = origenes.id_origenes
	WHERE id_corridas = '{$_GET["id_corridas"]}'
	AND estatus_boletos  == 'Activo'
	";
	
	
	$consulta_boletos.="GROUP BY id_precio";
	
	$result_boletos = mysqli_query($link,$consulta_boletos);
	
	while($fila_boletos = mysqli_fetch_assoc($result_boletos)){
		
		$boletos[] = $fila_boletos ;
	}
	
	
	
	//Gastos Por Corrida
	$consulta_gastos = "SELECT * FROM gastos_corrida
	LEFT JOIN cat_gastos USING(id_cat_gastos)
	WHERE id_corridas = '{$_GET["id_corridas"]}'";
	
	
	$consulta_gastos .=" 
	AND estatus_gastos  <> 'Cancelado'
	ORDER BY fecha_gastos ";
	
	$result_gastos = mysqli_query($link,$consulta_gastos);
	
	while($fila = mysqli_fetch_assoc($result_gastos)){ 
		$gastos[] = $fila ;
		
	}
	
	
	
	//Paquetes por corrida 
	
	$consulta_paquetes = "SELECT * FROM paquetes
	LEFT JOIN taquillas ON taquillas.id_taquilla = paquetes.id_taquilla_destino
	
	WHERE id_corridas = '{$_GET["id_corridas"]}' 
	AND estatus_paquetes <> 'Cancelado'
	";
	
	
	$result_paquetes = mysqli_query($link,$consulta_paquetes);
	
	while($fila = mysqli_fetch_assoc($result_paquetes)){ 
		$paquetes[] = $fila ;
		
	}
	
	
	
	//Equipaje por corrida
	
	$consulta_equipaje= "SELECT * FROM equipaje
	WHERE id_corridas = '{$_GET["id_corridas"]}'
	AND estatus <> 'Cancelado'
	";
	
	
	
	$result_equipaje = mysqli_query($link,$consulta_equipaje);
	
	while($fila = mysqli_fetch_assoc($result_equipaje)){ 
		$equipajes[] = $fila ;
		
	}
	
	
	
	
	
	
	if($result_guia){
		
		if( mysqli_num_rows($result_guia) == 0){
			die("<div class='alert alert-danger'>No hay boletos venidos</div>");
			
		}
		$copias = ["Operador", "Taquilla"];
		
		foreach( $copias AS $copia){
			
			// $respuesta = file_get_contents('logo_brujaz.tmb');
			
			$empresa = "";
			
			$respuesta.=   "\x1b"."@";
			$respuesta.= "\x1b"."E".chr(1); // Bold
			// $respuesta.= "!";
			// $respuesta.= "!";
			$respuesta.= "!\x10"; //font size
			// $respuesta.=   "$empresa \n";
			$respuesta.=   "GUIA $copia\n";
			$respuesta.=  "\x1b"."E".chr(0); // Not Bold
			$respuesta.= "!\x10"; //font size
			$respuesta.= "Folio: ". $guias[0]["id_corridas"];
			$respuesta.= "\x1b"."d".chr(1); // 4 Blank lines
			$respuesta.= "Fecha:". $guias[0]["fecha_corridas"];
			$respuesta.= "\x1b"."d".chr(1); // 4 Blank lines
			
			$respuesta.= "Taquillero:". $guias[0]["nombre_usuarios"];
			$respuesta.= "\x1b"."d".chr(1); // 4 Blank lines
			
			$respuesta.= "Num Eco:". $guias[0]["num_eco"];
			$respuesta.= "\x1b"."d".chr(1); // 4 Blank lines
			
			
			$respuesta.=   "\x1b"."@"; // RESET defaults
			$respuesta.= "\x1b"."d".chr(2); // 4 Blank lines
			
			
			
			$total_guia = 0;
			$total_boletos = 0;
			$total_gastos = 0;
			$total_paquetes = 0;
			$total_equipaje = 0;
			
			if(!$result_guia){
				echo "<pre>".mysqli_error($result_guia)."</pre>";
				
			}
			
			
			$respuesta.= "ASIENTO           PASAJERO            PRECIO\n";
			foreach($guias AS $i =>$fila){
				if($fila["estatus_boletos"] == "Cancelado"){
					
					
					$respuesta.= "CANCELADO:";
					$respuesta.=  $fila["num_asiento"]."\x09";
					$respuesta.=  $fila["nombre_pasajero"]."\x09";
					$respuesta.="$". number_format($fila["precio_boletos"],2)."\x09   ";
					
					$respuesta.= "\x1b"."d".chr(1); // Blank line
					
				}
				else{
					$importe= $fila["precio_boletos"];
					$total_guia+= $importe;
					$total_boletos++;
					
					$respuesta.=  $fila["num_asiento"]."\x09";
					$respuesta.=  substr($fila["nombre_pasajero"], 0 , 22)."\x09";
					$respuesta.="$". number_format($fila["precio_boletos"],2);
					
					$respuesta.= "\n"; // Blank line
					
					
				}
				
				
			}
			
			$respuesta.= "!\x10"; //font size
			$respuesta.= "\nTOTAL:   $". number_format($total_guia). "\n"; // Blank line
			$respuesta.= "Boletos Vendidos:  ". $total_boletos ."\n"; // Blank line
			$respuesta.= "\x1b"."d".chr(1). "\n"; // Blank line
			// $respuesta.= "VA"; // Cut
			
			$respuesta.= "________________________\n "; 
			
			
			
			
			
			//TIPOS DE BOLETO
			
			$respuesta.=   "\x1b"."@";
			$respuesta.= "\x1b"."E".chr(1); // Bold
			$respuesta.= "!\x10"; //font size
			$respuesta.=   "  TIPOS DE BOLETOS \n";
			$respuesta.=   "\x1b"."@"; 
			$respuesta.=  "  DESTINO           CANTIDAD \n";
			
			foreach($boletos AS $i =>$boleto){
				$respuesta.=  "  ".$boleto["destino"]." \x09       ";
				$respuesta.= $boleto["cantidad"]."\n";
			}
			
			$respuesta.= "  ______________________\n "; 
			
			//GASTOS
			
			$respuesta.=   "\x1b"."@";
			$respuesta.= "\x1b"."E".chr(1); // Bold
			$respuesta.= "!\x10"; //font size
			$respuesta.=   "LISTA DE  GASTOS \n";
			$respuesta.=   "\x1b"."@"; 
			
			foreach($gastos AS $i =>$gasto){
				$importe= $gasto["importe"];
				$total_gastos+= $importe;
				
				$respuesta.=  $gasto["id_gastos"]."\x09";
				$respuesta.=  $gasto["descripcion_gastos"]."\x09"."\x09";
				$respuesta.="$". number_format($gasto["importe"], 0)."\n";
				
			}
			
			$respuesta.= "______________________\n "; 
			
			
			//PAQUETES
			
			$respuesta.=   "\x1b"."@";
			$respuesta.= "\x1b"."E".chr(1); // Bold
			$respuesta.= "!\x10"; //font size
			$respuesta.=   "LISTA DE PAQUETES \n";
			$respuesta.=   "\x1b"."@"; 
			$total_paquetes = 0;
			foreach($paquetes AS $i =>$paquete){
				
				$total_paquetes+= $paquete["costo"];
				
				
				$respuesta.=  $paquete["tipo_paquete"]."\x09    ";
				$respuesta.="$". number_format($paquete["costo"])."\n";
				
			}
			
			$respuesta.= "______________________\n "; 
			
			
			
			//EQUIPAJE
			
			$respuesta.=   "\x1b"."@";
			$respuesta.= "\x1b"."E".chr(1); // Bold
			$respuesta.= "!\x10"; //font size
			$respuesta.=   "LISTA DE  EQUIPAJE EXTRA \n";
			$respuesta.=   "\x1b"."@"; 
			$total_equipaje = 0;
			foreach($equipajes AS $i =>$equipaje){
				
				$total_equipaje+= $equipaje["importe"];
				
				
				$respuesta.=  $equipaje["tipo_equipaje"]."\x09    ";
				$respuesta.="$". number_format($equipaje["importe"])."\n";
				
			}
			
			$respuesta.= "______________________\n "; 
			
			
			
			
			
			$respuesta.=   "\nTOTAL BOLETOS: $". number_format($total_guia). "\n";
			$respuesta.=   "TOTAL GASTOS: $". number_format($total_gastos). "\n";
			$respuesta.=   "TOTAL PAQUETERIA: $". number_format($total_paquetes). "\n";
			$respuesta.=   "TOTAL EQUIPAJE EXTRA: $". number_format($total_equipaje). "\n";
			$respuesta.=   "BALANCE: $". number_format($total_guia - $total_gastos + $total_paquetes +$total_equipaje). "\n";
			
			$respuesta.= "VA"; // Cut
			
			
		}
		// echo  ( $respuesta );
		echo base64_encode ( $respuesta );
		
		exit(0);
		
		
		
	}
	
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>				