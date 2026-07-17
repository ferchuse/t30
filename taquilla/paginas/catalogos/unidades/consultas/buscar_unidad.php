<?php 
	session_start();
	include('../../../../conexi.php');
	$link = Conectarse();
	$respuesta = Array();
	
	$consulta = "SELECT * FROM unidades 
	LEFT JOIN empresas USING(id_empresas) 
	WHERE unidades.num_eco = '{$_GET["num_eco"]}'
	";
	
	
	$result = mysqli_query($link,$consulta);
	
	if($result){
		$respuesta["estatus"] = "success";
		$num_registros = mysqli_num_rows($result);
		
		if($num_registros > 0){
			
			while($fila = mysqli_fetch_assoc($result)){
				
				$respuesta["existe"] = "SI";
				$respuesta["unidad"] = $fila;
				
			}
		}
		else{
			$respuesta["existe"] = "NO";
			
		}
		
		
	}
	else {
		$respuesta["estatus"] = "error";
		$respuesta["consulta"] = "$consulta";
		$respuesta["error"] = mysqli_error($link);
		
	}
	
	echo json_encode($respuesta);
	
