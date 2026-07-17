<?php
	header('Content-Type: application/json');
	require '../conexi.php'; // Archivo con conexión a la base de datos
	
	$link = Conectarse();
	
	$folio = isset($_GET['folio_facturacion']) ? trim($_GET['folio_facturacion']) : '';
	
	if (empty($folio)) {
		echo json_encode(['valido' => false, 'mensaje' => 'Código vacío']);
		exit;
	}
	
	
	
	
	$query = "SELECT * FROM sencillos_boletos WHERE folio_facturacion = ?";
	
	$stmt = mysqli_prepare($link, $query);
	
	mysqli_stmt_bind_param($stmt, "s", $folio);
	
	mysqli_stmt_execute($stmt);
	
	$result = mysqli_stmt_get_result($stmt);
	
	if (mysqli_num_rows($result) > 0) {
		$boleto = mysqli_fetch_assoc($result);
		
		
		if ($boleto['facturado']) {
			echo json_encode(['valido' => false, 'mensaje' => 'El boleto ya ha sido facturado']);
		} 
		if ($boleto['validado']) {
			echo json_encode(['valido' => false, 'mensaje' => 'El boleto ya ha sido capturado']);
		} 
		else {
			
			echo json_encode(['valido' => true, "boleto" => $boleto]  );
			
			$update_boleto =  "UPDATE sencillos_boletos SET 
			validado = 1 
			
			WHERE id_boletos = '{$boleto["id_boletos"]}'";
			
			
			$result_update_boleto = mysqli_query($link, $update_boleto);
			
			$resultado['update_boleto'] = $result_update_boleto ;
		}
		} else {
		echo json_encode(['valido' => false, 'mensaje' => 'El boleto no existe']);
	}
	
	mysqli_stmt_close($stmt);
	mysqli_close($conn);
?>
