<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/dame_permiso.php');
	$link = Conectarse();
	
	$consulta = "SELECT * FROM lista_espera 
	LEFT JOIN usuarios USING(id_usuarios) 
	WHERE 1 
	";
	
	
	
	if($_GET["estatus"] != ''){
		$consulta.= " AND estatus = '{$_GET["estatus"]}' ";
	}
	
	if($_GET["cliente"] != ""){
		
		$consulta.=" AND cliente LIKE '%{$_GET["cliente"]}%'";
	}
	
	
	$consulta.= " ORDER BY id_espera ";
	$result = mysqli_query($link,$consulta);
	
	if($result){
		$num_registros = mysqli_num_rows($result);
	?>
	<table class="table table-bordered" id="tabla_registros" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th class="text-center">Folio</th>
				<th class="text-center">Cliente</th>
				<th class="text-center">Teléfono</th>
				<th class="text-center">Pasajeros</th>
				<th class="text-center">Estatus</th>
				<th class="text-center">Acciones</th>
			</tr>
		</thead>
		<tbody >
			<?php
				while($fila = mysqli_fetch_assoc($result)){
					
					switch($fila["estatus"]){
						
						case "En Espera":
						$color = "badge-warning";
						break;
						
						case "Finalizado":
						$color = "badge-success";
						break;
						case "Cancelado":
						$color = "badge-danger";
						break;
						
						
						
					}
					
				?>
				
				
				<tr>
					
					<td><?php echo $fila["id_espera"];?></td>
					<td><?php echo $fila["cliente"];?></td>
					<td><?php echo $fila["telefono"];?></td>
					<td><?php echo $fila["pasajeros"];?></td>
					
					<td><?php echo "<span class='badge $color'>{$fila["estatus"]}</span>";?></td>
					<td>
						
						<button class="btn btn-warning btn_editar" data-id_registro="<?php echo $fila["id_espera"];?>">
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
				<td colspan="7">
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