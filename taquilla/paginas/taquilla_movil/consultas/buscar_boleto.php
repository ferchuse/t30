<?php 
	include('../../../conexi.php');
	
	$link = Conectarse();
	
	
	// $taq = intval(substr($_POST['boleto'],1,2));
	// $costo = intval(substr($_POST['boleto'],3,4));
	
	// si la lngitud de la cadena es 8 entondes solo tomar los ultimos 2 digitos sino toma del
	
	$folio = intval($_POST['boleto']);		
	
	
	
	$resultado = array('error' => 0, 'mensaje' => '', 'html' => '');
	
	$resultado['folio'] = $folio;
	// $resultado['taq'] = $taq;
	
	$resultado['longitud'] = strlen($_POST['boleto']);
	$start_time = microtime(true);
	
	$buscar_boleto="
	SELECT *, 		
	DATEDIFF(CURDATE(), fecha_boletos) as dias 
	FROM sencillos_boletos 
	
	WHERE  id_boletos = '$folio'";
	
	
	$resultado['buscar_boleto'] = $buscar_boleto ;
	
	$res = mysqli_query($link, $buscar_boleto);
	
	$end_time = microtime(true);
	
	$query_time = $end_time - $start_time;
	$resultado['query_time'] = $query_time;
	
	if($row = mysqli_fetch_array($res)){
		if($row['folio_recaudacion'] > 0){
			$resultado['error'] = 1;
			$resultado['mensaje'] = "El boleto ya fue cobrado en el recibo {$row["folio_recaudacion"]} y fue ponchado por {$row["usuario_ponchado"]} el dia " . date("d-m-Y H: i:s" , strtotime($row["fecha_ponchado"]));
		}
		elseif($row['estatus_boletos']== 'Cancelado'){
			$resultado['error'] = 1;
			$resultado['mensaje'] = 'El boleto está cancelado';
		}
		elseif($row['estatus_ponchado']== "Recaudado"){
			$resultado['error'] = 1;
			$resultado['mensaje'] = 'El boleto ya esta recaudado';
		}
		elseif($row['estatus_ponchado'] == "Ponchado"){
			$resultado['error'] = 1;
			$resultado['mensaje'] = "El boleto ya ha sido ponchado por {$row["usuario_ponchado"]} el dia " . date("d-m-Y H: i:s" , strtotime($row["fecha_ponchado"]));
		}
		// elseif($row['dias']>5){
		// $resultado['error'] = 1;
		// $resultado['mensaje'] = 'El boleto esta caducado mayor 5 dias';
		// }
		else{
			//El boleto si existe
			
			// $resultado['html'] .= rowb(false);
			$resultado['html'] .= '<tr><td align="center">';
			// $resultado['html'] .= "<input type='hidden'  name='taquilla[]' value='{$row['taquilla']}'>";
			$resultado['html'] .= "<input type='hidden' name='folio_boleto[]' value='{$row['id_boletos']}'>";
			$resultado['html'] .= '<button class="btn btn-danger btn-sm btn_borrar d-none"   data-taquilla="'.$row['taquilla'].'" data-folio="'.$row['id_boletos'].'" data-monto="'.$row['precio'].'" title="Quitar">
			<i class="fas fa-trash"></i> 
			</button>';
			$resultado['html'] .= '</td>';
			// $resultado['html'] .= '<td align="left">'.utf8_encode($row['taquilla_nombre']).'</td>';
			$resultado['html'] .= '<td align="center id_boletos"> <input type="hidden" name="id_boletos[]" value="'.$row['id_boletos'].'">'.$row['id_boletos'].'</td>';
			$resultado['html'] .= '<td align="center">'.date("d-m-Y H:i:s" ).'</td>';
			$resultado['html'] .= '<td align="center">'.$row['destino'].'</td>';
			$resultado['html'] .= '<td align="right" class="monto" data-monto="'.$row['precio'].'">$'.number_format($row['precio'],2).'</td>';
			$resultado['html'] .= '</tr>';
			
			
			
			
			//Cambiar a recaudado
			
			$update_boleto =  "UPDATE sencillos_boletos SET 
			estatus_ponchado = 'Ponchado' ,
			fecha_ponchado = NOW(),
			usuario_ponchado = '{$_COOKIE["nombre_usuarios"]}'
			
			WHERE id_boletos = '{$folio}'";
			
			
			$result_update_boleto = mysqli_query($link, $update_boleto);
			
			$resultado['update_boleto'] = $result_update_boleto ;
			
			
		}
	}
	else{
		$resultado['error'] = 1;
		$resultado['buscar_boleto'] = $buscar_boleto;
		$resultado['mensaje'] = 'No se encontró el boleto';
	}
	
	
	
	echo json_encode($resultado);
	exit();
?>			