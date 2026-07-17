<?php 
	 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	
	
		$insert ="UPDATE boletos SET 
		
		num_asiento = {$_POST['num_asiento']}
		
		WHERE id_boletos = {$_POST['id_boletos']}
		";
		
		$result_detalle = 	mysqli_query($link,$insert);
		
		if($result_detalle){
			$respuesta["estatus"] = "success";
			
		}
		else{ 
			$respuesta["estatus"] = "error";		
			$respuesta["mensaje"] = "Error en insert: $insert  ".mysqli_error($link);		
		}		
	
	
	
	echo json_encode($respuesta);
	
?>