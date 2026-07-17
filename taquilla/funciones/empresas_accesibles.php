<?php
	$consulta_empresas = "SELECT GROUP_CONCAT(id_empresas) AS empresas_accesibles FROM usuarios_empresas WHERE id_usuarios ={$_COOKIE["id_usuarios"]} AND acceso = 'SI' ";
	// echo $consulta_empresas;
	
	$empresas_accesibles = '';
	
	$result_accesos = mysqli_query($link,$consulta_empresas );
	if(mysqli_num_rows($result_accesos) > 0){
		if($result_accesos){
			while($row = mysqli_fetch_assoc($result_accesos)){
				
				$empresas_accesibles = $row["empresas_accesibles"];
			}
		}
		else{
			
			die(mysqli_error($link). $consulta_empresas);
		}
	}
?>