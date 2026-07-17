<?php 
	session_start() ;
	include('../../conexi.php');
	$link = Conectarse();
	header("Content-Type: application/json; charset=UTF-8");
	
	$respuesta = array();
	$datos = json_decode($_POST['datos'],false);
	
	$q_corte ="
	SELECT
	id_empresas,
	nombre_empresas,
	sum(monto_abonogeneral) AS montoGeneral,
	sum(abonos_unidad) AS total_abonos,
	FROM
	abono_general
	LEFT JOIN empresas USING (id_empresas)
	LEFT JOIN abonos_unidades USING (id_usuarios)
	LEFT JOIN mutualidad USING (id_usuarios)
	
	WHERE
	id_usuarios = 1
	GROUP BY nombre_empresas
	
	
	";
	
	$result = mysqli_query($link, $q_corte);
	
	while($fila = mysqli_fetch_assoc($link)){
		
		$reporte["total_abonos"][] = $fila["total_abonos"] 
	}
	
	
	
	
?>