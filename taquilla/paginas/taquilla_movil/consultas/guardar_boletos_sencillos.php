<?php 
	header("Content-Type: application/json");
	include('../../../conexi.php');
	include('../../../funciones/generar_id.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	
	$consulta_tarifa = "SELECT * FROM sencillos_precios 
	WHERE id_precio = '{$_POST["destino"]}'
	";
	
	
	$result_tarifa = mysqli_query($link,$consulta_tarifa) or die("Error en $consulta_tarifa: ".mysqli_error($link));
	
	
	while($row = mysqli_fetch_assoc($result_tarifa) ){
		$tarifa = $row;
	}
	
	$respuesta["tarifa"] = $tarifa;
	
	$precio = $tarifa["precio"];
	$cantidad = $_POST["cantidad"];
	$folio_facturacion= generarIDconVerificador(date("Y-m-d H:i:s.u"));
	
	

	if($tarifa["tipo_viaje"] == "Redondo"){
		$precio = $tarifa["precio"] / 2; 	
		$cantidad = $_POST["cantidad"] * 2;
	}
	
	
	for($i = 1 ; $i <= $cantidad ; $i++){
		
		
		$insert ="INSERT INTO sencillos_boletos SET 
		fecha_boletos = NOW(),
		destino = '{$tarifa["destino"]}',
		precio = '{$precio}',
		forma_pago = '{$_POST["forma_pago"]}',
		folio_facturacion = '{$folio_facturacion}',
		nombre = '{$_POST["nombre"]}',
		id_usuarios = '{$_COOKIE["id_usuarios"]}'
		";
		
		$result = 	mysqli_query($link,$insert);
		
		if($result){
			$respuesta["insert"][] = $insert;
			$respuesta["estatus"] = "success";
			$respuesta["folios"][] = mysqli_insert_id($link);
		}
		else{ 
			$respuesta["estatus"] = "error";		
			$respuesta["error"] = "Error en insert: $insert  ".mysqli_error($link);		
		}		
	}
	
	
	
	echo json_encode($respuesta);
	
?>