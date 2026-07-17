<?php 
	include('../../../../conexi.php');
	$link = Conectarse();
	$fila = array();
	$respuesta = array();
	
	
	$events = array();
	
	
	
	$consulta_boletos = "SELECT * FROM recolecciones 
	
	LEFT JOIN usuarios USING(id_usuarios)
	LEFT JOIN unidades USING (num_eco)
	LEFT JOIN empresas USING (id_empresas)
	LEFT JOIN conductores USING (id_conductores)
	
	
	WHERE 
	1
	";
	
	
	
	if($_GET["start"] != ""){
		$consulta_boletos.="AND  DATE(fecha_recoleccion) BETWEEN '{$_GET["start"]}'
		AND '{$_GET["end"]}' ";
	}
	
	

	
	$consulta_boletos.=" ORDER BY id_boletos";
	
	
	$result_boletos = mysqli_query($link,$consulta_boletos);
	
	if(!$result_boletos){
		
		die(mysqli_error($link));
	}
	
	// $events["result_boletos"]= mysqli_num_rows($result_boletos);
	
	while ($row = mysqli_fetch_assoc($result_boletos)) {
		$events[] = array(
		'id'    => $row['id_recoleccion'],
		'title' => $row['destino'],
		'start' => $row['fecha_recoleccion'],
		// 'end'   => isset($row['end']) ? $row['end'] : $row['fecha_boletos'], // Opcional si no tienes tiempo de fin
		);
	}
	
	// $events["consulta"]= $consulta_boletos;
	echo json_encode($events);
?>													