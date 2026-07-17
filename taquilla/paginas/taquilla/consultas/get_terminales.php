<?php
	
	
	function getTerminales($link){
		$filas = array();
		$consulta = "SELECT * FROM cat_terminales ORDER by terminal";
		
		$result = mysqli_query($link,$consulta) or die(mysqli_error($link));
		
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
