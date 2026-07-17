<?php 
	 
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/dame_permiso.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	
	
	$consulta = "SELECT * FROM pagos_taquilla 
	LEFT JOIN usuarios USING(id_usuarios)
	";
	
	
	
	
	$consulta .=	" WHERE DATE(fecha_pagos) BETWEEN '{$_GET["fecha_inicial"]}' AND '{$_GET["fecha_final"]}'";
	
	
	if($_GET["id_usuarios"] != ""){
		$consulta.="AND pagos_taquilla.id_usuarios = '{$_GET["id_usuarios"]}'";
	}
	$consulta .=	" ORDER BY id_pagos ASC";
	
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros</div>");
			
		}
		
		while($row = mysqli_fetch_assoc($result)){
			// console_log($fila);
			$filas[] = $row ;
			
		}
		
		
		
	?>  
	<table class="table table-bordered table-condensed">
		<thead>
			<tr>
				<th></th>
				<th>Folio</th>
				<th>Fecha</th>
				<th>Importe</th>
				<th>Corridas</th>
				<th>Usuario</th>
				<th>Recibe</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				
				foreach($filas as $i => $fila){
					
				?>
				<tr>
					<td>
						<?php if($fila["estatus_pagos"] != 'Cancelado'){?>
							
							<button class="btn btn-info  btn-sm imprimir" title="Imprimir" data-id_registro="<?php echo $fila["id_pagos"]?>"  >
								<i class="fas fa-print"></i> 
							</button>
							<?php
								if(dame_permiso("venta_boletos.php", $link) == 'Supervisor'){
								?>
								<button class="btn btn-danger cancelar" title="Cancelar"     data-id_registro='<?php echo $fila["id_pagos"]?>'>
									<i class="fas fa-times"></i>
								</button>	
								
								<?php
								}
								
								$total_pagos+= $fila["total_pagos"];
							}
							else{
								
								
								echo "<span class='badge badge-danger'>".$fila["estatus_pagos"]."</span>";
								echo "<small>".$fila["datos_cancelacion"]."</small>";
							}
							
						?>
						
					</td>
					
					<td><?php echo $fila["id_pagos"]?></td>
					<td><?php echo $fila["fecha_pagos"]?></td>
					<td class="text-right">
						$ <?php echo number_format($fila["total_pagos"], 0)?>
					</td>
					<td style="word-break:break-all"><?php echo $fila["corridas"]?></td>
					<td><?php echo $fila["nombre_usuarios"]?></td>
					<td><?php echo $fila["recibe"]?></td>
				</tr>
				
				<?php
					
					
					
					
				}
			?>
			
			
		</tbody>
		<tfoot>
			<tr class="bg-secondary text-white">
				<td colspan="3">TOTAL:</td>
				<td class="text-right">$ <?= number_format($total_pagos,0)?></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</tfoot>
	</table>
	
	<?php
		
	}
	
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>	