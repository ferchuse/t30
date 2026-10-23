<?php
	
	// header('Content-Type: application/json; charset=utf-8');
	
	include("../../../conexi.php");
	
	$link = Conectarse();
	
	try{
		
		$nombre      = mb_strtoupper(trim($_POST["nombre_cliente"]), "UTF-8");
		$apellidop   = mb_strtoupper(trim($_POST["apellidop_cliente"]), "UTF-8");
		$apellidom   = mb_strtoupper(trim($_POST["apellidom_cliente"]), "UTF-8");
		$telefono    = trim($_POST["telefono_cliente"]);
		$correo      = trim($_POST["correo_cliente"]);
		$rfc         = mb_strtoupper(trim($_POST["rfc_cliente"]), "UTF-8");
		$descuento   = isset($_POST["porcentaje_descuento_cliente"]) ? floatval($_POST["porcentaje_descuento_cliente"]) : 0;
		
		if($nombre == ""){
			throw new Exception("Debe capturar el nombre del cliente.");
		}
		
		// Validar teléfono
		if($telefono != ""){
			
			$consulta = "SELECT id_cliente_frecuente
			FROM clientes_frecuentes
			WHERE telefono_cliente='".mysqli_real_escape_string($link,$telefono)."'
			LIMIT 1";
			
			$result = mysqli_query($link,$consulta);
			
			if(mysqli_num_rows($result) > 0){
				throw new Exception("Ya existe un cliente con ese teléfono.");
			}
			
		}
		
		// Validar RFC
		if($rfc != ""){
			
			$consulta = "SELECT id_cliente_frecuente
			FROM clientes_frecuentes
			WHERE rfc_cliente='".mysqli_real_escape_string($link,$rfc)."'
			LIMIT 1";
			
			$result = mysqli_query($link,$consulta);
			
			if(mysqli_num_rows($result) > 0){
				throw new Exception("Ya existe un cliente con ese RFC.");
			}
			
		}
		
		$consulta = sprintf("
        INSERT INTO clientes_frecuentes
        (
		nombre_cliente,
		apellidop_cliente,
		apellidom_cliente,
		telefono_cliente,
		correo_cliente,
		rfc_cliente,
		porcentaje_descuento_cliente,
		fecha_registro_cliente,
		estatus_cliente
        )
        VALUES
        (
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',
		'%s',
		%.2f,
		NOW(),
		'Activo'
        )",
		
        mysqli_real_escape_string($link,$nombre),
        mysqli_real_escape_string($link,$apellidop),
        mysqli_real_escape_string($link,$apellidom),
        mysqli_real_escape_string($link,$telefono),
        mysqli_real_escape_string($link,$correo),
        mysqli_real_escape_string($link,$rfc),
        $descuento
		);
		
		if(!mysqli_query($link,$consulta)){
			throw new Exception(mysqli_error($link));
		}
		
		$id_cliente = mysqli_insert_id($link);
		
		echo json_encode(array(
		"ok" => true,
		"id_cliente" => $id_cliente,
		"nombre" => trim($nombre." ".$apellidop." ".$apellidom),
		"telefono" => $telefono
		));
		
		}catch(Exception $e){
		
		echo json_encode(array(
        "ok" => false,
        "mensaje" => $e->getMessage()
		));
		
	}			