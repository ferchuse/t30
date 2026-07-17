<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/dame_permiso.php');
	$link = Conectarse();
	
	$consulta = "SELECT * FROM boletos_abiertos
	
	LEFT JOIN usuarios USING(id_usuarios) 
	LEFT JOIN taquillas USING(id_taquilla) 
	
	WHERE 1
	";
	
	
	
	
	if($_GET["id_usuarios"] != ""){
		$consulta.=" AND id_usuarios = '{$_GET["id_usuarios"]}' ";
	}
	if($_GET["estatus_boleto"] != ""){
		$consulta.=" AND estatus_boleto = '{$_GET["estatus_boleto"]}' ";
	}
	
	
	$consulta.= " ORDER BY fecha_boleto ";
	$result = mysqli_query($link,$consulta);
	
	if($result){
		$num_registros = mysqli_num_rows($result);
	?>
	<table class="table table-bordered" id="tabla_registros" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th class="text-center"></th>
				<th class="text-center">Folio</th>
				<th class="text-center">Nombre Pasajero</th>
				<th class="text-center">Fecha</th>
				<th class="text-center">Importe</th>
				<th class="text-center">Usuario</th>
				<th class="text-center">Taquilla</th>
				
			</tr>
		</thead>
		<tbody >
			<?php
				while($fila = mysqli_fetch_assoc($result)){ 
					
					
				?>
				
				<tr>
					<td>
						<?php
							if($fila["estatus_boleto"] == "Cancelado"){
								echo "<span class='badge badge-danger'>Cancelado <br>{$fila["datos_cancelacion"]}</span>";
							}
							else{
								
								$suma_gastos+= $fila["importe"];
								
								if(dame_permiso("boleto_abierto.php", $link) == 'Supervisor'){
								?>
								<button class="btn btn-danger cancelar" title="Cancelar"     data-id_registro='<?php echo $fila["id_boleto"]?>'>
									<i class="fas fa-times"></i>
								</button>	
								
								<?php 	
								}
							}
						?>
					</td>
					
					<td><?php echo $fila["id_boleto"];?></td>
					<td><?php echo $fila["nombre_pasajero"];?></td>
					<td><?php echo $fila["fecha_boleto"];?></td>
					<td>
						
						<?php echo $fila["estatus_boleto"] == "Cancelado" ? "" :"$".$fila["importe"];?>
						
						</td>
					<td><?php echo $fila["nombre_usuarios"];?></td>
					<td><?php echo $fila["nombre_taquilla"];?></td>
					
					
					
					
				</tr>
				
				<?php 	
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td >
					<?php echo mysqli_num_rows($result);?> Registros.
				</td>
				<td ></td>
				<td ></td>
				<td ><B> Total </b></td>
				<td >
					$<?php echo number_format($suma_gastos);?>
				</td>
				<td ></td>
			</tr>
		</tfoot>
	</table>
	
	
	<?php
		
		
	}
	else {
		echo "Error en".$consulta. mysqli_error($link);
	}
	
	
?>					