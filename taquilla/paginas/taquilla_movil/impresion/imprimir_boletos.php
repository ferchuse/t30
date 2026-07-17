<?php 
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	$boletos = implode("," ,$_GET['boletos']);
	
	$consulta = "SELECT * FROM boletos 
	LEFT JOIN corridas  USING(id_corridas)
	LEFT JOIN origenes  USING(id_origenes)
	WHERE id_boletos IN($boletos)";
  
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			
			die("<div class='alert alert-danger'>Registro no encontrado</div>");
			
			
		}
		
		while($fila = mysqli_fetch_assoc($result)){
			
			$filas[] = $fila ;
			
		}
		
	?> 
	<div>
		
		<?php foreach($filas as $i => $item){?>
			<h4>Copia Taquilla</h4>
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Boleto #:</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["id_boletos"]?>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Origen :</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["nombre_origenes"]?>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Destino :</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["nombre_destinos"]?>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Fecha :</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["fecha_corridas"]?>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Hora :</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["hora_corridas"]?>
				</div>
			</div>
			
			
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Num Eco :</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["num_eco"]?>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Tipo de Boleto :</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["tipo_boleto"]?>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Asiento :</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["num_asiento"]?>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Nombre Pasajero :</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["nombre_pasajero"]?>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Precio :</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["precio"]?>
				</div>
			</div>
			<hr class="" style="page-break-after:always;">
			
			<hr style="page-break-after: always">
			
			<h4>Copia Pasajero</h4>
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Boleto #:</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["id_boletos"]?>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Origen :</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["nombre_origenes"]?>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Destino :</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["nombre_destinos"]?>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Fecha :</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["fecha_corridas"]?>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Hora :</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["hora_corridas"]?>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Num Eco :</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["num_eco"]?>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Tipo de Boleto :</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["tipo_boleto"]?>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Asiento :</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["num_asiento"]?>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Nombre Pasajero :</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["nombre_pasajero"]?>
				</div>
			</div>
			<div class="form-row mb-2">
				<div class="col-4">
					<b >Precio :</b>
				</div>	 
				<div class="col-8">			
					<?php echo $item["precio"]?>
				</div>
			</div>
			
			
			<hr class="" style="page-break-after:always;">
			
			
			<?php	 
			}
		?>
		
		
	</div>
	
	
	<?php
		
		
	}
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>