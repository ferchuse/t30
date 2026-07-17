<?php
	
	
	function getAPIKey($link , $tipo){
		$filas = array();
		$consulta = "SELECT * FROM api_keys WHERE tipo = '$tipo'";
		
		$result = mysqli_query($link,$consulta);
		
		if(!$result){
			return ("Error en $consulta" . mysqli_error($link) );
		}
		else{
			
			while($row = mysqli_fetch_assoc($result)){
				$fila = $row;        
			}
			
		}
		
		return $fila;
	}
	
	
?>
