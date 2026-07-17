<?php
	
	
	function getDestinos($link){
		$filas = array();
		$consulta = "SELECT CONCAT('Colonia ', colonia, ', ',  codigo_postal ) AS destino  FROM colonias ORDER by id_colonia";
		
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
