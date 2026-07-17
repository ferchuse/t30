<?php
	header("Content-Type: application/json");
	
	include("../../../conexi.php");
	$link = Conectarse();
	$respuesta = [];
	
	
	$id_emisores = isset($_GET["id_emisores"]) ? $_GET["id_emisores"] : 1;
	
	$id_emisores = $id_emisores == "" ? 1 : $id_emisores;
	
	$consulta = "SELECT * FROM emisores WHERE id_emisores = '{$id_emisores}'";
	$respuesta["consulta"] = $consulta;
	
	$result = mysqli_query($link,$consulta) ;
	
	if(!$result){
		$respuesta["error"] =  mysqli_error($link);
		
	}
	else{
		while($fila = mysqli_fetch_assoc($result)){
			$respuesta["datos"] = $fila;
		}
	}
	
	echo json_encode($respuesta)
?>