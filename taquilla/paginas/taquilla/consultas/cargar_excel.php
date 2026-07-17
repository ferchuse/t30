<?php
	include('../../../conexi.php');
	$link = Conectarse();
	$fila = array();
	$respuesta = array();
	$repetidos = 0 ;
	$guardados = 0 ;
	$casetas_tag = array();
	
	$texto =$_POST["texto_excel"];
	
	
	
	// $entregas_string = "";
	// filas = texto.split("\n").split("\t");
	
	$filas = explode("\n", $texto); // Por cada salto de linea crea un afila
	
	$tabla = array();
	
	forEach($filas  AS $index => $fila ){
		$columnas =  explode("\t", $fila); //item.split("\t") //por cada tabulacion crea una columna
		
		// $index++;
		if(count($columnas) > 1){
			
			$tabla[$index] = $columnas ;
			
			// $timestamp = strtotime(str_replace('/', '-', columnas[1]));
			
			// $fecha_viaje = date("Y-m-d H:i:s", strtotime($timestamp));
			
			
			// Fecha y hora en formato inicial
			// $fechaHoraInicial =  $columnas[2]. ;
			$fechaHoraInicial =  $columnas[2]. " ". $columnas[3];
			
			// Crear un objeto DateTime para analizar la fecha
			// $fechaHoraObjeto = DateTime::createFromFormat('d/m/Y H:i:s', $fechaHoraInicial);
			$fechaHoraObjeto = DateTime::createFromFormat('d/m/Y H:i:s', $fechaHoraInicial);
			
			// Verificar si la fecha se analizó correctamente
			if ($fechaHoraObjeto) {
				// Formatear la fecha en el nuevo formato deseado (por ejemplo, Y-m-d H:i:s)
				$nuevoFormato = $fechaHoraObjeto->format('Y-m-d H:i:s');
				$fecha_corta = $fechaHoraObjeto->format('Y-m-d');
				
				// Mostrar el resultado
				// echo "Fecha y hora en nuevo formato: " . $nuevoFormato;
				} else {
				// echo "Error al analizar la fecha y hora.";
			}
			
			$importe = str_replace("$", "", $columnas[6]);
			$observaciones = $columnas[4] . "- ".  $columnas[5] ;
			
			
			
			$insertar_casetas_tag = "INSERT INTO casetas_tag SET
			
			
			
			tag = '{$columnas[0]}',
			num_eco_tag = '{$columnas[1]}',
			fecha_viaje = '{$nuevoFormato}',
			concesionaria = '{$columnas[4]}',
			entrada = '{$columnas[5]}',
			importe = '{$importe}',
			folio_boleto = '{$columnas[7]}'
			
			ON DUPLICATE KEY UPDATE
			
			tag = '{$columnas[0]}',
			num_eco_tag = '{$columnas[1]}',
			fecha_viaje = '{$nuevoFormato}',
			concesionaria = '{$columnas[4]}',
			entrada = '{$columnas[5]}',
			importe = '{$importe}',
			folio_boleto = '{$columnas[7]}'
			";
			
			$respuesta["insertar_casetas_tag"][]= $insertar_casetas_tag;
			
			$result_casetas_tag = mysqli_query($link, $insertar_casetas_tag) ;
			
			$errnum = mysqli_errno($link);
			
			$respuesta["error"][]= mysqli_error($link);
			$respuesta["errnum"][]= $errnum;
			$respuesta["result_casetas_tag"][]= $result_casetas_tag;
			// $result_casetas_tag = mysqli_query($link, $insertar_casetas_tag) OR DIE(mysqli_error($link));
			
			if($errnum == "1062"){
				$repetidos++;
			}
			else{
				$guardados++;
				
				$conductor = array();
				//Si es nuevo registro Insertar en tabla gastos_operador
				
				$consulta_conductor = "SELECT * FROM conductores
				WHERE tag_operador = '{$columnas[0]}' AND estatus_conductores = 'Activo'";
				
				$result_conductor = mysqli_query($link, $consulta_conductor) ;
				
				while($row = mysqli_fetch_assoc($result_conductor)){
					$conductor = $row;
				}
				
				$respuesta["conductor"] = $conductor;
				
				if(count($conductor) > 0){
					$insertar_gasto = "INSERT INTO gastos_operador SET 
					fecha_gasto =  '{$fecha_corta}',
					fecha_captura = NOW(),
					id_cat_gastos = '17',
					observaciones = '{$observaciones}',
					id_conductores = '{$conductor["id_conductores"]}',
					id_usuarios = '{$_COOKIE["id_usuarios"]}',
					monto_gasto = '{$importe}'";
					
					$respuesta["insertar_gasto"][] = $insertar_gasto;
					$result_gasto = mysqli_query($link, $insertar_gasto) or die(mysqli_error($link))  ;
					
				}
				
				
				
			}
		}
		
		
		
		
	}
	
	$respuesta["guardados"] =  $guardados;
	$respuesta["repetidos"] =  $repetidos;
	$respuesta["tabla"] =  $tabla;
	
	
	
	
	
	echo json_encode($respuesta);
	
	
?>