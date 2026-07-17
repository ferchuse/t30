<?php
	include("../../../conexi.php");
	$link = Conectarse();
	
	
	
	$filas = array();
	$consulta = "SELECT * FROM destinos WHERE destino = '{$_GET["destino"]}'";
	
	$result = mysqli_query($link,$consulta);
	
	if(!$result){
		return ("Error en $consulta" . mysqli_error($link) );
	}
	else{
		
		while($row = mysqli_fetch_assoc($result)){
			$filas[] = $row;        
		}
		
		}
	
	echo json_encode($filas);
	
	
	
	
	
?>
