<?php
	header("Content-Type: application/json");
	
	include("../taquilla/conexi.php");
	$link = Conectarse();
	
	$consulta ="SELECT * FROM empleados";
	
	$result=mysqli_query($link,$consulta) or die("Error en: $consulta  ".mysqli_error($link));
	
	// $respuesta["consulta"] = $consulta;
	
	while($row = mysqli_fetch_assoc($result)){
		
		$respuesta[] = array("id" => $row["id_empleado"], "nombre" =>$row["nombre_empleado"]);
		
	}
	
	// echo $respuesta;
	echo json_encode($respuesta);
?>
?>