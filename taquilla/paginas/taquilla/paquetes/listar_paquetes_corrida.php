<?php 

	include('../../../conexi.php');
	include('../../../funciones/dame_permiso.php');
	
	$link = Conectarse();
	
	$suma_importe =0;
	
	$consulta = "SELECT * FROM paquetes
	LEFT JOIN taquillas ON taquillas.id_taquilla = paquetes.id_taquilla_destino
	LEFT JOIN usuarios USING(id_usuarios)
	
	WHERE 
	 id_corridas = '{$_GET["id_corridas"]}'
	";
	
	if($_GET["id_usuarios"] != ""){
		$consulta.=" AND id_usuarios = '{$_GET["id_usuarios"]}' ";
	}
	

	$consulta.= "ORDER BY id_paquetes ";
	$result = mysqli_query($link,$consulta);
	
	if($result){
		$num_registros = mysqli_num_rows($result);
	?>
	<table class="table table-bordered" id="tabla_registros" width="100%" cellspacing="0">
		<thead>
			<tr>
				
				<th class="text-center">Folio</th>
				<th class="text-center">Taquilla Destino</th>
				<th class="text-center">Tamaño</th>
				<th class="text-center">Costo</th>
				<th class="text-center">Usuario</th>
			
			</tr>
		</thead>
		<tbody >
			<?php
				while($fila = mysqli_fetch_assoc($result)){ 
				
					?>
				
				<tr>
					<td><?php echo $fila["id_paquetes"];?></td>
					<td><?php echo $fila["nombre_taquilla"];?></td>
					<td><?php echo $fila["tipo_paquete"];?></td>
					<td>$<?php echo $fila["costo"];?></td>
					<td><?php echo $fila["nombre_usuarios"];?></td>
					<td>
						<?php 
							
							if(dame_permiso("equipaje.php", $link) == 'Supervisor' AND $fila["estatus_paquetes"] != "Cancelado"){
							?>
							<button class="btn btn-danger btn_cancelar" title="Cancelar"     data-id_registro='<?php echo $fila["id_paquetes"]?>'>
								<i class="fas fa-times"></i>
							</button>	
							<?php
							}
							if($fila["estatus_paquetes"] == "Cancelado"){
								
								echo "<span class='badge badge-danger'>".$fila["estatus_paquetes"]. "<br>". $fila["datos_cancelacion"]."</span>";
								
							}
							else{
								$suma_importe+= $fila["costo"];
								
							}
						?>
						
					</td>
					
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
				<td ><B> Total Paquetes</b></td>
				<td ></td>
				<td >
					$<?php echo number_format($suma_importe,2);?>.
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