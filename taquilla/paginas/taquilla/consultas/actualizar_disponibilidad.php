<?php 
	include("../../../conexi.php");
	$link = Conectarse();
	
	$consulta = "SELECT * FROM boletos WHERE id_boletos = '{$_POST["id_boletos"]}'";
	
	
	$result_producto = mysqli_query($link,$consulta);
	
	if($result_producto ){
		while($row = mysqli_fetch_assoc($result_producto))
		$registro_anterior = $row;
	}
	
	$respuesta["registro_anterior"] = $registro_anterior;
	
	foreach($registro_anterior AS $columna => $valor_anterior){
		
		$ignorar_columnas = ["fecha_boletos", "id_facturas", "estatus_boletos", "cp_destino" , "domicilio"];
		
		$respuesta["valores_anteriores"][$columna] =  $registro_anterior[$columna];
		$respuesta["valores_nuevos"][$columna] =  $_POST[$columna];
		$respuesta["comparaciones"][] =  array($columna, $_POST[$columna], $registro_anterior[$columna]);
		
		
		if($registro_anterior[$columna] != $_POST[$columna] &&  !in_array($columna, $ignorar_columnas)){
			
			// $campo = $columna;
			// if($columna == "costo_proveedor"){
				// $campo = "Costo";
			// }
			// if($columna == "precio_menudeo"){
				// $campo = "Precio Público";
			// }
			
			$insert_historial = "INSERT INTO boletos_historial SET 
			id_boletos =   '{$_POST["id_boletos"]}',
			fecha_historial = NOW(),
			campo = '{$columna}',
			valor_anterior = '{$registro_anterior[$columna]}',
			valor_nuevo = '{$_POST[$columna]}',
			id_usuarios = '{$_COOKIE["id_usuarios"]}'
			";
			
			
			
			// $respuesta["insert"] = $insert;
			$result_historial = mysqli_query($link,$insert);
			
			if($exec_query){
				$respuesta["estatus"] = "success";
				$respuesta["mensaje"] ="Guardado Correctamente";
				
			}
			else{
				$respuesta["estatus"] = "error";
				$respuesta["error"] = mysqli_error($link);	
				
			}
			
			$result_historial = mysqli_query($link, $insert_historial);
			
			if(!$result_historial){
				$respuesta["error_historial"] = mysqli_error($link);
				$respuesta["insert_historial"] = $insert_historial ;
				
			}
			
			
		}
	}
	
	
	$insert = "UPDATE boletos SET
	
	num_eco = '{$_POST["num_eco"]}',
	total = '{$_POST["total"]}',
	efectivo = '{$_POST["efectivo"]}',
	tarjeta = '{$_POST["tarjeta"]}',
	transferencia = '{$_POST["transferencia"]}',
	id_conductores = '{$_POST["id_conductores"]}',
	id_usuarios = '{$_POST["id_usuarios"]}',
	destino = '{$_POST["destino"]}',
	origen = '{$_POST["origen"]}',
	facturar = '{$_POST["facturar"]}',
	taquilla = '{$_POST["taquilla"]}',
	forma_pago = '{$_POST["forma_pago"]}'
	
	
	WHERE id_boletos =  '{$_POST["id_boletos"]}'
	";
	
	// $respuesta["insert"] = $insert;
	$exec_query = mysqli_query($link,$insert);
	
	if($exec_query){
		$respuesta["estatus"] = "success";
		$respuesta["mensaje"] ="Guardado Correctamente";
		
	}
	else{
		$respuesta["estatus"] = "error";
		$respuesta["error"] = mysqli_error($link);	
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	echo json_encode($respuesta);
?>	