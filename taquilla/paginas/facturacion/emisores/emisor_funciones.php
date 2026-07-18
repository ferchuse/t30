<?php
	
	
	
	function encriptar($texto)
	{
		$key = hash('sha256', ENCRYPT_KEY, true);
		
		$iv = random_bytes(16);
		
		$cipher = openssl_encrypt(
        $texto,
        'AES-256-CBC',
        $key,
        OPENSSL_RAW_DATA,
        $iv
		);
		
		return base64_encode($iv.$cipher);
	}
	
	function desencriptar($texto)
	{
		$key = hash('sha256', ENCRYPT_KEY, true);
		
		$data = base64_decode($texto);
		
		$iv = substr($data,0,16);
		
		$cipher = substr($data,16);
		
		return openssl_decrypt(
        $cipher,
        'AES-256-CBC',
        $key,
        OPENSSL_RAW_DATA,
        $iv
		);
	}
	
	function getEmisor($id){
		global $link;
		$consulta = "SELECT *  FROM emisores 
		
		WHERE id_emisores = '$id'";
		
		$result = mysqli_query( $link, $consulta ) 
		or die("Error al ejecutar consulta: $consulta".mysqli_error($link));
		
		
		while($row = mysqli_fetch_assoc($result)) {
			
			$respuesta = $row ;
			
		}
		
		// $respuesta["count_rows"] = "$count_rows" ;
		
		return $respuesta;
		
	}
?>