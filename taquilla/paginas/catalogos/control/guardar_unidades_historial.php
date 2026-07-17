<?php 
	session_start();
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	$tabla = $_POST["tabla"];
	$columnas = $_POST["datos"];
	$str_pairs = "";
	
	$consulta = "SELECT * FROM $tabla WHERE 
	{$columnas[0]['name']} = '{$columnas[0]['value']}' ";
	
	$respuesta["consulta_anteriores"] = $consulta;
	$result = mysqli_query($link, $consulta);
	
	while($fila = mysqli_fetch_assoc($result)){
		
		$valor_anterior = $fila;
		$respuesta["valores_anteriores"] = $fila;
	}
	// $respuesta["existe"] = "NO";
	
	if(mysqli_num_rows($result) == 0){
		
		$respuesta["existe"] = "NO";
		$query ="INSERT INTO $tabla SET ";	
		
		foreach($columnas as $arr_field_value){
			$str_pairs.= $arr_field_value["name"]. " = '" . $arr_field_value["value"] . "',";
		}
		
		$str_pairs  = trim($str_pairs, ",");
		$query.= $str_pairs;
		
		
	}
	else{
		
		//El registro ya existe, actualizarlo
		//Buscar Registro devolver campos.. 
		//Por cada Campo comparar valor anterior y valor nuevo
		//Si son diferentes guardar en la tabla de historial, 
		//Por cada cambio insertar 
		$respuesta["existe"] = "SI";
		
		$inserta_historial = "";
		
		$query ="UPDATE $tabla SET ";	
		
		foreach($columnas as $index => $columna){
			$str_pairs.= $columna["name"]. " = '" . $columna["value"] . "',";
			
			$respuesta["valor_anterior"][]= $valor_anterior[$columna["name"]];
			$respuesta["valor_nuevo"][]= $columna["value"];
			if($valor_anterior[$columna["name"]] != $columna["value"]){
				
				$cambios[$index] = [
				"fecha_modificacion" => date("Y-m-d H:i:s"),
				"campo_modificado" => $columna["name"],
				"valor_anterior" => $valor_anterior[$columna["name"]],
				"valor_nuevo" => $columna["value"]
				
				
				];
				$fecha_modificacion = date("Y-m-d H:i:s");
				$inserta_historial= "INSERT INTO unidades_historial SET
				fecha_modificacion = '{$fecha_modificacion}',
				id_usuarios = '{$_COOKIE["id_usuarios"]}',
				num_eco = '{$columnas[0]['value']}',
				campo_modificado = '{$columna["name"]}',
				valor_anterior = '{$valor_anterior[$columna["name"]]}',
				valor_nuevo = '{$columna["value"]}'
				
				;";
				
				$respuesta["inserta_historial"][] = $inserta_historial;
				$respuesta["result_historial"] = mysqli_query($link,$inserta_historial) ;
				$respuesta["error_historial"] =  mysqli_error($link);
				
			}
		}
		
		
		
		
		$respuesta["cambios"] = $cambios;
    
		$str_pairs  = trim($str_pairs, ",");
		$query.= $str_pairs." WHERE ".$columnas[0]['name']."='".$columnas[0]['value']."'";
	}	
	
	$exec_query = 	mysqli_query($link,$query);
	
	if($exec_query){
		$respuesta["estatus"] = "success";
		$respuesta["mensaje"] = "Agregado";
		$respuesta["query"] = $query;
		
    }else{
		
		$respuesta["estatus"] = "error";
		$respuesta["mensaje"] = "Error en insert: $query  ".mysqli_error($link);		
	}
	
	echo json_encode($respuesta);
	
?>