<?php 
	session_start();
	if(count($_COOKIE) == 0){
		die("<div class='alert alert-danger'>Tu Sesión ha caducado, recarga la página.</div>");
	}
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	
	
	$consulta = "SELECT * FROM boletos 
	
	WHERE id_corridas = {$_GET["id_corridas"]}
	ORDER BY id_boletos DESC
	";
  
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros</div>");
			
		}
		
	?>  
	<pre hidden>
		<?php echo $consulta;?>
	</pre>
	<table class="table table-bordered table-condensed">
		<thead>
			<tr>
				<th></th>
				<th>Folio Boleto</th>
				<th>Num Asiento</th>
				<th>Nombre Pasajero</th>
				<th>Tipo de Boleto</th>
				<th>Precio</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				
				while($fila = mysqli_fetch_assoc($result)){
					// console_log($fila);
					$filas = $fila ;
					
				?>
				<tr>
					<td>
						<?php if($fila["estatus_boletos"] != 'Cancelado'){?>
							
							<a target="_blank" class="btn btn-info imprimir " title="Imprimir" href="impresion/imprimir_boletos.php?boletos[]=<?php echo $filas["id_boletos"]?>" data-id_registro='<?php echo $filas["id_corridas"]?>'>
								<i class="fas fa-print"></i>
							</a>	
							
							<?php
								
							}
						?>
					</td>
					<td><?php echo $filas["id_boletos"]?></td>
					<td><?php echo $filas["num_asiento"]?></td>
					<td><?php echo $filas["nombre_pasajero"];?></td>
					<td><?php echo $filas["tipo_boleto"];?></td>
					<td>$<?php echo number_format($filas["precio_boletos"])?></td>
					<td><?php echo $filas["nombre_origenes"]?></td>
					<td><?php echo $filas["nombre_destinos"]?></td>
					<td><?php
						echo $filas["estatus_corridas"]."<br>";
						if($filas["estatus_corridas"] == "Cancelado"){
							echo $fila["datos_cancelacion"];
							
						}
					?></td>
				</tr>
				
				<?php
					$total_saldo_unidades+= $filas["saldo_unidades"];
					$total_ingresos+= $ingresos;
					$total_cargos+= $filas["gasto_administracion"];
					$total_seguro+= $filas["seguro_derroteros"];
					
				}
			?>
			
			<tr hidden>
				<td colspan="4"> TOTALES</td>
				<td><?php echo number_format($total_saldo_unidades);?></td>
				<td><?php echo number_format($total_ingresos);?></td>
				<td><?php echo number_format($total_cargos);?></td>
				<td><?php echo number_format($ingresos)?></td>
				
			</tr>
		</tbody>
	</table>
	
	<?php
		
	}
	
	else {
		echo "Error en ".$consulta.mysqli_Error($link);
		
	}
	
	
?>