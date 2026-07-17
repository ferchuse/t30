<?php
	
	
	function getDestinos($link){
		$filas = array();
		$consulta = "SELECT * FROM destinos ORDER by destino";
		
		$result = mysqli_query($link,$consulta);
		
		if(!$result){
			return ("Error en $consulta" . mysqli_error($link) );
		}
		else{
			
			while($row = mysqli_fetch_assoc($result)){
				$filas[] = $row;        
			}
			
		}
		
		return $filas;
		
		
	}
	
	
?>
