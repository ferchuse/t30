<?php 
	
	include('../../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	
	
	
	$consulta = "SELECT * FROM recolecciones WHERE id_recoleccion = '{$_GET["id_registro"]}'";
	
	
	
	
	$query = mysqli_query($link,$consulta);
	if($query){
		
		while($row = mysqli_fetch_assoc($query)){
			$fila = $row;
		}
		$respuesta['estatus'] = 'success';
		// $respuesta['mensaje'] = $lista;
		$respuesta['fila'] = $fila;
	}
	else {
		$respuesta["estatus"] = "error";
		$respuesta["mensaje"] = "Error ".mysqli_error($link);
		$respuesta['query'] = $consulta;
	}
	
	echo json_encode($respuesta);
?>