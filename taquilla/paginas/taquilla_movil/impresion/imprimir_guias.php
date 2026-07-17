<?php 
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	
	
	$consulta = "SELECT *, SUM(precio_boletos) AS suma_boletos FROM corridas 
	LEFT JOIN unidades USING(id_unidades) 
	LEFT JOIN origenes USING(id_origenes)
	LEFT JOIN (
	SELECT id_origenes AS id_destinos, 
	nombre_origenes AS nombre_destinos 
	FROM origenes ) AS t_destinos 
	USING(id_destinos)
	LEFT JOIN usuarios USING(id_usuarios)
	LEFT JOIN boletos USING(id_corridas)
	LEFT JOIN precios_boletos USING(id_precio)
	
	WHERE corridas.id_corridas = '{$_GET["id_registro"]}'
	
	GROUP BY id_precio
	";
  
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			
			die("<div class='alert alert-danger'>Tarjeta No encontrada</div>");
			
			
		}
		
		while($fila = mysqli_fetch_assoc($result)){
			
			$filas[] = $fila ;
			
		}
		
	?> 
	<div>
		
		<div class="row mb-1">
			<div class="col-6">
				<b >CORRIDA :</b>
			</div>	 
			<div class="col-6">			
				<?php echo $filas[0]["id_corridas"]?>
			</div>
		</div>
		<div class="row mb-1">
			<div class="col-6">
				<b >ORIGEN :</b>
			</div>	 
			<div class="col-6">			
				<?php echo $filas[0]["nombre_origenes"]?>
			</div>
		</div>
		<div class="row mb-1">
			<div class="col-6">
				<b >DESTINO :</b>
			</div>	 
			<div class="col-6">			
				<?php echo $filas[0]["nombre_destinos"]?>
			</div>
		</div>
		<div class="row mb-2">
			<div class="col-6">
				<b >NUM ECO:</b>
			</div>	 
			<div class="col-6">			
				<?php echo $filas[0]["num_eco"]?>
			</div>
		</div>
		<div class="row mb-2">
			<div class="col-6">
				<b >FECHA:</b>
			</div>	 
			<div class="col-6">			
				<?php echo$filas[0]["fecha_corridas"]?>
			</div>
		</div>
		<div class="row mb-2">
			<div class="col-6">
				<b >NUM PASAJEROS:</b>
			</div>	 
			<div class="col-6">			
				<?php echo count($filas)?>
			</div>
		</div>
	
		<hr>
		
		<div class="form-row">
			<div class="col-3">
				Tipo Boleto.	
			</div>
			<div class="col-9">
				Importe
			</div>
			 
		</div>
		
		<?php foreach($filas as $i => $fila){ ?>
			
			<div class="form-row">
				<div class="col-3">
					<?php echo $fila["tipo_precio"]?>
				</div>
				<div class="col-9">
					<?php echo $fila["suma_boletos"]?>
				</div>
			</div>
			<?php	 
			}
		?>
		
		<hr>
		
	</div>
	
	
	<?php
		
		
	}
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>