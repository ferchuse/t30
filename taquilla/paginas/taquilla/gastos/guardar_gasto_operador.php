<?php 
	include('../../../conexi.php');
	$link = Conectarse();
	
	$respuesta = array();
	
	
	
	$query ="INSERT INTO gastos_operador SET 
	id_gasto_operador =  '{$_POST["id_gasto_operador"]}',
	fecha_gasto =  '{$_POST["fecha_gasto"]}',
	fecha_captura = NOW(),
	id_cat_gastos = '{$_POST["id_cat_gastos"]}',
	observaciones = '{$_POST["observaciones"]}',
	id_conductores = '{$_POST["id_conductores"]}',
	id_unidades = '{$_POST["id_unidades"]}',
	id_usuarios = '{$_COOKIE["id_usuarios"]}',
	monto_gasto = '{$_POST["monto_gasto"]}'
	
	
	ON DUPLICATE KEY UPDATE 
	
	fecha_gasto =  '{$_POST["fecha_gasto"]}',
	fecha_captura = NOW(),
	id_cat_gastos = '{$_POST["id_cat_gastos"]}',
	observaciones = '{$_POST["observaciones"]}',
	id_conductores = '{$_POST["id_conductores"]}',
	id_unidades = '{$_POST["id_unidades"]}',
	id_usuarios = '{$_COOKIE["id_usuarios"]}',
	monto_gasto = '{$_POST["monto_gasto"]}'
	";	
	
	
	
	$exec_query = 	mysqli_query($link,$query);
	$respuesta["query"] = $query;
	
	if($exec_query){
		$respuesta["estatus"] = "success";
		$respuesta["mensaje"] = "Guardado";
		$respuesta["folio"] = mysqli_insert_id($link);
		
		
    }else{
		
		$respuesta["estatus"] = "error";
		$respuesta["mensaje"] = "Error en insert: $query  ".mysqli_error($link);		
	}
	
	echo json_encode($respuesta);
	
?>