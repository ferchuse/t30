<?php 
	session_start();
	include('../../../conexi.php');
	include('../../../funciones/generar_select.php');
	include('../../../funciones/console_log.php');
	$link = Conectarse();
	$filas = array();
	$respuesta = array();
	
	
	
	$consulta = "SELECT * FROM boletaje 
	LEFT JOIN unidades USING(id_unidades) 
	LEFT JOIN usuarios USING(id_usuarios)
	WHERE unidades.id_administrador = {$_COOKIE["id_administrador"]}
	";
  
	
	$result = mysqli_query($link,$consulta);
	if($result){
		
		if( mysqli_num_rows($result) == 0){
			die("<div class='alert alert-danger'>No hay registros</div>");
			
		}
		
		
		
	?>  
	<table class="table table-bordered table-condensed">
		<thead>
			<tr>
				<th></th>
				<th>Folio</th>
				<th>Fecha</th>
				<th>Num Eco</th>
				<th>Cant Boletos</th>
				<th>Importe</th>
				<th>Usuario</th>
				<th>Estatus</th>
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
					<?php if($fila["estatus_boletaje"] != 'Cancelado'){?>
						<button class="btn btn-primary imprimir " title="Imprimir" data-id_registro='<?php echo $filas["id_boletaje"]?>'>
							<i class="fas fa-print"></i>
						</button>
						<button class="btn btn-danger cancelar " title="Cancelar" data-id_registro='<?php echo $filas["id_boletaje"]?>'>
							<i class="fas fa-times"></i>
						</button>
						<?php
							
					}
					?>
					</td>
					<td><?php echo $filas["id_boletaje"]?></td>
					<td><?php echo $filas["fecha_boletaje"]?></td>
					<td><?php echo $filas["num_eco"]?></td>
					<td><?php echo $filas["cantidad_boletos"]?></td>
					<td><?php echo $filas["total_boletaje"]?></td>
					<td><?php echo $filas["nombre_usuarios"]?></td>
					<td><?php
							echo $filas["estatus_boletaje"]."<br>";
							if($filas["estatus_boletaje"] == "Cancelado"){
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