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
	
	
	
	$consulta = "
	SELECT 
	* 
	FROM corridas 
	LEFT JOIN unidades USING(id_unidades) 
	LEFT JOIN origenes USING(id_origenes)
	LEFT JOIN 
	(
		SELECT id_origenes AS id_destinos, 
		nombre_origenes AS nombre_destinos 
		FROM origenes 
	) 
		AS t_destinos USING(id_destinos)
	LEFT JOIN usuarios USING(id_usuarios)
	LEFT JOIN 
	(
		SELECT 
		id_corridas, 
		fecha_boletos, 
		COALESCE(SUM(precio_boletos), 0)  AS importe_corridas, 
		COALESCE(COUNT(id_boletos), 0)  AS boletos_vendidos 
		FROM boletos  
		GROUP BY id_corridas
	) 
	AS t_boletos USING(id_corridas)
	WHERE unidades.id_administrador = {$_COOKIE["id_administrador"]}
	AND date(fecha_boletos) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	";
	
	if($_GET["id_corridas"] != ''){
		
			$consulta.=" AND id_corridas = '{$_GET["id_corridas"]}'";
	}
  
	
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
				<th>Folio Corrida</th>
				<th>Num Eco</th>
				<th>Boletos Vendidos</th>
				<th>Importe</th>
				<th>Origen</th>
				<th>Destino</th>
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
						
						<button class="btn btn-info imprimir " title="Imprimir" data-id_registro='<?php echo $filas["id_corridas"]?>'>
							<i class="fas fa-print"></i>
						</button>	
						
						<?php
							
					}
					?>
					</td>
					<td><?php echo $filas["id_corridas"]?></td>
					<td><?php echo $filas["num_eco"]?></td>
					<td><?php echo number_format($filas["boletos_vendidos"])?></td>
					<td>$<?php echo number_format($filas["importe_corridas"])?></td>
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