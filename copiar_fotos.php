<?php
	include("taquilla/conexi.php");
	
	$conexion = Conectarse();
	
	if($_SERVER["SERVER_NAME"] == "localhost"){
		
		$directorio = "http://{$_SERVER["SERVER_NAME"]}".dirname($_SERVER["PHP_SELF"])."/";
	}
	else{
		
		$directorio = "https://{$_SERVER["SERVER_NAME"]}".dirname($_SERVER["PHP_SELF"])."/";
	}
	
	$columnas = array("foto_poliza", "foto_tarjeta", "foto_factura", "foto_unidad");
	$columna_id= "id_unidades";
	$tabla = "unidades";
	
	$sql = "SELECT * FROM {$tabla} WHERE foto_poliza LIKE '%cloudinary%' LIMIT 5";
	
	
	$resultado = mysqli_query($conexion, $sql) or die(	"Error al ejecutar la consulta: " . mysqli_error($conexion));
	
	// Obtener el valor de max_execution_time
	$max_execution_time = ini_get('max_execution_time');
	
	// Imprimir el valor
	echo "$sql <br>";
	echo "El valor de max_execution_time es: $max_execution_time segundos<br>";
	echo "Copiando ".mysqli_num_rows($result) ;
	
	if ($resultado) {
		// Iterar sobre cada fila
		while ($fila = mysqli_fetch_assoc($resultado)) {
			
			$id = $fila[$columna_id];
			
			foreach($columnas AS $columna){
				echo $columna ."<br>";
				
				if($fila[$columna] != ""){
					$ruta_actual = $fila[$columna];
					$nueva_ruta = 'fileupload/files/' . basename($ruta_actual);
					
					if (copy($ruta_actual, $nueva_ruta)) {
						echo 'foto_vale copiada exitosamente.';
						
						// Actualizar la fila con la nueva ruta
						$sql_actualizar = "UPDATE {$tabla} SET {$columna}='{$directorio}{$nueva_ruta}'
						
						
						
						WHERE {$columna_id} = '{$id}'";
						
						if (mysqli_query($conexion, $sql_actualizar)) {
							echo "Ruta actualizada para  ID $id ".mysqli_affected_rows($sql_actualizar)."<br>";
						} 
						else {
							echo "Error al actualizar la ruta: " . mysqli_error($conexion);
						}
					} 
					else {
						echo 'Hubo un error al copiar la imagen.' .$directorio.$nueva_ruta."<br>";
					}
				}
			}
			
			
			
			
			
			
			
			
			
		}
	}
	
	
	
	/*
		
		// URL de la imagen que quieres copiar
		$url = 'https://res.cloudinary.com/dlktzxrwz/image/upload/v1713915314/ydegxsom6yq026op0ost.jpg';
		
		$nombre_imagen = basename($url);
		
		
		// Ruta donde quieres guardar la imagen en el servidor
		$ruta_local = '../afiliados/fotos/'. $nombre_imagen;
		
		// Copiar la imagen
		if (copy($url, $ruta_local)) {
		echo 'Imagen copiada exitosamente.';
		} else {
		echo 'Hubo un error al copiar la imagen.';
		}
		
	*/
?>