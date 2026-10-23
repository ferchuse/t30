<?php
	
	function getClientes($estatus = "Activo"){
		global $link;
		
		$clientes = array();
		
		$consulta = "SELECT *,
		TRIM(CONCAT(
		nombre_cliente,' ',
		apellidop_cliente,' ',
		apellidom_cliente
		)) AS nombre_completo
		FROM clientes_frecuentes
		WHERE estatus_cliente = '".mysqli_real_escape_string($link,$estatus)."'
		ORDER BY nombre_cliente, apellidop_cliente, apellidom_cliente";
		
		$result = mysqli_query($link, $consulta)
		or die("Error al ejecutar consulta: $consulta<br>".mysqli_error($link));
		
		while($fila = mysqli_fetch_assoc($result)){
			$clientes[] = $fila;
		}
		
		return $clientes;
	}
	
?>
