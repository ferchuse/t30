<?php 
	include('../../../conexi.php');
	
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	$boletos = implode("," ,$_GET['boletos']);
	
	$consulta = "SELECT * FROM boletos 
	
	LEFT JOIN usuarios  USING(id_usuarios)
	LEFT JOIN corridas  USING(id_corridas)
	LEFT JOIN precios_boletos  USING(id_precio)
	LEFT JOIN origenes  ON precios_boletos.id_origenes = origenes.id_origenes
	LEFT JOIN (
	SELECT 
	id_origenes AS id_destinos, 
	nombre_origenes AS nombre_destinos
	FROM origenes
	) AS destinos
	ON precios_boletos.id_destinos = destinos.id_destinos
	WHERE id_boletos IN($boletos)";
	
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			
			die("<div class='alert alert-danger'>Registro no encontrado</div>");
			
			
		}
		
		while($fila = mysqli_fetch_assoc($result)){
			
			$filas[] = $fila ;
			
		}
		
		
		
		// $texto .= file_get_contents('../boletos_iv/logo_brujaz.tmb');
		$texto .="\x1b"."@ ";
		foreach($filas as $i => $item){
			
			
			// ESC t 0x02
			// $texto.="\x1bt2";
			$texto.="    ENLACES DE TRANSPORTE TERRESTRE".chr(10).chr(13);
			$texto.="       7 DE ENERO, S.A. DE C.V.".chr(10).chr(13).chr(10).chr(13);
			
			if($item["id_taquilla"] == '4'){
				
				$texto.=" LATERAL AV. INSURGENTES NORTE,\n METRO INDIOS VERDES ANDEN  'A' \n";
				$texto.=" TEL: 55 57 50 08 58 \n\n";
				
			}
			else{
				
				$texto.="     CATEMACO 294-943-1725 \n";
				$texto.="     SAN ANDRES 294-942-1976 \n\n";
			}
			// $texto.="     TERMINAL EN MEXICO D.F. METRO".chr(10).chr(13); 
			// $texto.="             INDIOS VERDES".chr(10).chr(13);
			// $texto.="   ANDEN 'A' TEL:5750-0858   5750-0847".chr(10).chr(13);
			// $texto.="TERMINAL EN CATEMACO VERACRUZ  LERDO No. 4".chr(10).chr(13);
			// $texto.="     OFICINA SAN ANDRES, TUXTLA VER.".chr(10).chr(13);
			// $texto.=" DIRECCION: BOULEVARD 5 DE FEBRERO # 1270 ".chr(10).chr(13);
			// $texto.="        COL. CAMPECHE SAN ANDRES".chr(10).chr(13);
			// $texto.="                TUXTLA VER.".chr(10).chr(13);
			// $texto.="                C.P. 95720".chr(10).chr(13);
			// $texto.="         TELEFONO 294 9 42 1976".chr(10).chr(13);
			$texto.="NOTA: ESTE BOLETO ES BUENO PARA LA FECHA Y".chr(10).chr(13);
			$texto.=" HORA EN QUE SE  EXPIDE  Y LE DA DERECHO".chr(10).chr(13);
			$texto.="          AL SEGURO DE VIAJERO".chr(10).chr(13);
			$texto.="               EXIJALO.".chr(10).chr(13);
			// $texto.="  RUTA: ".$_GET['ruta'].chr(10).chr(13);
			$texto.="  FOLIO: ".$item["id_boletos"].chr(10).chr(13);
			$texto.="  FECHA VENTA: ".$item['fecha_boletos'].chr(10).chr(13);
			$texto.="  FECHA IMPRESION: ".date("d-m-Y H:i:s").chr(10).chr(13);
			$texto.="  TIPO DE BOLETO: ".$item["tipo_precio"].chr(10).chr(13);
			$texto.="  ORIGEN: ".$item["nombre_origenes"].chr(10).chr(13);
			$texto.="  DESTINO: ".$item["nombre_destinos"].chr(10).chr(13);
			$texto.="  PRECIO: ".$item["precio_boletos"].chr(10).chr(13);
			$texto.="  FECHA SALIDA: ".$item["fecha_corridas"].chr(10).chr(13);
			$texto.="  HORA SALIDA: ".$item["hora_corridas"].chr(10).chr(13);
			$texto.="  ASIENTO: ".$item["num_asiento"].chr(10).chr(13);
			$texto.="  UNIDAD: ". $item["num_eco"].chr(10).chr(13);
			$texto.="  TAQUILLERO: ". $item["nombre_usuarios"].chr(10).chr(13);
			// $texto.="  TIPO CRED: ".$_GET['tipocred'].chr(10).chr(13);
			// $texto.="  CREDENCIAL: ".$_GET['credencial'].chr(10).chr(13);
			$texto.="  PASAJERO: ".$item["nombre_pasajero"].chr(10).chr(13);
			$texto.="                      PASAJERO".chr(10).chr(13);
			$texto.=chr(10).chr(10).chr(13).chr(29).chr(86).chr(66).chr(0);
			
			
			//////COPIA OPERADOR
			
			
			$texto.=chr(10).chr(13);
			$texto.="    ENLACES DE TRANSPORTE TERRESTRE".chr(10).chr(13);
			$texto.="       7 DE ENERO, S.A. DE C.V.".chr(10).chr(13).chr(10).chr(13);
			$texto.="FOLIO: ".$item["id_boletos"].chr(10).chr(13);
			$texto.="FECHA VENTA: ".$item['fecha_boletos'].chr(10).chr(13);
			$texto.="FECHA IMPRESION: ".date("d-m-Y H:i:s").chr(10).chr(13);
			$texto.="TAQUILLERO: ". $item["nombre_usuarios"].chr(10).chr(13);
			$texto.="TIPO DE  BOLETO: ".$item["tipo_precio"].chr(10).chr(13);
			$texto.="ORIGEN: ".$item["nombre_origenes"].chr(10).chr(13);
			$texto.="DESTINO: ".$item["nombre_destinos"].chr(10).chr(13);
			$texto.="PRECIO: ".$item["precio_boletos"].chr(10).chr(13);
			$texto.="FECHA SALIDA: ".$item["fecha_corridas"].chr(10).chr(13);
			$texto.="HORA SALIDA: ".$item["hora_corridas"].chr(10).chr(13);
			$texto.="ASIENTO: ".$item["num_asiento"].chr(10).chr(13);
			$texto.="PASAJERO: ".$item["nombre_pasajero"].chr(10).chr(13);
			
			$texto.="UNIDAD: ".$item["num_eco"].chr(10).chr(13);
			// $texto.=chr(29)."h".chr(80).chr(29)."H".chr(2).chr(29)."k".chr(2)."1".sprintf("%011s",(intval($item["id_boletos"]))).chr(0);
			$texto.=chr(10).chr(13)."              OPERADOR".chr(10).chr(13);
			$texto.=chr(10).chr(10).chr(13).chr(29).chr(86).chr(66).chr(0);
			
		}
		
		
		
		$respuesta["consulta"] = $consulta;
		// echo json_encode ( $respuesta  );
		echo base64_encode ( $texto );
		// exit(0);
		
	}
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>