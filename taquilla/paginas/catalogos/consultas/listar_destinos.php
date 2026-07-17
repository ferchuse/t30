<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/dame_permiso.php');
	$link = Conectarse();
	
	$consulta = "SELECT * FROM destinos WHERE 1";
	
	if($_GET["destino"] != ""){
		
		$consulta.= " AND destino LIKE '%{$_GET["destino"]}%' ";
	}
	
	
	$consulta.= " ORDER BY {$_GET["order_by"]} ";
	
	$result = mysqli_query($link,$consulta);
	
	if($result){
		$num_registros = mysqli_num_rows($result);
	?>
	<table class="table table-bordered" id="tabla_registros" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th class="text-center">Zona</th>
				<th class="text-center">Destino</th>
				<th class="text-center">Precio Sedan</th>
				<th class="text-center">Precio Ejecutiva</th>
				<th class="text-center">Acciones</th>
				
			</tr>
		</thead>
		<tbody >
			<?php
				while($fila = mysqli_fetch_assoc($result)){?>
				
				<tr>
					<td><?php echo $fila["zona"];?></td>
					<td><?php echo $fila["destino"];?></td>
					<td class="text-right">$<?php echo number_format($fila["precio"],2);?></td>
					<td class="text-right">$<?php echo number_format($fila["precio_ejecutiva"],2);?></td>
					<td>
						
						<button class="btn btn-warning btn_editar" data-id_registro="<?php echo $fila["id_precio"];?>">
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