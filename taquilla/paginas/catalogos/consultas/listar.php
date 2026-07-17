<?php 
	session_start();
	include('../../../conexi.php');
	$link = Conectarse();
	
	$consulta = "SELECT * FROM taquillas";
	
	
	$result = mysqli_query($link,$consulta);
	
	if($result){
		$num_registros = mysqli_num_rows($result);
	?>
	<table class="table table-bordered" id="tabla_registros" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th class="text-center">Id</th>
				<th class="text-center">Nombre</th>
				<th class="text-center">Hora de Salida</th>
			
			</tr>
		</thead>
		<tbody >
			<?php
				while($fila = mysqli_fetch_assoc($result)){?>
				
				<tr>
					<td><?php echo $fila["id_taquilla"];?></td>
					<td><?php echo $fila["nombre_taquilla"];?></td>
					<td><?php echo $fila["hora_salida"];?></td>
					<td>
						<button class="btn btn-warning btn_editar" data-id_registro="<?php echo $fila["id_taquilla"];?>">
							<i class="fas fa-edit"></i>
						</button>
					</td>
				</tr>
				
				<?php 	
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3">
					<?php echo mysqli_num_rows($result);?> Registros.
				</td>
			</tr>
		</tfoot>
	</table>
	
	
	<?php
		
		
	}
	else {
		echo "Error en".$consulta. mysqli_error($link);
	}
	
	
?>	