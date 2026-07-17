<?php 
	
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/dame_permiso.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	
	
	$consulta = "SELECT * FROM sencillos_boletos
	WHERE estatus_ponchado = 'Ponchado'
	AND DATE(fecha_ponchado) = CURDATE() 
	AND estatus_boletos = 'Activo'
	AND folio_recaudacion IS NULL
	";
	
	$consulta.= " ORDER BY id_boletos";
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		// if( mysqli_num_rows($result) == 0){
			// die("<div class='alert alert-danger'>No hay registros</div>");
			
		// }
		
		
		
	?>  
	<table class="table table-bordered table-condensed table-hover table-sm">
		<thead>
			<tr>
				
				<th>Quitar</th>
				<th>Folio</th>
				<th>Fecha Ponchado</th>
				<th>Destino</th>
				<th>Precio</th>
				
			</tr>
		</thead>
		<tbody id="tablaboletossencillos">
			<?php 
				$total = 0;
				$cant_boletos = 0;
				while($row = mysqli_fetch_assoc($result)){
					
					
					
					$cant_boletos++;
					$total+=  $row["precio"];
					
					
				?>
				<tr>
					<td align="center">
						<!-- <input type="hidden" name="taquilla[]" value="<?php echo $row['taquilla']; ?>"> -->
						<input type="hidden" name="folio_boleto[]" form="form_abono" value="<?php echo $row['id_boletos']; ?>">
						<button class="btn btn-danger btn-sm btn_borrar d-none" 
						data-taquilla="<?php echo $row['taquilla']; ?>" 
						data-folio="<?php echo $row['id_boletos']; ?>" 
						data-monto="<?php echo $row['precio']; ?>" 
						title="Quitar">
							<i class="fas fa-trash"></i>
						</button>
					</td>
					<!-- <td align="left"><?php echo utf8_encode($row['taquilla_nombre']); ?></td> -->
					<td align="center" class="id_boletos">
						<input type="hidden" name="id_boletos[]" value="<?php echo $row['id_boletos']; ?>">
						<?php echo $row['id_boletos']; ?>
					</td>
					<td align="center">
						<?php echo date("d-m-Y H:i:s", strtotime($row['fecha_ponchado'])); ?>
						<?php echo $row['usuario_ponchado']; ?>	
					</td>
					
					<td align="center"><?php echo $row['destino']; ?></td>
					<td align="right" class="monto" data-monto="<?php echo $row['precio']; ?>">
						$<?php echo number_format($row['precio'], 2); ?>
					</td>
				</tr>
				
				
				
				<?php
					
				}
			?>
			
		</tbody>
		<tfoot>
			
			<tr class="text-white bg-secondary">
				
				<td><span id="cant_boletos"><?php echo  $cant_boletos?></span> Boletos</th>
				
				<td></td>
				<td></td>
				<td></td>
				<td class="text-right"><span id="total_boletos">
					<input type="hidden" name="suma_boletos" id="suma_boletos" value="<?php echo $total; ?>">
				$<?php echo number_format($total,2)?></span>
				</td>
				
				
			</tr>
			
			
		</tfoot>
	</table>
	
	<?php
		
	}
	
	else {
		echo "<pre>Error en ".$consulta.mysqli_Error($link)."</pre>";
		
	}
	
	
?>