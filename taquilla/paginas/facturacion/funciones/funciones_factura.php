<?php
	function getEmisor($link,$id_emisores){
		$respuesta = [];
		
		$consulta = "SELECT * FROM emisores 
		WHERE id_emisores = '$id_emisores'";
		
		$result = mysqli_query($link,$consulta) ;
		
		if(!$result){
			$respuesta["error"] =  mysqli_error($link);
		}
		else{
			while($fila = mysqli_fetch_assoc($result)){
				$respuesta["datos"] = $fila;
			}
		}
		return $respuesta;
		
	}	
	
	
	
	
	
	
	// function facturaVacia($link){
	
	// $conceptos = "
	// SELECT  
	
	// 1 as cantidad,
	// 'E48' as clave_unidad,
	// '78111804'	 AS clave_sat,
	// CONCAT('VIAJE DESDE AIFA A ', destino)  AS descripcion,
	// '0.000000'  AS tasa_iva,
	// total    AS precio,
	// 0 AS iva
	
	// FROM facturas_detalle
	
	// WHERE id_facturas = '${id_facturas}'
	
	// ";
	
	// }
	
	function facturaVacia(){
		
		
		
		$factura = array
		(
		"id_clientes" => "13",
		"id_facturas" => "",
		"id_emisores" => 1,
		"folio_facturas" => "",
		"fecha_facturas" => "",
		"metodo_pago" => "PUE",
		"uso_cfdi" => "S01",
		"forma_pago"=> "04",
		"observaciones"=> "",
		"correo_clientes" => "facturacion@taxidriveraifa.com",
		"razon_social_clientes" =>"PUBLICO EN GENERAL",
		"rfc_clientes" => "XAXX010101000",
		"regimen_clientes" => "616",
		"cp_clientes" => "07700",
		"conceptos" => Array
        (
		0 => Array
		(
		"id_facturas" => "",
		"id_facturas_detalle" => "",
		"clave_productos" => "78111804",
		"clave_unidad" => "E48",
		"unidad" => "Unidad de Servicio",
		"cantidad" => 1,
		"descripcion" => "SERVICIOS PRIVADOS DE TRANSPORTE DE PASAJEROS ",
		"precio" => 0,
		"importe" => 0,
		"descuento" => 0,
		"impuestos" => Array
		(
		0 => Array
		(
		"id_facturas" => "",
		"id_facturas_detalle" => "",
		"tipo_impuesto" => "Traslado",
		"impuesto" => "002",
		"base" => 0,
		"tasa" => "0",
		"tipo_factor" => "Exento",
		"impuesto_importe" => 0
		)
		
		)
		
		)
		
        )
		
		);
		
		// $factura = array
		// (
		// ["id_clientes"] => "",
		// ["id_facturas"] => ""
		// );
		
		// print_r($factura);
		
		return $factura;
		
	}
	
	function copiarFactura($link,$id_facturas){
		$factura = [];
		$conceptos = [];
		
		
		$consulta_factura = " SELECT  * FROM facturas
		LEFT JOIN clientes USING(id_clientes)
		WHERE id_facturas = '${id_facturas}' ";
		
		
		$consulta_conceptos = " SELECT  * FROM facturas_detalle
		WHERE id_facturas = '${id_facturas}' ";
		
		
		
		
		
		$result_factura = mysqli_query($link,$consulta_factura) or die( mysqli_error($link)) ;
		$result_conceptos = mysqli_query($link,$consulta_conceptos) or die( mysqli_error($link)) ;
		
		
		
		while($fila = mysqli_fetch_assoc($result_factura)){
			$factura=$fila ;
		}
		
		while($fila_conceptos = mysqli_fetch_assoc($result_conceptos)){
			$impuestos = [];
			$concepto=  $fila_conceptos ;
			
			$consulta_impuestos = " SELECT  * FROM facturas_impuestos
			WHERE id_facturas_detalle = '${fila_conceptos["id_facturas_detalle"]}' ";
			
			$result_impuestos = mysqli_query($link,$consulta_impuestos)or die( mysqli_error($link)) ;
			
			while($fila_impuestos = mysqli_fetch_assoc($result_impuestos)){
				$impuestos[]=$fila_impuestos ;
			}
			
			$concepto["impuestos"] =  $impuestos;
			
			
			$factura["conceptos"][] = $concepto;
		}
		
		
		
		
		
		
		return $factura;
		
	}	
	
	
	function copyProductos($link,$folios){
		$respuesta = [];
		
		$consulta = "
		SELECT  
		
		1 as cantidad,
		
		'E48' as clave_unidad,
		'78111804'	 AS clave_sat,
		CONCAT('VIAJE DESDE AIFA A ', destino)  AS descripcion,
		'0.000000'  AS tasa_iva,
		total    AS precio,
		0 AS iva
		
		FROM boletos
		
		WHERE id_boletos IN ($folios)
		
		";
		$respuesta["consulta"] = $consulta;
		
		$result = mysqli_query($link,$consulta) ;
		
		if(!$result){
			die(mysqli_error($link));
			$respuesta["error"] =  mysqli_error($link);
			
		}
		else{
			while($fila = mysqli_fetch_assoc($result)){
				$respuesta["productos"][] = $fila;
				
			}
		}
		
		return $respuesta;
		
	}	
	
	function copyVenta($link,$folios, $fecha){
		$respuesta = [];
		
		$consulta = "SELECT * FROM boletos 
		
		WHERE id_boletos IN ($folios) AND DATE(fecha_boletos) = '$fecha'
		";
		$respuesta["consulta"] = $consulta;
		
		$result = mysqli_query($link,$consulta) ;
		
		if(!$result){
			die( mysqli_error($link));
			
		}
		else{
			while($fila = mysqli_fetch_assoc($result)){
				$respuesta["fila"] = $fila;
				
			}
		}
		
		return $respuesta;
		
	}	
	
	
	
	function getFolio($link, $id_emisores){
		$respuesta=[];
		
		$consulta= "SELECT serie, folio FROM emisores 
		WHERE id_emisores = '$id_emisores'
		";
		
		$result = mysqli_query($link,$consulta) ;
		
		if(!$result){
			$respuesta["error"] =  mysqli_error($link);
			
		}
		else{
			while($fila = mysqli_fetch_assoc($result)){
				$respuesta["serie"] = $fila["serie"];
				$respuesta["folio"] = $fila["folio"];
				
			}
		}
		
		return $respuesta;
		
	}
	
	function getProductos($link,$id_emisores, $id_selected = 0){
		$respuesta = "";
		$query = "SELECT * FROM productos_sat 
		FULL JOIN productos_emisor USING(id_productos) 
		WHERE id_emisores = '$id_emisores'
		ORDER BY descripcion_productos
		";
		
		$result = mysqli_query($link,$query) ;
		
		if(!$result){
			return "<option value=''>Ocurrio un error".mysqli_error($link)."</option>"; 
		}
		else{
			while($fila = mysqli_fetch_assoc($result)){
				$respuesta.= "<option value='{$fila["id_productos"]}'";
				$respuesta.= $fila["id_productos"] == $id_selected ? " selected " : "";
				$respuesta.= ">";
				
				
				$respuesta.= $fila["descripcion_productos"]."-".$fila["id_productos"]."</option>"; 
			}
		}
		return $respuesta; 
	}
	
	function getUnidades($link,$id_emisores, $id_selected = 0 ){
		$respuesta = "";
		$query = "SELECT * FROM unidades_sat 
		FULL JOIN unidades_emisor USING(id_unidades) 
		WHERE id_emisores = '$id_emisores'
		ORDER BY nombre_unidades
		";
		
		$result = mysqli_query($link,$query) ;
		
		if(!$result){
			return "<option value=''>Ocurrio un error".mysqli_error($link)."</option>"; 
		}
		else{
			while($fila = mysqli_fetch_assoc($result)){
				$respuesta.= "<option value='{$fila["id_unidades"]}'";
				$respuesta.=  $fila["id_unidades"] == $id_selected ? " selected " : "";
				$respuesta.= "'>";
				
				
				
				$respuesta.= $fila["nombre_unidades"]."</option>"; 
			}
		}
		return $respuesta; 
	}
	
	
?>