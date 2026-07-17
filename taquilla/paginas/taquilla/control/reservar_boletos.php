<?php 
	 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	
	//Inserta detalle de boletos
	foreach($_POST["num_asiento"] as $i=> $item){
		$insert ="INSERT INTO boletos SET 
		id_corridas = '{$_POST['id_corridas']}' ,
		num_asiento = {$_POST['num_asiento'][$i]},
		nombre_pasajero = '{$_POST["nombre_pasajero"][$i]}',
		curp = '{$_POST["curp"][$i]}',
		id_precio = '{$_POST["id_precio"][$i]}',
		precio_boletos = '{$_POST["precio"][$i]}',
		id_usuarios = '{$_COOKIE["id_usuarios"]}',
		fecha_boletos = NOW()
		";
		$result_detalle = 	mysqli_query($link,$insert);
		
		if($result_detalle){
			$respuesta["result"] = "success";
			$respuesta["boletos"][] = mysqli_insert_id($link);
		}
		else{ 
			$respuesta["result"] = "error";		
			$respuesta["mensaje"] = "Error en insert: $insert  ".mysqli_error($link);		
		}		
	}
	
	
	
	echo json_encode($respuesta);
	
?>