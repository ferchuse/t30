<?php 
	session_start();
	include('../../../conexi.php');
	$link = Conectarse();
	
	$consulta = "SELECT * FROM unidades_historial
	LEFT JOIN usuarios USING (id_usuarios)
	WHERE num_eco = '{$_GET["num_eco"]}'
	ORDER BY fecha_modificacion DESC
	
	";
	
	
	// $consulta.= "ORDER BY num_eco ";
	$result = mysqli_query($link,$consulta);
	
	if($result){
		$num_registros = mysqli_num_rows($result);
	?>
	<div class="table-responsive">
		<table class="table table-bordered" id="tabla_registros" width="100%" cellspacing="0">
			<thead>
				<tr>
					<th class="text-center">Fecha</th>
					<th class="text-center">Usuario</th>
					<th class="text-center">Campo Modificado</th>
					<th class="text-center">Valor Anterior</th>
					<th class="text-center">Valor Nuevo</th>
					
				</tr>
			</thead>
			<tbody >
				<?php
					while($fila = mysqli_fetch_assoc($result)){?>
					
					<tr>
						<td><?php echo $fila["fecha_modificacion"];?></td>
						<td><?php echo $fila["nombre_usuarios"];?></td>
						<td><?php echo $fila["campo_modificado"];?></td>
						<td><?php echo $fila["valor_anterior"];?></td>
						<td><?php echo $fila["valor_nuevo"];?></td>
						
					</tr>
					
					<?php 	
					}
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="7">
						<?php echo mysqli_num_rows($result);?> Registros.
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
	
	
	<?php
		
		
	}
	else {
		echo "Error en".$consulta. mysqli_error($link);
	}
	
	
?>	