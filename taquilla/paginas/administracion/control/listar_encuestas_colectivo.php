<?php 

	include('../../../conexi.php');
	$link = Conectarse();
	
	$consulta = "SELECT * FROM encuestas_colectivo
	WHERE DATE(fecha) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'
	ORDER BY fecha";
	
	$result = mysqli_query($link, $consulta);
	
	if ($result) {
		$num_registros = mysqli_num_rows($result);
	?>
	<table class="table table-bordered" id="tabla_registros" width="100%" cellspacing="0">
		<thead>
			<tr class="text-center">
				<th>ID</th>
				<th>Nombre</th>
				<th>Contacto</th>
				<th>Frecuencia</th>
				<th>Horario de Llegada</th>
				<th>Origen</th>
				<th>Destino</th>
				<th>Transporte</th>
				<th>Costo Actual</th>
				<th>Costo Dispuesto</th>
				<th>Horario Servicio</th>
				<th>Tiempo de Viaje</th>
				<th>Aspectos Importantes</th>
				<th>Reservación</th>
				<th>Comentarios</th>
				<th>Fecha</th>
				<th>UID</th>
			</tr>
		</thead>
		<tbody>
			<?php
			while ($fila = mysqli_fetch_assoc($result)) {
			?>
				<tr>
					<td><?php echo $fila["id"]; ?></td>
					<td><?php echo $fila["nombre"]; ?></td>
					<td><?php echo $fila["contacto"]; ?></td>
					<td><?php echo $fila["frecuencia"]; ?></td>
					<td><?php echo $fila["horario_llegada"]; ?></td>
					<td><?php echo $fila["origen"] . "<br>" . $fila["origen_otro"]; ?></td>
					<td><?php echo $fila["destino"] . "<br>" . $fila["destino_otro"]; ?></td>
					<td><?php echo $fila["transporte"] . "<br>" . $fila["transporte_otro"]; ?></td>
					<td><?php echo $fila["costo_actual"] . "<br>" . $fila["costo_otro"]; ?></td>
					<td><?php echo $fila["costo_dispuesto"] . "<br>" . $fila["costo_dispuesto_otro"]; ?></td>
					<td><?php echo $fila["horario_servicio"] . "<br>" . $fila["horario_servicio_otro"]; ?></td>
					<td><?php echo $fila["tiempo_viaje"]; ?></td>
					<td><?php echo $fila["aspectos_importantes"] . "<br>" . $fila["aspectos_importantes_otro"]; ?></td>
					<td><?php echo $fila["reservacion"]; ?></td>
					<td><?php echo $fila["comentarios"]; ?></td>
					<td><?php echo date("d/m/Y H:i", strtotime($fila["fecha"])); ?></td>
					<td><?php echo $fila["uid"]; ?></td>
				</tr>
			<?php
			}
			?>
		</tbody>
	</table>
	<?php
	} else {
		echo "Error en " . $consulta . " " . mysqli_error($link);
	}
?>
