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
	
	
	
	
	
	
	function copyProductos($link,$folios){
		$respuesta = [];
		
		$consulta = "
		SELECT  
		
		1 as cantidad,
		
		'E48' as clave_unidad,
		'Unidad de servicio' as nombre_unidades,
		'78111804'	 AS clave_sat,
		#CONCAT('VIAJE DESDE ', origen, ' A ', destino)  AS descripcion,
		CONCAT('SERVICIO DE TAXI LOCAL AIFA, DESTINO: ', destino, ' FOLIO ', id_boletos)  AS descripcion,
		'0.000000'  AS tasa_iva,
		total    AS precio,
		0    AS cant_descuento,
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
	
	function copyVenta($link,$folios, $fecha, $total){
		$respuesta = [];
		
		$consulta = "SELECT * FROM boletos 
		LEFT JOIN facturas USING(id_facturas)
		WHERE id_boletos IN ($folios) 
		AND DATE(fecha_boletos) = '$fecha'
		AND boletos.total = '{$total}'
		
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