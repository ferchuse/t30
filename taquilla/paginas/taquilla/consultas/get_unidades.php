<?php
	
	
	function getUnidades($link){
		$filas = array();
		$consulta = "SELECT * FROM unidades ORDER BY num_eco";
		
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
	
	function getTiposUnidades($link){
		$filas = array();
		$consulta = "SELECT DISTINCT(tipo_unidad) AS tipo_unidad FROM unidades ORDER BY tipo_unidad";
		
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
