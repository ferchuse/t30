<?php 
	
	include('../../../conexi.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	
	
	$consulta = "SELECT num_asiento, estatus_boletos FROM boletos 
	
	WHERE id_corridas= {$_GET["id_corridas"]}
	AND estatus_boletos  = 'Activo'
	";
	
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros</div>");
			
		}
		
		
		while($fila = mysqli_fetch_assoc($result)){
			if($fila["estatus_boletos"] == "Reservado"){
				$respuesta["asientos_reservados"][] = $fila["num_asiento"] ;
				
			}
			else{
				
				$respuesta["asientos_ocupados"][] = $fila["num_asiento"] ;
			}
			
			// $respuesta["fila"][] = $fila;
		}
		
		
		
		echo json_encode($respuesta);
		
	}
	
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>