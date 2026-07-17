<?php 
	session_start();
	include('../../../conexi.php');

	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	$totales = array_fill (  0 ,  1 , 0 ); //Llena el array totales con 10 elementos en 0s
	
	
	
	$consulta = "SELECT SUM(saldo_unidades) AS saldo_empresa FROM unidades
	LEFT JOIN empresas USING(id_empresas) 
	
	WHERE unidades.id_empresas = {$_SESSION["id_empresas"]}
	";
	
	
	$result = mysqli_query($link,$consulta);
	
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			$respuesta["result"] = "Sin registros";
		}
		else{
			$respuesta["result"] = "success";
		}
		
		while($fila = mysqli_fetch_assoc($result)){
		
			$respuesta["saldo_empresa"] = $fila["saldo_empresa"] ;
		}
	}
	else {
		$respuesta["result"] = "Error en ".$consulta.mysqli_Error($link);
	}
	
	echo json_encode($respuesta);
	
?>	