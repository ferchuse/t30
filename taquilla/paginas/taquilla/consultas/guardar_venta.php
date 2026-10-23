<?php 
	include("../../../conexi.php");
	$link = Conectarse();
	
	
	if(!isset($_COOKIE["id_usuarios"])){
		
		$respuesta["error"] = "La sesión a caducado, vuelva a iniciar sesión";
		echo json_encode($respuesta);
		
		exit();
	}

	$id_cliente_frecuente = isset($_POST['id_cliente_frecuente']) ? (int) $_POST['id_cliente_frecuente'] : 0;
	$usar_puntos = isset($_POST['usar_puntos']) && $_POST['usar_puntos'] === '1';
	$subtotal = max(0, (float) $_POST['subtotal']);
	$descuento_general = 0;
	$codigo_cupon = isset($_POST['codigo_cupon']) && is_string($_POST['codigo_cupon']) ? strtoupper(trim($_POST['codigo_cupon'])) : '';
	$promocion = null;
	if ($usar_puntos && $codigo_cupon !== '') {
        echo json_encode(array('estatus'=>'error','error'=>'Solo puedes usar un descuento: puntos o cupón, no ambos.'));
        exit;
    }
	$total_base = max(0, $subtotal - $descuento_general);
	$puntos_canjeados = 0;
	$puntos_otorgados = 0;
	$descuento_puntos = 0.00;
	$cliente_sql = 'NULL';

	mysqli_begin_transaction($link);
	require_once __DIR__ . '/promocion.php';
    if ($id_cliente_frecuente > 0) {
        // Serializa las ventas del cliente antes de comprobar primera compra.
        $bloqueoCliente = mysqli_query($link, "SELECT id_cliente_frecuente FROM clientes_frecuentes WHERE id_cliente_frecuente={$id_cliente_frecuente} FOR UPDATE");
        if (!$bloqueoCliente) { mysqli_rollback($link); echo json_encode(array('error'=>'No fue posible validar al cliente.')); exit; }
    }
	if ($codigo_cupon !== '') {
		require_once __DIR__ . '/promocion.php';
		try { $promocion = consultarCupon($link, $codigo_cupon, $subtotal, true, $id_cliente_frecuente); }
		catch (Exception $e) { mysqli_rollback($link); echo json_encode(array('estatus'=>'error','error'=>$e->getMessage())); exit; }
		$descuento_general = $promocion['importe'];
	}
    elseif (!$usar_puntos && $id_cliente_frecuente > 0) {
        try { $promocion = promocionPrimeraCompra($link, $id_cliente_frecuente, $subtotal, true); }
        catch (Exception $e) { mysqli_rollback($link); echo json_encode(array('error'=>$e->getMessage())); exit; }
        if ($promocion) { $codigo_cupon=$promocion['codigo_promocion']; $descuento_general=$promocion['importe']; }
    }
	$total_base = max(0, $subtotal - $descuento_general);
	if ($id_cliente_frecuente > 0) {
		$cliente = mysqli_query($link, "SELECT id_cliente_frecuente, puntos_cliente FROM clientes_frecuentes WHERE id_cliente_frecuente = {$id_cliente_frecuente} AND estatus_cliente = 'Activo' LIMIT 1 FOR UPDATE");
		$cliente_datos = $cliente ? mysqli_fetch_assoc($cliente) : null;
		if (!$cliente_datos) {
			mysqli_rollback($link);
			echo json_encode(['estatus' => 'error', 'error' => 'El cliente seleccionado no es válido.']);
			exit();
		}
		$cliente_sql = $id_cliente_frecuente;
		$configuracion = mysqli_query($link, "SELECT activo, monto_por_punto, valor_punto, canje_minimo FROM configuracion_lealtad WHERE id_configuracion = 1 LIMIT 1");
		$lealtad = $configuracion ? mysqli_fetch_assoc($configuracion) : null;
		if ($lealtad && (int) $lealtad['activo'] === 1) {
			$puntos_disponibles = (int) $cliente_datos['puntos_cliente'];
			if ($usar_puntos && $puntos_disponibles >= (int) $lealtad['canje_minimo']) {
				$puntos_canjeados = $puntos_disponibles;
				$descuento_puntos = min($total_base, round($puntos_canjeados * (float) $lealtad['valor_punto'], 2));
			}
			if ((float) $lealtad['monto_por_punto'] > 0) {
				$puntos_otorgados = (int) floor(max(0, $total_base - $descuento_puntos) / (float) $lealtad['monto_por_punto']);
			}
		}
	}
	$total_final = max(0, $total_base - $descuento_puntos);
	$descuento_total = round($descuento_general + $descuento_puntos, 2);
	$id_promocion_sql = $promocion ? (int)$promocion['id_promocion'] : 'NULL';
	$codigo_cupon_sql = mysqli_real_escape_string($link, $codigo_cupon);
	$suma_pagos = round((float)$_POST['efectivo'] + (float)$_POST['tarjeta'] + (float)$_POST['transferencia'], 2);
	if (abs($suma_pagos - $total_final) > 0.009) { mysqli_rollback($link); echo json_encode(array('estatus'=>'error','error'=>'El total cambió. Vuelve a aplicar el cupón y verifica los pagos.')); exit; }
	
	
	$insert = "INSERT INTO boletos SET
	fecha_boletos = NOW(),
	id_usuarios = '{$_COOKIE["id_usuarios"]}',
	origen = '{$_POST["origen"]}',
	destino = UPPER('{$_POST["destino"]}'),
	nombre_pasajero = UPPER('{$_POST["nombre_pasajero"]}'),
	pasajeros = '{$_POST["pasajeros"]}',
	cp_destino = '{$_POST["cp_destino"]}',
	celular = '{$_POST["celular"]}',
	id_conductores = '{$_POST["id_conductores"]}',
	total = '{$total_final}',
	num_eco = '{$_POST["num_eco"]}',
	efectivo = '{$_POST["efectivo"]}',
	tarjeta = '{$_POST["tarjeta"]}',
	transferencia = '{$_POST["transferencia"]}',
	facturar = '{$_POST["facturar"]}',
	forma_pago = '{$_POST["forma_pago"]}',
	taquilla = '{$_POST["taquilla"]}',
	id_terminal = '{$_POST["id_terminal"]}',
	domicilio = '{$_POST["domicilio"]}',
	id_cliente_frecuente = {$cliente_sql},
	puntos_otorgados = {$puntos_otorgados},
	puntos_canjeados = {$puntos_canjeados},
	descuento_puntos = '{$descuento_puntos}',
	subtotal_boleto = '{$subtotal}',
	id_promocion = {$id_promocion_sql},
	codigo_cupon = '{$codigo_cupon_sql}',
	descuento_promocion = '{$descuento_general}',
	descuento_total = '{$descuento_total}'
	
	
	";
	
	// $respuesta["insert"] = $insert;
	$exec_query = mysqli_query($link,$insert);
	
	if($exec_query){
		$folio_creado = mysqli_insert_id($link);
        if ($promocion) {
            try { registrarUsoPrimeraCompra($link, $promocion, $id_cliente_frecuente, $folio_creado); }
            catch (Exception $e) { mysqli_rollback($link); echo json_encode(array('error'=>'No fue posible registrar el uso de la promoción.')); exit; }
        }
		if ($promocion && !mysqli_query($link, "UPDATE promociones SET usos_realizados = usos_realizados + 1 WHERE id_promocion = {$id_promocion_sql}")) { mysqli_rollback($link); echo json_encode(array('estatus'=>'error','error'=>'No fue posible registrar el uso del cupón.')); exit; }
		if ($id_cliente_frecuente > 0) {
			$actualizar_puntos = "UPDATE clientes_frecuentes SET puntos_cliente = puntos_cliente - {$puntos_canjeados} + {$puntos_otorgados} WHERE id_cliente_frecuente = {$id_cliente_frecuente}";
			if (!mysqli_query($link, $actualizar_puntos)) {
				mysqli_rollback($link);
				echo json_encode(['estatus' => 'error', 'error' => 'No fue posible actualizar los puntos del cliente.']);
				exit();
			}
		}
		mysqli_commit($link);
		$respuesta["estatus"] = "success";
		$respuesta["folio"] = $folio_creado;
		
	}
	else{
		mysqli_rollback($link);
		$respuesta["estatus"] = "error";
		$respuesta["error"] = mysqli_error($link);	
		
	}
	
	
	
	
	
	
	
	
	
	echo json_encode($respuesta);
?>	
