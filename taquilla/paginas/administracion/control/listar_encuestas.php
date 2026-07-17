<?php 
	session_start();
	include('../../../conexi.php');
	$link = Conectarse();
	
	$consulta = "SELECT * FROM encuestas
	LEFT JOIN boletos USING(id_boletos)
	LEFT JOIN usuarios USING(id_usuarios)
	LEFT JOIN conductores USING(id_conductores)
	
	
	WHERE DATE(fecha_encuesta) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	
	ORDER BY fecha_encuesta
	"; 
	
	$result = mysqli_query($link,$consulta);
	
	if($result){
		$num_registros = mysqli_num_rows($result);
	?>
	<table class="table table-bordered" id="tabla_registros" width="100%" cellspacing="0">
		<thead>
			<tr class="text-center">
				<th >Folio Boleto </th>
				<th >Fecha </th>
				<th >Destino</th>
				<th >Usuario Taquilla</th>
				<th >Conductor</th>
				<th >Tipo Unidad</th>
				<th >Frecuencia </th>
				<th >Calif Taquilla </th>
				<th >Calif Modulacion </th>
				<th >Calif Conductor </th>
				<th >Satisfecho Limpieza </th>
				<th >Volvería a Viajar </th>
				<th >Comentarios</th>
			</tr>
		</thead>
		<tbody >
			<?php
				while($fila = mysqli_fetch_assoc($result)){
					
				
				?>
				
				<tr>
					<td><?php echo $fila["id_boletos"];?></td>
					<td><?php echo date("d/m/Y H:i", strtotime($fila["fecha_encuesta"]));?></td>
					<td><?php echo $fila["destino"];?></td>
					<td><?php echo $fila["nombre_usuarios"];?></td>
					<td><?php echo $fila["nombre_conductores"];?></td>
					<td><?php echo $fila["tipo_unidad"];?></td>
					<td><?php echo $fila["frequencia_viaje"];?></td>
					<td><?php echo $fila["rating_taquilla"];?></td>
					<td><?php echo $fila["rating_modulacion"];?></td>
					<td><?php echo $fila["rating_conductor"];?></td>
					<td><?php echo $fila["limpieza"];?></td>
					<td><?php echo $fila["volveria_viajar"];?></td>
					<td><?php echo $fila["comentarios"];?></td>
					
				</tr>
				
				<?php 	
				}
			?>
		</tbody>
	</table>
	
	
	<?php
		
		
	}
	else {
		echo "Error en".$consulta. mysqli_error($link);
	}
	
	
?>	