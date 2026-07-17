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
	
	
	
	$consulta = "SELECT * FROM corridas 
	LEFT JOIN unidades USING(id_unidades) 
	LEFT JOIN origenes USING(id_origenes)
	LEFT JOIN (
	SELECT id_origenes AS id_destinos, 
	nombre_origenes AS nombre_destinos 
	FROM origenes ) AS t_destinos 
	USING(id_destinos)
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
				<th>Num Eco</th>
				<th>Fecha</th>
				<th>Hora</th>
				<th>Origen</th>
				<th>Destino</th>
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
					<?php if($fila["estatus_corridas"] != 'Cancelado'){?>
						
						<button class="btn btn-danger cancelar " title="Cancelar" data-id_registro='<?php echo $filas["id_corridas"]?>'>
							<i class="fas fa-times"></i>
						</button>	
						<a class="btn btn-success " title="Boletos" href='boletos.php?id_corridas=<?php echo $filas["id_corridas"]."&num_eco=".$filas["num_eco"]."&asientos=".$filas["asientos"]?>'>
							<i class="fas fa-ticket-alt"></i>
						</button>
						<?php
							
					}
					?>
					</td>
					<td><?php echo $filas["id_corridas"]?></td>
					<td><?php echo $filas["num_eco"]?></td>
					<td><?php echo $filas["fecha_corridas"]?></td>
					<td><?php echo $filas["hora_corridas"]?></td>
					<td><?php echo $filas["nombre_origenes"]?></td>
					<td><?php echo $filas["nombre_destinos"]?></td>
					<td><?php echo $filas["nombre_usuarios"]?></td>
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